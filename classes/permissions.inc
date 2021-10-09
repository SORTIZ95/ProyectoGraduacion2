<?php 

    $permissions = new stdClass;

    $permissions->modulo["caja"] = [0,1];
    $permissions->modulo["clientes"] = [0,1];
    
    $permissions->administracion = [0, 1];
    $permissions->mantenimientos = [0];
    $permissions->reportes = [0,1];

?>