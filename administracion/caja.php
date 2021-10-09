<?php

 require_once("../assets/config.php");

?>
<?php $section = basename($_SERVER["SCRIPT_FILENAME"], '.php'); ?><?php if(!in_array(Security::$permission, $permissions->modulo[$section])): ?><?php header("Location: ../"); ?><?php endif; ?>
<?php include("../assets/layouts/header.php"); ?>

<div class="container">


    <div class="col-md-12"><?php

        View::showViewFromTable("CAJA", "Caja", Array("photo" => false, "detail" => true), "table_caja");

        ?></div>


</div>

<?php include("../assets/layouts/footer.php"); ?>
