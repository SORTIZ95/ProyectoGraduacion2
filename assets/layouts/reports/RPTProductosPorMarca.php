<?php

$data = $_POST["data"];
$table = $_POST["table"];
$key = $_POST["key"];
$cod = $_POST["cod"];

$idmarca = json_decode($data)->marca;


?>
<?php

setlocale(LC_TIME, "ES");

?>
<?php

// Consulta de Ventas
$queryMarca = Controller::$connection->query("SELECT p.idproducto as idproducto, p.nombre as nomproducto, m.NOMBRE as nommarca, 
(sum(i.ingreso)-sum(i.egreso)) as existencia 
FROM producto as p 
INNER JOIN marca as m
on m.IDMARCA = p.marca
INNER JOIN inventario as i
on i.idproducto = p.idproducto
WHERE m.IDMARCA = '$idmarca'
GROUP BY p.idproducto
ORDER BY m.NOMBRE");



//  Asignamos la trama de datos a la variable Data
if($queryMarca->rowCount()) {
    $dataMarca = $queryMarca->fetchAll(PDO::FETCH_ASSOC);
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


$idProducto = $dataMarca[0]["idproducto"];
$nombreProducto = $dataMarca[0]["nomproducto"];
$nombreMarca = $dataMarca[0]["nommarca"];
$existencia = $dataMarca[0]["existencia"];


$detalle1 = " ";



  // Llena el reporte con los datos de los clietes morosos.

  foreach($dataMarca as $key => $value) {

    $detalle1 .= "<tr>

    <td>".$value["idproducto"]."</td>
    <td>".$value["nomproducto"]."</td>
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
    <title>Productos por Marca</title>
</head>
<body>
<div style="text-align:center; line-height: 3px;"><h1> Reporte Productos por Marca</h1></div>
<div style="text-align:center; line-height: 3px;"><h2> AGROSERVICIO EL REGADILLO </h2></div>    
<div style="text-align:center; line-height: 3px;"><h3>San Miguel Conacaste, Sanarate, El Progreso</h3></div>

 
    <br>
    <h2>Marca: $nombreMarca </h2>
    
   
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center' class="encabezado">

        <td><strong>ID Producto</strong></td>
        <td><strong>Producto</strong></td>
        <td><strong>Existencia</strong></td>
 
        

    </tr>

        $detalle1

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
$pdf->Output('RPTProductosPorMarca.pdf', 'I');

?>
