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


$queryProductos = Controller::$connection->query("SELECT p.idproducto, p.nombre 
                                                    FROM producto p");

if($queryProductos->rowCount()) {

    $dataProductos = $queryProductos->fetchAll(PDO::FETCH_ASSOC);

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


// declaro variable detalle
$detalle = "";

// Carga los productos a la variable detalle
    foreach($dataProductos as $key => $value) {

    $idproducto = $value["idproducto"];
    $queryExistencia = Controller::$connection->query("SELECT i.existencia
    FROM inventario i 
    WHERE i.idproducto = '$idproducto'
    ORDER BY i.idInventario DESC
    LIMIT 1");        

    $dataExistencia = $queryExistencia->fetch(PDO::FETCH_ASSOC);

      $detalle .= "<tr>

      <td>".$value["idproducto"]."</td>
      <td>".$value["nombre"]."</td>
      <td>".$dataExistencia["existencia"]."</td>

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
    <title> Existencia </title>
</head>
<body>
    <div style="text-align:center; line-height: 1px;"><h1> Reporte de Existencia de Productos </h1></div>
    <div style="text-align:center; line-height: 3px;"><h2> AGROSERVICIO EL REGADILLO </h2></div>    
    <div style="text-align:center; line-height: 3px;"><h3>San Miguel Conacaste, Sanarate, El Progreso</h3></div>
    
    <div> </div>
    
    <br>

    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center'>
        <td><strong><big>Código de Producto</big></strong></td>
        <td><strong><big>Nombre del Producto</big></strong></td>
        <td><strong><big>Cantidad</big></strong></td>
    </tr>

        $detalle

    </table>

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
$pdf->Output('RPTExistencia.pdf', 'I');

?>
