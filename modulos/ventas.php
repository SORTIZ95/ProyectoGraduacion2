<?php


 require_once("../assets/config.php");

?>
<?php include("../assets/layouts/header.php"); ?>

<div class="container">


    <div class="col-md-12"><?php

        View::showViewFromTable("VENTA", "Control de Ventas", Array("photo" => false, "detail" => true), "table_venta");

        ?></div>

</div>

<?php include("../assets/layouts/footer.php"); ?>







