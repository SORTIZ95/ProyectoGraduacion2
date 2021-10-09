<?php
/**
 * User: Bcalderon
 * Date: 15/02/19
 * Time: 20:13
 */

$table_name = "producto";
$table_name2 = "marca";
try {


    $registries = Controller::$connection->query("SELECT * FROM $table_name");

    if($registries) {

    $registries = $registries->fetchAll(PDO::FETCH_NUM);

    }
}

catch(mysqli_sql_exception $e) {

    echo $e->getMessage();

}

/*TRY CATCH PARA RECUPERAR INFORMACIÓN DE LA TABLA MARCA*/

try{
    $registries2 = Controller::$connection->query("SELECT * FROM $table_name2");

    if($registries2) {

    $registries2 = $registries2->fetchAll(PDO::FETCH_NUM);

    }

}

catch(mysqli_sql_exception $e2) {

    echo $e2->getMessage();

}

?>

<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">

    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>

            <strong>Inventario</strong>

        </h3>
    </div>

    <div class="panel-collapse collapse in">

        <div class="panel-body">

                    <div id="CLIENTE" class="panel panel-default">
                        
                        <div class="panel-heading">
                            <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                                <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                    <strong>Reporte de Movimiento de un Producto</strong>
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

                            <div class="col-md-4">

                                <div class="form-group">
                                    <br><br><br><br><br>
                                    
                                    <button id="print" template="RPTMovimientoProducto" type="button" class="print btn btn-default btn-md btn-block">
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
                                    <strong>Reporte de Movimiento de todos los Productos</strong>
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
                                                    <input id="fecha_3" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
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
                                                    <input id="fecha_4" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <br><br><br><br>
                                    
                                    <button id="print" template="RPTMovimientoProductos" type="button" class="print btn btn-default btn-md btn-block">
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
                                <strong>Reporte de Existencia de Productos</strong>
                            </a>
                            
                        </h3>
                    </div>

                <div id="CLIENTE-panel" class="panel-collapse collapse in">

                    <div class="panel-body">

                        <div class="col-md-12 text-center">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="RPTExistencia" type="button" class="print btn btn-default btn-md btn-md">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte de Existencia
                                </button>
                                    <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

         <!---------------------------------REPORTE DE PRODUCTOS POR MARCA--------------------------------------------->
            <div id="CLIENTE" class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                            <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                <strong>Productos por Marca</strong>
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

                                             <select id="marca" class="form-control" aria-describedby="basic-addon">

                                                 <script>

                                                    $('#total').attr('disabled', 'disabled'); //Disable

                                                        $(document).ready(function() {

                                                            $("select#marca").select2({ data:[
                                                                
                                                            <?php foreach($registries2 as $key => $value): ?>
                                                                    
                                                                    {
                                                                        id: '<?php echo $value[0]; ?>',
                                                                        text: '<?php if(isset($value[0])) {echo $value[0];} ?><?php if(isset($value[1])) {echo " - ".$value[1];} ?>'
                                                                    },

                                                            <?php endforeach; ?>

                                                            ],

                                                                minimumInputLength: 0

                                                            });

                                                        })

                                                    </script>

                                                 <option value="nothing">Selecciona una Marca</option>*
                                             </select>
                                        </div>
                                    </div>
                                </div>
                             </div>
                        </div>

                        <div class="col-md-4">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="RPTProductosPorMarca" type="button" class="print btn btn-default btn-md btn-block">
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


