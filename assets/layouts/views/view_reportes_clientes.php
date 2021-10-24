<?php


$table_name = "cliente";
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

            <strong>Compras</strong>

        </h3>
    </div>

    <div class="panel-collapse collapse in">

        <div class="panel-body">


            <div id="CLIENTE" class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

                            <a data-toggle="collapse" data-target="#CLIENTE-panel">
                                <strong>Reporte de Total Adeudados por los Clientes</strong>
                            </a>
                            
                        </h3>
                    </div>

                <div id="CLIENTE-panel" class="panel-collapse collapse in">

                    <div class="panel-body">

                        <div class="col-md-12 text-center">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="RPTAdeudados" type="button" class="print btn btn-default btn-md btn-md">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte de Adeudados
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
                                <strong>Reporte de Clientes Morosos</strong>
                            </a>
                            
                        </h3>
                    </div>

                <div id="CLIENTE-panel" class="panel-collapse collapse in">

                    <div class="panel-body">

                        <div class="col-md-12 text-center">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="RPTMorosos" type="button" class="print btn btn-default btn-md btn-md">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte de Morosos
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
                                <strong>Historial de pago de clientes</strong>
                            </a>
                            
                        </h3>
                    </div>

                <div id="CLIENTE-panel" class="panel-collapse collapse in">

                    <div class="panel-body">
                        <select id="cliente" class="form-control" aria-describedby="basic-addon">

                            <script>


                                $(document).ready(function() {

                                    $("select#cliente").select2({ data:[
                                        
                                    <?php foreach($registries as $key => $value): ?>
                                            
                                            {
                                                id: '<?php echo $value[0]; ?>',
                                                text: '<?php if(isset($value[1])) {echo $value[0];} ?><?php if(isset($value[3])) {echo " - ".$value[1];} ?>'
                                            },

                                    <?php endforeach; ?>

                                    ],

                                        minimumInputLength: 0

                                    });

                                })

                            </script>

                            <option value="nothing">Selecciona un Cliente</option>*
                         </select>

                        <div class="col-md-12 text-center">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="RPTHistorialPagoCliente" type="button" class="print btn btn-default btn-md btn-md">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte Historial de Pagos
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


