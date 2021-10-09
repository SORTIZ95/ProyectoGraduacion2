<?php
/**
 * Created by PhpStorm.
 * User: RSpro
 * Date: 22/05/16
 * Time: 15:31
 */

/* Form Construct Data */

try {

    $fields = Controller::$connection->query("DESC $table_name");

    if($fields) {

        $fields = $fields->fetchAll(PDO::FETCH_NUM);
    }

}

catch(mysqli_sql_exception $e) {

    echo $e->getMessage();

}


try {


    $registries = Controller::$connection->query("SELECT * FROM $table_name");

    if($registries) {

    $registries = $registries->fetchAll(PDO::FETCH_NUM);

    }


}


catch(mysqli_sql_exception $e) {

    echo $e->getMessage();

}

/* End Form Construct Data */


?>

<div id="<?php echo $table_name; ?>" class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>

            <a data-toggle="collapse" data-target="#<?php echo $table_name."-panel"; ?>">
                <strong><?php echo $table_title; ?></strong>
            </a>

        </h3>

    </div>

    <div id="<?php echo $table_name."-panel"; ?>" class="panel-collapse collapse in">

    <div class="panel-body">


    <div class="col-md-<?php if($options["related"] == true) { echo "8"; } else { echo "12"; } ?>">

        <div class="well">


            <div class="inputs_wrapper" style="max-height: inherit;">

             <?php if($fields): ?>

            <?php $counter = 0; foreach($fields as $key => $value): ?>


        <?php if($value[3] == "MUL"): ?>

        <div class="form-group">

            <div class="input-group">
                <span class="input-group-addon" id="basic-addon">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    </span>

                <select id="<?php echo $value[0]; ?>" class="form-control" aria-describedby="basic-addon">

                    <option value="nothing"><?php echo strtoupper($value[0]); ?></option>

                </select>

            </div>

        </div>

        <script>


            $(document).ready(function() {

                $("select#<?php echo $value[0]; ?>").select2({ data:[


                    <?php $FK_table = Controller::$connection->query("SELECT referenced_table_name as table_name
                  from information_schema.referential_constraints
                  where table_name = '$table_name'");

                    $FK_table = $FK_table->fetchAll(PDO::FETCH_NUM); ?>

                    <?php $FKData = Controller::$connection->query("SELECT * FROM ".$FK_table[$counter][0]);


                    $FKData = $FKData->fetchAll(PDO::FETCH_NUM); ?>


                <?php foreach($FKData as $key => $value): ?>

                        {
                            id: '<?php echo $value[0]; ?>',
                            text: '<?php if(isset($value[0])) {echo $value[0];} ?><?php if(isset($value[1])) {echo " - ".$value[1];} ?><?php if(isset($value[2])) {echo " - ".$value[2];} ?>'
                        },


                <?php endforeach; ?>

                ],

                    minimumInputLength: 0

                })

            });


        </script>


        <?php $counter++; else: ?>

        <div class="form-group">

            <div class="input-group">
                <span class="input-group-addon" id="basic-addon">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                </span>
                <input id="<?php echo $value[0]; ?>" type="text" class="<?php if($value[1] == "date") { echo "datepicker"; } ?> form-control" placeholder="<?php echo strtoupper($value[0]); ?>" aria-describedby="basic-addon" <?php if($value[5] == "auto_increment") { echo "disabled"; } ?>>
            </div>

        </div>

            <?php endif; ?>


            <?php endforeach; ?>

                <?php else: ?>

                 <div style="font-size: 16px;"><center>Error: tabla especificada no existe en la base de datos.</center></div>

                <?php endif; ?>


            </div>

            <br>

                <div style="text-align: center;">

                    <button id="new" type="button" class="new btn btn-success btn-md">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
                    </button>

                     <button id="create" type="button" class="hacerPago btn btn-primary btn-md btn-md">
                        <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Hacer Pago
                    </button>

                    <!-- <button id="delete" type="button" class="delete btn btn-danger btn-md" disabled>
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Borrar
                    </button> -->

                    <button id="prev" type="button" class="prev btn btn-warning btn-md">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Anterior
                    </button>

                    <button id="next" type="button" class="next btn btn-warning btn-md">
                        Siguiente <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    </button>

                    <button id="print" template="recAbonoCliente" type="button" class="print btn btn-default btn-md" disabled>
                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Recibo
                    </button>

                </div>


        </div>


    </div>

        <?php if($options["related"] == true): ?>

            <div class="col-md-4">

                <div class="well">

                    <div style="text-align: center;">

                    <img class="form_image" src="../assets/img/no_pic.jpg">

                        <br>

                        <button style="margin-top: 10px;" type="button" class="update-pic btn btn-info btn-md" disabled>
                            <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Actualizar
                        </button>

                        <hr>

                        <div><strong>Saldo:</strong> Q <span class="saldo_value">0.00</span></div>

                    </div>

                </div>


            </div>

        <?php endif; ?>

        <?php if($options["detail"] == true): ?>

        <div class="col-md-12">

            <div class="well">


            <table id="<?php echo $table_name; ?>" class="detail_table display" cellspacing="0" width="100%">
                <thead>
                <tr>

                    <th>idpago_cliente</th>
                    <th>Fecha</th>
                    <th>Cód. Cliente</th>
                    <th>Forma de Pago</th>
                    <th>No. Cheque</th>
                    <th>Banco</th>
                    <th>No. Cuenta</th>
                    <th>Total Abono</th>
                    <th>Saldo Anterior</th>

                </tr>
                </thead>

                <tbody>


                <?php foreach($registries as $key => $value): ?>
                <tr>


                    <?php foreach($value as $key => $value): ?>
                        <td><?php echo $value; ?></td>
                    <?php endforeach; ?>



                </tr>
                <?php endforeach; ?>



                </tbody>


            </table>

                </div>

        </div>

        <?php endif; ?>

    </div>


    </div>

</div>

<script>

$("#saldoAnterior").attr({"disabled": "disabled"});

$("#saldoAnterior").parent().hide();

$("#saldoAnterior").attr({"type": "hidden"});


    $("select#idcliente").on("change", function(e) {
        
        $.ajax({
            url: "../classes/Api.php?action=one",
            method: "POST",
            data: { "data": e.target.value, "table": "cliente", "key": "idcliente", "cod": e.target.value },
            dataType: "JSON",
            success: function(r) {

                if(r[0]) {

                    $("#saldoAnterior").val(r[0].saldo);
                    $(".saldo_value").text(r[0].saldo);

                }
                else {

                    $(".saldo_value").text("0.00");
                    $("#saldoAnterior").val("0.00");

                }
            }

        });

    });

    $("#noCheque").parent().css({"display":"none"});
    $("#banco").parent().css({"display":"none"});
    $("#nocuenta").parent().css({"display":"none"});


    $("select#idFormapago").on("change", function(e) {

        switch(this.value) {

            case "1":

                $("#noCheque").parent().css({"display":"none"});
                $("#banco").parent().css({"display":"none"});
                $("#nocuenta").parent().css({"display":"none"});


            break;

            case "2":

                $("#noCheque").parent().css({"display":"table"});
                $("#banco").parent().css({"display":"table"});
                $("#nocuenta").parent().css({"display":"none"});

            break;
            case "3":

                $("#noCheque").parent().css({"display":"none"});
                $("#banco").parent().css({"display":"none"});
                $("#nocuenta").parent().css({"display":"table"});

            break;

        }
        
    });

</script>
