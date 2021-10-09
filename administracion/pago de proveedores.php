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

        View::showViewFromTable("PAGO_PROVEEDOR", "Pago de Proveedores", Array("related" => true, "detail" => true), "table_pago_proveedor");

        ?></div>

</div>

<?php include("../assets/layouts/footer.php"); ?>







