<?php


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


try {

    $productos = Controller::$connection->query("SELECT * FROM producto");

    if($productos) {

        $productos = $productos->fetchAll(PDO::FETCH_NUM);

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

                <select id="<?php echo $value[0]; ?>" class="form-control" aria-describedby="basic-addon">

                    <option value="nothing"><?php echo strtoupper($value[0]); ?></option>

                </select>

            </div>

        </div>

        <script>


            $(document).ready(function() {


              $("button.nextDevolucion").on("click", function()    {


                  var control = this;

                  var fields = $(this).closest(".panel").find("input").first().val();

                  var form = $(control).closest(".panel");


                  var table = $(this).closest(".panel").attr("id");

                  var key = $(this).closest(".panel").find("input").first().attr("id");

                  var cod = $(this).closest(".panel").find("input").first().val();



                  $.ajax({


                      url: "../classes/Api.php?action=nextDevolucion",
                      method: "POST",
                      data: { "data": fields, "table": table, "key": key, "cod": cod },
                      dataType: "JSON",
                      success: function(r) {

                        if(r != "") {

                            switchUDDevolucion(control, true);
                            refreshDetailDevolucion(control);

                        }

                          $(".detalle_devolucion_table").DataTable().clear().draw();

                          $(".detalle_devolucion_table").DataTable().rows.add(r[1]).draw();


                          $.each(r[0][0], function(key, value) {

                              $(form).find("#"+key).val(value);

                              if($(form).find("#"+key).data("select2")) {

                                  $(form).find("#"+key).select2("trigger", "select", {
                                      data: { id: value }
                                  });

                              }

                          });

                      }


                  });



              });


              $("button.prevDevolucion").on("click", function()    {


                  var control = this;

                  var fields = $(this).closest(".panel").find("input").first().val();

                  var form = $(control).closest(".panel");


                  var table = $(this).closest(".panel").attr("id");

                  var key = $(this).closest(".panel").find("input").first().attr("id");

                  var cod = $(this).closest(".panel").find("input").first().val();


                  $.ajax({

                      url: "../classes/Api.php?action=prevDevolucion",
                      method: "POST",
                      data: { "data": fields, "table": table, "key": key, "cod": cod },
                      dataType: "JSON",
                      success: function(r) {

                        if(r != "") {

                            switchUDDevolucion(control, true);
                            refreshDetailDevolucion(control);

                        }

                          $(".detalle_devolucion_table").DataTable().clear().draw();

                          $(".detalle_devolucion_table").DataTable().rows.add(r[1]).draw()


                          $.each(r[0][0], function(key, value) {

                              $(form).find("#"+key).val(value);

                              if($(form).find("#"+key).data("select2")) {

                                  $(form).find("#"+key).select2("trigger", "select", {
                                      data: { id: value }
                                  });

                              }

                          });

                      }


                  });

              });


                $("button.newDevolucion").on("click", function()     {


                    var control = this;

                    var form = $(control).closest(".panel");

                    $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea").val("");

                    $("select#IDCLIENTE").select2("trigger", "select", {
                        data: { id: "nothing" }
                    });

                    $("select#IDVENTA").select2("trigger", "select", {
                        data: { id: "nothing" }
                    });

                     $(".detalle_devolucion_table").DataTable().clear().draw();

                    switchUDDevolucion(control, false);
                    refreshDetailDevolucion(form);

                });


                $("select#<?php echo $value[0]; ?>").select2({ data:[


                <?php $FK_table = Controller::$connection->query("SELECT referenced_table_name as table_name
                  from information_schema.referential_constraints
                  where table_name = '$table_name'");

                    $FK_table = $FK_table->fetchAll(PDO::FETCH_NUM); ?>

                    <?php $FKData = Controller::$connection->query("SELECT * FROM ".$FK_table[$counter][0]);


                    $FKData = $FKData->fetchAll(PDO::FETCH_NUM);

                    $Validador = $FKData;

                    ?>

                    <?php foreach ($FKData as $key => $value): ?>

                        {
                            id: '<?php echo $value[0]; ?>',
                            text: 'ID: <?php if (isset($value[0])) {
                                        echo $value[0];
                                    } ?><?php if (isset($value[1])) {
                                            echo " - " . $value[1];
                                        } ?> <?php if (isset($value[2])) {
                                                echo " - " . $value[2];
                                            } ?> <?php if (isset($value[7])) {
                                                echo " - " . $value[7];
                                            } ?>'
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

                    <button id="new" type="button" class="newDevolucion btn btn-success btn-md">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo
                    </button>

                     <button id="create" type="button" class="hacerDevolucion btn btn-primary btn-md btn-md" disabled>
                        <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Hacer Devolución
                    </button>

                    <!-- <button id="delete" type="button" class="deleteDevolucion btn btn-danger btn-md" disabled>
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Borrar
                    </button> -->

                    <button id="prev" type="button" class="prevDevolucion btn btn-warning btn-md">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Anterior
                    </button>

                    <button id="next" type="button" class="nextDevolucion btn btn-warning btn-md">
                        Siguiente <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    </button>

                    <button disabled id="print" template="devolucion" type="button" class="print btn btn-default btn-md">
                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Recibo
                    </button>

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

    <div class="col-md-7">

            <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> <b>Detalle de la Devolución</b>


            <div class="well">


                <div class="col-md-6">


                <select id="producto" class="form-control" aria-describedby="basic-addon">

                    <option value="0">Selecciona un Producto</option>

                </select>


            </div>

            <div class="col-md-6">


                <div class="form-group">

                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        </span>



                        <script type="text/javascript">


                            function enableAdd(cantidad) {


                                if($("select#producto").val() != 0 && $(cantidad).val() > 0) {

                                    $("#add").removeAttr("disabled");


                                }
                                else {

                                    $("#add").attr("disabled", true);

                                }

                            }


                        </script>


                        <input id="cantidadCtn" min="0" type="number" value="0" class="form-control" placeholder="CANTIDAD" aria-describedby="basic-addon" onchange="enableAdd(this)">


                    </div>

                </div>


            </div>



                <br>

                <br>


                <script>


                $('#total').attr('disabled', 'disabled'); //Disable


                    $(document).ready(function() {

                        $("select#producto").select2({ data:[


                        <?php foreach($productos as $key => $value): ?>

                        {
                            id: '<?php echo $value[0]; ?>',
                            text: '<?php if (isset($value[0])) {
                            echo $value[0];
                            } ?><?php if (isset($value[2])) {
                            echo " - " . $value[2];
                            } ?><?php if (isset($value[4])) {
                                echo " - Precio: Q" . $value[4];
                            } ?><?php if (isset($value[5])) {
                                echo " - Precio Mayorista: Q" . $value[5];
                            } ?>'
                        },

                        <?php endforeach; ?>

                        ],

                            minimumInputLength: 0

                        });



                    })

                </script>

            <center>

                <button id="add" type="button" class="btn btn-success btn-md" disabled>
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Añadir
                </button>

                <button id="remove" type="button" class="btn btn-danger btn-md" disabled>
                    <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Quitar
                </button>

            </center>

            <table id="" class="detalle_devolucion_table display" cellspacing="0" width="100%">
                <thead>
                <tr>

                    <th>ID PRODUCTO</th>
                    <th>CANTIDAD</th>
                    <th>PRECIO</th>
                    <th>SUBTOTAL</th>

                </tr>
                </thead>

                <tbody>


                </tbody>


            </table>

                </div>

        </div>



    <?php if($options["detail"] == true): ?>


        <div class="col-md-5">

            <span class="glyphicon glyphicon-file" aria-hidden="true"></span> <b>Registro de Devoluciones</b>

            <div class="well">


            <table id="<?php echo $table_name; ?>" class="detail_table_devolucion display" cellspacing="0" width="100%">
                <thead>
                <tr>

                    <?php foreach($fields as $key => $value): ?>

                        <th><?php echo $value[0]; ?></th>

                    <?php endforeach; ?>

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


var code = "";

window.addEventListener("keydown", (e) => {

    if (e.keyCode === 13 && code.length > 1) {

        //alert(code);

        scanProductVenta(code);

    } else {

        code += String.fromCharCode(e.keyCode);
    }

    setTimeout(() => {

        code = "";

    }, 250);

});

</script>

<script>

function scanProductVenta(producto_code) {


$(".hacerVenta").attr("disabled", false);


descuento = $("#descuentoCtn").val();

if ($('#precioMayorista').is(":checked")) {

    precioMayorista = 1;

} else {

    precioMayorista = 0;

}


$("#cantidadCtn").val(0);

$("#descuentoCtn").val(0);

$('#precioMayorista').removeAttr("checked");


$.ajax({
    url: "../classes/Api.php?action=askExistencia",
    method: "POST",
    data: {
        "data": {
            "id_producto": code
        },
        "table": "inventario",
        "key": "idproducto",
        "cod": code
    },
    dataType: "JSON",
    success: function(r) {

        if (r) {

            stock = r.existencia;

            $("select#producto").select2("trigger", "select", {

                data: {
                    id: producto_code
                }

            });

            swal({
                title: 'Inventario',
                html: '<strong>' + r.nombre + '</strong><br><strong>Existencia: ' + r.existencia + '</strong><br><strong>Precio: Q' + r.precioSugerido + '</strong><br> Ingresa la "Cantidad a Devolver"',
                input: 'text',
                type: 'info',
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                allowOutsideClick: true
            }).then((result) => {

                if (result.value || result.value == " ") {

                    cantidad = result.value

                    if (parseFloat(cantidad) <= parseFloat(stock)) {

                        $.ajax({

                            url: "../classes/Api.php?action=addItemDevolucion",
                            method: "POST",
                            data: {
                                "data": cantidad,
                                "table": "producto",
                                "key": "idproducto",
                                "cod": producto_code
                            },
                            dataType: "JSON",
                            success: function(r) {

                                if (r != "error_descuento" && r != "error_id_product") {

                                    console.log(r);

                                    total = total + parseFloat(r[0][3]);

                                    $(".inputs_wrapper").find("#total").val(parseFloat(total).toFixed(2));

                                    $(".detalle_devolucion_table").DataTable().rows.add(r).draw();

                                    $("#remove").attr("disabled", true);

                                } else if (r == "error_id_product") {

                                    swal({
                                        title: 'Existencia de Producto',
                                        text: "Error, El producto no existe en el inventario.",
                                        type: 'error',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Aceptar'
                                    });

                                } else {

                                    swal({
                                        title: 'Descuento de Producto',
                                        text: "Error, el descuento que aplicaste supera al precio del producto.",
                                        type: 'error',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Aceptar'
                                    });

                                }

                            }

                        });

                    } else {

                        swal({
                            title: 'Existencia de Producto',
                            text: "Error, no hay stock.",
                            type: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        });

                    }

                }

            });

        } else {

            swal({
                title: 'Existencia de Producto',
                text: "Error, El producto no existe en el inventario.",
                type: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });


        }

    }
});

}


</script>

        <script type="text/javascript">

                var total = 0.00;

                    $("#remove").on('click', function() {


                        r = detalle_devolucion_table.row().data();

                        total = total - parseFloat(r[3]);


                        $(".inputs_wrapper").find("#total").val(parseFloat(total).toFixed(2));


                        detalle_devolucion_table.row().remove().draw();

                        $("#remove").attr("disabled", true);


                    });


                    $('.detalle_devolucion_table tbody').on( 'click', 'tr', function () {

                            $("#remove").attr("disabled", false);

                    });


                    $("#add").on('click', function() {


                      var cod = $("select#IDVENTA").val();

                      id = $("select#producto").val();

                      cant = $("#cantidadCtn").val();


                      $.ajax({

                          url: "../classes/Api.php?action=oneVenta",
                          method: "POST",
                          data: { "data": cod, "table": "venta", "key": "idventa", "cod": cod },
                          dataType: "JSON",
                          success: function(r) {

                            var flag = false;


                                if(r[1]) {


                                   $.each(r[1], function(k, v) {


                                     if(v[0] == id) {

                                       
                                       if(cant <= v[2]) {


                                        flag = 1;

                                         $(".hacerDevolucion").attr("disabled", false);


                                         $("#add").attr("disabled", true);


                                         cant = $("#cantidadCtn").val();


                                         $("#cantidadCtn").val(0);


                                         id = $("select#producto") .val();


                                         $("select#producto").select2("trigger", "select", {

                                             data: { id: 0 }

                                         });

                                           $.ajax({

                                             url: "../classes/Api.php?action=addItemDevolucion",
                                             method: "POST",
                                             data: { "data": cant, "table": "producto", "key": "idproducto", "cod": id},
                                             dataType: "JSON",
                                             success: function(r) {


                                                 total = total + parseFloat(r[0][3]);

                                                 $(".inputs_wrapper").find("#total").val(parseFloat(total).toFixed(2));

                                                 $(".detalle_devolucion_table").DataTable().rows.add(r).draw();

                                                 $("#remove").attr("disabled", true);



                                             }


                                         });


                                       }
                                       else {

                                          flag = 2;
                                          swal({
                                            title: 'Error!',
                                            text: "La cantidad del producto que desea devolver supera la cantidad de la compra.",
                                            type: 'error',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Aceptar'
                                          });


                                       }

                                     }


                                   });

                                   if(flag == false) {

                                     swal({
                                       title: 'Error!',
                                       text: "Éste producto no existe en la venta seleccionada.",
                                       type: 'error',
                                       confirmButtonColor: '#3085d6',
                                       confirmButtonText: 'Aceptar'
                                     });

                                 }


                              }

                          }


                      });


                    });


                    table_details_devolucion = $('.detail_table_devolucion').DataTable({

                        responsive: true,
                        dom: 'Bfrtlip',
                        order: [ 0, "desc" ],
                        buttons: [
                            {
                                extend: 'pdfHtml5',
                                title: 'reporte'
                            },
                            {
                                extend: 'excelHtml5',
                                title: 'reporte'
                            },
                            {
                                extend: 'print',
                                title: 'reporte',
                                customize: function ( win ) {

                                    $(win.document.body).css('background', 'none');

                                }
                            },
                            {
                                extend: 'copyHtml5',
                                title: 'reporte'
                            }
                        ],
                        select: true,
                        pageLength: 10,
                        scrollY:    150,
                        oLanguage:  {
                            "sUrl": "../assets/libs/datatables/Spanish.json"
                        }

                    });


                    $('.detail_table_devolucion tbody').on( 'click', 'tr', function () {

                      $("#create").attr("disabled", true);


                        if ( $(this).hasClass('selected') ) {


                                var control = $(this).closest('.panel').find('.newDevolucion');

                                var form = $(control).closest(".panel");


                                var table = $(this).closest(".detail_table_devolucion").attr("id");

                                var key = $(this).closest(".detail_table_devolucion").find("th").first().text();

                                var cod = table_details_devolucion.row(this).data()[0];

                                switchUDDevolucion(control, true);



                            $.ajax({

                                url: "../classes/Api.php?action=oneDevolucion",
                                method: "POST",
                                data: { "data": cod, "table": table, "key": key, "cod": cod },
                                dataType: "JSON",
                                success: function(r) {


                                    $.each(r[0][0], function(key, value) {


                                        $(form).find("#"+key).val(value);


                                        if($(form).find("#"+key).data("select2")) {

                                            $(form).find("#"+key).select2("trigger", "select", {
                                                data: { id: value }
                                            });

                                        }



                                    });


                                    if(r[1]) {

                                         $(".detalle_devolucion_table").DataTable().clear().draw();


                                        $(".detalle_devolucion_table").DataTable().rows.add(r[1]).draw();


                                    }

                                }


                            });

                        }


                    } );


                </script>
