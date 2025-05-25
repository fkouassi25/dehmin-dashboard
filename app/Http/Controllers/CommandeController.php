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

class CommandeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function list_att_regl(Request $req) {
        $statut = 'EN_ATTENTE_REGLEMENT';
        $data = Commande::with('donateur')->where('statut', $statut)->get();
        return view('list_cmd_att_regl', ['commandes'=>$data]);
    }
    
    public function list_cmd(Request $req) {
        $data = Commande::with('donateur')->get();
        return view('list_cmd', ['commandes'=>$data]);
    }

    public function cmd_infos(Request $req, $code) {
        $cmd = Commande::with('donateur')->where('code', $code)->first();
        if (empty($cmd)) return redirect('/list_cmd');

        $lignes = DB::table('ligne_cmd')
                    ->join('type_bon', 'ligne_cmd.id_type_bon', '=', 'type_bon.id')
                    ->join('categorie', 'type_bon.id_cat', '=', 'categorie.id')
                    ->where('ligne_cmd.id_commande', $cmd->id)
                    ->groupBy('ligne_cmd.id_type_bon', 'type_bon.pu', 'categorie.libelle')
                    ->select(
                        'ligne_cmd.id_type_bon',
                        DB::raw('COUNT(*) as qte'),
                        'type_bon.pu',
                        'categorie.libelle'
                    )
                    ->get();
        $total = 0;
        foreach ($lignes as $ligne) {
            $total += $ligne->qte * intval($ligne->pu);
        }

        return view('cmd_infos', ['donateur'=>$cmd->donateur,'lignes'=>$lignes, 'total'=>$total, 'code_cmd'=>$code]);
    }

    public function cmd_att_regl(Request $req, $code) {
        $cmd = Commande::with('donateur')->where('code', $code)->first();
        if (empty($cmd)) return redirect('/list_cmd_attente_regl');

        $lignes = DB::table('ligne_cmd')
                    ->join('type_bon', 'ligne_cmd.id_type_bon', '=', 'type_bon.id')
                    ->join('categorie', 'type_bon.id_cat', '=', 'categorie.id')
                    ->where('ligne_cmd.id_commande', $cmd->id)
                    ->groupBy('ligne_cmd.id_type_bon', 'type_bon.pu', 'categorie.libelle')
                    ->select(
                        'ligne_cmd.id_type_bon',
                        DB::raw('COUNT(*) as qte'),
                        'type_bon.pu',
                        'categorie.libelle'
                    )
                    ->get();
        
        $total = 0;
        foreach ($lignes as $ligne) {
            $total += $ligne->qte * intval($ligne->pu);
        }

        return view('cmd_att_regl', ['donateur'=>$cmd->donateur,'lignes'=>$lignes, 'total'=>$total, 'code_cmd'=>$code]);
    }

    public function regler_une_commande(Request $req) {
        try {
            // dd($req->type_regl);
            $cmd = Commande::where('code', $req->code_cmd)->first();
            if (empty($cmd)) return 'error';
            $cmd->statut = 'REGLEE';
            $cmd->type_regl = $req->type_regl;
            $cmd->date_regl = $req->date_regl;
            $cmd->save();

            // update ligne cmd
            $lignes = LigneCommande::where('id_commande', $cmd->id)->get();
            foreach ($lignes as $ligne) {
                $ligne->statut = 'EN_ATTENTE_ATTRIBUTION';
                $ligne->save();
            }
            return "success";

        } catch (\Throwable $th) {
            return 'error';
            dd($th);
        }
    }
}
