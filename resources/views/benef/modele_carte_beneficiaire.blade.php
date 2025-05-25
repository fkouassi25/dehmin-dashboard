<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Beneficiaire - <span style="text-transform:uppercase;">{{ $benef->nom }}</span>  {{ $benef->prenom }} </title>
    <style>
    @page { size: 53.98mm 85.9mm; margin: 0in; }

    body {
        padding:0;
        margin:0;
        width:100%;
        height: 100%;
    }

    #main {
        padding:0;
        margin:0;
        width:100%;
    }

    #model {
        width: 100%;
        height: auto;
        position: absolute;
        top:0;
        left:0;
    }

    #qr-code {
        z-index:2;
        width: auto;
        height:auto;
        position: absolute;
        top: 55px;
        left:28%;
    }

    p {text-align: center;z-index:100;width:100%; font-size:.7em;}
    #nom {position:absolute; top:165px;}
    #commune {position:absolute; top:190px;}
    #cni {position:absolute; top:215px;}
    #cel {position:absolute; top:235px;}

  </style>
</head>
<body>
    <div id="main">
        <!-- <img id="qr-code" src="../resources/views/benef/qr-code.png" alt="QR Code"> -->
        <img src="../resources/views/benef/model.jpg" id="model" alt="">
        <img id="qr-code" alt="QR Code" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(80)->generate($benef->code)) !!} ">
        
        <p id="nom">
            <b> <span style="text-transform:uppercase;">{{ $benef->nom }}</span>  {{ $benef->prenom }} </b>
        </p>
        
        <p id="commune">
            <b>Commune: </b> {{ $benef->commune }}
        </p>
        
        <p id="cni">
            <b>CNI: </b> {{ $benef->cni }}
        </p>
        
        <p id="cel">
            <b>Cel: </b> {{ $benef->cel }}
        </p>
    </div>
</body>
</html>