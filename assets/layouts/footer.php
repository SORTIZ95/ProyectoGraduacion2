
<!-- Ajax Scripts -->
<script src="../assets/js/main.js"></script>

<script type="text/javascript">

    <?php

    function getCurrentDirectory() {

        $path = dirname($_SERVER['PHP_SELF']);

        $position = strrpos($path,'/') + 1;

        return substr($path,$position);

    }

    ?>


    $("li#section_<?php echo ucfirst(getCurrentDirectory()); ?> li#<?php echo ucfirst(substr(basename($_SERVER["SCRIPT_NAME"]), 0, -4)); ?>").addClass("active");

    $("li#section_<?php echo ucfirst(getCurrentDirectory()); ?>").addClass("active");


</script>
