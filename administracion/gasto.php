<?php


 require_once("../assets/config.php");

?>
<?php include("../assets/layouts/header.php"); ?>

<div class="container">


    <div class="col-md-12"><?php

        View::showViewFromTable("GASTO", "Administración de los Egresos", Array("photo" => false, "detail" => true),"table_gasto");

        ?></div>



</div>

<?php include("../assets/layouts/footer.php"); ?>
