<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Folio Solicitud</title>
        <style>
            @page 
            {
                margin: 0cm 0cm;
            }

            header 
            {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                width:100%;
                height: 30px;
                color: rgba(0, 0, 0,0.7);
            }

            footer 
            {
                font-size: 12px;
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 40px;
                line-height: 40px;

                padding: 0 30px;
                padding-bottom: 15px;
                color: rgba(0, 0, 0,0.7);
            }

            body
            {
                position: relative;
                height: 100%;
                width: 100%; 
                padding:30px;
                padding-bottom:55px;
            }

            table 
            {
                width: 92.5%; 
                border-collapse: collapse;
                font-family: Tahoma, Geneva, sans-serif;
            }

            table td 
            {
                text-align: center; 
                padding: 5px;
                color: rgba(0, 0, 0,0.9);
            }

            table thead td 
            {
                background-color: rgb(0, 0, 0);
                color: rgb(255, 255, 255);
                font-weight: bold;
                font-size: 12px;
                border: none;
            }
            
            table tbody td 
            {
                color: #636363;
                font-size: 9px;
                border: 1px solid #dddfe1;
            }

            table tbody tr 
            {
                background-color: #f9fafb;
            }

            table tbody tr:nth-child(odd) 
            {
                background-color: #ffffff;
            }

            .line
            {
                height: 3px;
                width: 100%;
                background-color:rgb(219, 168, 0);
            }

            .line_head1,
            .line_head2
            {
                height: 15px;
                width: 51%;
                background-color:rgb(219, 168, 0);
                display: inline-block;
            }

            .line_head2
            {
                width: 8%; 
            }

            .logo
            {
                object-fit: contain;
                width:32%;
                height:130px;
            }

            .grid_detail
            {
                padding: 10px;
                width:57.5%;
                height:130px;

                display: inline-block;
                background-color:rgb(250, 250, 250);
            }
            
            .title
            {
                height: 30px;
                text-align: center;
                color:black;
                font-size:30px;
                margin: 0 10px;
            }

            .sub-title
            {
                height: 15px;
                text-align: start;
                color:black;
                font-size:12px;

                margin-bottom: 10px;
            }

            .grid_title
            {
                height: 40px;
                width: 100%;
            }

            .form_item
            {
                height: 40px;
                margin-bottom: 10px;
            }

            .cont_form
            {
                width: 100%;
                margin-bottom: 0;
            }

            .cont_form .form_item
            {
                width:15%;
                display: inline-block;
            }

            .matLabel
            {
                font-size: 10px;
                color:rgb(112, 40, 2);
            }

            .matInput
            {
                font-size: 15px;
            }

            .header_title
            {
                font-size: 12px;
                padding-top: 15px;
                padding-left: 30px;
                text-align: start;
                width: 75%;
                display:inline-block;
            }

            .header_date
            {
                font-size: 12px;
                padding-top: 15px;
                display:inline-block;
            }

            .present_text
            {
                width: 92.5%; 
                font-size: 12px;
                text-align: justify;
            }
        </style>
    </head>
    <body>
        
        <header>
            <div class="header_title">Folio: {{$quotation_web['id']}}</div>
            <div class="header_date">{{$currentTime}}</div>
        </header>

        <footer>
            <div class="line"></div>
            <label>Priv. 6a Sur 2914-Oficina No. 303, Ladrillera de Benítez, 72530 Puebla, Pue.</label><br>
            <label>22-28-94-71-94</label>
            <script type="text/php">
                if (isset($pdf) ) 
                {
                    $pdf->page_script('
                        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                        $pdf->text(560, 816, "$PAGE_NUM:$PAGE_COUNT", $font, 10);
                    ');
                }
            </script>
        </footer>

        <div class="grid_title">
            <div class="line_head1"></div><label class="title">Solicitud Cotización</label><div class="line_head2"></div>
        </div>
        <br><br>

        <img class="logo" src="files/default/datos_hidraulicas.png"/>

        <div class="grid_detail">
            <div class="form_item">
                <div class="matLabel">Contacto:</div>
                <div class="matInput">{{$contact_web['name']}}</div>
            </div>

            <div class="form_item">
                <div class="matLabel">Correo:</div>
                <div class="matInput">{{$contact_web['email']}}</div>
            </div>

            <div class="form_item">
                <div class="matLabel">Teléfono:</div>
                <div class="matInput">{{$contact_web['phone']}}</div>
            </div>
        </div>

        <div class="present_text">
            Atendiendo a su amable solicitud, ponemos el siguiente folio de seguimiento por los conceptos adjuntos en este mismo archivo, 
            con un tiempo aproximado de respuesta de 24 hrs, para nosotros es un placer atenderle y quedando a sus órdenes para cualquier aclaración.
        </div>
        <br>

        <table>
            <thead>
                <tr>
                    <td>Clave</td>
                    <td>Producto</td>
                    <td>Descripción</td>
                    <td>Cantidad</td>
                </tr>
            </thead>
            <tbody>
                @foreach($listData as $itrData)
                    <tr>
                        <td>{{$itrData['key']}}</td>   
                        <td>{{$itrData['name']}}</td>            
                        <td>{{$itrData['description']}}</td>            
                        <td>{{$itrData['quantity']}}</td>  
                    </tr>
                @endforeach
            </tbody>

        </table>
    </body>
</html>

