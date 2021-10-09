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
$queryVenta = Controller::$connection->query("SELECT C.idcliente, C.nombre, C.saldo
        FROM cliente AS C
        WHERE C.saldo > '0'");

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

$detalle = "";  

  $totalCredito = 0;


  // Llena el reporte con las ventas diarias
foreach($dataVenta as $key => $value) {

    $idcliente = $value["idcliente"];
    $queryPagos = Controller::$connection->query("SELECT *
        FROM pago_cliente AS pc
        WHERE pc.idcliente = '$idcliente'");        

    $dataPagos = $queryPagos->fetch(PDO::FETCH_ASSOC);

    if($queryPagos->rowCount()) 
    {
    }else{
        $detalle .= "
        <tr>

            <td>".$value["idcliente"]."</td>
            <td>".$value["nombre"]."</td>
            <td>"."Q. ".$value["saldo"]."</td>

        </tr>";
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

</style>

<html>
<head>
    <title> Venta </title>
</head>
<body>
    <div style="text-align:center; line-height: 1px;"><h1> Reporte de Morosos </h1></div>
    
    <div style="text-align:center;"> MIREYA'S SALÓN </div> 
    <div> </div>
    <br>
    
    <br>

    <table width="100%" cellpadding="5" border="1" align="center">

    <tr align='center'>

        <td><strong>ID Cliente</strong></td>
        <td><strong>Nombre</strong></td>
        <td><strong>Saldo</strong></td>

    </tr>

        $detalle

    </table>
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
$pdf->Output('Venta.pdf', 'I');

?>
