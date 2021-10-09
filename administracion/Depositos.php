<?php
/**
 * Created by PhpStorm.
 * User: Bcalderon
 * Date: 04/02/19
 * Time: 06:25
 */

 require_once("../assets/config.php");

?>
<?php include("../assets/layouts/header.php"); ?>

<div class="container">


    <div class="col-md-12"><?php

        View::showViewFromTable("deposito", "Administrar Depósitos", Array("photo" => false, "detail" => true), "table_deposito");

        ?></div>



</div>

<?php include("../assets/layouts/footer.php"); ?>







