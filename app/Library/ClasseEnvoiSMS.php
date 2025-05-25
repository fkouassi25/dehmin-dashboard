<?php /** @noinspection LossyEncoding */

namespace App\Library;

use GuzzleHttp\Client;

// require('vendor/autoload.php');

class ClasseEnvoiSMS
{
    //input parameters ---------------------
    var $login = "contact@dehmin.org";                           //Votre Login SMSECO (adresse email)
    var $password = "Dehm1CI";                       //Votre Mot de Passe SMSECO
    var $expediteur = "DEHMIN";                    //Votre Nom exp�diteur ou ID SENDER. Doit �tre au pr�alable enregistr� sur smseco (voir la section noms exp�diteurs)
    var $msgid;                        //ID du message n�c�ssaire pour les accus�s de reception
    var $message;                     //le message � envoyer
    var $flash;                      //message flash (1 ou 0) Optionnel
    var $unicode;                   //message unicode (1 ou 0) Optionnel
    var $binaire;                   //message en binaire (1 ou 0) Optionnel
    var $destinataires = array();  //liste des num�ros des destinataires
    var $apikey = '';
    var $url;
    var $host;
    var $path;
    var $XMLgsmnumbers;
    var $xmldata;
    var $request_data;
    var $response;

    // private $login = "contact@dehmin.org";
    // private $password = "Dehm1CI";
    // private $expediteur = "DEHMIN";


    function EnvoiSMS($msgid, $login, $password, $expediteur, $message, $destinataires, $flash = 0, $unicode = 0, $binaire = 0)
    {
        $this->login = $login;
        $this->password = $password;
        $this->msgid = str_replace("_", "-", $msgid);
        $this->expediteur = $expediteur;
        $this->message = $message;
        $this->flash = $flash;
        $this->destinataires = $destinataires;
        $this->unicode = $unicode;
        $this->binaire = $binaire;

        $this->expediteur = str_replace("+", "%2b", $this->expediteur);
        $this->message = str_replace("+", "%2b", $this->message);
        $this->expediteur = str_replace("&", "%26", $this->expediteur);
        $this->message = str_replace("&", "%26", $this->message);

        $this->host = "www.smseco.com"; // Mettre www.smseco.com kan c'est pas une white page
        //$this->path = "/XML/XMLdatas.php";
        $this->path = "/api/xml/sendsms/";
        $this->url = $this->host . $this->path;

        $this->convertGSMnumberstoXML();
        $this->prepareXMLdata();
        //$this->response = $this->doPost($this->path, $this->request_data, $this->host);
        $this->response = $this->sendSmsRequest($this->url, $this->request_data);
        return $this->response;
    }

    function convertGSMnumberstoXML()
    {
        $gsmcount = count($this->destinataires);
        $msg_user = "";
        for ($i = 0; $i < $gsmcount; $i++) {
            $numero = $this->destinataires[$i];
            $this->XMLgsmnumbers .= "<numero>$numero</numero>";
        }
    }

    
    function prepareXMLdata()
    {
        $this->xmldata = "<SMS><compte><login>" . $this->login . "</login><password>" . $this->password . "</password></compte><message><msgid>" . $this->msgid . "</msgid><expediteur>" . $this->expediteur . "</expediteur>";
        $this->xmldata .= "<msg>" . $this->message . "</msg>";
        if ($this->flash) $this->xmldata .= "<flash>" . $this->flash . "</flash>";
        if ($this->unicode) $this->xmldata .= "<unicode>" . $this->unicode . "</unicode>";
        if ($this->binaire) $this->xmldata .= "<binaire>" . $this->binaire . "</binaire>";
        $this->xmldata .= "</message><destinataires>" . $this->XMLgsmnumbers . "</destinataires></SMS>";

        $this->request_data = 'XML=' . $this->xmldata;

    }

    public function prepareJson()
    {
        $JSON = '{
            "compte":{
                "login":"xxx","password":"xxx"
                },
            "message":{
            "expediteur":"xx",
            "msgid":"xx",
            "msg":"xx",
            "datesend":"xx",
            "flash":"" ,
            "unicode":"",
            "binaire":""
            },
            "destinataires":[
                {"numero":"xx"},
                {"numero":"xx"}
            ]
            }';

        $compte['login'] = $this->login;
    }

    /**
     * @param $url
     * @param $body
     * @return string
     */
    function sendSmsRequest($url, $body)
    {
        try {
            // dd($url);
            $guzzleClient = new Client();

            // dd($body);

            $wsSendSms = $guzzleClient->post($url,
                ['body' => $body]
            );
            $responseParseXml = simplexml_load_string($wsSendSms->getBody()->getContents());
            $response = $responseParseXml->statut;
            return $response;
        } catch (RequestException $e) {
            $response = "Une erreur est survenue lors de l'envoidu sms.  Code erreur:" . $e->getCode();
        }
        return $response;
    }

    function doPost($uri, $postdata, $host)
    {
        echo $postdata;

        $da = @fsockopen($host, 80, $errno, $errstr);
        $response = "";
        if (!$da) {
            return "$errstr ($errno)";
        } else {
            $salida = "POST $uri  HTTP/1.1\r\n";
            $salida .= "Host: $host\r\n";
            $salida .= "User-Agent: PHP Script\r\n";
            $salida .= "Content-Type: text/xml\r\n";
            $salida .= "Content-Length: " . strlen($postdata) . "\r\n";
            $salida .= "Connection: close\r\n\r\n";
            $salida .= $postdata;
            fwrite($da, $salida);
            while (!feof($da)) $response .= fgets($da, 128);
            $response = explode("\r\n\r\n", $response);
            print_r($response);
            $header = $response[0];
            $responsecontent = $response[1];
            if (!(strpos($header, "Transfer-Encoding: chunked") === false)) {
                $aux = explode("\r\n", $responsecontent);
                for ($i = 0; $i < count($aux); $i++)
                    if ($i == 0 || ($i % 2 == 0)) $aux[$i] = "";
                $responsecontent = implode("", $aux);
            }
            return $responsecontent;
        }
    }

    // function test() {
    //     return 'ok';
    // }

    function notif_beneficiaire($numbon, $typebon, $montant, $mobile) {
        $cel = str_replace(' ', '', $mobile);
        if ($cel == "") return;
        
        $msg = "Vous venez de recevoir un bon ".$typebon." offert par l association DEHMIN. Code Bon: ".$numbon." Valeur bon ".$montant." f. Info 58142720. Que Dieu prenne soin de nous.";
        

        // $destinataires = [];
        // array_push($destinataires, $cel);
        // $reponse = $this->EnvoiSMS("", $this->login, $this->password, $this->expediteur, $msg, $destinataires, 0);
        // if ($reponse == 1) {
        //     return "success";
        // } else {
        //     return "err";
        // }
        
        $this->send_with_api_mtn($msg, $cel);
        return "success";
        
    }
    
    function notif_benef_signup($code, $mobile) {
        $resp = ['statut'=>'success'];
        try {
            $cel = str_replace(' ', '', $mobile);
            if ($cel == "") {
                $resp['statut'] = 'err';
                $resp['msg'] = "Aucun numéro renseigné pour l'envoi du SMS.";
            }
            else {
                $msg = "Vous allez beneficier du programme DEHMIN. Notez votre identifiant ".$code.". Appel ou SMS au 58142720 pour connaitre la boutique ou echanger vos bons.";     
                $this->send_with_api_mtn($msg, $cel);
            }
        } catch (\Throwable $th) {
            $resp['statut'] = 'err';
            $resp['msg'] = "Une erreur s'est produite lors de l'envoi du SMS.";
        }
        return $resp;
    }
    
    function send_with_api_mtn($msg, $dest) {
        $mtnClient = new Client();
        //$cid = "4499";
        $cid = "0070";
        $username = "ACHI";
        $pwd = "Password001";
        $originator = "DEHMIN";
        // $msg = "Ceci est le dernier test";
        // $dest = "08433162";

        $response = $mtnClient->get('https://smspro.mtn.ci/bms/soap/messenger.asmx/HTTP_SendSms?customerID='
        .$cid.'&userName='.$username.'&userPassword='.$pwd.'&originator='.$originator.'&messageType=Latin&defDate=20141103170000&blink=false&flash=false&private=false&smsText='.$msg.'&recipientPhone='.$dest);
        
        $resp = json_decode($response->getBody());
    }
    
    function test_api_mtn() {
        $client = new Client();
        $cid = "0070";
        $username = "ACHI";
        $pwd = "Password001";
        $originator = "DEHMIN";
        $msg = "Vous venez de recevoir un bon Sante offert par l association DEHMIN. Code Bon: E3JXTZ2X30 Valeur bon 225 f. Info 58142720. Que Dieu prenne soin de nous.";
        
        // dd(strlen($msg));
        
        $dest = "0707478117";
        
        // $url = "https://smspro.mtn.ci/bms/soap/messenger.asmx/HTTP_SendSms?customerID='
        // .$cid.'&userName='.$username.'&userPassword='.$pwd.'&originator='.$originator.'&messageType=Latin&defDate=20141103170000&blink=false&flash=false&private=false&smsText='.$msg.'&recipientPhone='.$dest";
        // dd($url);

        $response = $client->get('https://smspro.mtn.ci/bms/soap/messenger.asmx/HTTP_SendSms?customerID='
        .$cid.'&userName='.$username.'&userPassword='.$pwd.'&originator='.$originator.'&messageType=Latin&defDate=20141103170000&blink=false&flash=false&private=false&smsText='.$msg.'&recipientPhone='.$dest);
        
        //dd($response);
        $resp = json_decode($response->getBody());
        
    }
}

?>