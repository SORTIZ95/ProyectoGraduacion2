<?php
/**
 * User: Bcalderon
 * Date: 15/02/19
 * Time: 20:13
 */

$table_name = "catalogoegresos";
try {


    $registries = Controller::$connection->query("SELECT * FROM $table_name");

    if($registries) {

    $registries = $registries->fetchAll(PDO::FETCH_NUM);

    }
}

catch(mysqli_sql_exception $e) {

    echo $e->getMessage();

}

?>

<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">

    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>

            <strong>Gastos</strong>

        </h3>
    </div>

    <div class="panel-collapse collapse in">

        <div class="panel-body">

                    <div id="CLIENTE" class="panel panel-default">
                        
                        <div class="panel-heading">
                            <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                                <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                    <strong>Reporte de Gastos</strong>
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
                                    <select id="gastos" class="form-control" aria-describedby="basic-addon">

                                        <script>

                                        $('#total').attr('disabled', 'disabled'); //Disable

                                            $(document).ready(function() {

                                                $("select#gastos").select2({ data:[
                                                    
                                                <?php foreach($registries as $key => $value): ?>
                                                        
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

                                        <option value="nothing">Selecciona un Gasto</option>*
                                        <option value="Todos">Ver Todos los Gastos</option>
                                     </select>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <div class="form-group">
                                    <br><br><br><br><br>
                                    
                                    <button id="print" template="RPTGastos" type="button" class="print btn btn-default btn-md btn-block">
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


