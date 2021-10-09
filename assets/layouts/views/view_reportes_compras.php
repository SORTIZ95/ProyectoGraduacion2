<?php
/**
 * User: Bcalderon
 * Date: 15/02/19
 * Time: 20:13
 */

$table_name = "producto";
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
                            <strong>Compras realizadas al Mes</strong>
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
                                
                                <button id="print" template="RPTComprasMes" type="button" class="print btn btn-default btn-md btn-block">
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
                                <strong>Reporte de Deudas a Proveedores</strong>
                            </a>
                            
                        </h3>
                    </div>

                <div id="CLIENTE-panel" class="panel-collapse collapse in">

                    <div class="panel-body">

                        <div class="col-md-12 text-center">

                            <div class="form-group">
                                <br>
                                
                                <button id="print" template="RPTComprasCredito" type="button" class="print btn btn-default btn-md btn-md">
                                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte de Deudas
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


