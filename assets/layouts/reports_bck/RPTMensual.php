<?php
$data = $_POST["data"];
$table = $_POST["table"];
$key = $_POST["key"];
$cod = $_POST["cod"];
$SaldoIVA = json_decode($data)->ivacredito ?: 0;
$OpeISR = json_decode($data)->trimestreOp;

?>
<?php

setlocale(LC_TIME, "ES");

?>
<?php

// Concatenar el dia a las variables de rango de fechas 
$cod2 = $cod . '-01';
$cod3 = $cod . '-31';

// Consulta de Ventas y Compras
    $queryCompras = Controller::$connection->query("SELECT SUM(c.precio_total) AS totalCompras
    FROM carga AS c
    WHERE c.fecha BETWEEN '$cod2' AND '$cod3'");

    $queryVentas = Controller::$connection->query("SELECT SUM(v.total) AS totalVentas 
    FROM venta AS v 
    WHERE v.fecha BETWEEN '$cod2' AND '$cod3'"); 


// Consulta para calcular ISR dependiendo el trimestre seleccionado

switch ($OpeISR) {
    case '1':
        $queryISR = Controller::$connection->query("SELECT SUM(v.total) AS totalVentas 
        FROM venta AS v 
        WHERE v.fecha BETWEEN '20180101' AND '20180331'");
        break;

    case '2':
        $queryISR = Controller::$connection->query("SELECT SUM(v.total) AS totalVentas 
        FROM venta AS v 
        WHERE v.fecha BETWEEN '20180401' AND '20180631'");
        break;

    case '3':
        $queryISR = Controller::$connection->query("SELECT SUM(v.total) AS totalVentas 
        FROM venta AS v 
        WHERE v.fecha BETWEEN '20180701' AND '20180931'");
        break;

    case '4':
        $queryISR = Controller::$connection->query("SELECT SUM(v.total) AS totalVentas 
        FROM venta AS v 
        WHERE v.fecha BETWEEN '20181001' AND '20181231'");
        break;

    default:
        # code...
        break;
}

//  Asignamos la trama de datos a la variable Data
if($queryVentas->rowCount()) {
    $dataVentas = $queryVentas->fetchAll(PDO::FETCH_ASSOC);
}
else {
  die("No hay datos.");
}

if($queryCompras->rowCount()) {
    $dataCompras = $queryCompras->fetchAll(PDO::FETCH_ASSOC);
}
else {
  die("No hay datos.");
}

if($queryISR->rowCount()) {
    $dataISR = $queryISR->fetchAll(PDO::FETCH_ASSOC);
}
else {
  die("No hay datos.");
}


class MYPDF extends TCPDF {

    //Page header
    public function Header() {

        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = '../assets/layouts/reports/images/report.jpg';
        $this->Image(null, 0, 0, 216, 356, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
$pdf->SetSubject('');

$pdf->SetPrintHeader(true);
$pdf->SetPrintFooter(true);

$pdf->SetMargins(18, 18, 18, true);


// add a page
$pdf->AddPage('P', 'LETTER');


// ---------------------------------------------------------

$totalCompras = $dataCompras[0]["totalCompras"];

$totalVentas = $dataVentas[0]["totalVentas"];

    $Compras = 0;
    $IVAxCobrar = 0;

    $Ventas = 0;
    $IVAxPagar = 0;    

    $IVACredito = 0;
    $IVAPago = 0; 

    $ISRCredito = 0;
    $ISRPago = 0; 

// Calculo de Financiero

$Compras = $totalCompras / 1.12;
$comprastotal = round($Compras, 2);

$IVAxCobrar = $Compras * 0.12;
$IVAC = round($IVAxCobrar, 2);


$Ventas = $totalVentas / 1.12;
$ventastotal = round($Ventas, 2);

$IVAxPagar = $Ventas * 0.12;
$IVAP = round($IVAxPagar, 2);

// Calculo de Impuestos IVA

$SaldoIVA += round($IVAxCobrar, 0);
$saldoValidar = $SaldoIVA - round($IVAxPagar, 0);

if($saldoValidar > 0)
    {
        $IVACredito = $saldoValidar;
    }elseif ($saldoValidar < 0) {
        $IVAPago = $saldoValidar * -1;
    }

// Calculo de Impuestos ISR

$pagoISR = round((($dataISR[0]["totalVentas"] * 0.28) * 0.31), 2);


// asigna el trimestre solicitado del ISR
switch ($OpeISR) {
    case '1':
        $trimestre = "Enero - Marzo";
        break;

    case '2':
        $trimestre = "Abril - Junio";
        break;

    case '3':
        $trimestre = "Julio - Septriembre";
        break;

    case '4':
        $trimestre = "Octubre - Diciembre ";
        break;

    default:
        # code...
        break;
}




// define some HTML content with style
$html = <<<EOF

<style>

body{

    font-size: 8px;
}

h1 {

    font-size: 20px;
}

</style>

<html>
<head>
    <title> Venta </title>
</head>
<body>
    <div style="text-align:center; line-height: 1px;"><h1> Informe Financiero del Mes $cod </h1></div>
    <!-- <div> </div>-->
    <div style="text-align:center;"> MIREYA'S SALÓN </div> 
    
    <br>
    <br>

    <!-- Tabla contable de compras y ventas -->
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center'>

        <td><strong> - 1 - </strong>
            <br> 
            <br> 

            <table width="100%" cellpadding="5" border="0" align="lefth">
                <tr align='center'>
                    <td> <strong> Compras </strong> </td>
                    <td> <strong>Q  $comprastotal </strong> </td>
                </tr>

                <tr align='center'>
                    <td> <strong> IVA por Cobrar </strong> </td>
                    <td> <strong> Q   $IVAC </strong> </td>
                </tr>
            </table>

            <table width="100%" cellpadding="5" border="0" align="right">
                <tr align='lefth'>
                    <td> <strong> Caja y Bancos </strong> </td>
                    <td> <strong> Q  $totalCompras </strong> </td>
                </tr>
            </table>

        </td>

    </tr>

    <tr align='center'>
        <td><strong> - 2 - </strong>
        <br> 
        <br> 

            <table width="100%" cellpadding="5" border="0" align="lefth">
                <tr align='center'>
                    <td> <strong> Caja y Bancos </strong> </td>
                    <td> <strong> Q  $ventastotal </strong> </td>
                </tr>
            </table>

            <table width="100%" cellpadding="5" border="0" align="right">
                <tr align='center'>
                    <td> <strong> Ventas </strong> </td>
                    <td> <strong> Q   $ventastotal </strong> </td>
                </tr>

                <tr align='lefth'>
                    <td> <strong> IVA por Pagar </strong> </td>
                    <td> <strong> Q   $IVAP </strong> </td>
                </tr>
            </table>
        </td>
    </tr>
    </table>

    <!-- Tabla contable de impuestos -->
    <br> 
    <br>
    <br> 
    <br>
    <br>  
    <div style="text-align:center; line-height: 1px;"><h1> Cálculo de Impuestos </h1></div>
    <div style="text-align:left; line-height: 1px;"><big><Strong><u>Cálculo de IVA: </u></strong></big></div>
    <div> </div>
    

    <table width="100%" cellpadding="5" border="1" align="center">

        <tr align='center'>

            <td>
                <strong><big> Impuesto </big></strong>
            </td>

            <td>
                <strong><big> Crédito </big></strong>
            </td>

            <td>
                <strong><big> Pago </big></strong>
            </td>

        </tr>

        <tr align='center'>
            <td><strong> IVA </strong>
 
            </td>
            <td>
                <strong> Q   $IVACredito </strong>
            </td>
            <td>
                <strong> Q   $IVAPago </strong>
            </td>
        </tr>
    </table>

    <br>
    <br>
    <br>
    <div></div>

    <div style="text-align:center; line-height: 1px;"><h1>  </h1></div>
    <div style="text-align:center; line-height: 1px;"><h1><Strong>Pago de ISR del trimestre $trimestre:</strong></h1></div>
    <div></div> 

    <table width="100%" cellpadding="5" border="1" align="center">

        <tr align='center'>
            <td>
                <strong> Impuesto </strong>

            </td>
            <td>
                <strong> Pago </strong>

            </td>
        </tr>

        <tr align='center'>
            <td>
                <strong> ISR </strong>
 
            </td>
            <td>
                <strong> Q $pagoISR </strong>
 
            </td>
        </tr>
    </table>

</body>
</html>


EOF;



// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Venta.pdf', 'I');

?>
