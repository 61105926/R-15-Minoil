<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            /* Quitamos el margen */
            /* Establecemos el mismo margen como padding */
            margin-top: 1cm;
            margin-bottom: 1cm;
        }

        .divider {
            width: calc(110%);
            /* El divider tiene un 5% menos de ancho que el cuerpo */
            border: 1px solid green;
            margin: 8px auto;
        }

        .column-container {
            width: 100%;
        }

        .column-70 {
            width: 75%;
            float: left;
            text-align: left;
            font-size: 12px;
            box-sizing: border-box;
            position: relative;
            /* Añadido para posicionar el pseudo-elemento correctamente */
        }

        .column-70::after {
            content: '';
            position: absolute;
            top: 4.5%;
            right: 7px;
            height: 68%;
            width: 0, 1%;
            /* Cambiado a 50% para que ocupe la mitad */
            backg-color: black;
            /* Cambiado de border a backgnumber_format-color */
        }


        .column-30 {
            width: 25%;
            float: left;
            box-sizing: border-box;
            font-size: 11px;
        }

        .signature {
            font-size: 11px;
            font-weight: bold;
            display: block;
            margin-top: 20px;
        }

        .director {
            font-size: 11px;
            font-weight: bold;
        }

        .version {
            font-size: 12px;
            text-align: center;
            margin-top: 8px;
            display: block;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .pagination {
            font-weight: bold;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th,
        td {
            border: none;
            /* Elimina los bordes */
            padding: 1px 8px;
            /* Ajusta el padding para reducir el espacio vertical */
            text-align: left;
        }

        th {
            font-weight: bold;
        }

        footer {
            text-align: center;
            font-size: 11px;
            position: fixed;
            bottom: -15px;
            width: 100%;
            /* Color de fondo opcional */
            /* Espaciado interno opcional */
        }

        footer em {
            font-style: italic;
            /* Aplicar cursiva */
            /* Puedes ajustar el valor según sea necesario */

        }

        header {
            position: fixed;
            top: -1cm;
            left: 0cm;
            right: 0cm;
            height: 4cm;
            /** Extra personal styles **/
            text-align: center;
        }
        .title {
    margin-left: -180px; /* Ajusta este valor según sea necesario */
}
    </style>

</head>

<body>
    <footer>


    </footer>
    <header>
        <?php
        setlocale(LC_TIME, 'es_ES.UTF-8'); // Establecer el idioma español
        
        $fecha_pago = date_create($resultados[0]->FECHA_PAGO); // Crear objeto DateTime
        $mes_pago = strftime('%B', $fecha_pago->getTimestamp()); // Obtener el nombre del mes en español
        // Obtener el nombre del mes en inglés
        $meses = [
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre',
        ];
        $mes_pago_espanol = $meses[$mes_pago]; // Convertir el nombre del mes a español
        $mes_pago_mayusculas = mb_strtoupper($mes_pago_espanol, 'UTF-8'); // Convertir a mayúsculas
        $anio_pago = date('Y', strtotime($resultados[0]->FECHA_PAGO)); // Obtener el año de la fecha de pago
        
        ?>
        <div class="header">
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="max-height: 100px;">
                    <td class="logo" style=" vertical-align: middle; text-align:left;">
                        <div style="display: inline-block; vertical-align: middle;">
                            <img src="{{ public_path('img/logo.png') }}" alt="Logo" style="max-width: 150px;">
                        </div>
                    </td>
                    <td class="title" style="display: flex; align-items: center;margin-left:-80px;margin-top:10px; text-align:center; justify-content: flex-start;">
                        <b style="font-size: 12px">
                            BOLETA DE PAGO
                        </b>
                        <br>
                        <b style="font-size: 18px;">
                        {{ $mes_pago_mayusculas }} {{ $anio_pago }}
                        </b>
                    </td>
                    <td style="width: 1px;text-align:right;"></td>
                    <td class="subtitle" style="text-align: right; padding: 0 8px;">
                        <b style="font-size: 12px">MINOIL</b><br>
                        <label for=""style="font-size: 12px"> {{ $resultados[0]->DEPARTAMENTO }}
                        </label>
                        <br>
                        <label for=""style="font-size: 12px"> {{ $resultados[0]->SUCURSAL }}
                        </label>

                    </td>
                </tr>
            </table>
        </div>

    </header>

    <div style="margin-top: -20px;">
        <table width="100%" border="0" style="font-size: 9px; times; border:1px solid black;margin: 8px auto;">
            <tbody>
                <tr>
                   
                <tr>

                    <td style="width: 25%; text-align:left; padding-top: 8px;"><b>MES: </b></td>
                    <td
                        style="width: 25%;text-align:left; padding-top: 8px;border-top: 1px solid black;border-right: 1px solid black;">
                        {{ $mes_pago_mayusculas }}<span style="margin-left: 60px;"> <b>AÑO:</b>
                            {{ $anio_pago }}</span> </td>
                    <td style="width: 25%;text-align:left; padding-top: 8px; black;border-top: 1px solid black; ">
                        <b>SOCIO DE NEGOCIO:</b>
                    </td>
                    <td style="width: 25%;text-align:left; padding-top: 8px; black;border-top: 1px solid black;">
                        {{ $resultados[0]->CODSN }}</td>
                </tr>
                </tr>
                <tr>
                    <td style="text-align:left;"><b>NOMBRE:</b></td>
                    <td style="text-align:left;;border-right: 1px solid"> {{ $resultados[0]->NOMBRE }}</td>
                    <td style="text-align:left;"><b>NUA:</b></td>
                    <td style="text-align:left;">{{ $resultados[0]->NUA }}</td>
                </tr>
                <tr>
                    <td style="text-align:left;"><b>CI:</b></td>
                    <td style="text-align:left;;border-right: 1px solid">{{ $resultados[0]->CI }}
                    </td>
                    <td style="text-align:left;"><b>GESTORA FONDO SOLIDARIO:</b></td>
                    <td style="text-align:left;">{{ $resultados[0]->AFILIADO }}</td>
                </tr>
                <tr>
                    <td style="text-align:left;"><b>CARGO:</b></td>
                    <td style="text-align:left;;border-right: 1px solid">
                        {{ $resultados[0]->CARGO }}
                    </td>

                    <td style="text-align:left;"><b>DIAS TRABAJADOS </b></td>
                    <td style="text-align:left;">{{ $resultados[0]->DIAS_TRABAJADOS }}</td>
                </tr>
                <tr>
                    <?php
                    $fecha_ingreso = date('d/m/Y', strtotime($resultados[0]->FECHA_INGRESO));
                    ?>
                    <td style="text-align:left;"><b>FECHA INGRESO:</b></td>
                    <td style="text-align:left;border-right: 1px solid">{{ $fecha_ingreso }}</td>
                    <td style="text-align:left;"><b>SALDO A FAVOR RC-IVA:</b></td>
                    <td style="text-align:left;">{{ $resultados[0]->RCIVA }}</td>
                </tr>
                <tr>
                    <td style="text-align:left; padding-bottom: 8px;"></td>
                    <td style="text-align:left; padding-bottom: 8px;border-right: 1px solid"></td>
                    <td style="text-align:left; padding-bottom: 8px;"><b>DIAS DISPONIBLES DE VACACIONES:</b></td>
                    <td style="text-align:left; padding-bottom: 8px;">{{ $resultados[0]->VACACIONES }} Dias</td>
                </tr>
            </tbody>


        </table>

        <table class="table center" width="100%" border="1" cellspacing="2" cellpadding="1"
            style="font-size: 8px; border:1px solid black;margin: 0px auto;">
            <tr>
                <td style="width: 50%;text-align:center;border-right: 1px solid;" colspan="3"><b>INGRESOS</b></td>
                <td style="width: 50%;text-align:center;" colspan="3"><b>DESCUENTOS</b></td>
            </tr>
            <tr>
                <td style="border-top: 1px solid;text-align:center""><b>CONCEPTO</b></td>
                <td style="border-top: 1px solid;text-align:center"><b></b></td>
                <td style="border-right: 1px solid;border-top: 1px solid;text-align:left"><b>IMPORTE</b></td>
                <td style="border-top: 1px solid;text-align:center"><b>CONCEPTO</b></td>
                <td style="border-top: 1px solid;text-align:center"><b></b></td>
                <td style="border-top: 1px solid;text-align:left"><b>IMPORTE</b></td>
            </tr>
            <tr>
                <td style="width: 20%;border-top:1px solid;padding-top: 8px;"><b>Haber
                        Basico</b></td>
                <td style="width: 10%;border-top:1px solid;padding-top: 8px;"></td>
                <td style="width: 20%;border-right: 1px solid;border-top:1px solid;padding-top: 8px;">
                    {{ $resultados[0]->SUELDO_BASICO }}</td>
                <td style="width: 20%;border-top:1px solid;padding-top: 8px;"><b>Gestora Fondo Solidario</b></td>
                <td style="width: 10%;border-top:1px solid;padding-top: 8px;"></td>
                <td style="width: 20%;border-top:1px solid;padding-top: 8px;">
                    {{ $resultados[0]->GPSSLP }}</td>
            </tr>
            <tr>
                <td style=""><b>Bono de Antiguedad</b></td>
                <td style=""></td>
                <td style="border-right:1px solid;">{{ $resultados[0]->BONO_ANTIGUEDAD }}</td>
                <td style=""><b>RC-I.V.A.</b></td>
                <td style=""></td>
                <td style="">{{ $resultados[0]->RCIVA }}</td>
            </tr>
            <tr>
                <td style=""><b>Bonos</b></td>
                <td style=""></td>
                <td style="border-right:1px solid;">{{ $resultados[0]->BONOS }}</td>
                <td style=""><b>Anticipo</b></td>
                <td style=""></td>
                <td style="">{{ $resultados[0]->ANTICIPOS }}</td>
            </tr>
            <tr>
                <td style=""><b>Comisiones Ventas</b></td>
                <td style=""></td>
                <td style="border-right:1px solid;">{{ $resultados[0]->COMIVENTAS }}</td>
                <td style=""><b>Multas</b></td>
                <td style="position: relative;">
                    <div style="position: absolute; top: 0; left: -40px;"> <!-- Ajusta la posición a la izquierda según tu necesidad -->
                        <img src="{{ public_path('img/logo.png') }}" alt="Descripción de la imagen" style="width: 100px; height: auto;">
                    </div>
                </td>
                <td style="">{{ $resultados[0]->MULTAS }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 70px;"></td>
                <td style="padding-bottom: 70px;"></td>
                <td style="border-right:1px solid;padding-bottom: 70px;"></td>
                <td style="padding-bottom: 70px;"><b>Prestamos</b></td>
                <td style="padding-bottom: 70px;"></td>
                <td style="padding-bottom: 70px;">{{ $resultados[0]->PRESTAMOS }}</td>
            </tr>
            <tr>
                <td style="text-align:center;border-top: 1px solid;"><b>TOTAL GANADO</b></td>
                <td style="border-top:1px solid;"></td>
                <td style="border-top:1px solid;border-right: 1px solid;">{{ $resultados[0]->TOTAL_INGRESOS }}</td>
                <td style="text-align:center;border-top: 1px solid;"><b>TOTAL DESCUENTO</b></td>
                <td style="border-top:1px solid;"></td>
                <td style="border-top:1px solid;">{{ $resultados[0]->TOTAL_DESCUENTOS }}</td>
            </tr>
            
         
        </table>


    </div>
    <br>

    <div style="width: 50%;margin-top: -10px; ">
        <table class="table" width="100%" border="1" style="font-size: 8px; border: 1px solid black;">
            <tr>
                <td colspan="1" style="width: 50%; border-top: 1px solid; padding-top: 8px;"><b>LIQUIDO A PAGAR:
                    </b></td>
                <td
                    style="width: 50%;text-align:right; border-right: 1px solid; border-top: 1px solid; padding-top: 8px;">
                    {{ $resultados[0]->LIQUIDO_PAGABLE }}</td>
            </tr>
        </table>
    </div>
    <div style="width: 50%; margin-top: 20px; margin-left: auto; margin-right: 0;">
        <table class="table" width="100%"  style="font-size: 8px;  text-align: center;">
            <tr>
                <hr style="width: 100px; border-top: 1px solid black !important;">
                <label for="" style="text-align:center
">FIRMA COLABORADOR</label>                 
            </tr>
        </table>
    </div>
    

</body>


</html>
