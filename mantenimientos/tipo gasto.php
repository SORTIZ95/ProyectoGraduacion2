<?php


 require_once("../assets/config.php");

?>
<?php include("../assets/layouts/header.php"); ?>

<div class="container">


    <div class="col-md-12"><?php

        View::showViewFromTable("TIPOGASTO", "Tipos de Gastos", Array("photo" => false, "detail" => true));

        ?></div>



</div>

<?php include("../assets/layouts/footer.php"); ?>
