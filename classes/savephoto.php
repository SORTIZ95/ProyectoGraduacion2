<?php require('../inc/db.inc'); ?>
<?php 


if(isset($_POST['data']) && !isset($_POST['id'])) {

	$id = mysql_query('SELECT * FROM clientes ORDER BY codcliente DESC LIMIT 1');
	$id = mysql_fetch_array($id);

	$img = $_POST['data'];

	$img = str_replace('data:image/jpeg;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);


	$file = "../assets/".$id['codcliente'].".png";
	$success = file_put_contents($file, $data);

	
}

if(isset($_POST['data']) && isset($_POST['id'])) {

	$id = $_POST['id'];

	$img = $_POST['data'];

	$img = str_replace('data:image/jpeg;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);


	$file = "../assets/".$id.".png";
	$success = file_put_contents($file, $data);

	echo json_encode("OK");

	
}


?>