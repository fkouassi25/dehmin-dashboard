<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

use Mail;


class BonController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function list_bon_non_attribue(Request $req) {
        $statut = 'EN_ATTENTE_ATTRIBUTION';
        $data = DB::table('ligne_cmd')
                     ->join('commande', 'ligne_cmd.id_commande', '=', 'commande.id')
                     ->join('type_bon', 'ligne_cmd.id_type_bon', '=', 'type_bon.id')
                     ->join('categorie', 'type_bon.id_cat', '=', 'categorie.id')
                     ->where('ligne_cmd.statut', '=', $statut)
                     ->select(DB::raw('ligne_cmd.id as id, ligne_cmd.date as date, 
                        commande.date_regl as date_regl, type_bon.montant_reel as pu, categorie.libelle as categ'))
                     ->get();
        return view('bons.list_bon_non_attribue', ['bons'=>$data]);
    }
    
    public function list_bon_attribue(Request $req) {
        $data = DB::table('attribution')
                     ->join('ligne_cmd', 'attribution.id_ligne_cmd', '=', 'ligne_cmd.id')
                     ->join('type_bon', 'ligne_cmd.id_type_bon', '=', 'type_bon.id')
                     ->join('beneficiaire', 'attribution.id_benef', '=', 'beneficiaire.id')
                     ->join('categorie', 'type_bon.id_cat', '=', 'categorie.id')
                     ->orderBy('attribution.id', 'ASC')
                     ->select(DB::raw('attribution.num_bon, attribution.date as date_attr, 
                        categorie.libelle as typebon, type_bon.montant_reel as montant_reel, type_bon.pu as pu, 
                        beneficiaire.nom, beneficiaire.prenom, attribution.statut as statut, attribution.id as id_attr'))
                     ->get();
        return view('bons.list_bon_attribue', ['data'=>$data]);
    }

    public function attribuer(Request $req) {
        $PLAFOND = 30000;
        $PLAFOND_JOURNALIER = 1000;

        $NBR_BONS_AFFECTES = 0;
        $MONTANT = 0;

        
        $statut_bons = 'EN_ATTENTE_ATTRIBUTION';


        // on recupere la liste des bons à partager

        $bons = DB::table('ligne_cmd')

                ->join('type_bon', 'ligne_cmd.id_type_bon', '=', 'type_bon.id')

                ->join('categorie', 'type_bon.id_cat', '=', 'categorie.id')

                ->where('ligne_cmd.statut', '=', $statut_bons)

                ->where('type_bon.statut', '=', 'ACTIF')

                ->orderBy('type_bon.pu', 'DESC')

                ->orderBy('categorie.libelle', 'ASC')

                ->select(DB::raw('ligne_cmd.*, type_bon.pu, type_bon.montant_reel, categorie.libelle as nature'))

                ->get();



        foreach ($bons as $bon) {

            

            //pour l'instant on bloque les bons de santé

            //if (strtolower($bon->nature) == "sante") continue;

            

            // personne pouvant bénéficier de ce type de bons et dont le plafond de consommation est inférieure à 30 000

            $candidat = DB::table('lien_benef_type_bon')

                     ->join('beneficiaire', 'lien_benef_type_bon.id_benef', '=', 'beneficiaire.id')

                     ->where('lien_benef_type_bon.id_type_bon', '=', $bon->id_type_bon)

                     ->where('beneficiaire.montant_bon', '<', $PLAFOND)

                     ->where('beneficiaire.conso_day', '<', $PLAFOND_JOURNALIER)

                     ->orderBy('beneficiaire.nb_bons', 'ASC')

                     ->orderBy('beneficiaire.id', 'ASC')

                     ->select(DB::raw('beneficiaire.*'))

                     ->first();

            if ($candidat != null) {

                // on a un gagnant, on lui donne le bon

                $attribution = new Attribution;

                $attribution->id_ligne_cmd = $bon->id;

                $attribution->id_benef = $candidat->id;

                $attribution->num_bon = $this->generate_num_bon();

                $attribution->date = date('d/m/Y H:i');

                

                

                $attribution->solde = $bon->montant_reel;

                $attribution->statut = "ATTRIBUEE";

                $attribution->save();



                // on change le statut de la ligne cmd

                $b = LigneCommande::find($bon->id);

                $b->statut = "ATTRIBUEE";

                $b->save();



                // on augmente le montant du benef

                $gagnant = Beneficiaire::find($candidat->id);

                $gagnant->montant_bon = $candidat->montant_bon + $bon->pu;

                $gagnant->conso_day = $candidat->conso_day + $bon->pu;



                $gagnant->nb_bons = $candidat->nb_bons + 1;

                $gagnant->bon_journalier = 1;

                $gagnant->save();



                // inform par sms le gagnant

                // pour les SMS

                $sms = new ClasseEnvoiSMS;

                $rep = $sms->notif_beneficiaire($attribution->num_bon, $bon->nature, $bon->montant_reel, $gagnant->cel);

                // faut gérer les cas d'erreur

                

                

                // juste un petit feedback pour moi :)

                $NBR_BONS_AFFECTES += 1;

                $MONTANT += $bon->pu;

            }

        }
        
        // juste une petite notif pour moi :)
        $this->inform_webmaster_attribution($MONTANT, $NBR_BONS_AFFECTES, "Le bouton Attribuer");
        
        return 'success';
    }

    public function detail_bon(Request $req, $id) {
        $attribution = Attribution::find($id);
        if (empty($attribution)) return redirect('/list_bon_attribue');

        $details = DB::table('conso_bon')
                     ->join('prestataire', 'conso_bon.id_prestataire', '=', 'prestataire.id')
                     ->where('conso_bon.id_attribution', '=', $id)
                     ->select(DB::raw('conso_bon.*, prestataire.code, prestataire.nom_magasin'))
                     ->get();
        
        return view('bons.detail_bon', ['attribution'=>$attribution, 'details'=>$details]);
    }

    public function generate_num_bon() {
        return $this->random_1(8).date('d');
    }
    
    public function random_1($car) {
        $string = "";
        $chaine = "T0US56XY1ZX2YZ78V9WRNCDEF41GHIJKOPQA23LBM0T0US56XY1ZX2YZ78V9WRNCDEF41GHIJKOPQA23LBM0";
        srand((double)microtime()*1000000);
        for($i=0; $i<$car; $i++) {
        $string .= $chaine[rand()%strlen($chaine)];
        }
        return $string;
    }

    public function calcul_conso_benef(Request $req) {
        /* Permet de mettre à jour la conso mensuel
            et le nbre de bons reçu, en se basant sur
            les bons attribués
        */
        $benefs = Beneficiaire::all();
        
        foreach ($benefs as $benef) {
            $conso = DB::table('attribution')
                ->join('ligne_cmd', 'attribution.id_ligne_cmd', '=', 'ligne_cmd.id')
                ->join('type_bon', 'ligne_cmd.id_type_bon', '=', 'type_bon.id')
                ->where('attribution.id_benef', '=', $benef->id)
                ->sum('type_bon.pu');

            $nbr_bons = DB::table('attribution')
                ->join('ligne_cmd', 'attribution.id_ligne_cmd', '=', 'ligne_cmd.id')
                ->join('type_bon', 'ligne_cmd.id_type_bon', '=', 'type_bon.id')
                ->where('attribution.id_benef', '=', $benef->id)
                ->count();
                
            $benef->montant_bon = $conso;
            $benef->nb_bons = $nbr_bons;
            $benef->save();
        }
        return 'ok';
    }
    
    public function test_mail_webmaster(Request $req) {
        $this->inform_webmaster_attribution(100000, 50, "La Tache Cron");
    }
    
    
    public function inform_webmaster_attribution($mnt, $nb_bons, $origin) {
        $data = new \stdClass();
        $data->mnt = $mnt;
        $data->nb = $nb_bons;
        $data->origin = $origin;
        
        
        Mail::send('mail.inform_attribution', ["data"=>$data], function($message) {
         $message->to('rtidiane@gmail.com', 'Notifs Dehmin')->subject
            ("Nouvelle notification Dehmin: Attribution");
         });

        return 'ok';
     }
     
     public function test_sms(Request $req) {
        //  $sms = new ClasseEnvoiSMS;
        //         $rep = $sms->notif_beneficiaire("BONTEST", "Alimentaire", 2500, "07478117");
        
        // $nums = ['08433162', '07478117', '55126664', '03082941'];
        $nums = ['0707478117','0749455013'];
        foreach($nums as $num) {
            $this->test_envoi($num);
        }
        return 'Test ok';
     }
     
     public function test_envoi($numero) {
         $sms = new ClasseEnvoiSMS;
        $rep = $sms->notif_beneficiaire("BONTEST", "Alimentaire", 2500, $numero);
     }
     
}
