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


    <div class="col-md-<?php if($options["photo"] == true) { echo "8"; } else { echo "12"; } ?>">

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

                <select id="<?php echo $value[0]; ?>" class="form-control" aria-describedby="basic-addon" disabled>

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
                <input disabled id="<?php echo $value[0]; ?>" type="text" class="<?php if($value[1] == "date") { echo "datepicker"; } ?> form-control" placeholder="<?php echo strtoupper($value[0]); ?>" aria-describedby="basic-addon" <?php if($value[5] == "auto_increment") { echo "disabled"; } ?>>
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

                     <!-- <button id="create" type="button" class="create btn btn-primary btn-md btn-md">
                        <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Guardar
                    </button> -->

                    <!--
                    <button id="delete" type="button" class="delete btn btn-danger btn-md" disabled>
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Borrar
                    </button> -->

                    <!-- <button id="update" type="button" class="update btn btn-info btn-md" disabled>
                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Actualizar
                    </button> -->

                    <button id="prev" type="button" class="prev btn btn-warning btn-md">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Anterior
                    </button>

                    <button id="next" type="button" class="next btn btn-warning btn-md">
                        Siguiente <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    </button>

                    <button id="retiro" type="button" class="retiro btn btn-success btn-md" onclick="retirarCaja(this)">
                        Retiro <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                    </button>

                    <button id="cierre" type="button" class="cierre btn btn-danger btn-md" onclick="cierreCaja(this)">
                        Cierre <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                    </button>

                    <button id="aperturar" type="button" class="aperturar btn btn-primary btn-md" onclick="aperturarCaja(this)">
                        Aperturar <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                    </button>

                    <br>
                    <br>

                    <div class="well">

                         <div class="text-center">
                            <div id="statusValue"><span id="statusVar"></span></div>
                         </div>
                         <br>
                         <div class="saldoValue">Saldo: <span id="saldoActualCaja">---,---.--</span></div>

                    </div>

                </div>

        </div>

    </div>

        <?php if($options["photo"] == true): ?>

            <div class="col-md-4">

                <div class="well">

                    <div style="text-align: center;">

                    <img class="form_image" src="../assets/img/no_pic.jpg">

                        <br>

                        <button style="margin-top: 10px;" type="button" class="update-pic btn btn-info btn-md" disabled>
                            <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Actualizar
                        </button>

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

                    <th>ID</th>
                    <th>FECHA</th>
                    <th>INGRESO</th>
                    <th>EGRESO</th>
                    <th>SALDO</th>
                    <th>MOTIVO</th>

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

<script type="text/javascript">

function getSaldoCaja() {

    var formatter = new Intl.NumberFormat('es-GT', {
        style: 'currency',
        currency: 'GTQ',
    });

    
    $.ajax({
        url: "../classes/Api.php?action=getSaldoCaja",
        method: "POST",
        data: { "data": {"ESTADO": "COMPROBAR"} },
        dataType: "JSON",
        success: function(r) {

            $("#saldoActualCaja").html(formatter.format(r.saldo));

        }
    });

    $.ajax({
        url: "../classes/Api.php?action=getStatusCaja",
        method: "POST",
        data: { "data": {"ESTADO": "EVALUAR"} },
        dataType: "JSON",
        success: function(r) {
        
            $("#statusVar").html(r[0].ESTADO);
            $("#statusValue").attr("class", r[0].ESTADO);

        }
    });

}


setInterval(() => {

    getSaldoCaja();

}, 2000);


function aperturarCaja(control) {

var form = $(control).closest(".panel");

$.ajax({
    url: "../classes/Api.php?action=getStatusCaja",
    method: "POST",
    data: { "data": {"ESTADO": "EVALUAR"} },
    dataType: "JSON",
    success: function(r) {

    if(r[0].ESTADO == "CERRADA") {

        swal({
            title: 'Aperturar Caja',
            text: '¿Quieres aperturar la caja?',
            type: 'info',
            showCancelButton: true,
            confirmButtonText: 'Aperturar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            allowOutsideClick: true
        })
        .then((result) => {

        if(result.value) {

            swal({
                title: '¿Monto extra para agregar a la caja?',
                input: 'text',
                type: 'info',
                showCancelButton: false,
                confirmButtonText: 'Continuar'
            })
            .then((result) => { 

                var monto = result.value ? result.value : 0;

                $.ajax({
                    url: "../classes/Api.php?action=setStatusCaja",
                    method: "POST",
                    data: { "data": {"ESTADO": "ABIERTA"} },
                    dataType: "JSON",
                    success: function(r) {

                        $.ajax({
                            url: "../classes/Api.php?action=depositoCaja",
                            method: "POST",
                            data: { "data": {"monto": monto, "motivo": "APERTURA"}, "table": "caja", "key": "ID", "cod": result.value},
                            dataType: "JSON",
                            success: function(r) {

                                if(r[0] == "Inserted") {

                                    swal({
                                        title: 'Aperturar Caja',
                                        text: 'La caja se ha abierto',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Aceptar',
                                        allowOutsideClick: true
                                    });

                                    refreshDetail(form);
                                    getSaldoCaja();

                                }

                            }
                            
                        });
                                
                    }

                    });

                    });

                    }
                    
                });

            }

    
    else {

        swal({
            title: 'Aviso',
            text: 'La caja ya está abierta',
            type: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Aceptar',
            allowOutsideClick: true
        });

    }

}

});

}

function cierreCaja(control) {

    var form = $(control).closest(".panel");

    $.ajax({
            url: "../classes/Api.php?action=getStatusCaja",
            method: "POST",
            data: { "data": {"ESTADO": "EVALUAR"} },
            dataType: "JSON",
            success: function(r) {

            if(r[0].ESTADO == "ABIERTA") {

                swal({
                    title: '¿Cúanto es el monto a <br>retirar de Caja?',
                    input: 'text',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Corte',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: true
                }).then((result) => {

                   if (result.value || result.value == " ") {

                    var monto = result.value ? result.value : 0;

                        $.ajax({

                        url: "../classes/Api.php?action=retiroCaja",
                        method: "POST",
                        data: { "data": {"monto": monto, "motivo": "CIERRE"}, "table": "caja", "key": "ID", "cod": 0},
                        dataType: "JSON",
                        success: function(r) {

                            if(r[0] == "Inserted") {

                                $.ajax({
                                    url: "../classes/Api.php?action=setStatusCaja",
                                    method: "POST",
                                    data: { "data": {"ESTADO": "CERRADA", "RETIRO": monto} },
                                    dataType: "JSON",
                                    success: function(r) {

                                        swal({
                                            title: 'Cierre de Caja',
                                            text: "Cierre del día realizado con éxito.",
                                            type: 'success',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Aceptar'
                                        });


                                        refreshDetail(form);
                                        getSaldoCaja();

                                        $.ajax({
                                            url: "../classes/Api.php?action=sendMailCierre",
                                            method: "POST",
                                            data: { "data": "OK" },
                                            dataType: "JSON",
                                            success: function(r) {

                                            }
                                        });

                                    }
                                });

                            }
                            if(r[0] == "Error_monto") {

                            swal({
                                title: 'Retiro de Caja',
                                text: "Retiro excede el saldo de la caja o hay un error en la cantdad.",
                                type: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            });

                            refreshDetail(form);
                            getSaldoCaja();

                            }
                            if(r[0] == "CAJA_CERRADA") {

                                swal({
                                title: 'Caja Cerrada',
                                text: "No se pudo realizar el retiro, la caja esta cerrada.",
                                type: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                                });

                                refreshDetail(form);
                                getSaldoCaja();

                            }

                        },
                        error: function(r) {

                        swal({
                        title: 'Error!',
                        text: "No se pudo realizar el retiro, intente de nuevo.",
                        type: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                        });


                        }


                        });

                       } 

                });

            }
            else {

                swal({
                    title: 'Aviso',
                    text: 'La caja ya está cerrada',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'Aceptar',
                    allowOutsideClick: true
                });

            }
        }
    
    });

}


function retirarCaja(control) {

  var form = $(control).closest(".panel");

  swal({
        title: '¿Cúal es el mótivo de este retiro?',
        input: 'text',
        type: 'info',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        allowOutsideClick: true
      }).then((result) => {

        if (result.value || result.value == " ") {

  var motivo = result.value

  swal({
        title: '¿Cúanto es el monto a <br>retirar de Caja?',
        input: 'text',
        type: 'info',
        showCancelButton: true,
        confirmButtonText: 'Retirar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        allowOutsideClick: true
      }).then((result) => {

        if (result.value) {


      $.ajax({

          url: "../classes/Api.php?action=retiroCaja",
          method: "POST",
          data: { "data": {"monto":result.value, "motivo": motivo}, "table": "caja", "key": "ID", "cod": result.value},
          dataType: "JSON",
          success: function(r) {

              if(r[0] == "Inserted") {

                swal({
                  title: 'Retiro de Caja',
                  text: "Retiro realizado con éxito.",
                  type: 'success',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
                });

                refreshDetail(form);
                getSaldoCaja();


              }
              if(r[0] == "Error_monto") {

                swal({
                  title: 'Retiro de Caja',
                  text: "Retiro excede el saldo de la caja o hay un error en la cantdad.",
                  type: 'error',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
                });

                refreshDetail(form);
                getSaldoCaja();

              }
                if(r[0] == "CAJA_CERRADA") {

                    swal({
                    title: 'Caja Cerrada',
                    text: "No se pudo realizar el retiro, la caja esta cerrada.",
                    type: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                    });

                    refreshDetail(form);
                    getSaldoCaja();

                }

          },
        error: function(r) {

          swal({
            title: 'Error!',
            text: "No se pudo realizar el retiro, intente de nuevo.",
            type: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
          });


        }


      });

        }
        else {



        }

      });


}
else {

  swal({
    title: 'Error!',
    text: "Debe especificar el mótivo del retiro.",
    type: 'error',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'Aceptar'
  });


}


});


}

</script>
