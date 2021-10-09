<?php

$data = $_POST["data"];
$table = $_POST["table"];
$key = $_POST["key"];
$cod = $_POST["cod"];


?>
<?php

setlocale(LC_TIME, "ES");

?>
<?php

// Consulta de Ventas
$queryVenta = Controller::$connection->query("SELECT v.idventa, v.idcliente, c.nombre as nombreCliente, v.total,  v.fecha, v.idtipo_venta, tp.nombre AS nombreVenta 
from venta as v
inner join cliente as c on v.idcliente = c.idcliente
inner join tipo_venta as tp on tp.idtipo_venta = v.idtipo_venta
where v.idtipo_venta = '2' AND v.fecha = '$cod' order by v.idventa asc");

//  Asignamos la trama de datos a la variable Data
if($queryVenta->rowCount()) {
    $dataVenta = $queryVenta->fetchAll(PDO::FETCH_ASSOC);
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


$idventa = $dataVenta[0]["idventa"];
$cliente = $dataVenta[0]["idcliente"];
$nombreCliente = $dataVenta[0]["nombreCliente"];
$nombreTipoVenta = $dataVenta[0]["nombreVenta"];
$total = $dataVenta[0]["total"];

$fecha = $dataVenta[0]["fecha"];


$detalle = "";
$detalle2 = "";


  $totalContado = 0;
  $totalCredito = 0;

  // Llena el reporte con las ventas diarias
foreach($dataVenta as $key => $value) {

    $detalle .= "<tr>

    <td>".$value["idventa"]."</td>
    <td>".$value["idcliente"]."</td>
    <td>".$value["nombreCliente"]."</td>
    <td>"."Q. ".$value["total"]."</td>

    </tr>";

    if($value["idtipo_venta"] == 1)
      {
        $totalContado += $value["total"];
      }else if($value["idtipo_venta"] == 2){
        $totalCredito += $value["total"];
      }
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
    <div style="text-align:center; line-height: 1px;"><h1> Reporte Ventas al Crédito </h1></div>
    
    <div style="text-align:center;"> MIREYA'S SALÓN </div> 
    <div> </div>
    <br>

    <div style="text-align:left;"><strong><big><u>Ventas al $nombreTipoVenta:</u></big></strong></div>
    <div style="text-align:left;"><strong><u>Fecha:</u></strong> $fecha </div>
    
    <br>

    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center'>
        <td><strong>ID Venta </strong></td>
        <td><strong>ID Cliete</strong></td>
        <td><strong>Nombre</strong></td>
        <td><strong>Total de Venta</strong></td>

    </tr>

        $detalle

    </table>

    <br>
    <br>
    <br>

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
