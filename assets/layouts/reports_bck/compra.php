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
p.nombre AS nombreProducto, dc.precioUnitario, dc.cantidad,  (dc.precioUnitario * dc.cantidad) AS subtotal, dc.idDetalleCompra, tp.descripcion AS nombreTipoCompra, 
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
$pdf->AddPage('L', 'HALF_LETTER');


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

    font-size: 18px;
}

h1 {

    font-size: 32px;
}

</style>

<html>
<head>
    <title> Compra </title>
</head>
<body>

    <div style="text-align:center; line-height: 1px;"><h1> Recibo de Compra </h1></div>
    <div style="text-align:center;">AGROSERVICIO EL REGADILLO</div>

    <div style="text-align:right; float:right"> <strong>Fecha:</strong> $fecha</div>
    <div> <strong>No. venta:</strong> $idCompra </div>
    <div> <strong>Tipo de venta:</strong> $nombreTipoCompra</div>
    <div> <span style='display:inline; white-space:pre;'><strong>Código Cliente:</strong> $proveedor  <strong>  Nombre:</strong> $nombre_proveedor              </span></div>
    <br>
    <br>

    <table width="100%" border="1" align="center">

    <tr align='center'>
        <td><strong>Código</strong></td>
        <td><strong>Nombre Producto</strong></td>
        <td><strong>Precio</strong></td>
        <td><strong>Cantidad</strong></td>
        <td><strong>SubTotal</strong></td>
    </tr>



                $detalle


    </table>

    <br>
    <strong><div style="text-align:right; float:right"> <strong>Total: Q.</strong> $total</div> </strong>



</body>
</html>


EOF;



// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Venta.pdf', 'I');

?>
