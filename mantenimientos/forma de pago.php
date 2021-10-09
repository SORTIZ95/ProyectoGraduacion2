<?php
/**
 * Created by PhpStorm.
 * User: BCalderon
 * Date: 01/30/19
 * Time: 10:30
 */

 require_once("../assets/config.php");

?>
<?php include("../assets/layouts/header.php"); ?>

<div class="container">


    <div class="col-md-12"><?php

        View::showViewFromTable("FORMAPAGO", "Forma de Pago", Array("photo" => false, "detail" => true));

        ?></div>



</div>

<?php include("../assets/layouts/footer.php"); ?>
