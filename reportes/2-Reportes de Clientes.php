<?php
/**
 * User: BCalderon
 * Date: 26/02/19
 */

 require_once("../assets/config.php");

?>
<?php include("../assets/layouts/header.php"); ?>

<div class="container">


    <div class="col-md-12"><?php

        View::showView("reportes_clientes");

        ?></div>

</div>


<?php include("../assets/layouts/footer.php"); ?>
