<?php

$data = $_POST["data"];
$table = $_POST["table"];
$key = $_POST["key"];
$cod = $_POST["cod"];

$fecha1 = json_decode($data)->fecha_1c;
$fecha2 = json_decode($data)->fecha_2c;

?>
<?php

setlocale(LC_TIME, "ES");

/*Manejo y conversión de fechas*/

//Fecha de Inicio
$fecha_1 = str_replace("/", "-", $fecha1);
$nuevaFecha1 = date("d-m-Y", strtotime($fecha_1));
// $fechaEnLetras1 = strftime("%A, %d de %B de %Y", strtotime($nuevaFecha1));

//Fecha de Fin
$fecha_2 = str_replace("/", "-", $fecha2);
$nuevaFecha2 = date("d-m-Y", strtotime($fecha_2));
// $fechaEnLetras2 = strftime("%A, %d de %B de %Y", strtotime($nuevaFecha2));



?>
<?php



// Consulta de Ventas por Intervalo de Fecha
$queryVenta = Controller::$connection->query("select
c.idCategoria as id,
c.descripcion as nombre, 
sum(d.subtotal) as total,
sum(d.cantidad*p.preciocosto) as costos,
sum(d.cantidad) as cantidad
from venta as v
inner join detalle_venta as d
on v.idventa = d.idventa
inner join producto as p
on p.idproducto = d.idproducto
inner join categoria as c
on c.idCategoria = p.idCategoria
where v.fecha BETWEEN '$fecha1' and '$fecha2'
group by c.idCategoria
");

// Consulta para obtener las devoluciones
$queryDevolucion = Controller::$connection->query("SELECT
sum(dv.subtotal) as subtotalDev,
sum(dv.cantidad*p.preciocosto) as costosDev,
sum(dv.cantidad) as cantidadDev,
c.idCategoria as categoria,
c.descripcion as nombrecat
from devolucion as d
inner join detalle_devolucion as dv
on dv.id_devolucion = d.id_devolucion
inner join producto as p
on p.idproducto = dv.idproducto
inner join categoria as c
on c.idCategoria = p.idCategoria
where d.fecha BETWEEN '$fecha1' and '$fecha2'
group by c.idCategoria
");

// Recuperar las categorias
$queryCategoria = Controller::$connection->query("SELECT idCategoria, descripcion as descri FROM categoria
");


// Asignamos la trama de datos a la variable Data
if($queryVenta->rowCount()) {
    $dataVenta = $queryVenta->fetchAll(PDO::FETCH_ASSOC);
}
else {
  die("No hay datos.");
}

// Asignación de los datos a la devolución
if ($queryDevolucion) {
    $dataDevolucion = $queryDevolucion->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sinDatos = 0;
}

// Asignación de los datos a la devolución
if ($queryCategoria) {
    $dataCategoria = $queryCategoria->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("No hay datos");
}

// print_r($dataVenta);


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


// $idVenta = $dataVenta[0]["idventa"];
// $fecha = $dataVenta[0]["fecha"];
// $nombreCliente = $dataVenta[0]["nomcliente"];
// $nombreProducto = $dataVenta[0]["nomproducto"];
// $cantidad = $dataVenta[0]["cantidad"];
// $subtotal = $dataVenta[0]["subtotal"];
// $total = $dataVenta[0]["total"];

// Recuperar el nombre de la categoria
$detalle1 = "";
$detalle2 = "";
$detalle3 = "";
$arrVenta[] = "";
$arrDev[] = "";
$arrIdVenta[] = "";
$arrIdDev[] = "";
$gananciaBruta = 0;
$gananciaNeta = 0;

foreach($dataVenta as $key => $value){  
    
    $gananciaBruta = $value['total'] - $value['costos'];
    $gananciaBruta = number_format($gananciaBruta, 2);

    $detalle1 .= " 
    <tr>
        <td>".$value['nombre']."</td>
        <td>".$value['cantidad']."</td>
        <td> Q. ".$value['costos']."</td>
        <td> Q. ".$value['total']."</td>
        <td> Q. $gananciaBruta</td>
    </tr>
    ";

    $arrVenta[$key] = $gananciaBruta;

    // Colocar en un array las categorias de las ventas
    $arrIdVenta[$key] = $value["id"];

}

$margenDev = 0;


foreach($dataDevolucion as $key => $value){
    $margenDev = $value["subtotalDev"] - $value["costosDev"];

    $detalle2 .= "
        <tr>
            <td>".$value["nombrecat"]."</td>
            <td>".$value["cantidadDev"]."</td>
            <td> Q.".$value["subtotalDev"]."</td>
            <td> Q.".$margenDev."</td>
        </tr>
    ";

    
    $arrDev[$key] = $margenDev;
    $arrIdDev[$key] = $value["categoria"];
    // EDC
    //echo $arrIdVenta[$key];
    
    // if($arrIdVenta[$key] == $value["categoria"]){
    //     $arrDev[$increment3] = $margenDev;
    //     $increment3++;
    // } else {
    //     $arrDev[$increment3] = 0;
    //     //$increment3--;
    // }
    
}

// Asignación real de las devoluciones comparado con ventas

$increment3 = 0;
$narrDev[] = 0;

// Cantidad de elementos en las devoluciones
$cantDevueltos = count($arrIdDev) - 1;
// echo $cantDevueltos;

foreach($dataVenta as $key => $value){
    
    if($arrIdVenta[$key] == $arrIdDev[$increment3]){
        $narrDev[$key] = $arrDev[$increment3];
        if($increment3 < $cantDevueltos){
            $increment3++;
        }
        
    } else {
        $narrDev[$key] = 0;
        //$increment3--;
    }
}


$elementos = count($arrVenta);
$elementosDev = count($narrDev);
$increment = 0;
$increment2 = 0;

foreach($dataCategoria as $key => $value){

    if($increment < $elementos){

        if ( $increment2 < $elementosDev ) {
            $devuelto = $narrDev[$key];
        }else {
            $devuelto = 0;
        }

        $gananciaNeta = $arrVenta[$key] - $devuelto;
        $gananciaNeta = number_format($gananciaNeta, 2);


        $detalle3 .= "
            <tr>
                <td> ".$value["descri"]."</td>
                <td> Q. ".$gananciaNeta."</td>
            </tr>
        ";
    }else {
        $detalle3 .= "
            <tr>
                <td>".$value["descri"]."</td>
                <td>0</td>
            </tr>
        ";
    }

    $increment++;
    $increment2++;
}


// Programación


// ----------------------------------------


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
    background-color: #797979;
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
    <title>Ventas por Categoria</title>
</head>
<body>

    <div style="line-height: 1px">
    <h2 style="color: white; background-color: #9C277A; line-height: 15px;">  RENDIMIENTO POR CATEGORIA</h2>
    </div>

    <div style="line-height: 2px">
    <h2 align="center">DIST. MIREYA'S BELLEZA TOTAL</h2>
    </div>

    <div style="line-height: 1px">
    <h3 align="center">Barrio El Porvenir, Guastatoya, El Progreso</h3>
    </div>
 
    <br>
    <h4>Desde el $nuevaFecha1 al $nuevaFecha2</h4> 


    <h3>Ventas y Costos del Producto</h3>
    
    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center' class="encabezado">

        <td width="100px"><strong>Categoria</strong></td>
        <td width="100px"><strong>Cant. Venta</strong></td>
        <td><strong>Costos</strong></td>
        <td>Ventas</td>
        <td>Ganancia Bruta</td>

    </tr>

        $detalle1

    </table>    
    <br>



    <h3>Devoluciones</h3>
    
    <table width="60%" cellpadding="5" border="1" align="center">

    <tr align='center' class="encabezado">

        <td width="100px"><strong>Categoria</strong></td>
        <td width="100px"><strong>Cant. Devuelta</strong></td>
        <td width="100px"><strong>Total Devuelto</strong></td>
        <td width="100px"><strong>Margen Devuelto</strong></td>


    </tr>

        $detalle2

    </table>   

    <br>
    
   
    <h3>Ganancia Neta</h3>
    
    <table width="60%" cellpadding="5" border="1" align="center">

    <tr align='center' class="encabezado">

        <td width="100px"><strong>Categoria</strong></td>
        <td width="100px"><strong>Ganancia Neta</strong></td>

    </tr>

        $detalle3

    </table>   

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
$pdf->Output('RTPCategorias.pdf', 'I');

?>
