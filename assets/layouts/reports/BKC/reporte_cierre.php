<?php

setlocale(LC_TIME, "ES");

?>
<?php

// Consulta de Ventas
$queryVenta = Controller::$connection->query("SELECT v.idventa as idventa, v.fecha as fecha, c.nit as nit, 
    c.nombre as cliente, p.nombre as nomproducto,
d.cantidad as cantidad, d.subtotal as subtotal, t.nombre as tipoventa, v.total as total
FROM venta as v 
INNER JOIN detalle_venta as d 
on d.idventa = v.idventa 
INNER JOIN producto as p 
on p.idproducto = d.idproducto 
INNER JOIN cliente as c 
on c.idcliente = v.idcliente
INNER JOIN tipo_venta as t
on t.idtipo_venta = v.idtipo_venta
WHERE v.fecha = CURDATE()
ORDER BY v.idventa
");

// Consulta de Devoluciones
$queryDevolucion = Controller::$connection->query("SELECT d.id_devolucion as iddevolucion, v.idventa as idventa, c.nombre as cliente, de.cantidad as cantidad, p.nombre as nomproducto, de.subtotal as subtotal
FROM devolucion as d
INNER JOIN detalle_devolucion as de
on de.id_devolucion = d.id_devolucion
INNER JOIN producto as p
on p.idproducto = de.idproducto
INNER JOIN cliente as c
on c.idcliente = d.idcliente
INNER JOIN venta as v 
on v.idventa = d.idventa
WHERE d.fecha = CURDATE()
ORDER BY v.idventa
");

// Consulta de Gastos
$queryGasto = Controller::$connection->query("SELECT g.noDocumento as nodocumento, t.descripcion as tipodocto,
f.descripcion as formapago,
g.fecha as fecha, ce.descripcion as catalogo, g.motivo as motivo, g.total as total
FROM gasto as g
INNER JOIN catalogoegresos ce
on ce.idCatalogoEgresos = g.idEgreso
INNER JOIN tipoDocumento as t
on t.idTipoDo = g.tipoDocumento
INNER JOIN formapago as f
on f.idFormapago = g.idFormaPago
where g.fecha = CURDATE()
ORDER by ce.descripcion");

//Recuperar el saldo de Apertura de Caja
$queryAperturaCaja = Controller::$connection->query("
    SELECT c.id as id_apertura, c.saldo as saldoapertura
    FROM caja as c
    where c.fecha = CURDATE()
    and c.motivo = 'APERTURA'
    ORDER BY c.id DESC
    LIMIT 1
");

//Recuperar el retiro del cierre de la caja
$queryCierreCaja = Controller::$connection->query("
    SELECT c.id as id_cierre, c.retiro as retirocierre
    FROM caja as c
    where c.fecha = CURDATE()
    and c.motivo = 'CIERRE'
    ORDER BY c.id DESC
    LIMIT 1
");

//Recuperar el total de Ingresos registrados en la caja
$queryIngresosCaja = Controller::$connection->query("
    SELECT sum(c.ingreso) as ingresosencaja
    FROM caja as c
    where c.fecha = CURDATE()
    and c.motivo NOT IN('APERTURA')
");


$queryFecha = Controller::$connection->query("
    SELECT CURDATE() as factual
");

if($queryFecha){$dataFecha = $queryFecha->fetchAll(PDO::FETCH_ASSOC);}


//Verificar si se seleccionó una fecha posterior a la de hoy

$fechaActual = strtotime($dataFecha[0]["factual"]);

$fechaSeleccionada = strtotime(json_decode($data)->fecha_Diario);

if($fechaSeleccionada>$fechaActual){
    include_once("manejo_errores/view_err_sindatos.php");
}

// Asignamos la trama de datos a la variable Data
if($queryGasto) {
    $dataGasto = $queryGasto->fetchAll(PDO::FETCH_ASSOC);
}
else {
    include_once("manejo_errores/view_err_sindatos.php");
    die();
}

// Asignamos la trama de datos a la variable Data
if($queryDevolucion) {
    $dataDevolucion = $queryDevolucion->fetchAll(PDO::FETCH_ASSOC);
}
else {
    include_once("manejo_errores/view_err_sindatos.php");
    die();
}

//  Asignamos la trama de datos a la variable Data
if($queryVenta) {
    $dataVenta = $queryVenta->fetchAll(PDO::FETCH_ASSOC);
}
else {
    include_once("manejo_errores/view_err_sindatos.php");
    die();
}


// Asignamos la trama de datos a la variable dataAperturaCaja
if($queryAperturaCaja->rowCount()) {
    $dataAperturaCaja = $queryAperturaCaja->fetchAll(PDO::FETCH_ASSOC);
}
else {
    //MANEJADOR DE ERRORES EN CASO LA CAJA NO SE HAYA APERTURADO
    include_once("manejo_errores/view_err_aperturacaja.php");
    die();
}


// Asignamos la trama de datos a la variable dataCierreCaja
if($queryCierreCaja->rowCount()) {
    $dataCierreCaja = $queryCierreCaja->fetchAll(PDO::FETCH_ASSOC);
}
else {
    //MANEJADOR DE ERRORES EN CASO LA CAJA NO SE HAYA CERRADO
    include_once("manejo_errores/view_err_cierrecaja.php");
    die();
}


// Asignamos la trama de datos a la variable dataIngresosCaja
if($queryIngresosCaja->rowCount()) {
    $dataIngresosCaja = $queryIngresosCaja->fetchAll(PDO::FETCH_ASSOC);
}
else {
    die("No hay datos. 6");
    $ingresosCaja = 0;
}

//FOREACH'S PARA RECUPERAR EL SALDO DE APERTURA, EL CIERRE Y LOS INGRESOS REGISTRADOS EN CAJA

foreach ($dataAperturaCaja as $key => $value) {
        if(isset($value["saldoapertura"])){
            $saldoAperturaCaja = $value["saldoapertura"];
            $idApertura = $value["id_apertura"];
        }else{
            $saldoAperturaCaja = 0;
        }
        
}

foreach ($dataCierreCaja as $key => $value) {
        if(isset($value["retirocierre"])){
            $cierreCaja = $value["retirocierre"];
            $idCierre = $value["id_cierre"];
        }else{
            $cierreCaja = 0;
        }
        
} 

foreach ($dataIngresosCaja as $key => $value) {
        if(isset($value["ingresosencaja"])){
            $ingresosCaja = $value["ingresosencaja"];
        }else{
            $ingresosCaja = 0;
        }

        
}  


//Comprobar que el id de apertura No sea mayor al de cierre
if($idApertura>$idCierre){
    include_once("manejo_errores/view_err_cierrecaja.php");
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
$pdf->AddPage('P', 'LETTER');


// ---------------------------------------------------------


$fecha = json_decode($data)->fecha_Diario;

$detalle1 = "";
$detalle2 = "";
$detalle3 = "";

$totalContado = 0;
$totalCredito = 0;
$ventaTotal = 0;


$colum='"3"';
$colum2='"5"';
$width='"50px"';
$width2='"180px"';
$encabezado='"encabezado"';
$align='"right"';
$border='"none"';
$ventaTotal = 0;
$titulo1 = '"titulo1"';
$titulo2 = '"titulo2"';

$medida1 = '"20%"';
$medida2 = '"55%"';
$medida3 = '"25%"';


  // Llena el reporte con las ventas diarias
foreach($dataVenta as $key => $value) {
//OBTENER EL TOTAL DE TODO LO VENDIDO
    $ventaTotal = $ventaTotal+$value["subtotal"];


   //ESTRUCTURA DE COMPARACIÓN QUE EVITA LA COMPROBACIÓN LA PRIMERA VEZ
   if(isset($noVenta)){

        if($value["idventa"]!=$noVenta){
            $detalle1 .= "
          
            <tr>
            <td colspan=$colum align=$align border='none'> Total de la Venta: Q.".$totalPorVenta."</td>
            </tr>
            
            
            <tr class=$encabezado>
                <td colspan=$colum class=$titulo2>No. Venta: ".$value["idventa"]." ---- 
                Cliente: ".$value["cliente"]." ---- Tipo de Venta: ".$value["tipoventa"]."
                </td>
            </tr>

            <tr align='center' class=$encabezado>
                <td width=$medida1><strong>Cantidad</strong></td>
                <td width=$medida2><strong>Producto</strong></td>
                <td width=$medida3><strong>Subtotal</strong></td>
            </tr>
            
            <tr>          
                <td>".$value["cantidad"]."</td>
                <td>".$value["nomproducto"]."</td>
                <td>".$value["subtotal"]."</td>
            </tr>

            ";
        }else{
            $detalle1 .= "
            
            <tr>          
            <td>".$value["cantidad"]."</td>
            <td>".$value["nomproducto"]."</td>
            <td>".$value["subtotal"]."</td>
            </tr>
         
            ";
        }

    }else{

        $detalle1 .= "
    

           
            <tr class=$encabezado>
                <td colspan=$colum class=$titulo2>No. Venta: ".$value["idventa"]." ---- 
                Cliente: ".$value["cliente"]." ---- Tipo de Venta: ".$value["tipoventa"]."
                </td>
            </tr>

            <tr align='center' class=$encabezado>
                
                <td width=$medida1><strong>Cantidad</strong></td>
                <td width=$medida2><strong>Producto</strong></td>
                <td width=$medida3><strong>Subtotal</strong></td>
            </tr>
            
        <tr>
        <td>".$value["cantidad"]."</td>
        <td>".$value["nomproducto"]."</td>
        <td>".$value["subtotal"]."</td>
        </tr>
           
        ";
        $noVenta = $value["idventa"];
    }

    
    $noVenta = $value["idventa"];
    $totalPorVenta = $value["total"];  

    if($value["tipoventa"] == 'Contado')
      {
        $totalContado += $value["subtotal"];
      }else if($value["tipoventa"] == 'Crédito'){
        $totalCredito += $value["subtotal"];
      }
}

if(isset($totalPorVenta)){
    $detalle1 .= "      
    <tr>
    <td colspan=$colum align=$align border='none'> Total de la Venta: Q.".$totalPorVenta."</td>
    </tr>";
}else{
    $detalle1 .= "";
}

$totalDevoluciones = 0;
// Llena el reporte con las devoluciones diarias
foreach($dataDevolucion as $key => $value) {

    //OBTENER EL TOTAL DE LAS DEVOLUCIONES
    $totalDevoluciones = $totalDevoluciones + $value["subtotal"];
    //ESTRUCTURA DE COMPARACIÓN QUE EVITA LA COMPROBACIÓN LA PRIMERA VEZ
   if(isset($noVentaDevuelta)){
        //Agregar código eliminado para que haga la suma de cada devolución    
        if($value["idventa"]!=$noVentaDevuelta){
            $detalle2 .= "
            

            
            
            <tr class=$encabezado>
                <td colspan=$colum class=$titulo2>No. Devolucion: ".$value["iddevolucion"]." ---- 
                Cliente: ".$value["cliente"]." ---- No. Venta: ".$value["idventa"]."
                </td>
            </tr>

            <tr align='center' class=$encabezado>
                <td width=$medida1><strong>Cantidad</strong></td>
                <td width=$medida2><strong>Producto</strong></td>
                <td width=$medida3><strong>Subtotal</strong></td>
            </tr>
            
            <tr>
                <td>".$value["cantidad"]."</td>          
                <td>".$value["nomproducto"]."</td>
                <td>".$value["subtotal"]."</td>
            </tr>

            ";
            $totalDevolucionPorVenta = $value["subtotal"];
        }else{
            $detalle2 .= "
            
            <tr>          
            <td>".$value["cantidad"]."</td>
            <td>".$value["nomproducto"]."</td>
            <td>".$value["subtotal"]."</td>
            </tr>
         
            ";
            $totalDevolucionPorVenta = $totalDevolucionPorVenta+$value["subtotal"];
        }

    }else{

        $detalle2 .= "
    

           
            <tr class=$encabezado>
                <td colspan=$colum class=$titulo2>No. Devolucion: ".$value["iddevolucion"]." ---- 
                Cliente: ".$value["cliente"]." ---- No. Venta: ".$value["idventa"]."
                </td>
            </tr>

            <tr align='center' class=$encabezado>
                <td width=$medida1><strong>Cantidad</strong></td>
                <td width=$medida2><strong>Producto</strong></td>
                <td width=$medida3><strong>Subtotal</strong></td>
            </tr>
            
        <tr>
        <td>".$value["cantidad"]."</td>
        <td>".$value["nomproducto"]."</td>
        <td>".$value["subtotal"]."</td>
        </tr>
           
        ";
        $totalDevolucionPorVenta = $value["subtotal"];
        $noVentaDevuelta = $value["idventa"];

    }

    
    $noVentaDevuelta = $value["idventa"];
    $totalDevolucionPorVenta = $value["subtotal"];

}




// Llena el reporte con los gastos del dia
$totalGasto = 0;
foreach($dataGasto as $key => $value) {
    //OBTENER EL TOTAL DEL GASTO
    $totalGasto = $totalGasto + $value["total"];

    //ESTRUCTURA DE COMPARACIÓN QUE EVITA LA COMPROBACIÓN LA PRIMERA VEZ
   if(isset($catalogoGasto)){
    $nombreGasto = $value["catalogo"];
    $nombreGasto = ucwords(strtolower($nombreGasto));

        if($value["catalogo"]!=$catalogoGasto){
            $detalle3 .= "
          
            
            <tr class=$encabezado>
                <td colspan=$colum2 class=$titulo2>".$nombreGasto." </td>
            </tr>

            <tr align='center' class=$encabezado>
                <td><strong>No. Documento</strong></td>
                <td><strong>Tipo Docto.</strong></td>
                <td><strong>Forma Pago</strong></td>
                <td><strong>Motivo</strong></td>
                <td><strong>Total</strong></td>
            </tr>
            
            <tr>          
                <td>".$value["nodocumento"]."</td>
                <td>".$value["tipodocto"]."</td>
                <td>".$value["formapago"]."</td>
                <td>".$value["motivo"]."</td>
                <td>".$value["total"]."</td>
            </tr>

            ";
            $totalGastoPorCatalogo = $totalGastoPorCatalogo+$value["total"];
            

        }else{
            $detalle3 .= "
            
             <tr>          
                <td>".$value["nodocumento"]."</td>
                <td>".$value["tipodocto"]."</td>
                <td>".$value["formapago"]."</td>
                <td>".$value["motivo"]."</td>
                <td>".$value["total"]."</td>
            </tr>
         
            ";
            $totalGastoPorCatalogo = $value["total"];
        }

    }else{
        $nombreGasto = $value["catalogo"];
        $nombreGasto = ucwords(strtolower($nombreGasto));
        $detalle3 .= "
    

           
            <tr class=$encabezado>
                <td colspan=$colum2 class=$titulo2>".$nombreGasto." </td>
            </tr>

            <tr align='center' class=$encabezado>
                <td><strong>No. Documento</strong></td>
                <td><strong>Tipo Docto.</strong></td>
                <td><strong>Forma Pago</strong></td>
                <td><strong>Motivo</strong></td>
                <td><strong>Total</strong></td>
            </tr>
            
            <tr>          
                <td>".$value["nodocumento"]."</td>
                <td>".$value["tipodocto"]."</td>
                <td>".$value["formapago"]."</td>
                <td>".$value["motivo"]."</td>
                <td>".$value["total"]."</td>
            </tr>
           
        ";
        $totalGastoPorCatalogo = $value["total"];
        $catalogoGasto = $value["catalogo"];

    }

    $catalogoGasto = $value["catalogo"];
    $totalGastoPorCatalogo = $value["total"];


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

    
    $gananciaDelDia = 0 + $gananciaDelDia[0][0] - $gananciaDevolucion[0][0];

    $totalDevolucion = 0 + $gananciaDevolucion[0][1];


    $ventasNetas = $totalContado - $totalDevolucion;
 
$saldoFinal = $saldoAperturaCaja+$ingresosCaja-$totalDevoluciones-$totalGasto;
$saldoCierreCaja = $saldoFinal - $cierreCaja; 

// define some HTML content with style
$html = <<<EOF

<style>

body{
    font-family: sans-serif;
    font-size: 8px;
}

h1 {

    font-size: 20px;
}

.encabezado td{
    background-color: #A9AAAA;
    color: black;
    font-size: 1.1em;
}

.encabezado td.titulo1{
    background-color: #737B9B;
    color: white;
    font-size: 1.5em;   
}

.encabezado td.titulo2{
    background-color: #737B9B;
    color: white;
    font-size: 1.5em;   
}

.resumenventas {
    float: left;

}

.movimientocaja{
    float: right;
}

.datosfactura{
    text-align: left;
    line-height: 10px; 
}

.texto{
    color: black;
    font-size: 14px;
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

.titulos{
    color: #122678;
    font-size: 2em;
}

</style>

<html>
<head>
    <title> Reporte del Dia </title>
</head>
<body>
    <div style="line-height: 1px">
        <h2 style="color: white; background-color: #122678; line-height: 12px;">  Reporte del Dia</h2>
    </div>

    <div style="line-height: 1px">
        <h2 align="center">MIREYA'S SALÓN</h2>
    </div>

    <div style="line-height: 1px">
        <h3 align="center">Barrio El Porvenir, Guastatoya, El Progreso</h3>
    </div>



    <br>

    <div class="datosfactura" style="line-height: 4px">
        <p class="texto" style="line-height: 6px">
            <strong class="strongtexto">Fecha:</strong> $fecha
        </p>
    </div>
    <hr>
    <h2 class="titulos" style="line-height: 6px">  Ventas:</h2> 

    <br>

    <table width="100%" cellpadding="5" border="1" align="center ">

        $detalle1

    </table>
    <h3 align="right">Total Ventas: <span class="cantidad">Q. $ventaTotal</span></h3>
    

    <br>   
    <h2 class="titulos" style="line-height: 6px">  Devoluciones:</h2> 

    <br>
    <table width="100%" cellpadding="5" border="1" align="center">

        $detalle2

    </table>
    <h3 align="right">Total Devoluciones: <span class="cantidad">Q. $totalDevoluciones</span></h3>
    
    
    <br>
    <h2 class="titulos" style="line-height: 6px">  Gastos:</h2> 

    <br>

    <table width="100%" cellpadding="5" border="1" align="center ">
    
        $detalle3

    </table>
    <h3 align="right">Total Devoluciones: <span class="cantidad">Q. $totalGasto</span></h3>

    
    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
              
                <h2 align="left" class="titulos" style="line-height: 6px">  Resumen sobre Ventas:</h2>
                <table width="40%" cellpadding="5" border="1" align="left" >

                    <tr>
                        <td><strong>Ventas al Crédito</strong> </td>
                        <td>Q. $totalCredito</td>
                    </tr>

                    <tr>
                        <td><strong>Ventas al Contado</strong></td>
                        <td>Q. $totalContado</td>
                    </tr>

                    <tr>
                        <td><strong>Devolución sobre ventas</strong></td>
                        <td>Q. $totalDevolucion</td>
                    </tr>
                    <tr>
                        <td><strong>Ventas Netas</strong></td>
                        <td>Q. $ventasNetas</td>
                    </tr>

                    <tr>
                        <td><strong>Ganancia del día</strong></td>
                        <td>Q. $gananciaDelDia</td>
                    </tr>

                </table>
            </div>
                
            <br>

            <div class="col-md-6">
                <h2 align="left" class="titulos" style="line-height: 6px">  Movimiento de Caja:</h2>
                <table width="40%" cellpadding="5" border="1" align="left" >
                    <tr>
                        <td><strong>Saldo Inicial</strong></td>
                        <td>Q. $saldoAperturaCaja</td>
                    </tr>

                    <tr>
                        <td><strong>(+) Ingresos</strong></td>
                        <td>Q. $totalContado</td>
                    </tr>

                    <tr>
                        <td><strong>(-) Devoluciones</strong></td>
                        <td>Q. $totalDevoluciones</td>
                    </tr>

                    <tr>
                        <td><strong>(-) Gastos</strong></td>
                        <td>Q. $totalGasto</td>
                    </tr>

                     <tr>
                        <td><strong>(=) Saldo Final</strong></td>
                        <td><strong>Q. $saldoFinal</strong></td>
                    </tr>
                    
                    <tr>
                        <td><strong>(-) Retiro por Cierre</strong></td>
                        <td>Q. $cierreCaja</td>
                    </tr>

                    <tr>
                        <td><strong>(=) Saldo de Caja</strong></td>
                        <td><strong>Q. $saldoCierreCaja</strong></td>
                    </tr>

                </table>
            </div>
        </div>

        
    </div>

    
    
    
    
    
    
    

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
