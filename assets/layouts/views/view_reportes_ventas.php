<?php


$table_name = "producto";
$table_name2 = "tipo_venta";
$table_name3 = "cliente";
try {


    $registries = Controller::$connection->query("SELECT * FROM $table_name");

    if($registries) {

    $registries = $registries->fetchAll(PDO::FETCH_NUM);

    }
}

catch(mysqli_sql_exception $e) {

    echo $e->getMessage();

}

//Recuperación de datos de la tabla de Tipo Venta
try {


    $registries2 = Controller::$connection->query("SELECT * FROM $table_name2");

    if($registries2) {

    $registries2 = $registries2->fetchAll(PDO::FETCH_NUM);

    }
}

catch(mysqli_sql_exception $e) {

    echo $e->getMessage();

}

//Recuperación de datos de la tabla Cliente
try {


    $registries3 = Controller::$connection->query("SELECT * FROM $table_name3");

    if($registries3) {

    $registries3 = $registries3->fetchAll(PDO::FETCH_NUM);

    }
}

catch(mysqli_sql_exception $e) {

    echo $e->getMessage();

}
?>

<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">

    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>

            <strong>Ventas</strong>

        </h3>
    </div>

    <div class="panel-collapse collapse in">

        <div class="panel-body">

                    <div id="CLIENTE" class="panel panel-default">
                        
                        <div class="panel-heading">
                            <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                                <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                    <strong>Reporte Diario de Ventas, Devoluciones y Ganancias</strong>
                                </a>
                                
                            </h3>
                        </div>

                    <div id="CLIENTE-panel" class="panel-collapse collapse in">

                        <div class="panel-body">

                            <div class="col-md-8">

                                <div class="well">

                                    <div class="inputs_wrapper" style="max-height: inherit;">

                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon">
                                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                    </span>
                                                    <input id="fecha_Diario" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <br>
                                    
                                    <button id="print" template="reporte_diario" type="button" class="print btn btn-default btn-md btn-block">
                                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                    </button>
                                        <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <div id="CLIENTE" class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                            <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                <strong>Articulos más/menos Vendidos del mes</strong>
                            </a>
                            
                        </h3>
                    </div>

                <div id="CLIENTE-panel" class="panel-collapse collapse in">

                    <div class="panel-body">

                        <div class="col-md-8">

                            <div class="well">

                                <div class="inputs_wrapper" style="max-height: inherit;">

                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon">
                                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                </span>
                                                <input id="fecha_mes" class="date_mensual form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="RPTProductosV" type="button" class="print btn btn-default btn-md btn-block">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                </button>
                                    <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="CLIENTE" class="panel panel-default">
            
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                            <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                <strong>Ventas por Fecha Específica</strong>
                            </a>
                            
                        </h3>
                    </div>

                <div id="CLIENTE-panel" class="panel-collapse collapse in">

                    <div class="panel-body">

                        <div class="col-md-8">

                            <div class="well">

                                <div class="inputs_wrapper" style="max-height: inherit;">

                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon">
                                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                </span>
                                                <input id="Vfecha_Diario" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="ventas_diarias" type="button" class="print btn btn-default btn-md btn-block">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                </button>
                                    <br>
                            </div>
                        </div>
                    </div>
                </div>
             </div>


            <div id="CLIENTE" class="panel panel-default">
            
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                            <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                <strong>Ventas al Crédito</strong>
                            </a>
                            
                        </h3>
                    </div>

                <div id="CLIENTE-panel" class="panel-collapse collapse in">

                    <div class="panel-body">

                        <div class="col-md-8">

                            <div class="well">

                                <div class="inputs_wrapper" style="max-height: inherit;">

                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon">
                                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                </span>
                                                <input id="fecha_Creditos" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="RPTVentaCredito" type="button" class="print btn btn-default btn-md btn-block">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                </button>
                                    <br>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
             <div id="CLIENTE" class="panel panel-default">
            
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                        <a data-toggle="collapse" data-target="#CLIENTE-panel">
                            <strong>Ventas al Contado</strong>
                        </a>
                        
                    </h3>
                </div>

                <div id="CLIENTE-panel" class="panel-collapse collapse in">

                    <div class="panel-body">

                        <div class="col-md-8">

                            <div class="well">

                                <div class="inputs_wrapper" style="max-height: inherit;">

                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon">
                                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                </span>
                                                <input id="fecha_Contado" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="RPTVentaContado" type="button" class="print btn btn-default btn-md btn-block">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                </button>
                                    <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="CLIENTE" class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                            <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                <strong>Ventas por Producto Mensual</strong>
                            </a>
                            
                        </h3>
                    </div>

                <div id="CLIENTE-panel" class="panel-collapse collapse in">

                    <div class="panel-body">

                        <div class="col-md-8">

                            <div class="well">

                                <div class="inputs_wrapper" style="max-height: inherit;">

                                    <div class="row">

                                        <div class="col-md-12">

                                            <div class="input-group">
                                                <span class="input-group-addon" id="basic-addon">
                                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                </span>
                                                <input id="VPfecha_mes" class="date_mensual form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                            </div>
                                            <br>
                                            <br>
                                             <select id="producto" class="form-control" aria-describedby="basic-addon">

                                                 <script>

                                                    $('#total').attr('disabled', 'disabled'); //Disable

                                                        $(document).ready(function() {

                                                            $("select#producto").select2({ data:[
                                                                
                                                            <?php foreach($registries as $key => $value): ?>
                                                                    
                                                                    {
                                                                        id: '<?php echo $value[0]; ?>',
                                                                        text: '<?php if(isset($value[1])) {echo $value[0];} ?><?php if(isset($value[3])) {echo " - ".$value[2];} ?>'
                                                                    },

                                                            <?php endforeach; ?>

                                                            ],

                                                                minimumInputLength: 0

                                                            });

                                                        })

                                                    </script>

                                                 <option value="nothing">Selecciona un Producto</option>*
                                             </select>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="RPTVentasProducto" type="button" class="print btn btn-default btn-md btn-block">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                </button>
                                    <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--------------------------------REPORTE DE VENTAS POR INTERVALO DE FECHA---------------------------------->

            <div id="CLIENTE" class="panel panel-default">
                        
                        <div class="panel-heading">
                            <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                                <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                    <strong>Ventas por Intervalo de Fecha</strong>
                                </a>
                                
                            </h3>
                        </div>

                    <div id="CLIENTE-panel" class="panel-collapse collapse in">

                        <div class="panel-body">

                            <div class="col-md-8">

                                <div class="well">

                                    <div class="inputs_wrapper" style="max-height: inherit;">
                                        <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                            <strong>Fecha de Inicio</strong>
                                        </a>    

                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon">
                                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                    </span>
                                                    <input id="fecha_1" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="inputs_wrapper" style="max-height: inherit;">
                                        
                                        <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                            <strong>Fecha de Fin</strong>
                                        </a>

                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon">
                                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                    </span>
                                                    <input id="fecha_2" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <br><br><br><br><br>
                                    
                                    <button id="print" template="RPTVentasIntervaloFecha" type="button" class="print btn btn-default btn-md btn-block">
                                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                    </button>
                                        <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--------------------------------REPORTE DE VENTAS POR INTERVALO DE FECHA---------------------------------->
                <div id="CLIENTE" class="panel panel-default">
                        
                        <div class="panel-heading">
                            <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                                <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                    <strong>Ventas por Tipo de Venta e Intervalo de Fechas</strong>
                                </a>
                                
                            </h3>
                        </div>

                    <div id="CLIENTE-panel" class="panel-collapse collapse in">

                        <div class="panel-body">

                            <div class="col-md-8">

                                <div class="well">

                                    <div class="inputs_wrapper" style="max-height: inherit;">
                                        <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                            <strong>Fecha de Inicio</strong>
                                        </a>    

                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon">
                                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                    </span>
                                                    <input id="fecha1" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="inputs_wrapper" style="max-height: inherit;">
                                        
                                        <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                            <strong>Fecha de Fin</strong>
                                        </a>

                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon">
                                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                    </span>
                                                    <input id="fecha2" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <select id="tipoventa" class="form-control" aria-describedby="basic-addon">

                                        <script>

                                        $('#total').attr('disabled', 'disabled'); //Disable

                                            $(document).ready(function() {

                                                $("select#tipoventa").select2({ data:[
                                                    
                                                <?php foreach($registries2 as $key => $value): ?>
                                                        
                                                        {
                                                            id: '<?php echo $value[0]; ?>',
                                                            text: '<?php if(isset($value[1])) {echo $value[0];} ?><?php if(isset($value[1])) {echo " - ".$value[1];} ?>'
                                                        },

                                                <?php endforeach; ?>

                                                ],

                                                    minimumInputLength: 0

                                                });

                                            })

                                        </script>

                                        <option value="nothing">Selecciona el Tipo de Venta</option>*
                                     </select>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <br><br><br><br><br>
                                    
                                    <button id="print" template="RPTVentasPorTipoIntervaloFecha" type="button" class="print btn btn-default btn-md btn-block">
                                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                    </button>
                                        <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--------------------------------REPORTE DE VENTAS POR TIPO E INTERVALO DE FECHA-------------------------------->
                <div id="CLIENTE" class="panel panel-default">
                        
                        <div class="panel-heading">
                            <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                                <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                    <strong>Ventas por Cliente</strong>
                                </a>
                                
                            </h3>
                        </div>

                    <div id="CLIENTE-panel" class="panel-collapse collapse in">

                        <div class="panel-body">

                            <div class="col-md-8">

                                <div class="well">

                                   
                                    <select id="cliente" class="form-control" aria-describedby="basic-addon">

                                        <script>

                                        $('#total').attr('disabled', 'disabled'); //Disable

                                            $(document).ready(function() {

                                                $("select#cliente").select2({ data:[
                                                    
                                                <?php foreach($registries3 as $key => $value): ?>
                                                        
                                                        {
                                                            id: '<?php echo $value[0]; ?>',
                                                            text: '<?php if(isset($value[1])) {echo $value[0];} ?><?php if(isset($value[1])) {echo " - ".$value[1];} ?>'
                                                        },

                                                <?php endforeach; ?>

                                                ],

                                                    minimumInputLength: 0

                                                });

                                            })

                                        </script>

                                        <option value="nothing">Selecciona un Cliente</option>*
                                     </select>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <br>
                                    
                                    <button id="print" template="RPTVentasPorClienteI" type="button" class="print btn btn-default btn-md btn-block">
                                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                    </button>
                                        <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--------------------------------REPORTE ARTICULOS MÁS DEVUELTOS-------------------------------->
                 <div id="CLIENTE" class="panel panel-default">
                        
                        <div class="panel-heading">
                            <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                                <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                    <strong>Productos comunmente más devueltos</strong>
                                </a>
                                
                            </h3>
                        </div>

                    <div id="CLIENTE-panel" class="panel-collapse collapse in">

                        <div class="panel-body">

                            <div class="col-md-12 text-center">

                                <div class="form-group">
                                    <br>                               
                                    <button id="print" template="RPTProductosMasDevueltos" type="button" class="print btn btn-default btn-md btn-md">
                                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                    </button>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!--------------------------------REPORTE DE VENTAS POR INTERVALO DE FECHA---------------------------------->

            <div id="CLIENTE" class="panel panel-default">
                        
                        <div class="panel-heading">
                            <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                                <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                    <strong>Reporte Ganancias por Intervalo de Fecha</strong>
                                </a>
                                
                            </h3>
                        </div>

                    <div id="CLIENTE-panel" class="panel-collapse collapse in">

                        <div class="panel-body">

                            <div class="col-md-8">

                                <div class="well">

                                    <div class="inputs_wrapper" style="max-height: inherit;">
                                        <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                            <strong>Fecha de Inicio</strong>
                                        </a>    

                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon">
                                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                    </span>
                                                    <input id="fecha_1a" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="inputs_wrapper" style="max-height: inherit;">
                                        
                                        <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                            <strong>Fecha de Fin</strong>
                                        </a>

                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="input-group">
                                                    <span class="input-group-addon" id="basic-addon">
                                                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                    </span>
                                                    <input id="fecha_2a" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <br><br><br><br><br>
                                    
                                    <button id="print" template="RPTGananciaPorIntervalo" type="button" class="print btn btn-default btn-md btn-block">
                                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
                                    </button>
                                        <br>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


        </div>
    </div>
</div>


