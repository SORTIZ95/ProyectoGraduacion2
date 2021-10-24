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

// Concatenar el dia a las variables de rango de fechas 

$queryVenta = Controller::$connection->query("SELECT v.fecha, v.idventa, v.idtipo_venta, v.idcliente, v.total, c.nombre as nombreCliente, tv.nombre as nombreTipoVenta from venta as v
  inner join cliente AS c ON c.idcliente = v.idcliente
  inner join tipo_venta AS tv ON tv.idtipo_venta = v.idtipo_venta
  where v.fecha = curdate()");


if($queryVenta->rowCount()) {

    $dataVenta = $queryVenta->fetchAll(PDO::FETCH_ASSOC);
    
}
else {
  include_once("manejo_errores/view_err_sindatos.php");
  die();

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


$fecha = $dataVenta[0]["fecha"];

// declaro variable detalle
$detalle = "";

// Carga los productos a la variable detalle
foreach($dataVenta as $key => $value) {

    $detalle .= "<tr>

    <td>".$value["idventa"]."</td>
    <td>".$value["idcliente"]."</td>
    <td>".$value["nombreCliente"]."</td>
    <td>".$value["nombreTipoVenta"]."</td>
    <td>"."Q. ".$value["total"]."</td>

    </tr>";
}


// define some HTML content with style
$html = <<<EOF

<style>

body{

    font-size: 14px;
}

h1 {

    font-size: 24px;
}

</style>

<html>
<head>
    <title> Venta </title>
</head>
<body>
    <div style="text-align:center; line-height: 1px;"><h1> VENTAS DEL DIA </h1></div>
    <div style="text-align:center; line-height: 3px;"><h2> SAN MIGUEL CONACASTE </h2></div>    
    <div style="text-align:center; line-height: 3px;"><h3>San Miguel Conacaste, Sanarate, El Progreso</h3></div>
    
    <div style="text-align:left;"><h1> Fecha: $fecha </h1></div>
    <table width="100%" border="1" align="center">

    <tr align='center'>
        <td><strong>Número de Venta</strong></td>
        <td><strong>Código Cliente</strong></td>
        <td><strong>Nombre Cliente</strong></td>
        <td><strong>Tipo de Venta</strong></td>
        <td><strong>Total</strong></td>
    </tr>

        $detalle

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
$pdf->Output('VentasHoy.pdf', 'I');

?>
