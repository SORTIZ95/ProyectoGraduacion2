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


$queryVenta = Controller::$connection->query("select *, p.nombre as nombre_producto, dv.cantidad as cantidad_venta, tp.nombre as nombreTipoVenta, c.nombre as nombreCliente, c.nit as nit, c.direccion as direccion
        from venta as v
        inner join detalle_venta as dv on dv.idventa = v.idventa
        inner join producto as p on p.idproducto = dv.idproducto
        inner join cliente as c on c.idcliente = v.idcliente
        inner join tipo_venta as tp on v.idtipo_venta = tp.idtipo_venta
        where v.idventa = $cod");

if($queryVenta) {

    $dataVenta = $queryVenta->fetchAll(PDO::FETCH_ASSOC);

}else {
    include_once("manejo_errores/view_err_sindatos.php");
    die();
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

$pdf->SetMargins(18, 8, 18, true);


// add a page
$pdf->AddPage('L', 'LETTER');


// ---------------------------------------------------------

//$h = new NumberToLetterConverter();



$idventa = $dataVenta[0]["idventa"];
$fecha = $dataVenta[0]["fecha"];
$idtipo_venta = $dataVenta[0]["idtipo_venta"];
$cliente = $dataVenta[0]["idcliente"];
$nombre_cliente = $dataVenta[0]["nombreCliente"];
$id_venta = $dataVenta[0]["idventa"];
$id_detalle_venta = $dataVenta[0]["id_detalle_venta"];
$idProducto = $dataVenta[0]["idproducto"];
$nombre_producto = $dataVenta[0]["nombre_producto"];
$cantidad_venta = $dataVenta[0]["cantidad_venta"];
$subtotal = $dataVenta[0]["subtotal"];
$total = $dataVenta[0]["total"];
$nombreTipoVenta = $dataVenta[0]["nombreTipoVenta"];
$nit = $dataVenta[0]["nit"];
$direccion = $dataVenta[0]["direccion"];



$detalle = "";

foreach($dataVenta as $key => $value) {

    if($value["cantidad_venta"] < 1) {

        $precioVenta / 100;
        $value["cantidad_venta"] =  $value["cantidad_venta"] * 100;

    }

    $precioVenta = sprintf("%.2f", $value["subtotal"] / $value["cantidad_venta"]);

    $detalle .= "<tr style='font-size: 0.8rem;'>

    <td>".$value["idproducto"]."</td>
    <td>".$value["nombre_producto"]."</td>
    <td>"."Q. ".$precioVenta."</td>
    <td>".$value["cantidad_venta"]."</td>
    <td>"."Q. ".$value["subtotal"]."</td>

    </tr>";
}



// define some HTML content with style
$html = <<<EOF

<style>

body{
    font-family: sans-serif;
    
}

.encabezado{
    text-align: center;
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
        <h1 style="color: white; background-color: #122678; line-height: 15px;">  Recibo de Venta</h1>
    </div>

    <div style="line-height: 1px">
        <h2 align="center"> MIREYA'S SALÓN</h2>
    </div>

    <div style="line-height: 1px">
        <h3 align="center">Barrio El Porvenir, Guastatoya, El Progreso</h3>
    </div>

        
        

    <div class="datosfactura" style="line-height: 1px">
        <p class="texto" style="line-height: 1px">
            <strong class="strongtexto">Fecha:</strong> $fecha
            <span class="separator">----------</span>
            <strong class="strongtexto">No. Venta:</strong> $idventa
            <span class="separator">----------</span>
            <strong class="strongtexto">Tipo de Venta:</strong> $nombreTipoVenta
        </p>
        <br>
        <p class="texto" style="line-height: 8px">
            <strong class="strongtexto">Nit:</strong> $nit
        </p>

        <p class="texto" style="line-height: 8px">
            <strong class="strongtexto">Cliente:</strong> $nombre_cliente
        </p>

        <p class="texto" style="line-height: 8px">
            <strong class="strongtexto">Dirección:</strong> $direccion
        </p>
        
    </div>

    <br>

    <table width="100%" border="0.25" align="center">

    <tr align='center' class="encabezadodata">
        <td width="18%"><strong>Código</strong></td>
        <td width="36%"><strong>Producto</strong></td>
        <td width="17%"><strong>Precio</strong></td>
        <td width="12%"><strong>Cantidad</strong></td>
        <td width="17%"><strong>SubTotal</strong></td>
    </tr>



                $detalle


    </table>

    <h3 align="right">Total Venta: <span class="cantidad">Q. $total</span></h3>
    <h4>No se aceptan cambios ni devoluciones</h4>
    <hr>




</body>
</html>


EOF;

$salidaVenta = "ReciboVenta-No".$idventa.".pdf";

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($salidaVenta, 'I');

?>
