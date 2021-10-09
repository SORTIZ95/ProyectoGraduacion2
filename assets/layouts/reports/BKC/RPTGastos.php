<?php

$data = $_POST["data"];
$table = $_POST["table"];
$key = $_POST["key"];
$cod = $_POST["cod"];

$fecha1 = json_decode($data)->fecha_1;
$fecha2 = json_decode($data)->fecha_2;
$gastoSeleccionado = json_decode($data)->gastos;



?>
<?php

setlocale(LC_TIME, "ES");

?>
<?php


//VALIDAR QUE SI SELECCIONÓ ALGUN GASTOS EN ESPECÍFICO O GENERAL
if($gastoSeleccionado=="Todos"){


//SE MUESTRA LOS DATOS DE TODO EL CATÁLOGO DE GASTOS
    //Query para ver el detalle de los gastos
    $queryGastoDetalle = Controller::$connection->query("SELECT g.noDocumento as nodocumento, 
        t.descripcion as tipodocto,
        f.descripcion as formapago,
        g.fecha as fecha, ce.descripcion as catalogo, g.motivo as motivo, g.total as total
        FROM gasto as g
        INNER JOIN catalogoegresos ce
        on ce.idCatalogoEgresos = g.idEgreso
        INNER JOIN tipoDocumento as t
        on t.idTipoDo = g.tipoDocumento
        INNER JOIN formapago as f
        on f.idFormapago = g.idFormaPago
        where g.fecha BETWEEN '$fecha1' AND '$fecha2'
        ORDER by g.fecha ASC
    ");

    //Query para ver el resumen de los gastos
    $queryGastoGeneral = Controller::$connection->query("SELECT ce.descripcion as catalogo, sum(g.total) as total
        FROM gasto as g
        INNER JOIN catalogoegresos as ce
        on ce.idCatalogoEgresos = g.idEgreso
        where g.fecha BETWEEN '$fecha1' AND '$fecha2'
        GROUP BY ce.descripcion
        ORDER by g.fecha ASC
    ");

    if($queryGastoDetalle->rowCount()) {
        $dataGastoDetalle = $queryGastoDetalle->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        die("No hay datos.");
    }

    if($queryGastoGeneral->rowCount()) {
        $dataGastoGeneral = $queryGastoGeneral->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        die("No hay datos.");
    }

}else{
//SE MUESTRA LOS DATOS DE UN GASTO EN ESPECÍFICO
    $queryGastoDetalle = Controller::$connection->query("SELECT g.noDocumento as nodocumento, 
        t.descripcion as tipodocto,
        f.descripcion as formapago,
        g.fecha as fecha, ce.descripcion as catalogo, g.motivo as motivo, g.total as total
        FROM gasto as g
        INNER JOIN catalogoegresos ce
        on ce.idCatalogoEgresos = g.idEgreso
        INNER JOIN tipoDocumento as t
        on t.idTipoDo = g.tipoDocumento
        INNER JOIN formapago as f
        on f.idFormapago = g.idFormaPago
        where ce.idCatalogoEgresos = '$gastoSeleccionado' AND
        g.fecha BETWEEN '$fecha1' AND '$fecha2'
        ORDER by g.fecha ASC
    ");

    if($queryGastoDetalle->rowCount()) {
        $dataGastoDetalle = $queryGastoDetalle->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        die("No hay datos.");
    }


}



//print_r($dataGastoDetalle);
//print_r($dataGastoGeneral);


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

/*
$idVenta = $dataVenta[0]["idventa"];
$fecha = $dataVenta[0]["fecha"];
$nombreCliente = $dataVenta[0]["nomcliente"];
$nombreProducto = $dataVenta[0]["nomproducto"];
$cantidad = $dataVenta[0]["cantidad"];
$subtotal = $dataVenta[0]["subtotal"];
$total = $dataVenta[0]["total"];
*/
$detalle1 = " ";
$detalle2 = " ";

if($gastoSeleccionado=="Todos"){
   $tituloResumenGastos = "<h3>Resumen de Gastos</h3>"; 
   $catalogoSeleccionado = "";
   $inicioTabla = '<table width="100%" cellpadding="5" border="1" align="center">';
   $finTabla = "</table>";
}else{
    $tituloResumenGastos = "";
    $inicioTabla = "";
    $finTabla = ""; 
}


  // Llena el reporte con los datos de las ventas a cada cliente.


  $colum='"6"';
  $width='"50px"';
  $width2='"180px"';
  $encabezado='"encabezado"';
  $encabezado2='"encabezado2"';
  $align='"right"';
  $border='"none"';
  $gastoTotal = 0;
  $relleno = '"relleno"';
  $datos = '"datos"';
 
  


  if($gastoSeleccionado=="Todos"){
     //FOR EACH PARA EL LLENADO DE LA TABLA DETALLADA      
    foreach($dataGastoDetalle as $key => $value) {

       //OBTENER EL TOTAL DE TODO LO VENDIDO
        $gastoTotal = $gastoTotal+$value["total"];

       //ESTRUCTURA DE COMPARACIÓN QUE EVITA LA COMPROBACIÓN LA PRIMERA VEZ
       if(isset($fecha)){

            if($value["fecha"]!=$fecha){
                $detalle1 .= "
              
                <tr>
                <td colspan=$colum align=$align border='none'> Total Gasto: Q. ".$totalGastoPorDia."</td>
                </tr>

                <tr align='center' class=$encabezado>
                    <td width=$width><strong>Fecha</strong></td>
                    <td><strong>No. Documento</strong></td>
                    <td ><strong>Tipo Docto.</strong></td>
                    <td width=$width><strong>Catalogo</strong></td>
                    <td width=$width2><strong>Motivo</strong></td>
                    <td><strong>Total</strong></td>
                </tr>
                
                <tr>          
                    <td>".$value["fecha"]."</td>
                    <td>".$value["nodocumento"]."</td>
                    <td>".$value["tipodocto"]."</td>
                    <td>".$value["catalogo"]."</td>
                    <td>".$value["motivo"]."</td>
                    <td>".$value["total"]."</td>
                </tr>

                ";
                $totalGastoPorDia = $value["total"];
            }else{
                $detalle1 .= "
                
                <tr>          
                    <td>".$value["fecha"]."</td>
                    <td>".$value["nodocumento"]."</td>
                    <td>".$value["tipodocto"]."</td>
                    <td>".$value["catalogo"]."</td>
                    <td>".$value["motivo"]."</td>
                    <td>".$value["total"]."</td>
                </tr>
             
                ";
                $totalGastoPorDia = $totalGastoPorDia+ $value["total"];
            }

        }else{

            $detalle1 .= "
            
            <tr>
                <td>".$value["fecha"]."</td>
                <td>".$value["nodocumento"]."</td>
                <td>".$value["tipodocto"]."</td>
                <td>".$value["catalogo"]."</td>
                <td>".$value["motivo"]."</td>
                <td>".$value["total"]."</td>
            </tr>
               
            ";
            $totalGastoPorDia = $value["total"];
            $fecha = $value["fecha"];
        }

        
        $fecha = $value["fecha"];
          
 
    }

    //LLENADO DE LA TABLA GENERAL
    foreach($dataGastoGeneral as $keys => $values) {
        $detalle2 .= "
            <tr align='center' class=$encabezado2>
                <td class=$relleno><strong>".$values["catalogo"]."</strong></td>
                <td class=$datos>Q. ".$values["total"]."</td>
            </tr>            
        ";

    }

  }
  else{
    foreach($dataGastoDetalle as $key => $value) {

       //OBTENER EL TOTAL DE TODO LO GASTADO
        $gastoTotal = $gastoTotal+$value["total"];

       //ESTRUCTURA DE COMPARACIÓN QUE EVITA LA COMPROBACIÓN LA PRIMERA VEZ
       if(isset($fecha)){

            if($value["fecha"]!=$fecha){
                $detalle1 .= "
              
                <tr>
                <td colspan=$colum align=$align border='none'> Total Gasto: Q. ".$totalGastoPorDia."</td>
                </tr>

                <tr align='center' class=$encabezado>
                    <td width=$width><strong>Fecha</strong></td>
                    <td><strong>No. Documento</strong></td>
                    <td ><strong>Tipo Docto.</strong></td>
                    <td width=$width><strong>Catalogo</strong></td>
                    <td width=$width2><strong>Motivo</strong></td>
                    <td><strong>Total</strong></td>
                </tr>
                
                <tr>          
                    <td>".$value["fecha"]."</td>
                    <td>".$value["nodocumento"]."</td>
                    <td>".$value["tipodocto"]."</td>
                    <td>".$value["catalogo"]."</td>
                    <td>".$value["motivo"]."</td>
                    <td>".$value["total"]."</td>
                </tr>

                ";
                $totalGastoPorDia = $value["total"];
            }else{
                $detalle1 .= "
                
                <tr>          
                    <td>".$value["fecha"]."</td>
                    <td>".$value["nodocumento"]."</td>
                    <td>".$value["tipodocto"]."</td>
                    <td>".$value["catalogo"]."</td>
                    <td>".$value["motivo"]."</td>
                    <td>".$value["total"]."</td>
                </tr>
             
                ";
                $totalGastoPorDia = $totalGastoPorDia+ $value["total"];
            }

        }else{

            $detalle1 .= "
            
            <tr>
                <td>".$value["fecha"]."</td>
                <td>".$value["nodocumento"]."</td>
                <td>".$value["tipodocto"]."</td>
                <td>".$value["catalogo"]."</td>
                <td>".$value["motivo"]."</td>
                <td>".$value["total"]."</td>
            </tr>
               
            ";
            $totalGastoPorDia = $value["total"];
            $fecha = $value["fecha"];
        }

        
        $fecha = $value["fecha"];
        $catalogoSeleccionado = "Concepto de Gasto: ".$value["catalogo"];
 
    }
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

.encabezado2 td.relleno{
    background-color: green;
    color: white;
    font-size: 1.2em;
}

.encab td.datos{
    font-size: 1.2em;   
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
    <title>Reporte de Gastos</title>
</head>
<body>
<div style="text-align:center; line-height: 3px;"><h1>Reporte de Gastos</h1></div>
<div style="text-align:center; line-height: 3px;"><h2> MIREYA'S SALÓN </h2></div>    
<div style="text-align:center; line-height: 3px;"><h3>Barrio El Porvenir, Guastatoya, El Progreso</h3></div>


    <br>
    <h4>$catalogoSeleccionado</h4>
    <h4>Fecha: $fecha1 al $fecha2</h4> 

    <br>
    
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center' class="encabezado">

        <td width="50px"><strong>Fecha</strong></td>
        <td><strong>No. Documento</strong></td>
        <td ><strong>Tipo Docto.</strong></td>
        <td width="50px"><strong>Catalogo</strong></td>
        <td width="180px"><strong>Motivo</strong></td>
        <td><strong>Total</strong></td>

    </tr>

        $detalle1

    </table>
    

    <br>
    <br>
    
    $tituloResumenGastos
    
    $inicioTabla    
        $detalle2
    $finTabla

    
    <br>
    <strong><div style="text-align:right; font-size: 15px; float:right;"> <strong>El Total de Gastos es:</strong> Q. $gastoTotal</div> </strong>

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
$pdf->Output('RPTGastos.pdf', 'I');

?>
