<?php


try {

    $fields = Controller::$connection->query("DESC $table_name");

    if ($fields) {

        $fields = $fields->fetchAll(PDO::FETCH_NUM);
    }

} catch (mysqli_sql_exception $e) {

    echo $e->getMessage();
}


    try {

        $registries = Controller::$connection->query("SELECT * FROM $table_name");

        if ($registries) {

            $registries = $registries->fetchAll(PDO::FETCH_NUM);
        }

    } catch (mysqli_sql_exception $e) {

        echo $e->getMessage();
    }

    // Productos
    try {

        $productos = Controller::$connection->query("SELECT * FROM producto");

        if ($productos) {

            $productos = $productos->fetchAll(PDO::FETCH_NUM);
        }
    } catch (mysqli_sql_exception $e) {

        echo $e->getMessage();
    }

    // Categorias
    try {

        $categorias = Controller::$connection->query("SELECT * FROM categoria");

        if ($categorias) {

            $categorias = $categorias->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (mysqli_sql_exception $e) {

        echo $e->getMessage();
    }

    // Marcas
    try {

        $marcas = Controller::$connection->query("SELECT * FROM marca");

        if ($marcas) {

            $marcas = $marcas->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (mysqli_sql_exception $e) {

        echo $e->getMessage();
    }

   
    $categoriasObj = new stdClass();

    foreach ($categorias as $key => $value) {
        
        $categoriasObj->{$value["idCategoria"]} = $value["descripcion"];
    
    }


    $marcasObj = new stdClass();

    foreach ($marcas as $key => $value) {
        
        $marcasObj->{$value["IDMARCA"]} = $value["NOMBRE"];
    
    }


    /* End Form Construct Data */

?>

<div id="<?php echo $table_name; ?>" class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>

            <a data-toggle="collapse" data-target="#<?php echo $table_name . "-panel"; ?>">
                <strong><?php echo $table_title; ?></strong>
            </a>

        </h3>

    </div>

    <div id="<?php echo $table_name . "-panel"; ?>" class="panel-collapse collapse in">

        <div class="panel-body">


            <div class="col-md-<?php if ($options["photo"] == true) {
                                    echo "8";
                                } else {
                                    echo "12";
                                } ?>">

                <div class="well">


                    <div class="inputs_wrapper" style="max-height: inherit;">


                        <?php if ($fields) : ?>

                        <?php $counter = 0;
                        foreach ($fields as $key => $value) : ?>

                        <?php if ($value[3] == "MUL") : ?>

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


                                $("button.nextCompra").on("click", function() {


                                    var control = this;

                                    var fields = $(this).closest(".panel").find("input").first().val();

                                    var form = $(control).closest(".panel");


                                    var table = $(this).closest(".panel").attr("id");

                                    var key = $(this).closest(".panel").find("input").first().attr("id");

                                    var cod = $(this).closest(".panel").find("input").first().val();



                                    $.ajax({


                                        url: "../classes/Api.php?action=nextCompra",
                                        method: "POST",
                                        data: {
                                            "data": fields,
                                            "table": table,
                                            "key": key,
                                            "cod": cod
                                        },
                                        dataType: "JSON",
                                        success: function(r) {

                                            if (r != "") {

                                                switchUDDevolucion(control, true);
                                                refreshDetailDevolucion(control);

                                            }

                                            $(".detalle_compra_table").DataTable().clear().draw();

                                            $(".detalle_compra_table").DataTable().rows.add(r[1]).draw();


                                            $.each(r[0][0], function(key, value) {


                                                $(form).find("#" + key).val(value);

                                                if ($(form).find("#" + key).data("select2")) {

                                                    $(form).find("#" + key).select2("trigger", "select", {
                                                        data: {
                                                            id: value
                                                        }
                                                    });

                                                }


                                            });



                                        }


                                    });



                                });


                                $("button.prevCompra").on("click", function() {


                                    var control = this;

                                    var fields = $(this).closest(".panel").find("input").first().val();

                                    var form = $(control).closest(".panel");


                                    var table = $(this).closest(".panel").attr("id");

                                    var key = $(this).closest(".panel").find("input").first().attr("id");

                                    var cod = $(this).closest(".panel").find("input").first().val();


                                    $.ajax({

                                        url: "../classes/Api.php?action=prevCompra",
                                        method: "POST",
                                        data: {
                                            "data": fields,
                                            "table": table,
                                            "key": key,
                                            "cod": cod
                                        },
                                        dataType: "JSON",
                                        success: function(r) {

                                            if (r != "") {

                                                switchUDDevolucion(control, true);
                                                refreshDetailDevolucion(control);

                                            }

                                            $(".detalle_compra_table").DataTable().clear().draw();

                                            $(".detalle_compra_table").DataTable().rows.add(r[1]).draw()


                                            $.each(r[0][0], function(key, value) {

                                                $(form).find("#" + key).val(value);

                                                if ($(form).find("#" + key).data("select2")) {

                                                    $(form).find("#" + key).select2("trigger", "select", {
                                                        data: {
                                                            id: value
                                                        }
                                                    });

                                                }

                                            });

                                        }

                                    });

                                });


                                $("button.newCompra").on("click", function() {


                                    var control = this;

                                    var form = $(control).closest(".panel");

                                    $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea").val("");

                                    
                                    $(".detalle_compra_table").DataTable().clear().draw();


                                    switchUDDevolucion(control, false);
                                    refreshDetailDevolucion(form);


                                    $("select").each(function(key, html) {

                                        $(html).select2("trigger", "select", {
                                            data: {
                                                id: "nothing"
                                            }
                                        });

                                    });


                                });


                                $("select#<?php echo $value[0]; ?>").select2({
                                    data: [


                                        <?php $FK_table = Controller::$connection->query("SELECT referenced_table_name as table_name
                                                                                                from information_schema.referential_constraints
                                                                                                where table_name = '$table_name'");

                                        $FK_table = $FK_table->fetchAll(PDO::FETCH_NUM); ?>

                                        <?php $FKData = Controller::$connection->query("SELECT * FROM " . $FK_table[$counter][0]);


                                        $FKData = $FKData->fetchAll(PDO::FETCH_NUM);

                                        $Validador = $FKData;

                                        ?>


                                        <?php foreach ($FKData as $key => $value) : ?>

                                        {
                                            id: '<?php echo $value[0]; ?>',
                                            text: '<?php if (isset($value[0])) {
                                                        echo $value[0];
                                                    } ?><?php if (isset($value[1])) {
                                            echo " - " . $value[1];
                                        } ?><?php if (isset($value[3])) {
                                                echo " - " . $value[4];
                                            } ?>'
                                        },


                                        <?php endforeach; ?>

                                    ],


                                    minimumInputLength: 0


                                })

                            });
                        </script>


                        <?php $counter++;
                    else : ?>

                        <div class="form-group">

                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                </span>
                                <input id="<?php echo $value[0]; ?>" type="text" class="<?php if ($value[1] == "date") {
                                                                                            echo "datepicker";
                                                                                        } ?> form-control" placeholder="<?php echo strtoupper($value[0]); ?>" aria-describedby="basic-addon" <?php if ($value[5] == "auto_increment") {
                                                                                                                                                                                    echo "disabled";
                                                                                                                                                                                } ?>>
                            </div>

                        </div>


                        <?php endif; ?>


                        <?php endforeach; ?>

                        <?php else : ?>

                        <div style="font-size: 16px;">
                            <center>Error: tabla especificada no existe en la base de datos.</center>
                        </div>

                        <?php endif; ?>


                    </div>

                    <br>

                    <div style="text-align: center;">

                        <button id="newProducto" onclick="scanProductoCompra();" type="button" class="newProducto btn btn-info btn-md">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Producto sin Código
                        </button>

                        <button id="new" type="button" class="newCompra btn btn-success btn-md">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nueva Compra
                        </button>

                        <button id="create" type="button" class="hacerCompra btn btn-primary btn-md btn-md" disabled>
                            <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Hacer Compra
                        </button>

                        <!-- <button id="delete" type="button" class="deleteDevolucion btn btn-danger btn-md" disabled>
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Borrar
                        </button> -->

                        <button id="prev" type="button" class="prevCompra btn btn-warning btn-md">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Anterior
                        </button>

                        <button id="next" type="button" class="nextCompra btn btn-warning btn-md">
                            Siguiente <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        </button>

                        <button disabled id="print" template="compra" type="button" class="print btn btn-default btn-md">
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Recibo
                        </button>

                    </div>


                </div>


            </div>

            <?php if ($options["photo"] == true) : ?>

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

                <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> <b>Detalle de la Compra</b>


                <div class="well">


                    <div class="col-md-6">


                        <select id="producto" class="form-control" aria-describedby="basic-addon">

                            <option value="nothing">Selecciona un Producto</option>

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


                                        if ($("select#producto").val() != 0 && $(cantidad).val() > 0) {

                                            $("#add").removeAttr("disabled");


                                        } else {

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

                            $("select#producto").select2({
                                data: [


                                    <?php foreach ($productos as $key => $value) : ?>

                                    {
                                        id: '<?php echo $value[0]; ?>',
                                        text: '<?php if (isset($value[0])) {
                                                    echo $value[0];
                                                } ?><?php if (isset($value[2])) {
                                                    echo " - " . $value[2];
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

                    <table id="" class="detalle_compra_table display" cellspacing="0" width="100%">
                        <thead>
                            <tr>

                                <th>ID PRODUCTO</th>
                                <th>NOMBRE</th>
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



            <?php if ($options["detail"] == true) : ?>


            <div class="col-md-5">

                <span class="glyphicon glyphicon-file" aria-hidden="true"></span> <b>Registro de Compras</b>

                <div class="well">


                    <table id="<?php echo $table_name; ?>" class="detail_table_compra display" cellspacing="0" width="100%">
                        <thead>
                            <tr>

                                <?php foreach ($fields as $key => $value) : ?>

                                <th><?php echo $value[0]; ?></th>

                                <?php endforeach; ?>

                            </tr>
                        </thead>

                        <tbody>


                            <?php foreach ($registries as $key => $value) : ?>
                            <tr>


                                <?php foreach ($value as $key => $value) : ?>
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
    var total = 0.00;


    $("#remove").on('click', function() {


        r = detalle_compra_table.row().data();

        total = total - parseFloat(r[4]);


        $(".inputs_wrapper").find("#total").val(parseFloat(total).toFixed(2));

        detalle_compra_table.row().remove().draw();

        $("#remove").attr("disabled", true);

    });


    $('.detalle_compra_table tbody').on('click', 'tr', function() {

        $("#remove").attr("disabled", false);

    });



    $("#add").on('click', function() {


        $(".hacerCompra").attr("disabled", false);


        $("#add").attr("disabled", true);


        cant = $("#cantidadCtn").val();


        $("#cantidadCtn").val(0);


        id = $("select#producto").val();


        $("select#producto").select2("trigger", "select", {

            data: {
                id: "nothing"
            }

        });

        $.ajax({

            url: "../classes/Api.php?action=addItemCompra",
            method: "POST",
            data: {
                "data": cant,
                "table": "producto",
                "key": "idproducto",
                "cod": id
            },
            dataType: "JSON",
            success: function(r) {

                total = total + parseFloat(r[0][4]);

                $(".inputs_wrapper").find("#total").val(parseFloat(total).toFixed(2));

                $(".detalle_compra_table").DataTable().rows.add(r).draw();

                $("#remove").attr("disabled", true);

            }

        });

    });


    table_details_compra = $('.detail_table_compra').DataTable({

        responsive: true,
        dom: 'Bfrtlip',
        order: [0, "desc"],
        buttons: [{
                extend: 'pdfHtml5',
                title: ''
            },
            {
                extend: 'excelHtml5',
                title: ''
            },
            {
                extend: 'print',
                title: '',
                customize: function(win) {

                    $(win.document.body).css('background', 'none');

                }
            },
            {
                extend: 'copyHtml5',
                title: ''
            }
        ],
        select: true,
        pageLength: 10,
        scrollY: 150,
        oLanguage: {
            "sUrl": "../assets/libs/datatables/Spanish.json"
        }

    });


    $('.detail_table_compra tbody').on('click', 'tr', function() {

   
        $("#create").attr("disabled", true);


        if ($(this).hasClass('selected')) {


            var control = $(this).closest('.panel').find('.newCompra');

            var form = $(control).closest(".panel");


            var table = $(this).closest(".detail_table_compra").attr("id");

            var key = $(this).closest(".detail_table_compra").find("th").first().text();

            var cod = table_details_compra.row(this).data()[0];

            switchUDDevolucion(control, true);

            $.ajax({

                url: "../classes/Api.php?action=oneCompra",
                method: "POST",
                data: {
                    "data": cod,
                    "table": table,
                    "key": key,
                    "cod": cod
                },
                dataType: "JSON",
                success: function(r) {
                

                    $.each(r[0][0], function(key, value) {

                        $(form).find("#" + key).val(value);


                        if ($(form).find("#" + key).data("select2")) {

                            $(form).find("#" + key).select2("trigger", "select", {
                                data: {
                                    id: value ? value : "nothing"
                                }
                            });

                        }

                    });

                    console.log(r);


                    if (r[1]) {
                  

                        $(".detalle_compra_table").DataTable().clear().draw();


                        $(".detalle_compra_table").DataTable().rows.add(r[1]).draw();


                    }

                }


            });

        }

    });


    function addItemScanCompra(code, nombre, cantidad, precio) {

        $(".hacerCompra").attr("disabled", false);

        $("#add").attr("disabled", true);

        $("#cantidadCtn").val(0);


        id = code;

        cant = cantidad;

        $("select#producto").select2("trigger", "select", {

            data: {
                id: "nothing"
            }

        });

        $.ajax({

            url: "../classes/Api.php?action=addItemCompra",
            method: "POST",
            data: {
                "data": cant,
                "table": "producto",
                "key": "idproducto",
                "cod": id
            },
            dataType: "JSON",
            success: function(r) {

                total = total + parseFloat(r[0][4]);


                $(".inputs_wrapper").find("#total").val(parseFloat(total).toFixed(2));

                $(".detalle_compra_table").DataTable().rows.add(r).draw();

                $("#remove").attr("disabled", true);

            }

        });

    }

    function scanProductoCompra(code) {

        if(!code) {
           code = prompt("Busque en el sistema el codigo de producto que corresponde e ingreselo, de lo contrario dejelo en blanco para generar un codigo nuevo.");
           if(!code) {
            code = Math.round(Math.random()*100000000);
           }
        }

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

                    costo = r.preciocosto;

                    //responsiveVoice.speak("Ingresa la cantidad comprada de " + r.nombre, idioma);

                    swal({
                        title: 'Inventario',
                        html: '<strong>' + r.nombre + '</strong><br> Ingresa la "Cantidad Comprada"',
                        input: 'text',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Siguiente',
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: true,
                        allowOutsideClick: true
                    }).then((result) => {

                        if (result.value || result.value == " ") {

                            cantidad = result.value;

                            //responsiveVoice.speak("Ahora, Ingresa el precio costo, por unidad", idioma);

                            swal({
                                title: 'Inventario',
                                html: '<strong>' + r.nombre + '</strong><br> Ingresa el "Precio costo, por unidad"',
                                input: 'text',
                                type: 'info',
                                showCancelButton: true,
                                confirmButtonText: 'Siguiente',
                                cancelButtonText: 'Cancelar',
                                showLoaderOnConfirm: true,
                                allowOutsideClick: true
                            }).then((result) => {

                                if (result.value || result.value == " ") {

                                    nCosto = result.value;

                                    //responsiveVoice.speak("Ahora, Ingresa el de Venta, por unidad", idioma);

                                    swal({
                                        title: 'Inventario',
                                        html: '<strong>' + r.nombre + '</strong><br> Ingresa el "Precio de Venta, por unidad"',
                                        input: 'text',
                                        type: 'info',
                                        showCancelButton: true,
                                        confirmButtonText: 'Siguiente',
                                        cancelButtonText: 'Cancelar',
                                        showLoaderOnConfirm: true,
                                        allowOutsideClick: true
                                    }).then((result) => {

                                    if (result.value || result.value == " ") {

                                        precioVenta = result.value;

                                    if (nCosto != costo) {

                                        $.ajax({
                                            url: "../classes/Api.php?action=updateProducto",
                                            method: "POST",
                                            data: {
                                                "data": {
                                                    "idproducto": code,
                                                    "preciocosto": nCosto,
                                                    "precioSugerido": precioVenta,
                                                    "precioTop": nCosto,
                                                },
                                                "table": "producto",
                                                "cod": "",
                                                "key": ""
                                            },
                                            dataType: "JSON",
                                            success: function(r) {

                                                //responsiveVoice.speak("Producto Actualizado!", idioma);

                                                swal({
                                                    title: 'Inventario',
                                                    text: 'Producto Actualizado!',
                                                    type: 'success',
                                                    showCancelButton: false,
                                                    confirmButtonText: 'Aceptar',
                                                    showLoaderOnConfirm: true,
                                                    allowOutsideClick: true
                                                })

                                                addItemScanCompra(code, r.nombre, cantidad, nCosto);

                                            }
                                        })

                                    } else {

                                        //responsiveVoice.speak("Producto Agregado!", idioma);

                                        swal({
                                            title: 'Inventario',
                                            text: 'Producto Agregado!',
                                            type: 'success',
                                            showCancelButton: false,
                                            confirmButtonText: 'Aceptar',
                                            showLoaderOnConfirm: true,
                                            allowOutsideClick: true
                                        })

                                        addItemScanCompra(code, r.nombre, cantidad, costo);

                                    }


                                }

                            })

                            }

                            });

                        }

                    });

                } else {

                    //responsiveVoice.speak("No he encontrado este producto en mi base de datos, ¿Quieres agregarlo al sistema?'.", idioma);

                    swal({
                        title: 'Compras',
                        text: 'El producto con código: ' + code + ', no existe en el inventario. ¿Quieres agregarlo al sistema?',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Agregar',
                        cancelButtonText: 'Cancelar',
                        showLoaderOnConfirm: true,
                        allowOutsideClick: true
                    }).then((result) => {

                        if (result.value || result.value == " ") {

                           // responsiveVoice.speak("Muy bien, ingresa el nombre del producto.", idioma);

                            swal({
                                title: 'Inventario',
                                text: 'Ingresa el "Nombre del producto"',
                                input: 'text',
                                type: 'info',
                                showCancelButton: true,
                                confirmButtonText: 'Siguiente',
                                cancelButtonText: 'Cancelar',
                                showLoaderOnConfirm: true,
                                allowOutsideClick: true
                            }).then((result) => {

                                if (result.value || result.value == " ") {

                                    nombre = result.value;

                                    //responsiveVoice.speak("Ahora, Ingresa la cantidad comprada de este producto.", idioma);

                                    swal({
                                        title: 'Inventario',
                                        text: 'Ingresa la "Cantidad Comprada"',
                                        input: 'text',
                                        type: 'info',
                                        showCancelButton: true,
                                        confirmButtonText: 'Siguiente',
                                        cancelButtonText: 'Cancelar',
                                        showLoaderOnConfirm: true,
                                        allowOutsideClick: true
                                    }).then((result) => {

                                        if (result.value || result.value == " ") {

                                            cantidad = result.value;

                                            //responsiveVoice.speak("Ahora, Ingresa el precio costo, por unidad", idioma);

                                            swal({
                                                title: 'Inventario',
                                                text: 'Ingresa el "Precio costo, por unidad"',
                                                input: 'text',
                                                type: 'info',
                                                showCancelButton: true,
                                                confirmButtonText: 'Siguiente',
                                                cancelButtonText: 'Cancelar',
                                                showLoaderOnConfirm: true,
                                                allowOutsideClick: true
                                            }).then((result) => {

                                                if (result.value || result.value == " ") {

                                                    precio = result.value;

                                                    //responsiveVoice.speak("Ahora, Ingresa el precio de venta, por unidad", idioma);

                                                swal({
                                                    title: 'Inventario',
                                                    text: 'Ingresa el "Precio de Venta"',
                                                    input: 'text',
                                                    type: 'info',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Agregar',
                                                    cancelButtonText: 'Cancelar',
                                                    showLoaderOnConfirm: true,
                                                    allowOutsideClick: true
                                                }).then((result) => {

                                                    if (result.value || result.value == " ") {

                                                        precioVenta = result.value;

                                                       // responsiveVoice.speak("¿Cúal es la categoría de este producto?", idioma);

                                                        swal({
                                                            title: 'Inventario',
                                                            text: '¿Cúal es la categoría de este producto?"',
                                                            input: 'select',
                                                            inputOptions: <?php echo json_encode($categoriasObj); ?>,
                                                            type: 'info',
                                                            showCancelButton: true,
                                                            confirmButtonText: 'Agregar',
                                                            cancelButtonText: 'Cancelar',
                                                            showLoaderOnConfirm: true,
                                                            allowOutsideClick: true
                                                        }).then((result) => {

                                                        if (result.value || result.value == " ") {

                                                            categoriaID = result.value;

                                                            //responsiveVoice.speak("¿Cúal es la marca de este producto?", idioma);

                                                            swal({
                                                                title: 'Inventario',
                                                                text: '¿Cúal es la marca de este producto?',
                                                                input: 'select',
                                                                input: 'select',
                                                                inputOptions: <?php echo json_encode($marcasObj); ?>,
                                                                type: 'info',
                                                                showCancelButton: true,
                                                                confirmButtonText: 'Agregar',
                                                                cancelButtonText: 'Cancelar',
                                                                showLoaderOnConfirm: true,
                                                                allowOutsideClick: true
                                                            }).then((result) => {

                                                                if (result.value || result.value == " ") {

                                                                    marcaProducto = result.value;

                                                                    //responsiveVoice.speak("¿Cúal es la serie de este producto?", idioma);

                                                                    swal({
                                                                        title: 'Inventario',
                                                                        text: '¿Cúal es la serie de este producto?',
                                                                        input: 'text',
                                                                        type: 'info',
                                                                        showCancelButton: true,
                                                                        confirmButtonText: 'Agregar',
                                                                        cancelButtonText: 'Cancelar',
                                                                        showLoaderOnConfirm: true,
                                                                        allowOutsideClick: true
                                                                    }).then((result) => {

                                                                        if (result.value || result.value == "") {

                                                                            serieProducto = result.value;

                                                                            //responsiveVoice.speak("¿Cúal es el modelo de este producto?", idioma);

                                                                            swal({
                                                                                title: 'Inventario',
                                                                                text: '¿Cúal es el modelo de este producto?',
                                                                                input: 'text',
                                                                                type: 'info',
                                                                                showCancelButton: true,
                                                                                confirmButtonText: 'Agregar',
                                                                                cancelButtonText: 'Cancelar',
                                                                                showLoaderOnConfirm: true,
                                                                                allowOutsideClick: true
                                                                            }).then((result) => {

                                                                                if (result.value || result.value == "") {

                                                                                    modeloProducto = result.value;

                                                                                    $.ajax({
                                                                                        url: "../classes/Api.php?action=addProducto",
                                                                                        method: "POST",
                                                                                        data: {
                                                                                            "data": {
                                                                                                "idproducto": code,
                                                                                                "idCategoria": categoriaID,
                                                                                                "nombre": nombre,
                                                                                                "preciocosto": precio,
                                                                                                "precioSugerido": precioVenta,
                                                                                                "precioTop": precio,
                                                                                                "marca": marcaProducto,
                                                                                                "serie": serieProducto,
                                                                                                "modelo": modeloProducto,
                                                                                            },
                                                                                            "table": "producto",
                                                                                            "cod": "",
                                                                                            "key": ""
                                                                                        },
                                                                                        dataType: "JSON",
                                                                                        success: function(r) {

                                                                                            //responsiveVoice.speak("Producto Agregado!", idioma);

                                                                                            swal({
                                                                                                title: 'Inventario',
                                                                                                text: 'Producto Agregado!',
                                                                                                type: 'success',
                                                                                                showCancelButton: false,
                                                                                                confirmButtonText: 'Aceptar',
                                                                                                showLoaderOnConfirm: true,
                                                                                                allowOutsideClick: true
                                                                                            })

                                                                                            addItemScanCompra(code, r.nombre, cantidad, precio);

                                                                                        }

                                                                                    });

                                                                                }

                                                                            });

                                                                        }

                                                                    });


                                                                }

                                                            });

                                                        }

                                                    });

                                                        
                                                    }
                                                    
                                                    });


                                                }

                                            });


                                        }

                                    });

                                }

                            });

                        }

                    });

                }

            }
        });

    }
</script>
<script>
    var code = "";

    window.addEventListener("keydown", (e) => {

        if (e.keyCode === 13 && code.length > 1) {

            code = code.replace(/[\x00-\x1F\x7F-\x9F]/g, "");
            scanProductoCompra(code);

        } else {

            code += String.fromCharCode(e.keyCode);
        }

        setTimeout(() => {

            code = "";

        }, 250);

    });


    $("select#idFormapago").parent().css({
        "display": "none"
    });


    $("select#IDTIPOCOMPRA").on("change", function(e) {


        switch (this.value) {

            case "2":

                $("select#idFormapago").parent().css({
                    "display": "table"
                });
                $("#noCheque").parent().css({
                    "display": "none"
                });
                $("#banco").parent().css({
                    "display": "none"
                });

                break;
            case "1":

                $("select#idFormapago").parent().css({
                    "display": "none"
                });
                $("#noCheque").parent().css({
                    "display": "none"
                });
                $("#banco").parent().css({
                    "display": "none"
                });

                break;

        }

    });


    $("#noCheque").parent().css({
        "display": "none"
    });
    $("#banco").parent().css({
        "display": "none"
    });

    $("#nocuenta").parent().css({
        "display": "none"
    });

    $("select#idFormapago").on("change", function(e) {

        switch (this.value) {

            case "1":

                $("#noCheque").parent().css({
                    "display": "none"
                });
                $("#banco").parent().css({
                    "display": "none"
                });
                $("#nocuenta").parent().css({
                    "display": "none"
                });

                break;

             case "2":

                $("#noCheque").parent().css({
                    "display": "table"
                });
                $("#banco").parent().css({
                    "display": "table"
                });
                $("#nocuenta").parent().css({
                    "display": "table"
                });

                break;
                case "3":

                $("#noCheque").parent().css({
                    "display": "none"
                });
                $("#banco").parent().css({
                    "display": "none"
                });
                $("#nocuenta").parent().css({
                    "display": "table"
                });

                break;

        }

    });
</script> 