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
$queryCompras = Controller::$connection->query("SELECT P.idProveedor, P.nombre, P.nombreComercial, P.saldo
    FROM proveedor AS P
    WHERE P.saldo > '0'");

//  Asignamos la trama de datos a la variable Data
if($queryCompras->rowCount()) {
    $dataCompras = $queryCompras->fetchAll(PDO::FETCH_ASSOC);
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

$detalle = "";

  // Llena el reporte con las ventas diarias
foreach($dataCompras as $key => $value) {

    $detalle .= "<tr>

    <td>".$value["idProveedor"]."</td>
    <td>".$value["nombre"]."</td>
    <td>".$value["nombreComercial"]."</td>
    <td>"."Q. ".$value["saldo"]."</td>

    </tr>";

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
    <div style="text-align:center; line-height: 1px;"><h1> Reporte de Deuda </h1></div>
    
    <div style="text-align:center;"> MIREYA'S SALÓN </div> 
    <div> </div>
    <br>

    <div style="text-align:left;"><strong><big><u>Deuda a Proveedores:</u></big></strong></div>
    
    <br>

    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center'>
        <td><strong>ID Proveedor </strong></td>
        <td><strong>Nombre</strong></td>
        <td><strong>Nombre Comercial</strong></td>
        <td><strong>Saldo Adeudado</strong></td>

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
