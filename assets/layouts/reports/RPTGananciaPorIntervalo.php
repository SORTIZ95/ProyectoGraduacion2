<?php

$data = $_POST["data"];
$table = $_POST["table"];
$key = $_POST["key"];
$cod = $_POST["cod"];

$fecha1 = json_decode($data)->fecha_1a;
$fecha2 = json_decode($data)->fecha_2a;
 

?>
<?php

setlocale(LC_TIME, "ES");
//print_r($data);



?>
<?php



// Consulta de Ventas por Intervalo de Fecha
$queryGanancia = Controller::$connection->query("SELECT ROUND(sum(d.subtotal),2) as venta, 
ROUND(sum(d.cantidad*p.preciocosto),2)as costos,
ROUND((sum(d.subtotal)-sum(d.cantidad*p.preciocosto)),2) as ganancia
FROM detalle_venta as d
INNER JOIN venta as v
on d.idventa = v.idventa
INNER JOIN producto as p
on p.idproducto = d.idproducto
where v.fecha BETWEEN '$fecha1' AND '$fecha2'
");




//  Asignamos la trama de datos a la variable Data
if($queryGanancia->rowCount()) {
    $dataGanancia = $queryGanancia->fetchAll(PDO::FETCH_ASSOC);
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



$detalle1 = " ";



  // Llena el reporte con los datos del detalle de las ganancias.

$encabezado = '"encabezado"';
  foreach($dataGanancia as $key => $value) {
        $ventas = $value["venta"];
        $costos = $value["costos"];
        $ganancia = $value["ganancia"];
        
        
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

.titulos{
    background-color: green;
    color: white;
    font-size: 1.5em;

}

.datos{
    font-size: 1.5em;
}

</style>

<html>
<head>
    <title>Ganancias por Intervalo</title>
</head>
<body>
<div style="text-align:center; line-height: 3px;"><h1>Reporte Ganancias</h1></div>
<div style="text-align:center; line-height: 3px;"><h2> AGROSERVICIO EL REGADILLO</h2></div>    
<div style="text-align:center; line-height: 3px;"><h3>Barrio El Porvenir, Guastatoya, El Progreso</h3></div>


    <br>
    <h4>Desde el $fecha1 al $fecha2</h4> 

    <br>
    
    <table width="100%" cellpadding="5" border="1" align="center">


        <tr>
        <td class="titulos"><strong>Ventas</strong></td>
        <td class="datos">Q. $ventas </td>
        </tr>
           
        <tr>
        <td class="titulos"><strong>Costos</strong></td>
        <td class="datos">Q. $costos </td>
        </tr>

        <tr>
        <td class="titulos"><strong>Ganancia</strong></td>
        <td class="datos">Q. $ganancia </td>
        </tr>

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
$pdf->Output('RPTGanancia.pdf', 'I');

?>
