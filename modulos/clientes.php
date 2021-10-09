<?php
/**
 * Created by PhpStorm.
 * User: RSpro
 * Date: 22/05/16
 * Time: 13:59
 */

 require_once("../assets/config.php");

?>
<?php include("../assets/layouts/header.php"); ?>

<div class="container">


    <div class="col-md-12"><?php

        View::showViewFromTable("CLIENTE", "Manejo de Clientes", Array("photo" => true, "detail" => true), "table_cliente");

        ?></div>



</div>

<script type="text/javascript">

<?php if(!in_array(Security::$permission, $permissions->reportes)): ?>

$("#saldo").attr("disabled", true);

<?php endif; ?>


</script>

<?php include("../assets/layouts/footer.php"); ?>
