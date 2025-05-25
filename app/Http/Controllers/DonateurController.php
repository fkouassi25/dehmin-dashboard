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

class DonateurController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    private $communes = ['Abobo', 'Adjamé', 'Atécoubé', 
                'Cocody', 'Koumassi', 'Marcory', 
                'Plateau', 'Port-bouet', 'Treichville', 'Yopougon', 'Autre commune'];
    private $pays = [
        "AC"=>"Île de l'Ascension",
        "AD"=>"Andorre",
        "AE"=>"Émirats arabes unis",
        "AF"=>"Afghanistan",
        "AG"=>"Antigua-et-Barbuda",
        "AI"=>"Anguilla",
        "AL"=>"Albanie",
        "AM"=>"Arménie",
        "AN"=>"Antilles néerlandaises",
        "AO"=>"Angola",
        "AQ"=>"Antarctique",
        "AR"=>"Argentine",
        "AS"=>"Samoa américaines",
        "AT"=>"Autriche",
        "AU"=>"Australie",
        "AW"=>"Aruba",
        "AX"=>"Åland",
        "AZ"=>"Azerbaïdjan",
        "BA"=>"Serbie-et-Monténégro",
        "BB"=>"Barbade",
        "BD"=>"Bangladesh",
        "BE"=>"Belgique",
        "BF"=>"Burkina Faso",
        "BG"=>"Bulgarie",
        "BH"=>"Bahreïn",
        "BI"=>"Burundi",
        "BJ"=>"Bénin",
        "BM"=>"Bermudes",
        "BN"=>"Brunei Darussalam",
        "BO"=>"Bolivie",
        "BR"=>"Brésil",
        "BS"=>"Bahamas",
        "BT"=>"Bhoutan",
        "BV"=>"Île Bouvet",
        "BW"=>"Botswana",
        "BY"=>"Biélorussie",
        "BZ"=>"Bélize",
        "CA"=>"Canada",
        "CC"=>"Îles Cocos",
        "CD"=>"République démocratique du Congo",
        "CF"=>"République centrafricaine",
        "CG"=>"République du Congo",
        "CH"=>"Suisse",
        "CI"=>"Côte d'Ivoire",
        "CK"=>"Îles Cook",
        "CL"=>"Chili",
        "CM"=>"Cameroun",
        "CN"=>"Chine",
        "CO"=>"Colombie",
        "CR"=>"Costa Rica",
        "CU"=>"Cuba",
        "CV"=>"Cap-Vert",
        "CX"=>"Île Christmas",
        "CY"=>"Chypre",
        "CZ"=>"République tchèque",
        "DE"=>"Allemagne",
        "DJ"=>"Djibouti",
        "DK"=>"Danemark",
        "DM"=>"Dominique",
        "DO"=>"République dominicaine",
        "DZ"=>"Algérie",
        "EC"=>"Équateur",
        "EE"=>"Estonie",
        "EG"=>"Égypte",
        "EH"=>"Sahara occidental",
        "ER"=>"Érythrée",
        "ES"=>"Espagne",
        "ET"=>"Éthiopie",
        "FI"=>"Finlande",
        "FJ"=>"Fidji",
        "FK"=>"Îles Malouines",
        "FM"=>"Micronésie",
        "FO"=>"Îles Féroé",
        "FR"=>"France",
        "FX"=>"France métropolitaine",
        "GA"=>"Gabon",
        "GB"=>"Royaume-Uni",
        "GD"=>"Grenade",
        "GE"=>"Géorgie",
        "GF"=>"Guyane",
        "GG"=>"Guernesey",
        "GH"=>"Ghana",
        "GI"=>"Gibraltar",
        "GL"=>"Groenland",
        "GM"=>"Gambie",
        "GN"=>"Guinée",
        "GP"=>"Guadeloupe",
        "GQ"=>"Guinée équatoriale",
        "GR"=>"Grèce",
        "GS"=>"Géorgie du Sud-et-les Îles Sandwich du Sud",
        "GT"=>"Guatemala",
        "GU"=>"Guam",
        "GW"=>"Guinée-Bissau",
        "GY"=>"Guyana",
        "HK"=>"Hong Kong",
        "HM"=>"Îles Heard-et-MacDonald",
        "HN"=>"Honduras",
        "HR"=>"Croatie",
        "HT"=>"Haïti",
        "HU"=>"Hongrie",
        "ID"=>"Indonésie",
        "IE"=>"Irlande",
        "IL"=>"Israël",
        "IM"=>"Île de Man",
        "IN"=>"Inde",
        "IO"=>"Territoire britannique de l'océan Indien",
        "IQ"=>"Irak",
        "IR"=>"Iran",
        "IS"=>"Islande",
        "IT"=>"Italie",
        "JE"=>"Jersey",
        "JM"=>"Jamaïque",
        "JO"=>"Jordanie",
        "JP"=>"Japon",
        "KE"=>"Kenya",
        "KG"=>"Kirghizistan",
        "KH"=>"Cambodge",
        "KI"=>"Kiribati",
        "KM"=>"Comores",
        "KN"=>"Saint-Christophe-et-Niévès",
        "KP"=>"Corée du Nord",
        "KR"=>"Corée du Sud",
        "KW"=>"Koweït",
        "KY"=>"Îles Caïmans",
        "KZ"=>"Kazakhstan",
        "LA"=>"Laos",
        "LB"=>"Liban",
        "LC"=>"Sainte-Lucie",
        "LI"=>"Liechtenstein",
        "LK"=>"Sri Lanka",
        "LR"=>"Libéria",
        "LS"=>"Lesotho",
        "LT"=>"Lituanie",
        "LU"=>"Luxembourg",
        "LV"=>"Lettonie",
        "LY"=>"Libye",
        "MA"=>"Maroc",
        "MC"=>"Monaco",
        "MD"=>"Moldavie",
        "ME"=>"Monténégro",
        "MF"=>"Saint-Martin",
        "MG"=>"Madagascar",
        "MH"=>"Îles Marshall",
        "MK"=>"Macédoine",
        "ML"=>"Mali",
        "MM"=>"Myanmar (Birmanie)",
        "MN"=>"Mongolie",
        "MO"=>"Macau",
        "MP"=>"Îles Mariannes du Nord",
        "MQ"=>"Martinique",
        "MR"=>"Mauritanie",
        "MS"=>"Montserrat",
        "MT"=>"Malte",
        "MU"=>"Maurice",
        "MV"=>"Maldives",
        "MW"=>"Malawi",
        "MX"=>"Mexico",
        "MY"=>"Malaisie",
        "MZ"=>"Mozambique",
        "NA"=>"Namibie",
        "NC"=>"Nouvelle-Calédonie",
        "NE"=>"Niger",
        "NF"=>"Île Norfolk",
        "NG"=>"Nigéria",
        "NI"=>"Nicaragua",
        "NL"=>"Pays-Bas",
        "NO"=>"Norvège",
        "NP"=>"Népal",
        "NR"=>"Nauru",
        "NU"=>"Niue",
        "NZ"=>"Nouvelle-Zélande",
        "OM"=>"Oman",
        "PA"=>"Panamá",
        "PE"=>"Pérou",
        "PF"=>"Tahïti (Polynésie française)",
        "PG"=>"Papouasie-Nouvelle-Guinée",
        "PH"=>"Philippines",
        "PK"=>"Pakistan",
        "PL"=>"Pologne",
        "PM"=>"Saint-Pierre-et-Miquelon",
        "PN"=>"Îles Pitcairn",
        "PR"=>"Porto Rico",
        "PS"=>"Territoires palestiniens occupés",
        "PT"=>"Portugal",
        "PW"=>"Palaos",
        "PY"=>"Paraguay",
        "QA"=>"Qatar",
        "RE"=>"La Réunion",
        "RO"=>"Roumanie",
        "RS"=>"Serbie (Yougoslavie)",
        "RU"=>"Russie",
        "RW"=>"Rwanda",
        "SA"=>"Arabie saoudite",
        "SB"=>"Îles Salomon",
        "SC"=>"Seychelles",
        "SD"=>"Soudan",
        "SE"=>"Suède",
        "SG"=>"Singapour",
        "SH"=>"Sainte-Hélène",
        "SI"=>"Slovénie",
        "SJ"=>"Svalbard et Jan Mayen",
        "SK"=>"Slovaquie",
        "SL"=>"Sierra Leone",
        "SM"=>"Saint-Marin",
        "SN"=>"Sénégal",
        "SO"=>"Somalie",
        "SR"=>"Suriname",
        "ST"=>"Sao Tomé-et-Principe",
        "SV"=>"Salvador",
        "SY"=>"Syrie",
        "SZ"=>"Swaziland",
        "TC"=>"Îles Turques-et-Caïques",
        "TD"=>"Tchad",
        "TF"=>"Terres australes et antarctiques françaises",
        "TG"=>"Togo",
        "TH"=>"Thaïlande",
        "TJ"=>"Tadjikistan",
        "TK"=>"Tokelau",
        "TL"=>"Timor oriental",
        "TM"=>"Turkménistan",
        "TN"=>"Tunisie",
        "TO"=>"Tonga",
        "TR"=>"Turquie",
        "TT"=>"Trinité-et-Tobago",
        "TV"=>"Tuvalu",
        "TW"=>"Taïwan",
        "TZ"=>"Tanzanie",
        "UA"=>"Ukraine",
        "UG"=>"Ouganda",
        "UM"=>"Îles mineures éloignées des États-Unis",
        "US"=>"États-Unis(USA)",
        "UY"=>"Uruguay",
        "UZ"=>"Ouzbékistan",
        "VA"=>"Cité du Vatican",
        "VC"=>"Saint-Vincent-et-les-Grenadines",
        "VE"=>"Venezuela",
        "VG"=>"Îles Vierges britanniques",
        "VI"=>"Îles Vierges(US)",
        "VN"=>"Viêt Nam",
        "VU"=>"Vanuatu",
        "WF"=>"Wallis-et-Futuna",
        "WS"=>"Samoa",
        "XK"=>"République du Kosovo",
        "YE"=>"Yémen",
        "YT"=>"Mayotte",
        "YU"=>"Serbia and Montenegro",
        "ZA"=>"Afrique du Sud",
        "ZM"=>"Zambie",
        "ZW"=>"Zimbabwe"
    ];

    public function list(Request $req) {
        $donateur = Donateur::all();
        $total = Donateur::count();
        return view('donateur.list_donateur', ['donateur'=>$donateur, 'total'=>$total]);
    }

    public function donateur(Request $req, $code) {
        $donateur = Donateur::where('code', $code)->first();
        if (empty($donateur)) return redirect('/list_donateur');

        $dons = Commande::where('id_donateur', $donateur->id)->get();

        $benefs = DB::table('commande')
                     ->join('ligne_cmd', 'ligne_cmd.id_commande', '=', 'commande.id')
                     ->join('attribution', 'attribution.id_ligne_cmd', '=', 'ligne_cmd.id')
                     ->join('beneficiaire', 'attribution.id_benef', '=', 'beneficiaire.id')
                     ->where('commande.id_donateur', '=', $donateur->id)
                     ->distinct()
                     ->select(DB::raw('beneficiaire.*'))
                     ->get();

        return view('donateur.donateur', ['donateur'=>$donateur, 'benefs'=>$benefs, 'dons'=>$dons, 'code'=>$code]);
    }

    public function updateForm(Request $req, $code) {
        $donateur = Donateur::where('code', $code)->first();
        if (empty($donateur)) return redirect('/list_donateur');
                     
        return view('donateur.update_donateur', ['donateur'=>$donateur, 'communes'=>$this->communes, 'pays'=>$this->pays]);
    }

    public function update_donateur(Request $req) {
        $input = $req->all();
        $resp = [];

        if (empty($req->code)) {
            $resp['code'] = 'err';
            $resp['msg'] = "Code introuvable.";
            return resp;
        }

        $donateur = Donateur::where('code', $req->code)->first();
        if (empty($donateur)) {
            $resp['code'] = 'err';
            $resp['msg'] = "Code introuvable.";
            return $resp;
        }


        try {      
            $donateur->nom = isset($req->nom) ? $req->nom : '';
            $donateur->prenom = isset($req->prenom) ? $req->prenom : '';
            $donateur->typepers = isset($req->typepers) ? $req->typepers : '';
            $donateur->designation = isset($req->designation) ? $req->designation : '';

            if (isset($req->pwd_upd_confirm) && isset($req->pwd)) {
                $donateur->password = bcrypt($req->pwd);
            }

            
            if ($req->typepers == 'pm') {
                $donateur->nom = '';
                $donateur->prenom = '';
            }
            else {
                $donateur->designation = '';
            }


            $donateur->tel = isset($req->tel) ? $req->tel : '';
            $donateur->cel = isset($req->cel) ? $req->cel : '';
            $donateur->bp = isset($req->bp) ? $req->bp : '';
            $donateur->commune = isset($req->commune) ? $req->commune : '';
            $donateur->pays = isset($req->pays) ? $req->pays : '';
            $donateur->ville = isset($req->ville) ? $req->ville : '';
            $donateur->referent = isset($req->referent) ? $req->referent : '';
            $donateur->email = isset($req->email) ? $req->email : '';
            $donateur->save();
            
            $resp['code'] = 'success';
            return $resp;
        } catch (\Throwable $th) {
            // dd($th);
            $resp['code'] = 'err';
            $resp['msg'] = "Oups! Une erreur s'est produite lors de l'enregistrement en base de données.";
            return $resp;
        }
    }
}
