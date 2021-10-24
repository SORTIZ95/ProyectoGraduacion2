<?php


require_once("../assets/config.php");

$cliente = Controller::$connection->query("SELECT * FROM cliente");

$cliente = $cliente->fetchAll(PDO::FETCH_NUM);

?>


<?php include("../assets/layouts/header.php"); ?>


<div class="container">

    <div class="col-md-12">


        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>

                    <strong>Evolución de Ventas</strong>

                </h3>

            </div>

            <div class="panel-collapse">

                <div class="panel-body">


           <?php View::newChart("Ventas", Controller::getChartData("SELECT fecha AS FECHA, SUM(v.total) as VENTA FROM venta as V
            WHERE FECHA BETWEEN curdate() - INTERVAL 12 MONTH AND curdate() group by FECHA")); ?>


                </div>

                <div class="panel-footer">

                    <div style="text-align: center;">


                    </div>

                </div>



            </div>

        </div>


        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>

                    <strong>Compra de Cliente</strong>

                </h3>

            </div>

            <div class="panel-collapse">

                <div class="panel-body">

              <?php View::newChart("Clientes", Controller::getChartData("SELECT fecha AS FECHA, SUM(v.total) as VENTA FROM venta as V
              WHERE idcliente = 1 and FECHA BETWEEN curdate() - INTERVAL 12 MONTH AND curdate() group by FECHA")); ?>


                <br>


                <script type="text/javascript">


                $(document).ready(function() {


                  $("select#cliente").select2({ data:[


                    <?php foreach($cliente as $key => $value): ?>

                            {
                                id: '<?php echo $value[0]; ?>',
                                text: '<?php if(isset($value[0])) {echo $value[0];} ?><?php if(isset($value[1])) {echo " - ".$value[1];} ?>'
                            },


                    <?php endforeach; ?>


                    ],

                        minimumInputLength: 0


                    });


                  $("select#cliente").on("change", function() {

                    cod = $(this).val();

                        query = "SELECT fecha AS FECHA, SUM(v.total) as VENTA FROM venta as V WHERE idcliente = "+cod+" and FECHA BETWEEN curdate() - INTERVAL 12 MONTH AND curdate() group by FECHA";


                        $.ajax({

                                url: "../classes/Api.php?action=getChartData",
                                method: "POST",
                                data: { "data": query, "table": 0, "key": 0, "cod": 0 },
                                dataType: "JSON",
                                success: function(r) {

            
                                    r.unshift(["Fecha", "Venta"]);

                                    chartClientes.draw(google.visualization.arrayToDataTable(r), optionsClientes);

                                }


                            });


                    });



                  });

                


                </script>


                <div class="col-md-6">

                    <select id="cliente" class="form-control" aria-describedby="basic-addon">

                            <option value="nothing">Selecciona un Cliente</option>

                    </select>

                </div>

                </div>

                <div class="panel-footer">

                    <div style="text-align: center;">


                    </div>

                </div>



            </div>

        </div>





    </div>


    <div class="col-md-6">



        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>

                    <strong>Reporte de Créditos</strong>

                </h3>

            </div>

            <div class="panel-collapse">

                <div class="panel-body">


                  <p style="text-align: center;">Haz click en imprimir para ver un registro detallado.</p>


                </div>

                <div class="panel-footer">

                    <div style="text-align: center;">

                        <button template="reporte_morosos" id="print" type="button" class="print btn btn-default btn-md">
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir
                        </button>

                    </div>

                </div>



            </div>

        </div>





    </div>


    <div class="col-md-6">



        <div class="panel panel-default">

            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>

                    <strong>Ventas de hoy</strong>

                </h3>

            </div>

            <div class="panel-collapse">

                <div class="panel-body">

                <p style="text-align: center;">Haz click en imprimir para ver un registro detallado.</p>

                </div>

                <div class="panel-footer">

                    <div style="text-align: center;">

                        <button template="ventas_hoy" id="print" type="button" class="print btn btn-default btn-md">
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir
                        </button>

                    </div>

                </div>



            </div>

        </div>





    </div>

</div>



<?php include("../assets/layouts/footer.php"); ?>
