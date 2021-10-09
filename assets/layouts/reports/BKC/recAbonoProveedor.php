<?php

$data = $_POST["data"];


$table = $_POST["table"];

$key = $_POST["key"];

$cod = $_POST["cod"];

?>
<?php  

	$fecha = date("Y-m-d");

	$query = Controller::$connection->query("SELECT p.idpago_proveedor AS idPago, pr.nombre as nombreProveedor, p.fecha AS fecha, f.descripcion AS formaPago, p.noCheque AS cheque, p.nocuenta AS noCuenta, p.banco as banco, p.total_abono AS abono, p.saldoAnterior AS saldoAnterior, p.idFormaPago as idFormaPago
    FROM pago_proveedor AS p
    INNER JOIN proveedor AS pr
    ON pr.idProveedor = p.idProveedor
    INNER JOIN formapago AS f
	ON f.idFormapago = p.idFormaPago
	WHERE p.idpago_proveedor = $cod
	");

	//print_r($query);

	if($query->rowCount()){
		
		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		
	}else{
		echo "No hay datos";
		die();
    }
    
    // =======================================================
    // DECLARACIÓN DE LAS VARIABLES
    // =======================================================

    $idPagoProveedor = $data[0]["idPago"];
    $proveedor = $data[0]["nombreProveedor"];
    $fecha = $data[0]["fecha"];
    $formaPago = $data[0]["formaPago"];
    $noCheque = $data[0]["cheque"];
	$noCuenta = $data[0]["noCuenta"];
	$banco = $data[0]["banco"];
	$abono =  $data[0]["abono"];
	$saldoAnterior = $data[0]["saldoAnterior"];

	$saldoActual = $saldoAnterior - $abono;
	$idFormaPago = $data[0]["idFormaPago"];

	$useFecha = date("d-m-Y", strtotime($fecha));
	$fechaDerechos = date('Y');
	$saldoAnterior = number_format($saldoAnterior, 2);
	

    // REALIZAR VALIDACIONES PARA COMPROBAR SI BANCOS TIENE DATOS
    // SI TIENE DATOS CREAR UNA VARIABLE PARA QUE APAREZCA RENDERIZADO EN EL REPORTE

	class MYPDF extends TCPDF{
		public function header(){
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

	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('');
	$pdf->SetSubject('');

	$pdf->SetPrintHeader(true);
	$pdf->SetPrintFooter(true);

	$pdf->SetMargins(18, 8, 18, true);


	// add a page
	$pdf->AddPage('L', 'A5');


	//GESTIÓN DE LOS DATOS DEVUELTOS POR LA QUERY
	$detalle = "";
	$colspan = '"3"';
	$colspan2 = '"4"';
	$encabezado = '"encabezado"';
	$encabezadoprincipal = '"encabezadoprincipal"';
	$diagnostico = '"diagnostico"';

	
	
	$classTexto = '"texto"';
	$lineHeight = '"line-height: 6px"';
	$strongTexto = '"strongtexto"';
	$detalleCheque = "";
	$detalleDeposito = "";

	// COMPROBAR SI SE PAGÓ CON CHEQUE O DEPÓSITO
	if($idFormaPago == 2){
		// Se pagó con cheque
		
		$detalleCheque .= "


		<tr>
			<td><strong> No. de Cheque: </strong></td>
			<td> $noCheque </td>
		</tr>

		<tr>
			<td><strong> Banco: </strong></td>
			<td> $banco </td>
		</tr>
	
		";
	} else {
		$detalleCheque .= "";
	}

	if($idFormaPago == 3){
		$detalleDeposito .= "

			
			<tr>
				<td><strong> No. de Cuenta: </strong></td>
				<td> $noCuenta </td>
			</tr>

		";
	} else {
		$detalleDeposito .= "";
	}



	


	$html = <<<EOF

<style>
	body{
		font-family: sans-serif;
    }
    
    h2{
        font-size: 20px;
        text-align: center;
    }

    .direccion {
        text-align: center;
        font-size: 12px;
    }

	.titulo{
		background-color: #ffffff;
		font-size: 2.1em;
		color: #9C277A;
		text-align: center;
	}

	.labels{
		font-size: 14px;
	}

	.datos{
		font-size: 10px;
		font-weigth: Regular !important;
	}

	.powered{
		font-size: 0.8em;
		color: #888B8D;
		text-align: center;
	}

	.texto{
		color: black;
		font-size: 14px;
	}
	
	.strongtexto{
		color: black;
	}

	td .centrado {
		align: center;
		text-align: center;
	}


</style>

<html>
<head>
    <title> Abono a Proveedores </title>
    
</head>
<body>
  
	<div></div>
    <h1 class="titulo" style="line-height: 5px">Abono a Proveedores</h1>
    <h2 style="line-height: 1px">MIREYA'S SALÓN</h2>
    <p class="direccion">Barrio El Porvenir, Guastatoya, El Progreso</p>
    <hr>
	
	<br>
	<div></div>

	<div class="datosfactura" style="line-height: 4px">
		<p class="texto" style="line-height: 6px">
			<strong class="strongtexto">Proveedor:</strong> $proveedor
		</p>
	</div>

	<br>
	<div></div>

	<table border="1" width="50%" align="center">
		<tr>
			<td><strong> No. Comprobante: </strong></td>
			<td align="center"> $idPagoProveedor </td>
		</tr>
		<tr>
			<td><strong> Fecha de Pago: </strong></td>
			<td align="center"> $useFecha </td>
		</tr>
		<tr>
			<td><strong> Forma de Pago: </strong></td>
			<td align="center"> $formaPago </td>
		</tr>
		$detalleCheque
		$detalleDeposito
	</table>

	<div></div>
	
	<table border="1" align="center" width="50%">
		
		
		<tr>
			<td><strong> Saldo Anterior: </strong></td>
			<td> Q. $saldoAnterior </td>
		</tr>

		<tr>
			<td><strong> Este Pago: </strong></td>
			<td> Q. $abono </td>
		</tr>

		
		<tr>
			<td><strong> Saldo Actual: </strong></td>
			<td>Q. $saldoActual </td>
		</tr>

	</table>


	<br>
	<hr>
	<p class="powered">Powered by: Vid@Online Corporation - Todos los derechos reservados - $fechaDerechos </p>



</body>
</html>


EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

$nombreArchivo = $idPagoProveedor.'_'.$useFecha;
//Close and output PDF document
$pdf->Output('AbonoProvedores-'.$nombreArchivo.'.pdf', 'I');
	

?>