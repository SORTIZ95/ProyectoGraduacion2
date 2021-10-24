<?php

setlocale(LC_TIME, "ES");

?>
<?php

// Consulta de Ventas
$queryVenta = Controller::$connection->query("SELECT c.nombre as nombreCliente, v.total, tp.nombre as nombreTipoVenta, v.idventa, v.idcliente, fecha, v.idtipo_venta 
    from venta as v
    inner join cliente as c on v.idcliente = c.idcliente
    inner join tipo_venta as tp on tp.idtipo_venta = v.idtipo_venta
    where v.fecha = CURDATE() order by v.idventa asc");

// Consulta de Devoluciones
$queryDevolucion = Controller::$connection->query("SELECT d.id_devolucion, d.idventa, d.idcliente, c.nombre as nombredevo, fecha, total 
    from devolucion as d
    inner join cliente as c on d.idcliente = c.idcliente
    where d.fecha = CURDATE() order by d.idventa asc");

// Asignamos la trama de datos a la variable Data
if($queryDevolucion) {
    $dataDevolucion = $queryDevolucion->fetchAll(PDO::FETCH_ASSOC);
}
else {
    die("No hay datos.");
}

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

$idventa = $dataVenta[0]["idventa"];
$cliente = $dataVenta[0]["idcliente"];
$nombreCliente = $dataVenta[0]["nombreCliente"];
$nombreTipoVenta = $dataVenta[0]["nombreTipoVenta"];
$total = $dataVenta[0]["total"];

$fecha = $dataVenta[0]["fecha"];


$detalle = "";
$detalle2 = "";


  $totalContado = 0;
  $totalCredito = 0;

  // Llena el reporte con las ventas diarias
foreach($dataVenta as $key => $value) {

    $detalle .= "<tr>

    <td>".$value["idventa"]."</td>
    <td>".$value["idcliente"]."</td>
    <td>".$value["nombreCliente"]."</td>
    <td>"."Q. ".$value["total"]."</td>
    <td>".$value["nombreTipoVenta"]."</td>

    </tr>";

    if($value["idtipo_venta"] == 1)
      {
        $totalContado += $value["total"];
      }else if($value["idtipo_venta"] == 2){
        $totalCredito += $value["total"];
      }
}

// Llena el reporte con las devoluciones diarias
foreach($dataDevolucion as $key => $value) {

    $detalle2 .= "<tr>
    <td>".$value["id_devolucion"]."</td>
    <td>".$value["idventa"]."</td>
    <td>".$value["idcliente"]."</td>
    <td>".$value["nombredevo"]."</td>
    <td>"."Q. ".$value["total"]."</td>
    </tr>";

}

$gananciaDelDia = Controller::$connection->query("SELECT (SUM(dtv.subtotal)-SUM(dtv.cantidad * p.preciocosto)) as Ganancia 
    FROM venta AS v
	inner join detalle_venta as dtv on v.idventa = dtv.idventa
	inner join producto as p on dtv.idproducto = p.idproducto
    where v.fecha = CURDATE() AND v.idtipo_venta = '1'");

  if($gananciaDelDia->rowCount()) {

      $gananciaDelDia = $gananciaDelDia->fetchAll(PDO::FETCH_NUM);

  }

$gananciaDevolucion = Controller::$connection->query("SELECT (SUM(dd.subtotal) - SUM(dd.cantidad * p.preciocosto)) AS Devolucion, SUM(dd.subtotal) as TotalDevolucion 
        FROM devolucion AS d
        inner join detalle_devolucion as dd on d.id_devolucion = dd.id_devolucion
        inner join producto as p on dd.idproducto = p.idproducto
        where d.fecha = CURDATE()");

    if($gananciaDevolucion->rowCount()) {

        $gananciaDevolucion = $gananciaDevolucion->fetchAll(PDO::FETCH_NUM);

    }

    
    $gananciaDelDia = number_format(round(0 + $gananciaDelDia[0][0], 2), 2);

    $totalDevolucion = number_format(round(0 + $gananciaDevolucion[0][1], 2), 2);

    $ventasNetas = number_format(round($totalContado - $totalDevolucion, 2), 2);
 

    $totalContado = number_format(round($totalContado, 2), 2);
    

// define some HTML content with style

$html = <<<EOF

<style>

body{

    font-size: 8px;
}

h1 {

    font-size: 20px;
}

</style>

<html>
<head>
    <title> Venta </title>
</head>
<body>
    <div style="text-align:center; line-height: 1px;"><h1> Reporte de Ventas y Devoluciones del día </h1></div>
    
    <div style="text-align:center;"><big> AGROSERVICIO EL REGADILLO </big></div>
    <div> </div>
    <br>

    <h4>Fecha: $fecha </h4>
    
    <h2><u>Ventas:</u></h2> 

    <br>

    <table width="100%" cellpadding="5" border="1" align="center ">

    <tr align='center'>
        <td><strong>ID Venta</strong></td>
        <td><strong>ID Cliete</strong></td>
        <td><strong>Nombre</strong></td>
        <td><strong>Total de Venta</strong></td>
        <td><strong>Tipo de Venta</strong></td>
    </tr>
    
        $detalle

    </table>

    <br>
    <br>
    <br>
    
    <h2><u>Devoluciones:</u></h2>
    <br>
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center'>
        <td><strong>ID Devolución</strong></td>
        <td><strong>ID Venta</strong></td>
        <td><strong>ID Cliente</strong></td>
        <td><strong>Nombre</strong></td>
        <td><strong>Total de Devolución</strong></td>
    </tr>

        $detalle2

    </table>
    <br>
    <br>
    <br>

    <table width="60%" cellpadding="5" border="0" align="left">

    <tr align='center'>
      <td><strong>Ventas al Crédito</strong></td>
          <td>Q. $totalCredito</td>
      </tr><tr><td></td> <td></td></tr>
      <tr><td><strong>Ventas al Contado</strong></td>
            <td>Q. $totalContado</td>
      </tr>
      <tr><td><strong>Devolución sobre Venta</strong></td>
      <td>Q. $totalDevolucion</td>
      </tr>
      <tr><td><strong>Ventas Netas</strong></td>
      <td>Q. $ventasNetas</td>
      </tr>
      <tr><td><strong>Ganancia del día</strong></td>
      <td><strong>Q. $gananciaDelDia</strong></td>
    </tr>
    </table>

</body>
</html>


EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output(__DIR__ .'/Cierre_Hoy.pdf', 'F');

?>
