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
$queryVenta = Controller::$connection->query("SELECT idcliente, nombre, direccion, telefono, saldo
FROM cliente
where saldo > 0
ORDER BY saldo ASC
");

//  Asignamos la trama de datos a la variable Data
if($queryVenta->rowCount()) {
    $dataVenta = $queryVenta->fetchAll(PDO::FETCH_ASSOC);
}
else {
  include_once("manejo_errores/view_err_sindatos.php");
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


$codCliente = $dataVenta[0]["idcliente"];
$nombreCliente = $dataVenta[0]["nombre"];
$direccion = $dataVenta[0]["direccion"];
$telefono = $dataVenta[0]["telefono"];
$saldo = $dataVenta[0]["saldo"];



$detalle = "";



$totalCredito = 0;



  // Llena el reporte con las ventas diarias
foreach($dataVenta as $key => $value) {

    $totalCredito = $totalCredito + $value["saldo"];
    
    $detalle .= "<tr>

    <td>".$value["idcliente"]."</td>
    <td>".$value["nombre"]."</td>
    <td>".$value["direccion"]."</td>
    <td>".$value["telefono"]."</td>
    <td>"."Q. ".$value["saldo"]."</td>

    </tr>";
}


$totalCredito2 = sprintf("%.2f",$totalCredito);


// define some HTML content with style
$html = <<<EOF

<style>



body{
    font-family: sans-serif;
    font-size: 0.8em;
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
    line-height:25px;
}

</style>

<html>
<head>
    <title> Venta </title>
</head>
<body>

    <div style="line-height: 1px">
        <h1 style="color: white; background-color: #122678; line-height: 15px;">  Reporte Total Adeudado por Clientes</h1>
    </div>

    <div style="line-height: 1px">
        <h2 align="center">MIREYA'S SALÓN</h2>
    </div>

    <div style="line-height: 1px">
        <h3 align="center">Barrio El Porvenir, Guastatoya, El Progreso</h3>
    </div>


    
    <br>
    <br>
    
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center' class="encabezadodata">
    
        <td width="15%"><strong>Código</strong></td>
        <td width="30%"><strong>Nombre</strong></td>
        <td width="20%"><strong>Dirección</strong></td>
        <td width="15%"><strong>Teléfono</strong></td>
        <td width="20%"><strong>Saldo</strong></td>


    </tr>

        $detalle

     
    </table>
    
    <br>
    
    <h3 align="right">El Total Adeudado por los clientes es: <span class="cantidad">Q. $totalCredito2</span></h3>
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
$pdf->Output('RPTAdeudados.pdf', 'I');

?>
