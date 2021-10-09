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


//Consultas a la ba$dataDevolucionse de datos
$queryDevolucion = Controller::$connection->query("SELECT p.nombre as nomproducto, sum(de.cantidad) as cantidad, 
sum(de.subtotal) as totaldevolucion
FROM devolucion as d
INNER JOIN detalle_devolucion as de
on de.id_devolucion = d.id_devolucion
INNER JOIN producto as p
on p.idproducto = de.idproducto
GROUP BY p.idproducto
ORDER BY sum(de.cantidad)
LIMIT 25
");



if($queryDevolucion->rowCount()) {

    $dataDevolucion = $queryDevolucion->fetchAll(PDO::FETCH_ASSOC);

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


$nomProducto = $dataDevolucion[0]["nomproducto"];
$cantidad = $dataDevolucion[0]["cantidad"];
$totaldevolucion = $dataDevolucion[0]["totaldevolucion"];

// declaro variable detalle
$detalle = "";

// Carga los productos a la variable detalle
    foreach($dataDevolucion as $key => $value) {

      $detalle .= "<tr>

      <td>".$value["nomproducto"]."</td>
      <td>".$value["cantidad"]."</td>
      <td>".$value["totaldevolucion"]."</td>
      
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

.encabezado td{
    background-color: green;
    color: white;
    font-size: 1.1em;
}

.encabezado2 td{
    background-color: yellow;
    color: black;
    font-size: 1.1em;
}

.encabezado3 td{
    background-color: orange;
    color: black;
    font-size: 1.1em;
}

.encabezado4 td{
    background-color: red;
    color: black;
    font-size: 1.1em;
}

</style>

<html>
<head>
    <title>Productos con mas Devoluciones</title>
</head>
<body>
<div style="text-align:center; line-height: 3px;"><h1>Productos con Mas Devoluciones</h1></div>
<div style="text-align:center; line-height: 3px;"><h2> MIREYA'S SALÓN </h2></div>    
<div style="text-align:center; line-height: 3px;"><h3>Barrio El Porvenir, Guastatoya, El Progreso</h3></div>


    <br> 
    <h4>Detalle Productos Mas Devueltos</h4> 

    <br>
    
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center' class="encabezado">

        <td><strong>Producto</strong></td>
        <td><strong>Cantidad Total</strong></td>
        <td><strong>Total Devoluciones en Quetzales</strong></td>
        
        

    </tr>

        $detalle

    </table>
    
    <br>
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
$pdf->Output('RPTProductosMasDevueltos.pdf', 'I');

?>
