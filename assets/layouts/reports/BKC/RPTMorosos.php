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
$queryVenta = Controller::$connection->query("SELECT MAX(p.fecha) AS fecha_ult_pago, 
p.idcliente as idcliente, c.nombre as nombre, c.direccion as direccion, c.telefono as telefono, c.saldo as saldo, 
DATEDIFF(NOW(), MAX(p.fecha)) AS dias_ult_pago
FROM pago_cliente as p
INNER JOIN cliente as c
on c.idcliente = p.idcliente
GROUP by idcliente");


//  Asignamos la trama de datos a la variable Data
if($queryVenta->rowCount()) {
    $dataVenta = $queryVenta->fetchAll(PDO::FETCH_ASSOC);
}
else {
  include_once("manejo_errores/view_err_sindatos.php");
  die();
}


//CONSULTA PARA RECUPERAR LA ULTIMA VENTA AL CRÉDITO DE LOS CLIENTES QUE NUNCA HAN REALIZADO
//NINGUN ABONO, SE BASA EN LA PRIMERA VENTA
$queryDeuda = Controller::$connection->query("SELECT v.idventa as idventa, v.idcliente as idcliente, c.nombre as nombre,  c.direccion as direccion, c.telefono as telefono,
c.saldo as saldo, MIN(v.fecha) as fecha_ult_pago,
DATEDIFF(NOW(), MIN(v.fecha)) AS dias_ult_pago
FROM venta as v
INNER JOIN cliente as c
on c.idcliente = v.idcliente
where v.idcliente IN(
SELECT c.idcliente FROM cliente as c 
WHERE c.idcliente NOT IN(SELECT DISTINCT(p.idcliente) FROM pago_cliente as p) 
AND c.saldo > 0
)
GROUP BY v.idcliente
ORDER BY DATEDIFF(NOW(), MIN(v.fecha))"
);

if($queryDeuda) {
    $dataDeuda = $queryDeuda->fetchAll(PDO::FETCH_ASSOC);
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

$fecha = $dataVenta[0]["fecha_ult_pago"];
$codCliente = $dataVenta[0]["idcliente"];
$nombreCliente = $dataVenta[0]["nombre"];
$direccion = $dataVenta[0]["direccion"];
$telefono = $dataVenta[0]["telefono"];
$saldo = $dataVenta[0]["saldo"];
$dias = $dataVenta[0]["dias_ult_pago"];


$detalle1 = "";
$detalle2 = "";
$detalle3 = "";  
$detalle4 = "";

$totalCredito1 = 0;
$totalCredito2 = 0;
$totalCredito3 = 0;
$totalCredito4 = 0;



  // Llena el reporte con los datos de los clietes morosos.

  foreach($dataVenta as $key => $value) {

    if ( $value["dias_ult_pago"]<=30) 
        {

            $totalCredito1 = $totalCredito1 + $value["saldo"];
    
            $detalle1 .= "<tr>

            <td>".$value["fecha_ult_pago"]."</td>
            <td>".$value["nombre"]."</td>
            <td>".$value["direccion"]."</td>
            <td>".$value["telefono"]."</td>
            <td>"."Q. ".$value["saldo"]."</td>
            <td>".$value["dias_ult_pago"]."</td>

            </tr>";
        }
    
    if ( $value["dias_ult_pago"]>30 && $value["dias_ult_pago"]<=60) 
        {

            $totalCredito2 = $totalCredito2 + $value["saldo"];
    
            $detalle2 .= "<tr>

            <td>".$value["fecha_ult_pago"]."</td>
            <td>".$value["nombre"]."</td>
            <td>".$value["direccion"]."</td>
            <td>".$value["telefono"]."</td>
            <td>"."Q. ".$value["saldo"]."</td>
            <td>".$value["dias_ult_pago"]."</td>

            </tr>";
        }
        
        
        if ( $value["dias_ult_pago"]>60 && $value["dias_ult_pago"]<=90) 
        {

            $totalCredito3 = $totalCredito3 + $value["saldo"];
    
            $detalle3 .= "<tr>

            <td>".$value["fecha_ult_pago"]."</td>
            <td>".$value["nombre"]."</td>
            <td>".$value["direccion"]."</td>
            <td>".$value["telefono"]."</td>
            <td>"."Q. ".$value["saldo"]."</td>
            <td>".$value["dias_ult_pago"]."</td>

            </tr>";
        }

        if ( $value["dias_ult_pago"]>90) 
        {

            $totalCredito4 = $totalCredito4 + $value["saldo"];
    
            $detalle4 .= "<tr>

            <td>".$value["fecha_ult_pago"]."</td>
            <td>".$value["nombre"]."</td>
            <td>".$value["direccion"]."</td>
            <td>".$value["telefono"]."</td>
            <td>"."Q. ".$value["saldo"]."</td>
            <td>".$value["dias_ult_pago"]."</td>

            </tr>";
        }
        

    
}


//---------------------------------------------------------------------------------------

if(isset($dataDeuda)){

 foreach($dataDeuda as $key => $value) {

    if ( $value["dias_ult_pago"]<=30) 
        {

            $totalCredito1 = $totalCredito1 + $value["saldo"];
    
            $detalle1 .= "<tr>

            <td>Ningún Abono</td>
            <td>".$value["nombre"]."</td>
            <td>".$value["direccion"]."</td>
            <td>".$value["telefono"]."</td>
            <td>"."Q. ".$value["saldo"]."</td>
            <td>".$value["dias_ult_pago"]."</td>

            </tr>";
        }
    
    if ( $value["dias_ult_pago"]>30 && $value["dias_ult_pago"]<=60) 
        {

            $totalCredito2 = $totalCredito2 + $value["saldo"];
    
            $detalle2 .= "<tr>

            <td>Ningún Abono</td>
            <td>".$value["nombre"]."</td>
            <td>".$value["direccion"]."</td>
            <td>".$value["telefono"]."</td>
            <td>"."Q. ".$value["saldo"]."</td>
            <td>".$value["dias_ult_pago"]."</td>

            </tr>";
        }
        
        
        if ( $value["dias_ult_pago"]>60 && $value["dias_ult_pago"]<=90) 
        {

            $totalCredito3 = $totalCredito3 + $value["saldo"];
    
            $detalle3 .= "<tr>

            <td>Ningún Abono</td>
            <td>".$value["nombre"]."</td>
            <td>".$value["direccion"]."</td>
            <td>".$value["telefono"]."</td>
            <td>"."Q. ".$value["saldo"]."</td>
            <td>".$value["dias_ult_pago"]."</td>

            </tr>";
        }

        if ( $value["dias_ult_pago"]>90) 
        {

            $totalCredito4 = $totalCredito4 + $value["saldo"];
    
            $detalle4 .= "<tr>

            <td>Ningún Abono</td>
            <td>".$value["nombre"]."</td>
            <td>".$value["direccion"]."</td>
            <td>".$value["telefono"]."</td>
            <td>"."Q. ".$value["saldo"]."</td>
            <td>".$value["dias_ult_pago"]."</td>

            </tr>";
        }
        

    
    }
}


//---------------------------------------------------------------------------------------


$totalCreditoformat1= sprintf("%.2f",$totalCredito1);
$totalCreditoformat2= sprintf("%.2f",$totalCredito2);
$totalCreditoformat3= sprintf("%.2f",$totalCredito3);
$totalCreditoformat4= sprintf("%.2f",$totalCredito4);

// define some HTML content with style
$html = <<<EOF

<style>


body{
    font-family: sans-serif;
    font-size: 8px;
}

.encabezado{
    text-align: center;
    line-height: 10px;
}

.texto{
    color: black;
}

.strongtexto{
    color: #0F8CD6;
}

.separator{
    color: white;
}

.cantidad{
    
    color: #122678;
}

.datosfactura{
    text-align: left;
    line-height: 10px; 
}

.encabezadodata{
    background-color: #A9AAAA;
    color: black;
}

.encabezadodata td{
    font-weight: bold;
}

.encabezado td{
    background-color: green;
    color: black;
    font-size: 1.5em;
}

.encabezado2 td{
    background-color: yellow;
    color: black;
    font-size: 1.5em;
}

.encabezado3 td{
    background-color: orange;
    color: black;
    font-size: 1.5em;
}

.encabezado4 td{
    background-color: red;
    color: black;
    font-size: 1.5em;
}

</style>

<html>
<head>
    <title> RPTMorosos </title>
</head>
<body>

<div style="line-height: 1px">
        <h1 style="color: white; background-color: #122678; line-height: 15px;">  Reporte Clientes Morosos</h1>
    </div>

    <div style="line-height: 1px">
        <h2 align="center">MIREYA'S SALÓN</h2>
    </div>

    <div style="line-height: 1px">
        <h3 align="center">Barrio El Porvenir, Guastatoya, El Progreso</h3>
    </div>

 
    <span class="separator">----------</span>
    <br>
    <br>
    
    
    <table width="100%" cellpadding="5" border="1" align="center">
    
    <tr class="encabezado" colspan="6">
        <td>Último Pago: 0 hasta 30 días</td>
    </tr>
    
    <tr align='center' class="encabezadodata">

        <td width="15%"><strong>Fecha Últ. Pago</strong></td>
        <td width="25%"><strong>Nombre</strong></td>
        <td width="20%"><strong>Dirección</strong></td>
        <td width="10%"><strong>Teléfono</strong></td>
        <td width="15%"><strong>Saldo</strong></td>
        <td width="15%"><strong>Días Atrasados</strong></td>

    </tr>

        $detalle1

    </table>
    <strong><div style="text-align:right; font-size: 10px; float:right;"> <strong>El Total Adeudado por los clientes es:</strong> Q. $totalCreditoformat1</div> </strong>
    <span class="separator">----------</span>
    <br>
    <br>
    
    
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr class="encabezado2" colspan="6">
        <td>Último Pago: 31 hasta 60 días</td>
    </tr>
    
    <tr align='center' class="encabezadodata">

        <td width="15%"><strong>Fecha Últ. Pago</strong></td>
        <td width="25%"><strong>Nombre</strong></td>
        <td width="20%"><strong>Dirección</strong></td>
        <td width="10%"><strong>Teléfono</strong></td>
        <td width="15%"><strong>Saldo</strong></td>
        <td width="15%"><strong>Días Atrasados</strong></td>

    </tr>

        $detalle2

    </table>
    <strong><div style="text-align:right; font-size: 10px; float:right;"> <strong>El Total Adeudado por los clientes es:</strong> Q. $totalCreditoformat2</div> </strong>
    <span class="separator">----------</span>
    <br>
    <br>
    
    
    <table width="100%" cellpadding="5" border="1" align="center">
    <tr class="encabezado3" colspan="6">
        <td>Último Pago: 61 hasta 90 días</td>
    </tr>
    
    <tr align='center' class="encabezadodata">

        <td width="15%"><strong>Fecha Últ. Pago</strong></td>
        <td width="25%"><strong>Nombre</strong></td>
        <td width="20%"><strong>Dirección</strong></td>
        <td width="10%"><strong>Teléfono</strong></td>
        <td width="15%"><strong>Saldo</strong></td>
        <td width="15%"><strong>Días Atrasados</strong></td>

    </tr>

        $detalle3

    </table>
    <strong><div style="text-align:right; font-size: 10px; float:right;"> <strong>El Total Adeudado por los clientes es:</strong> Q. $totalCreditoformat3</div> </strong>
    <span class="separator">----------</span>
    <br>
    <br>
    
    
    
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr class="encabezado4" colspan="6">
        <td>Último Pago: Más de 90 días</td>
    </tr>
    
    <tr align='center' class="encabezadodata">

        <td width="15%"><strong>Fecha Últ. Pago</strong></td>
        <td width="25%"><strong>Nombre</strong></td>
        <td width="20%"><strong>Dirección</strong></td>
        <td width="10%"><strong>Teléfono</strong></td>
        <td width="15%"><strong>Saldo</strong></td>
        <td width="15%"><strong>Días Atrasados</strong></td>

    </tr>

        $detalle4

    </table>
    <strong><div style="text-align:right; font-size: 10px; float:right;"> <strong>El Total Adeudado por los clientes es:</strong> Q. $totalCreditoformat4</div> </strong>
    <span class="separator">----------</span>
    <hr>
    


</body>
</html>


EOF;



// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('RPTMorosos.pdf', 'I');

?>
