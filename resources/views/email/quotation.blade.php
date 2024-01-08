<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud cotizacion</title>

    <style>
        @page
        {
            margin: 0cm 0cm;
        }
        body
        {
            width: 100vw;
            height:100vh;
        }

        .logo
        {
            object-fit: contain;
            width:32%;
            height:130px;
        }

        .subtitle
        {
            font-weight: bolder;
        }

        p
        {
            width:50%;
        }
    </style>
</head>
<body>
    <img class="logo" src=src="https://satia-prod.s3.amazonaws.com/default/soluciones.png" alt="logo"/>
    <br>
    <h1>Solicitud de cotización</h1>
    <br>

    <label class="subtitle">Folio seguimiento: </label>
    <label>{{$request['quotation']['id']}}</label>
    <br>
    <label class="subtitle">Empresa/persona: </label>
    <label>{{$request['contact']['name']}}</label>
    <br>
    <label class="subtitle">Correo: </label>
    <label>{{$request['contact']['email']}}</label>
    <br>
    <label class="subtitle">Telefono: </label>
    <label>{{$request['contact']['phone']}}</label>
    <br>
    <label class="subtitle">CP: </label>
    <label>{{$request['contact']['cp']}}</label>

    <br>
    <p>
        Es necesario revisar en el sistema en el apartado de cotizaciones web la solicitud de
        cotización generada en el sitio web con el folio de seguimiento {{$request['quotation']['id']}}
    </p>
</body>
</html>
