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


$queryCompra = Controller::$connection->query("SELECT c.fecha, c.idCompra, tp.descripcion AS TipoCompra, c.idProveedor, pv.nombre AS nombreProveedor, dc.idproducto, 
p.nombre AS nombreProducto, dc.precioUnitario, dc.cantidad,  (dc.precioUnitario * dc.cantidad) AS subtotal, dc.idDetalleCompra, tp.descripcion AS nombreTipoCompra, c.noFactura,
tp.idTipoCompra
        from compra as c
        inner join detalle_compra as dc on dc.idCompra = c.idCompra
        inner join producto as p on p.idproducto = dc.idproducto
        inner join proveedor AS pv ON pv.idProveedor = c.idProveedor
        INNER JOIN tipocompra as tp on tp.idTipoCompra = c.idTipoCompra
        where c.idCompra = '$cod'");

if($queryCompra) {

    $dataCompra = $queryCompra->fetchAll(PDO::FETCH_ASSOC);

}
else {
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
        /*$img_file = '../assets/layouts/reports/images/report.png';
        
        $this->Image($img_file, 0, 9, 205, 40);

        $img_file_logo = '../assets/layouts/reports/images/logo_report.png';
        $this->Image($img_file_logo, 170.5, 15, 25, 25);
        */
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



$idCompra = $dataCompra[0]["idCompra"];
$fecha = $dataCompra[0]["fecha"];
$idtipocomrpa = $dataCompra[0]["idTipoCompra"];
$proveedor = $dataCompra[0]["idProveedor"];
$nombre_proveedor = $dataCompra[0]["nombreProveedor"];
$id_compra = $dataCompra[0]["idCompra"];
$id_detalle_compra = $dataCompra[0]["idDetalleCompra"];
$idProducto = $dataCompra[0]["idproducto"];
$nombre_producto = $dataCompra[0]["nombreProducto"];
$cantidad_venta = $dataCompra[0]["cantidad"];
$total = 0;
$nombreTipoCompra = $dataCompra[0]["nombreTipoCompra"];
$noFactura = $dataCompra[0]["noFactura"];



$detalle = "";

foreach($dataCompra as $key => $value) {

    //if($value["cantidad"] < 1) {

    //  $precioCompra / 100;
  //    $value["cantidad"] =  $value["cantidad"] * 100;

//  }

   $precioCompra = sprintf("%.2f", $value["precioUnitario"] / $value["cantidad"]);

    $total = $total + $value["precioUnitario"];

    $detalle .= "<tr>

    <td>".$value["idproducto"]."</td>
    <td>".$value["nombreProducto"]."</td>
    <td>"."Q. ".$precioCompra."</td>
    <td>".$value["cantidad"]."</td>
    <td>"."Q. ".$value["precioUnitario"]."</td>

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
    <title> Compra </title>
</head>

<body>

    <div style="line-height: 1px">
        <h1 style="color: white; background-color: #122678; line-height: 15px;">  Recibo de Compra</h1>
    </div>

    <div style="line-height: 1px">
        <h2 align="center">MIREYA'S SALÓN</h2>
    </div>

    <div style="line-height: 1px">
        <h3 align="center">Barrio El Porvenir, Guastatoya, El Progreso</h3>
    </div>



    <div class="datosfactura" style="line-height: 1px">
        <p class="texto" style="line-height: 1px">
            <strong class="strongtexto">Fecha:</strong> $fecha
            <span class="separator">----------</span>
            <strong class="strongtexto">No. Compra:</strong> $idCompra
            <span class="separator">----------</span>
            <strong class="strongtexto">Tipo de Compra:</strong> $nombreTipoCompra
        </p>
        <br>
        <p class="texto" style="line-height: 8px">
            <strong class="strongtexto">Nombre Proveedor:</strong> $nombre_proveedor
        </p>
        <p class="texto" style="line-height: 8px">
            <strong class="strongtexto">No. Documento Extendido:</strong> $noFactura
        </p>
        
    </div>
    
    
    <br>
    <br>

    <table width="100%" border="0.25" align="center">

    <tr align='center' class="encabezadodata">
        <td width="15%">Código</td>
        <td width="40%">Nombre Producto</td>
        <td width="15%">Precio</td>
        <td width="13%">Cantidad</td>
        <td width="17%">SubTotal</td>
    </tr>

                $detalle


    </table>

    <br>
    
    <h3 align="right">Total Compra: <span class="cantidad">Q. $total</span></h3>
    <hr>


</body>
</html>


EOF;

$nombreSalida = "ReciboCompra-No".$idCompra.".pdf";

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output($nombreSalida, 'I');

?>
