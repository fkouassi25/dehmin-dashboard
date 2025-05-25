<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Beneficiaire;
use App\Models\Users;
use App\Models\Commande;
use App\Models\Bon;
use App\Models\Prestataire;
use App\Models\Donateur;
use App\Models\Proposition;
use App\Models\TypeBon;
use App\Models\LigneCommande;
use App\Models\Attribution;
use App\Models\LienBenefTypeBon;
use App\Library\ClasseEnvoiSMS;
use Illuminate\Support\Facades\DB;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BeneficiaireController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    private $communes = ['', 'Abobo', 'Adjamé', 'Atécoubé', 
                'Cocody', 'Koumassi', 'Marcory', 
                'Plateau', 'Port-bouet', 'Treichville', 'Yopougon'];

    public function signupForm(Request $req) {
        return view('benef.signup_benef', ['communes'=>$this->communes]);
    }
    
    public function signup(Request $req) {
        $input = $req->all();
        $resp = [];

        $sms = new ClasseEnvoiSMS;

        try {
            $benef = new Beneficiaire;
            $benef->nom = isset($req->nom) ? $req->nom: '';
            $benef->prenom = isset($req->prenom) ? $req->prenom : '';
            $benef->cni = isset($req->cni) ? $req->cni : '';
            $benef->cel = isset($req->cel) ? $req->cel : '';
            $benef->commune = isset($req->commune) ? $req->commune : '';
            $benef->referent = isset($req->referent) ? $req->referent : '';
            $benef->date_inscription = date('d/m/Y h:i');
            $benef->nb_bons = 0;
            $benef->montant_bon = 0;
            $benef->bon_journalier = 0;
            $benef->conso_day = 0;
            $benef->code = $this->generate_code();
            $benef->save();
            $resp['code'] = 'success';

            foreach ($input as $key => $value) {
                if ($this->startsWith($key, 'ba_') || $this->startsWith($key, 'bs_')) {
                    $typeBon = TypeBon::where('sobriquet', $key)->first();
                    // save in lien benef
                    $lienBenef = new LienBenefTypeBon;
                    $lienBenef->id_benef = $benef->id;
                    $lienBenef->id_type_bon = $typeBon->id;
                    $lienBenef->save();
                }
            }

            // Change statut proposition
            if (isset($req->proposition_state)) {
                $prop = Proposition::find($req->proposition_state);
                if ($prop) {
                    $prop->statut = 'VALIDEE';
                    $prop->save();
                }
            }

            // inform par sms le benef
            $rep = $sms->notif_benef_signup($benef->code, $benef->cel);
            if ($rep['statut'] == 'err') {
                $resp['code'] = 'err';
                $resp['msg'] = "Inscription réussie. Mais nous n'avons pas pu envoyé le SMS au bénéficiaire.";
            }

            return $resp;
        } catch (\Throwable $th) {
            $resp['code'] = 'err';
            $resp['msg'] = "Oups! Une erreur s'est produite lors de l'enregistrement en base de données.";
            return $resp;
        }
    }

    public function updateForm(Request $req, $code) {
        $benef = Beneficiaire::where('code', $code)->first();
        if (empty($benef)) return redirect('/list_benef');

        $typebons = DB::table('lien_benef_type_bon')
                     ->join('type_bon', 'lien_benef_type_bon.id_type_bon', '=', 'type_bon.id')
                     ->where('lien_benef_type_bon.id_benef', '=', $benef->id)
                     ->select(DB::raw('type_bon.sobriquet as lib'))
                     ->get()
                     ->toArray();
        $bons = [];
        foreach ($typebons as $key => $bon) {
            array_push($bons, $bon->lib);
        }
                     
        return view('benef.update_benef', ['benef'=>$benef, 'bons'=>$bons,'communes'=>$this->communes]);
    }

    public function update_benef(Request $req) {
        $input = $req->all();
        $resp = [];

        if (empty($req->code)) {
            $resp['code'] = 'err';
            $resp['msg'] = "Code introuvable.";
            return $resp;
        }

        $benef = Beneficiaire::where('code', $req->code)->first();
        if (empty($benef)) {
            $resp['code'] = 'err';
            $resp['msg'] = "Code introuvable.";
            return $resp;
        }


        try {
            $benef->nom = isset($req->nom) ? $req->nom: '';
            $benef->prenom = isset($req->prenom) ? $req->prenom : '';
            $benef->cni = isset($req->cni) ? $req->cni : '';
            $benef->cel = isset($req->cel) ? $req->cel : '';
            $benef->commune = isset($req->commune) ? $req->commune : '';
            $benef->referent = isset($req->referent) ? $req->referent : '';
            $benef->save();
            
            //retire les liens types bons
            $deletedLien = LienBenefTypeBon::where('id_benef', $benef->id)->delete();
            
            //j'affecte ce qu'il a coché
            foreach ($input as $key => $value) {
                if ($this->startsWith($key, 'ba_') || $this->startsWith($key, 'bs_')) {
                    $typeBon = TypeBon::where('sobriquet', $key)->first();
                    // save in lien benef
                    $lienBenef = new LienBenefTypeBon;
                    $lienBenef->id_benef = $benef->id;
                    $lienBenef->id_type_bon = $typeBon->id;
                    $lienBenef->save();
                }
            }
            
            $resp['code'] = 'success';
            return $resp;
        } catch (\Throwable $th) {
            // dd($th);
            $resp['code'] = 'err';
            $resp['msg'] = "Oups! Une erreur s'est produite lors de l'enregistrement en base de données.";
            return $resp;
        }
    }

    public function startsWith($string, $startString) { 
        $len = strlen($startString); 
        return (substr($string, 0, $len) === $startString); 
    }

    public function list(Request $req) {
        $benefs = Beneficiaire::all();
        $total = Beneficiaire::count();
        return view('benef.list_benef', ['benefs'=>$benefs, 'total'=>$total]);
    }

    public function list_proposition(Request $req) {
        $props = Proposition::all();
        $total = Proposition::count();
        return view('proposition.list_proposition', ['propositions'=>$props, 'total'=>$total]);
    }

    public function process_proposition_view(Request $req, $id) {
        $prop = Proposition::find($id);
        if ($prop) return view('proposition.detail_proposition', ['proposition'=>$prop]);
        else return redirect('list_proposition');
    }
    
    public function process_proposition(Request $req, $id, $choix) {
        $prop = Proposition::find($id);
        if ($prop) {
            if ($choix == 'refusee') {
                // change status
                $prop->statut = 'REFUSEE';
                $prop->save();
                
            } elseif ($choix == 'validee') {
                $prop->statut = 'VALIDEE';
                $prop->save();
            } elseif ($choix == 'signup_validee') {
                return redirect('signup_benef')->with(['proposition'=>$prop]);
            }
        }
        return redirect('list_proposition');
    }

    public function benef(Request $req, $code) {
        $benef = Beneficiaire::where('code', $code)->first();
        if (empty($benef)) return redirect('/list_benef');

        $typebons = DB::table('beneficiaire')
                     ->join('lien_benef_type_bon', 'beneficiaire.id', '=', 'lien_benef_type_bon.id_benef')
                     ->join('type_bon', 'lien_benef_type_bon.id_type_bon', '=', 'type_bon.id')
                     ->join('categorie', 'type_bon.id_cat', '=', 'categorie.id')
                     ->where('beneficiaire.code', '=', $code)
                     ->select(DB::raw('categorie.libelle as libelle, type_bon.pu as montant'))
                     ->get();
        
        return view('benef.benef', ['benef'=>$benef, 'typebons'=>$typebons, 'code'=>$code]);
    }

    public function print_carte(Request $req, $code) {
        $benef = Beneficiaire::where('code', $code)->first();
        if (empty($benef)) return "Aucun bénéficiaire possède ce code.";

        $pdf = PDF::loadView('benef.modele_carte_beneficiaire',['benef'=>$benef]);
        return $pdf->download('carte_beneficiaire_'.strtoupper($benef->nom).'_'.$benef->prenom.'.pdf');
    }

    public function generate_code() {
        $count = Beneficiaire::count() + 1;
        $code = $count."-B-".date('dy');
        return $code;
    }
    
    public function inform_all_benef(Request $req) {
        // permet d'envoyer le sms inscription à tous les benefs
        $sms = new ClasseEnvoiSMS;
        $benefs = Beneficiaire::all();
        foreach ($benefs as $b) {
            $resp = $sms->notif_benef_signup($b->code, $b->cel);
        }
        return "SMS envoyé";
    }
    
    public function clean_db_benef(Request $req) {
        ini_set('max_execution_time', 0);
        // get list benef fantome
        $benefs = DB::select("SELECT B.code, B.id, B.nom, B.prenom, B.cel, 
        count(*) as nb_bons_recu, 
        count(case A.solde when not T.montant_reel then 1 else null end) as nb_bons_consommee
        
        FROM attribution A inner join beneficiaire B on (A.id_benef=B.id)
        inner join ligne_cmd L on (A.id_ligne_cmd=L.id)
        inner join type_bon T on (L.id_type_bon=T.id)
        GROUP by B.code
        having nb_bons_consommee = 0 and nb_bons_recu > 3
        ORDER BY `nb_bons_recu`  DESC");

        // dd($benefs);

        $nb_benefs = 0;
        $nb_bons_doublons = 0;
        $nb_bons_unique = 0;


        foreach ($benefs as $benef) {
            // parcours liste bon attribué
            $bons = DB::select("SELECT A.*, B.code, T.montant_reel
            FROM attribution A inner join beneficiaire B on (A.id_benef=B.id) 
            inner join ligne_cmd L on (A.id_ligne_cmd=L.id) 
            inner join type_bon T on (L.id_type_bon=T.id)
            
            where A.id_benef = ".$benef->id);

            foreach ($bons as $bon) {
                // pour chaque bon, faut verifier qu'il n'est pas en double
                $ligneCheck = DB::select("SELECT A.id, A.id_ligne_cmd, B.code, T.montant_reel, (case A.solde when not T.montant_reel then 'CONSOMMEE' else 'ATTRIBUEE' end) as statut
                FROM attribution A inner join beneficiaire B on (A.id_benef=B.id) 
                inner join ligne_cmd L on (A.id_ligne_cmd=L.id) 
                inner join type_bon T on (L.id_type_bon=T.id)
            
                where A.id_ligne_cmd = ".$bon->id_ligne_cmd);

                $consommee = false;
                $nb = 0;
                foreach ($ligneCheck as $ligne) {
                    if ($ligne->statut == "CONSOMMEE") $consommee = true;
                    if ($ligne->statut == "ATTRIBUEE") {
                        // supprimer ligne dans table attribution
                        Attribution::where('id', $ligne->id)->delete();
                    }
                    $nb += 1;
                }
                if ($nb > 1) $nb_bons_doublons += 1;
                else $nb_bons_unique += 1;

                if (!$consommee) {
                    // update ligne dans table ligne_cmd
                    $b = LigneCommande::find($ligne->id_ligne_cmd);
                    $b->statut = "EN_ATTENTE_ATTRIBUTION";
                    $b->save();
                }
            }

            // on supprime le ga lui même
            LienBenefTypeBon::where('id_benef', $benef->id)->delete();
            Beneficiaire::where('id', $benef->id)->delete();
            $nb_benefs += 1;
        }

        $data = ['benefs'=>$nb_benefs, 'doublons'=>$nb_bons_doublons, 'unique'=>$nb_bons_unique];
        return $data;
    }
    
    public function test_api_mtn(Request $req) {
        $sms = new ClasseEnvoiSMS;
        $sms->test_api_mtn();
        return 'Test API MTN';
    }
}
