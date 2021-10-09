<?php

$data = $_POST["data"];
$table = $_POST["table"];
$key = $_POST["key"];
$cod = $_POST["cod"];
$idcliente = json_decode($data)->cliente;


?>
<?php

setlocale(LC_TIME, "ES");

?>
<?php


// Consulta de Ventas
$queryVenta = Controller::$connection->query("SELECT c.idcliente as codcliente, 
c.nombre as nombre, p.fecha as fecha, f.descripcion as formPago, p.total_abono as total  
from cliente as c
INNER JOIN pago_cliente as p
on p.idcliente = c.idcliente
INNER JOIN formapago as f
on f.idFormapago = p.idFormaPago
where c.idcliente = '$idcliente'
ORDER BY p.fecha DESC");




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


$codCliente = $dataVenta[0]["codcliente"];
$nombreCliente = $dataVenta[0]["nombre"];
$fecha = $dataVenta[0]["fecha"];
$formaPago = $dataVenta[0]["formPago"];
$totalPago = $dataVenta[0]["total"];

$detalle1 = " ";



  // Llena el reporte con los datos de los clietes morosos.

  foreach($dataVenta as $key => $value) {

    $detalle1 .= "<tr>

    <td>".$value["fecha"]."</td>
    <td>".$value["formPago"]."</td>
    <td> </td>
    <td>"."Q. ".$value["total"]."</td>

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
    <title> Venta </title>
</head>
<body>
<div style="text-align:center; line-height: 3px;"><h1> Reporte Pago de Clientes </h1></div>
<div style="text-align:center; line-height: 3px;"><h2> MIREYA'S SALÓN </h2></div>    
<div style="text-align:center; line-height: 3px;"><h3>Barrio El Porvenir, Guastatoya, El Progreso</h3></div>

 
    <br>
    <h2>Cliente: $nombreCliente </h2>
    
   
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center' class="encabezado">

        <td><strong>Fecha</strong></td>
        <td><strong>Forma de Pago</strong></td>
        <td><strong>Motivo</strong></td>
        <td><strong>Total</strong></td>
        

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
$pdf->Output('RPTHistorialPagoCliente.pdf', 'I');

?>
