<?php

$data = $_POST["data"];
$table = $_POST["table"];
$key = $_POST["key"];
$cod = $_POST["cod"];

$idproducto = json_decode($data)->producto;
$cod2 = json_decode($data)->fecha_2;
?>
<?php

setlocale(LC_TIME, "ES");

?>
<?php

//Asigna a variables string de la trama


$queryProductos = Controller::$connection->query("SELECT i.fecha, i.idproducto, p.nombre, i.tipoMovimiento, i.ingreso, i.egreso, i.existencia
        FROM inventario AS i
        INNER JOIN producto AS p ON p.idproducto = i.idproducto
        WHERE i.fecha BETWEEN '$cod' AND '$cod2' 
            AND i.idproducto = '$idproducto'");

if($queryProductos->rowCount()) {

    $dataProductos = $queryProductos->fetchAll(PDO::FETCH_ASSOC);

}else {
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


// declaro variable detalle
$detalle = "";

$produ = $dataProductos[0]["nombre"];

// Carga los productos a la variable detalle
    foreach($dataProductos as $key => $value) {

      $detalle .= "<tr>

      <td>".$value["fecha"]."</td>
      <td>".$value["nombre"]."</td>
      <td>".$value["tipoMovimiento"]."</td>
      <td>".$value["ingreso"]."</td>
      <td>".$value["egreso"]."</td>
      <td>".$value["existencia"]."</td>

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
    <title> Movimiento Productos </title>
</head>
<body>
    <div style="text-align:center; line-height: 1px;"><h1> Reporte de Movimiento</h1></div>
    <div style="text-align:center; line-height: 3px;"><h2> MIREYA'S SALÓN </h2></div>    
    <div style="text-align:center; line-height: 3px;"><h3>Barrio El Porvenir, Guastatoya, EL Progreso</h3></div>
    
    <div> </div>
    <div style="text-align:center; line-height: 1px;"><h2> Del Producto: <u>"$produ"</u> </h2></div>
    <div> </div>
    <div style="text-align:center; line-height: 1px;"><h2> De la Fecha <u>"$cod"</u> para la Fecha <u>"$cod2"</u> </h2></div>

    <div> </div>

    <h3>Movimiento:</h3>
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center'>
        <td><strong><big>Fecha</big></strong></td>
        <td><strong><big>Nombre del Producto</big></strong></td>
        <td><strong><big>Tipo de Movimiento</big></strong></td>
        <td><strong><big>Ingreso</big></strong></td>
        <td><strong><big>Egreso</big></strong></td>
        <td><strong><big>Existencia</big></strong></td>
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
$pdf->Output('RPTMovimientoProducto.pdf', 'I');

?>
