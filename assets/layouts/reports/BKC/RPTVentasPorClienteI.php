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



// Consulta de Ventas por Cliente
$queryVenta = Controller::$connection->query("SELECT v.idventa as idventa, v.fecha as fecha,
d.cantidad as cantidad, p.nombre as nomproducto, d.subtotal as subtotal, v.total as total,
c.nombre as nomcliente
FROM venta as v
INNER JOIN detalle_venta as d
on d.idventa = v.idventa
INNER JOIN producto as p
on p.idproducto = d.idproducto
INNER JOIN cliente as c
on c.idcliente = v.idcliente
where c.idcliente = '$idcliente'
ORDER BY v.idventa
");



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


$idVenta = $dataVenta[0]["idventa"];
$fecha = $dataVenta[0]["fecha"];
$nombreCliente = $dataVenta[0]["nomcliente"];
$nombreProducto = $dataVenta[0]["nomproducto"];
$cantidad = $dataVenta[0]["cantidad"];
$subtotal = $dataVenta[0]["subtotal"];
$total = $dataVenta[0]["total"];

$detalle1 = " ";



  // Llena el reporte con los datos de las ventas a cada cliente.


  $colum='"6"';
  $width='"50px"';
  $width2='"180px"';
  $encabezado='"encabezado"';
  $align='"right"';
  $border='"none"';
  $ventaTotal = 0;
  foreach($dataVenta as $key => $value) {

   //OBTENER EL TOTAL DE TODO LO VENDIDO
    $ventaTotal = $ventaTotal+$value["subtotal"];

   //ESTRUCTURA DE COMPARACIÓN QUE EVITA LA COMPROBACIÓN LA PRIMERA VEZ
   if(isset($noVenta)){

        if($value["idventa"]!=$noVenta){
            $detalle1 .= "
          
            <tr>
            <td colspan=$colum align=$align border='none'> Total de la Venta: ".$totalPorVenta."</td>
            </tr>

            <tr align='center' class=$encabezado>
            <td width=$width><strong>No.Venta</strong></td>
            <td><strong>Fecha</strong></td>
            <td width=$width2><strong>Producto</strong></td>
            <td width=$width><strong>Cantidad</strong></td>
            <td><strong>Subtotal</strong></td>
            </tr>
            
            <tr>          
            <td>".$value["idventa"]."</td>
            <td>".$value["fecha"]."</td>
            <td>".$value["nomproducto"]."</td>
            <td>".$value["cantidad"]."</td>
            <td>".$value["subtotal"]."</td>
            </tr>

            ";
        }else{
            $detalle1 .= "
            
            <tr>          
            <td>".$value["idventa"]."</td>
            <td>".$value["fecha"]."</td>
            <td>".$value["nomproducto"]."</td>
            <td>".$value["cantidad"]."</td>
            <td>".$value["subtotal"]."</td>
            </tr>
         
            ";
        }

    }else{

        $detalle1 .= "
        
        <tr>
        <td>".$value["idventa"]."</td>
        <td>".$value["fecha"]."</td>
        <td>".$value["nomproducto"]."</td>
        <td>".$value["cantidad"]."</td>
        <td>".$value["subtotal"]."</td>
        </tr>
           
        ";
        $noVenta = $value["idventa"];
    }

    
    $noVenta = $value["idventa"];
    $totalPorVenta = $value["total"];  
 
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
    <title>Ventas por Cliente</title>
</head>
<body>
<div style="text-align:center; line-height: 3px;"><h1> Ventas por Cliente</h1></div>
<div style="text-align:center; line-height: 3px;"><h2> MIREYA'S SALÓN </h2></div>    
<div style="text-align:center; line-height: 3px;"><h3>Barrio El Porvenir, Guastatoya, El Progreso</h3></div>

 
    <br>
    <h4>Cliente: $nombreCliente</h4> 

    <br>
    
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center' class="encabezado">

        <td width="50px"><strong>No.Venta</strong></td>
        <td><strong>Fecha</strong></td>
        <td width="180px"><strong>Producto</strong></td>
        <td width="50px"><strong>Cantidad</strong></td>
        <td><strong>Subtotal</strong></td>
        

    </tr>

        $detalle1

    </table>
    <br>
    <h2>Total Vendido: $ventaTotal</h2>

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
$pdf->Output('RPTVentasPorCliente.pdf', 'I');

?>
