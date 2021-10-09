<?php
/**
 * User: BCalderon
 * Date: 15/02/19
 * Time: 20:16
 */

 require_once("../assets/config.php");

?>
<?php include("../assets/layouts/header.php"); ?>

<div class="container">


    <div class="col-md-12"><?php

        View::showView("reportes_inventario");

        ?></div>

</div>


<?php include("../assets/layouts/footer.php"); ?>
