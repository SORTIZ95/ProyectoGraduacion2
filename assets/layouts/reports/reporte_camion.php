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


$queryCamion = Controller::$connection->query("SELECT v.idcliente, c.nombre, v.idventa, dt.idproducto, dt.cantidad, v.fecha FROM venta AS v
INNER JOIN cliente AS c ON c.idcliente = v.idcliente
INNER JOIN detalle_venta AS dt ON dt.idventa = v.idventa
where c.nombre LIKE 'Camión%' and v.fecha = '$cod'");



if($queryCamion->rowCount()) {

    $dataCamion = $queryCamion->fetchAll(PDO::FETCH_ASSOC);

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
$pdf->AddPage('L', 'LETTER');

$fecha = $dataCamion[0]["fecha"];



$detalle = "";

function tabla($value){

  global $cod;


  $detalle = "";

  $queryCamion = Controller::$connection->query("SELECT v.idventa AS idVenta, v.idcliente as idclienteCli, c.nombre AS nombreCliente, dt.idproducto AS idProductodt, dt.cantidad AS dtCantidad, d.id_devolucion as id_devolucion, dd.idproducto as ddidProducto, dd.cantidad AS ddCantidad FROM venta AS v
INNER JOIN cliente AS c ON c.idcliente = v.idcliente
INNER JOIN detalle_venta AS dt ON dt.idventa = v.idventa
left JOIN devolucion AS d ON d.idventa = v.idventa
left JOIN detalle_devolucion AS dd ON dd.id_devolucion = d.id_devolucion
where c.nombre LIKE 'Camión%' and v.fecha = '$cod' group by idVenta order by v.idcliente");

  if($queryCamion) {
      $dataCamion = $queryCamion->fetchAll(PDO::FETCH_ASSOC);
  }


  foreach($dataCamion as $key => $value) {


    $id_venta = $value["idVenta"];

    $id_devolucion = $value["id_devolucion"];

    $idcliente = $value["idclienteCli"];

    $iddevolucion = $value["id_devolucion"];


    $query1 = Controller::$connection->query("SELECT * FROM detalle_venta WHERE idventa = $id_venta");

    if($query1) {
        $query1 = $query1->fetchAll(PDO::FETCH_ASSOC);
    }

    $query2 = Controller::$connection->query("SELECT * FROM detalle_devolucion WHERE id_devolucion = $id_devolucion");

    if($query2) {
        $query2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    }

    $TG10l = "-";
    $TG20l = "-";
    $TG25l = "-";
    $TG35l = "-";
    $TG40l = "-";
    $TG60l = "-";
    $TG100l = "-";
    $TG10 = "-";
    $TG20 = "-";
    $TG25 = "-";
    $TG35 = "-";
    $TG40 = "-";
    $TG60 = "-";
    $TG100 = "-";

    $totalVenta = 0;
    $totalDevolucion = 0;


    foreach ($query1 as $key => $value) {

      $totalVenta += $value["subtotal"];

      switch ($value["idproducto"]) {
        case "TG10":
          $TG10l = $value["cantidad"];
          break;
        case "TG20":
          $TG20l = $value["cantidad"];
          break;
        case "TG25":
          $TG25l = $value["cantidad"];
          break;
        case "TG35":
          $TG35l = $value["cantidad"];
          break;
        case "TG40":
          $TG40l = $value["cantidad"];
          break;
        case "TG60":
          $TG60l = $value["cantidad"];
          break;
        case "TG100":
          $TG100l = $value["cantidad"];
          break;

        default:
          # code...
          break;
      }

    }

if($query2) {

  foreach ($query2 as $key => $value) {

    $totalDevolucion += $value["subtotal"];

      switch ($value["idproducto"]) {
        case "TG10":
          $TG10 = $value["cantidad"];
          break;
        case "TG20":
          $TG20 = $value["cantidad"];
          break;
        case "TG25":
          $TG25 = $value["cantidad"];
          break;
        case "TG35":
          $TG35 = $value["cantidad"];
          break;
        case "TG40":
          $TG40 = $value["cantidad"];
          break;
        case "TG60":
          $TG60 = $value["cantidad"];
          break;
        case "TG100":
          $TG100 = $value["cantidad"];
          break;

        default:
          # code...
          break;
      }

    }
}


    $detalle .= "<tr>
    <td>".$id_venta."</td>
    <td>".$idcliente."</td>
    <td>".$TG10l."</td>
    <td>".$TG20l."</td>
    <td>".$TG25l."</td>
    <td>".$TG35l."</td>
    <td>".$TG40l."</td>
    <td>".$TG60l."</td>
    <td>".$TG100l."</td>
    <td> Q. ".$totalVenta."</td>
    <td>".$iddevolucion."</td>
    <td>".$TG10."</td>
    <td>".$TG20."</td>
    <td>".$TG25."</td>
    <td>".$TG35."</td>
    <td>".$TG40."</td>
    <td>".$TG60."</td>
    <td>".$TG100."</td>
    <td> Q. ".$totalDevolucion."</td></tr>";

  }

  return $detalle;
}

    $detalle = tabla($dataCamion[0]);


// define some HTML content with style
$html = <<<EOF

<style>

body{

    font-size: 6px;
}

h1 {

    font-size: 20px;
}

</style>

<html>
<head>
    <title> Llenado de Gas </title>
</head>
<body>
    <div style="text-align:center; line-height: 1px;"><h1> AGROSERVICIO EL REGADILLO </h1></div>
    <div> </div>
    <!-- <div style="text-align:center;">San Miguel Conacaste, Sanarate, El Progreso</div> -->

    <div style="text-align:center; font-size: 14px;"><strong>Reporte de Ventas en Camiones del dia:</strong> $fecha</div>
    <br>

    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center'>
        <td colspan="10"><strong>Venta</strong></td>
        <td colspan="9"><strong>Devolución</strong></td>
    </tr>

    <tr align='center'>
        <td><strong>Venta</strong></td>
        <td><strong>Camión</strong></td>
        <td><strong>TG10</strong></td>
        <td><strong>TG20</strong></td>
        <td><strong>TG25</strong></td>
        <td><strong>TG35</strong></td>
        <td><strong>TG40</strong></td>
        <td><strong>TG60</strong></td>
        <td><strong>TG100</strong></td>
        <td><strong>Total</strong></td>
        <td><strong>Devolución</strong></td>
        <td><strong>TG10</strong></td>
        <td><strong>TG20</strong></td>
        <td><strong>TG25</strong></td>
        <td><strong>TG35</strong></td>
        <td><strong>TG40</strong></td>
        <td><strong>TG60</strong></td>
        <td><strong>TG100</strong></td>
        <td><strong>Total</strong></td>
    </tr>



        $detalle


    </table>

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
$pdf->Output('Venta.pdf', 'I');

?>
