<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Beneficiaire;
use App\Models\Users;
use App\Models\Commande;
use App\Models\Bon;
use App\Models\Prestataire;
use App\Models\Donateur;
use App\Models\Proposition;
use App\Models\Operation;
use App\Models\TypeBon;
use App\Models\LigneCommande;
use App\Models\Attribution;
use App\Models\LienBenefTypeBon;
use App\Library\ClasseEnvoiSMS;

use Illuminate\Http\Request;

class PrestataireController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    private $communes = ['Abobo', 'Adjamé', 'Atécoubé', 
                'Cocody', 'Koumassi', 'Marcory', 
                'Plateau', 'Port-bouet', 'Treichville', 'Yopougon'];

    public function list(Request $req) {
        $presta = Prestataire::all();
        $total = Prestataire::count();
        return view('presta.list_presta', ['presta'=>$presta, 'total'=>$total]);
    }

    public function presta(Request $req, $code) {
        $presta = Prestataire::where('code', $code)->first();
        if (empty($presta)) return redirect('/list_presta');

        $attribution = Operation::where('id_prestataire', $presta->id)->get();
        $solde = 0;
        $debit = 0;
        $credit = 0;
        foreach ($attribution as $value) {
            $debit += $value['debit'];
            $credit += $value['credit'];
        }
        $solde = $credit - $debit;

        return view('presta.presta', ['presta'=>$presta,'compte'=>$attribution, 'solde'=>$solde, 'code'=>$code]);
    }

    public function updateForm(Request $req, $code) {
        $presta = Prestataire::where('code', $code)->first();
        if (empty($presta)) return redirect('/list_presta');
                     
        return view('presta.update_presta', ['presta'=>$presta, 'communes'=>$this->communes]);
    }

    public function update_presta(Request $req) {
        $input = $req->all();
        $resp = [];

        if (empty($req->code)) {
            $resp['code'] = 'err';
            $resp['msg'] = "Code introuvable.";
            return resp;
        }

        $presta = Prestataire::where('code', $req->code)->first();
        if (empty($presta)) {
            $resp['code'] = 'err';
            $resp['msg'] = "Code introuvable.";
            return $resp;
        }


        try {
            $presta->nom_magasin = isset($req->nom_magasin) ? $req->nom_magasin: '';
            $presta->localisation = isset($req->localisation) ? $req->localisation : '';
            $presta->nom_representant = isset($req->nom_representant) ? $req->nom_representant : '';
            $presta->tel = isset($req->tel) ? $req->tel : '';
            $presta->cel = isset($req->cel) ? $req->cel : '';
            $presta->commune = isset($req->commune) ? $req->commune : '';
            $presta->email = isset($req->email) ? $req->email : '';
            
            if (isset($req->pwd_upd_confirm) && isset($req->pwd)) {
                $presta->password = bcrypt($req->pwd);
            }
            
            $presta->save();
            
            $resp['code'] = 'success';
            return $resp;
        } catch (\Throwable $th) {
            // dd($th);
            $resp['code'] = 'err';
            $resp['msg'] = "Oups! Une erreur s'est produite lors de l'enregistrement en base de données.";
            return $resp;
        }
    }

    public function approvisionner(Request $req) {
        try {
            $presta = Prestataire::where('code', $req->code)->first();

            $oper = new Operation;
            $oper->date_oper = date('d/m/Y H:i');
            $oper->designation = "APPROVISIONNEMENT DE COMPTE";
            $oper->debit = 0;
            $oper->credit = $req->montant;
            $oper->id_prestataire = $presta->id;
            $oper->id_attribution = 0;
            $oper->save();

            return 'success';
        } catch (\Throwable $th) {
            //throw $th;
            return 'error';
        }
    }
}
