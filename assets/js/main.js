/**
 * Created by RSpro on 23/05/16.
 */

function supportInfo() {

    swal({
        title: 'Soporte',
        text: "Llamar: 5960 - 0502",
        type: 'info',
        cancelButtonText: 'Aceptar'
    });

}

function clientImage(key) {

    showCam(false);

    if (key != null) {

        $("#client-img").fadeOut(100, function () {
            $("#client-img").attr({ 'src': '../assets/pictures/cliente/' + key + ".jpg?" + Math.random() });

            $("img").error(function () {
                $(this).unbind("error").attr({ 'src': '../assets/pictures/cliente/0.jpg?' });
            });

        }).fadeIn(100);

    }

}

function EnableUpdatePic(option) {

    if (option) {

        $(".updatetake-pic").attr("disabled", false);


    }
    else {

        $(".updatetake-pic").attr("disabled", true);

    }


}

function showCam(option) {

    if (option) {


        $("#capturar-foto").css({ 'display': 'inline-block' });

        $("#client-img").css({ 'display': 'none' });

        $(".update-pic").attr("disabled", true);

    }
    else {

        $("#capturar-foto").css({ 'display': 'none' });

        $("#client-img").css({ 'display': 'inline-block' });

    }

}


function capturarImagen(key) {


    var cod = key;

    video = document.getElementById("capturar-foto");

    canvas = document.getElementById("canvas");

    imagen = canvas.getContext('2d');

    imagen.drawImage(video, 0, 0, 640, 480);

    imagen = canvas.toDataURL("image/jpeg", 1.0);


    $.ajax({

        method: "POST",
        url: "../classes/Api.php?action=savePhoto",
        data: { "id": cod, "data": imagen },
        dataType: "JSON",
        success: function (r) {



        }


    });


}


var detalle_venta_table = $('.detalle_venta_table').DataTable({

    responsive: true,
    order: [0, "desc"],
    dom: 'rtlip',
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
            customize: function (win) {

                $(win.document.body).css('background', 'none');

            }
        },
        {
            extend: 'copyHtml5',
            title: 'reporte'
        }],
    select: true,
    pageLength: 50,
    scrollY: 220,
    oLanguage: {
        "sUrl": "../assets/libs/datatables/Spanish.json"
    }

});


var detalle_devolucion_table = $('.detalle_devolucion_table').DataTable({

    responsive: true,
    order: [0, "desc"],
    dom: 'rtlip',
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
            customize: function (win) {

                $(win.document.body).css('background', 'none');

            }
        },
        {
            extend: 'copyHtml5',
            title: 'reporte'
        }],
    select: true,
    pageLength: 50,
    scrollY: 220,
    oLanguage: {
        "sUrl": "../assets/libs/datatables/Spanish.json"
    }

});

var detalle_compra_table = $('.detalle_compra_table').DataTable({

    responsive: true,
    order: [0, "desc"],
    dom: 'rtlip',
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
            customize: function (win) {

                $(win.document.body).css('background', 'none');

            }
        },
        {
            extend: 'copyHtml5',
            title: 'reporte'
        }],
    select: true,
    pageLength: 50,
    scrollY: 220,
    oLanguage: {
        "sUrl": "../assets/libs/datatables/Spanish.json"
    }

});


var table_details = $('.detail_table').DataTable({

    responsive: true,
    dom: 'Bfrtlip',
    order: [0, "desc"],
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
            customize: function (win) {

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
    scrollY: 300,
    oLanguage: {
        "sUrl": "../assets/libs/datatables/Spanish.json"
    }

});

creating = 1;

$('.detail_table tbody').on('click', 'tr', function () {

    $("#create").attr("disabled", true);


    if ($(this).hasClass('selected')) {


        var control = $(this).closest('.panel').find('.new');

        var form = $(control).closest(".panel");


        var table = $(this).closest(".detail_table").attr("id");

        var key = $(this).closest(".detail_table").find("th").first().text();

        var cod = table_details.row(this).data()[0];


        $.ajax({

            url: "../classes/Api.php?action=one",
            method: "POST",
            data: { "data": cod, "table": table, "key": key, "cod": cod },
            dataType: "JSON",
            success: function (r) {

                creating = 0;


                if (r != "") {

                    switchUD(control, true);

                    EnableUpdatePic(false);

                    clientImage(r[0]["idcliente"]);

                }

                $.each(r[0], function (key, value) {


                    $(form).find("#" + key).val(value);


                    if ($(form).find("#" + key).data("select2")) {

                        $(form).find("#" + key).select2("trigger", "select", {
                            data: { id: value ? value : "nothing" }
                        });

                    }



                });

            }


        });



    }


});


function adjustHeaders() {


    table_details.columns.adjust().draw();


}

setTimeout(function () { adjustHeaders(); }, 100);



$(".panel-title").on("click", function () {


    setTimeout(function () { adjustHeaders(); }, 100);


});


$(window).resize(function () {


    setTimeout(function () { adjustHeaders(); }, 100);


});

$(".updatetake-pic").on("click", function () {


    video = document.getElementById("capturar-foto");

    canvas = document.getElementById("canvas");

    imagen = canvas.getContext('2d');

    imagen.drawImage(video, 0, 0, 640, 480);

    imagen = canvas.toDataURL("image/jpeg", 1.0);


    var control = this;

    var fields = $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea, select");

    var arrayFields = [];


    $.each(fields, function (key, value) {


        arrayFields[value.id] = $(value).val();


    });

    var obj = $.extend({}, arrayFields);


    var cod = $(this).closest(".panel").find("input").first().val();


    $.ajax({

        url: "../classes/Api.php?action=updatePic",
        method: "POST",
        data: { "data": imagen, "id": cod },
        dataType: "JSON",
        success: function (r) {

            $(".updatetake-pic").attr("disabled", true);
            $(".update-pic").attr("disabled", false);

            showCam(false);

            clientImage(cod);


            swal({
                title: 'Actualización de Datos',
                text: "Foto actualizada!",
                type: 'success',
                cancelButtonText: 'Aceptar'
            });

        }


    });



});

$("button.hacerGasto").on("click", function()  {


    errors = [];
    

    if(!$("#noDocumento").val()) {

        errors[9] = "No se ha ingresado un numero de Documento.";
    }

    if(!$("#tipoDocumento").val()) {

        errors[8] = "No se ha seleccionado un tipo de Documento.";
    }

    if(!$("#idEgreso").val()) {

        errors[7] = "No se ha seleccionado un ID de Egreso";
    }

    if(!$("#fecha").val()) {

        errors[6] = "No se especificado una Fecha.";
    }

    if(!$("#idFormaPago").val()) {

        errors[5] = "No se ha seleccionado una forma de Pago.";
    }

    else if($("#idFormaPago").val() == 2) {

        
        if(!$("#noCheque").val()) {

            errors[4] = "No se ha ingresado un no. de Cheque.";

        }

        if(!$("#banco").val()) {

            errors[3] = "No se ha ingresado un Banco de este Cheque.";

        }

        if(!$("#noCuenta").val()) {

            errors[2] = "No se ha ingresado una Cuenta para este Pago.";

        }

    }

        if(!$("#motivo").val()) {

        errors[1] = "No se ingresado un Motivo.";
    }

    if(!$("#total").val()) {

        errors[0] = "No se ingresado un Total.";
    }



    if(errors.length == 0) {


    swal({

        title: 'Gastos',
        text: "¿Quiere realizar este gasto?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'

    }).then((result) => {

    if(result.value) {
   
    var control = this;

    var fields = $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea, select");

    var form = $(control).closest(".panel");


    var arrayFields = [];


    $.each(fields, function(key, value) {


        arrayFields[value.id] = $(value).val();


    });

    var obj = $.extend({}, arrayFields);


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();


        $.ajax({

        url: "../classes/Api.php?action=hacerGasto",
        method: "POST",
        data: { "data": obj, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function(r) {


            if(r == "Inserted") {

                $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                switchUD(control, false);
                refreshDetail(form);
                $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                    data: { id: "nothing" }
                });

            }

            if (r[0] == "CAJA_CERRADA") {


                swal({
                    title: 'Error!',
                    html: "Caja Cerrada",
                    type: 'error',
                    cancelButtonText: 'Aceptar',
                });


            }


        }


    });


}


    });

        } else {

            $.each(errors, function(key, value) {

                swal({
                    title: 'Error',
                    text: value,
                    type: 'error'
                })
            })
        }

    });

    
$("button.new").on("click", function () {


    var control = this;

    var form = $(control).closest(".panel");

    $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea").val("");

    $(this).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
        data: { id: "nothing" }
    });

    switchUD(control, false);
    refreshDetail(form);

    showCam(true);


});


$("button.hacerPago").on("click", function () {


    var control = this;

    var fields = $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea, select");

    var form = $(control).closest(".panel");


    var arrayFields = [];


    $.each(fields, function (key, value) {


        arrayFields[value.id] = $(value).val();


    });

    var obj = $.extend({}, arrayFields);


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();


    $.ajax({

        url: "../classes/Api.php?action=hacerPago",
        method: "POST",
        data: { "data": obj, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function (r) {


            if (r[0] == "Inserted") {

                $(control).closest(".panel").find("input").first().val(r[1]);

                swal({
                    title: 'Pago Relizado',
                    text: "¿Quieres imprimir el Recibo?",
                    type: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {

                        printManager.trigger("click");
                        switchUD(control, false);
                        refreshDetail(form);
                        $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                        $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                            data: { id: "nothing" }
                        });


                    } else {

                        switchUD(control, false);
                        refreshDetail(form);
                        $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                        $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                            data: { id: "nothing" }
                        });


                    }
                });

                switchUD(control, false);
                refreshDetail(form);


            }

            if (r[0] == "cliente_saldado") {


                swal({
                    title: 'Error!',
                    text: "Este cliente no tiene ninguna deuda que abonar ó su abono supera la deuda.",
                    type: 'error',
                    cancelButtonText: 'Aceptar'
                });


            }

            if (r[0] == "CAJA_CERRADA") {


                swal({
                    title: 'Error!',
                    html: "Caja Cerrada",
                    type: 'error',
                    cancelButtonText: 'Aceptar',
                });


            }


        }


    });


});

$("button.hacerPagoProveedor").on("click", function () {


    var control = this;

    var fields = $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea, select");

    var form = $(control).closest(".panel");


    var arrayFields = [];


    $.each(fields, function (key, value) {


        arrayFields[value.id] = $(value).val();


    });

    var obj = $.extend({}, arrayFields);


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();


    $.ajax({

        url: "../classes/Api.php?action=hacerPagoProveedor",
        method: "POST",
        data: { "data": obj, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function (r) {


            if (r[0] == "Inserted") {

                $(control).closest(".panel").find("input").first().val(r[1]);

                swal({
                    title: 'Pago Relizado al Proveedor',
                    text: "¿Quieres imprimir el Recibo?",
                    type: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {

                        printManager.trigger("click");
                        switchUD(control, false);
                        refreshDetail(form);
                        $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                        $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                            data: { id: "nothing" }
                        });


                    } else {

                        switchUD(control, false);
                        refreshDetail(form);
                        $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                        $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                            data: { id: "nothing" }
                        });


                    }
                });

                switchUD(control, false);
                refreshDetail(form);


            }

            if (r[0] == "proveedor_saldado") {


                swal({
                    title: 'Error!',
                    text: "Este proveedor no tiene ninguna deuda que abonarse ó su abono supera la deuda.",
                    type: 'error',
                    cancelButtonText: 'Aceptar'
                });


            }

            if (r[0] == "CAJA_CERRADA") {


                swal({
                    title: 'Error!',
                    html: "Caja Cerrada",
                    type: 'error',
                    cancelButtonText: 'Aceptar',
                });


            }


        }


    });


});

$("button.hacerDevolucion").on("click", function () {


    var control = this;

    var fields = $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea, select");

    var form = $(control).closest(".panel");


    var arrayFields = [];


    $.each(fields, function (key, value) {


        arrayFields[value.id] = $(value).val();


    });

    var obj = $.extend({}, arrayFields);


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();

    var data_detalle = detalle_devolucion_table.rows().data().toArray();


    if (!cod) {

        $.ajax({

            url: "../classes/Api.php?action=hacerDevolucion",
            method: "POST",
            data: { "data": obj, "table": table, "key": key, "cod": cod, "data_detalle": data_detalle },
            dataType: "JSON",
            success: function (r) {


                if (r[0] == "Inserted") {

                    $(control).closest(".panel").find("input").first().val(r[1]);

                    swal({
                        title: 'Devolución Relizada',
                        text: "¿Quieres imprimir la Factura?",
                        type: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.value) {

                            printManager.trigger("click");
                            $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                            $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                                data: { id: "nothing" }
                            });

                            $(".detalle_devolucion_table").DataTable().clear().draw();



                        } else {

                            $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                            $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                                data: { id: "nothing" }
                            });

                            $(".detalle_devolucion_table").DataTable().clear().draw();

                        }
                    });

                    total = 0;

                    switchUDDevolucion(control, false);
                    refreshDetailDevolucion(control);


                }

                if (r[0] == "producto_no_existencia") {


                    swal({
                        title: 'Error!',
                        html: "Del producto <strong>" + r[1][0][0] + "</strong> no hay existencias para cubrir la venta, hay " + r[1][0][5] + " en existencia.",
                        type: 'error',
                        cancelButtonText: 'Aceptar',
                    });


                }

                if (r[0] == "CAJA_CERRADA") {


                    swal({
                        title: 'Error!',
                        html: "Caja Cerrada",
                        type: 'error',
                        cancelButtonText: 'Aceptar',
                    });
    
    
                }


            }


        });


    }


});


$("button.hacerCompra").on("click", function () {


    var control = this;

    var fields = $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea, select");

    var form = $(control).closest(".panel");


    var arrayFields = [];


    $.each(fields, function (key, value) {


        arrayFields[value.id] = $(value).val();


    });

    var obj = $.extend({}, arrayFields);


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();

    var data_detalle = detalle_compra_table.rows().data().toArray();


    if (!cod) {

        $.ajax({

            url: "../classes/Api.php?action=hacerCompra",
            method: "POST",
            data: { "data": obj, "table": table, "key": key, "cod": cod, "data_detalle": data_detalle },
            dataType: "JSON",
            success: function (r) {

                if (r[0] == "Inserted") {

                    $(control).closest(".panel").find("input").first().val(r[1]);

                    swal({
                        title: 'Compra Relizada',
                        text: "¿Quieres imprimir la Factura?",
                        type: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí',
                        cancelButtonText: 'No'
                    }).then((result) => {

                        if (result.value) {

                            printManager.trigger("click");
                            $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                            $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                                data: { id: "nothing" }
                            });

                            $(".detalle_compra_table").DataTable().clear().draw();


                        } else {

                            $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                            $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                                data: { id: "nothing" }
                            });

                            $(".detalle_compra_table").DataTable().clear().draw();

                        }
                    });

                    total = 0;

                    switchUDDevolucion(control, false);
                    refreshDetailDevolucion(control);


                }

                if (r[0] == "producto_no_existencia") {


                    swal({
                        title: 'Error!',
                        html: "Del producto <strong>" + r[1][0][0] + "</strong> no hay existencias para cubrir la venta, hay " + r[1][0][5] + " en existencia.",
                        type: 'error',
                        cancelButtonText: 'Aceptar',
                    });


                }

                if (r[0] == "CAJA_CERRADA") {


                    swal({
                        title: 'Error!',
                        html: "Caja Cerrada",
                        type: 'error',
                        cancelButtonText: 'Aceptar',
                    });
    
    
                }


            }


        });


    }


});


$("button.hacerVenta").on("click", function () {

    var control = this;

    var fields = $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea, select");

    var form = $(control).closest(".panel");


    var arrayFields = [];


    $.each(fields, function (key, value) {

        arrayFields[value.id] = $(value).val();

    });


    var obj = $.extend({}, arrayFields);


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();

    var data_detalle = detalle_venta_table.rows().data().toArray();


    if (!cod) {


        $.ajax({

            url: "../classes/Api.php?action=hacerVenta",
            method: "POST",
            data: { "data": obj, "table": table, "key": key, "cod": cod, "data_detalle": data_detalle },
            dataType: "JSON",
            success: function (r) {


                if (r[0] == "Inserted") {

                    $(control).closest(".panel").find("input").first().val(r[1]);

                    swal({
                        title: 'Venta Relizada',
                        text: "¿Quieres imprimir la Factura?",
                        type: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.value) {

                            nTotal = obj.total;
                            idFormapago = obj.idFormapago;

                            printManager.trigger("click");
                            $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                            $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                                data: { id: "nothing" }
                            });

                            $("select#producto").select2("trigger", "select", {
                                data: { id: "nothing" }
                            });
         
                            $(".detalle_venta_table").DataTable().clear().draw();

                            if(idFormapago == "1") {

                            swal({
                                title: 'Cambio',
                                input: "text",
                                text: "Ingresa la cantidad de dinero con el que paga el cliente, para poder calcular su cambio a recibir.",
                                type: 'info',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Calcular cambio'
                            }).then((result) => {
                        
                                if (result.value) {

                                   var cambio = result.value - nTotal;

                                   if(cambio >= 0) {

                                    swal({
                                        title: 'Cambio',
                                        html: "El cambio para el cliente es: <br> <strong>Q"+cambio+"</strong>",
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Finalizar',
                                    });

                                   }
                                   else {

                                    swal({
                                        title: 'Error',
                                        html: "Asegurese que la cantidad a cobrar sea de: <br> <strong>Q"+nTotal+"</strong>",
                                        type: 'error',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Lo he comprobado',
                                    });


                                   }

                                    }

                                });

                            }

                        } else {

                            nTotal = obj.total;
                            idFormapago = obj.idFormapago;

                            $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                            $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                                data: { id: "nothing" }
                            });

                            $("select#producto").select2("trigger", "select", {
                                data: { id: "nothing" }
                            });
         

                            $(".detalle_venta_table").DataTable().clear().draw();

                            if(idFormapago == "1") {

                            swal({
                                title: 'Cambio',
                                input: "text",
                                text: "Ingresa la cantidad de dinero con el que paga el cliente, para poder calcular su cambio a recibir.",
                                type: 'info',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Calcular cambio'
                            }).then((result) => {
                        
                                if (result.value) {

                                   var cambio = result.value - nTotal;

                                    swal({
                                        title: 'Cambio',
                                        html: "El cambio para el cliente es: <br> <strong>Q"+parseFloat(cambio).toFixed(2)+"</strong>",
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Finalizar',

                                    });

                                    }

                                });
                            }

                        }
                    });

                    total = 0;

                    switchUDVenta(control, false);
                    refreshDetailVenta(control);


                }

                if (r[0] == "producto_no_existencia") {


                    swal({
                        title: 'Error!',
                        html: "Del producto <strong>" + r[1][0][0] + "</strong> no hay existencias para cubrir la venta, hay " + r[1][0][5] + " en existencia.",
                        type: 'error',
                        cancelButtonText: 'Aceptar',
                    });


                }

                if (r[0] == "CAJA_CERRADA") {


                    swal({
                        title: 'Error!',
                        html: "Caja Cerrada",
                        type: 'error',
                        cancelButtonText: 'Aceptar',
                    });


                }

                if(r[0] == "NO_EXISTENCIA") {

                    swal({
                        title: 'Error de Producto',
                        text: r[1].nombre,
                        type: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });

                }

                if(r[0] == "NO_FECHA") {

                    swal({
                        title: 'Error de Fecha',
                        text: "Ingrese una fecha correcta para el registro de la venta",
                        type: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });

                }

            }

        });

    }


});



$("button.create").on("click", function () {


    var control = this;

    var fields = $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea, select");

    var form = $(control).closest(".panel");


    var arrayFields = [];


    $.each(fields, function (key, value) {


        arrayFields[value.id] = $(value).val();


    });

    var obj = $.extend({}, arrayFields);


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();


    $.ajax({

        url: "../classes/Api.php?action=create",
        method: "POST",
        data: { "data": obj, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function (r) {

            if (r == "Inserted") {

                $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");

                $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                    data: { id: "nothing" }
                });

                switchUD(control, false);
                refreshDetail(form);

            }
            else {

                swal({
                    title: 'Error',
                    html: "Revise porfavor que haya ingresado todos los datos necesarios en este formulario e intente de nuevo.",
                    type: 'error',
                    cancelButtonText: 'Aceptar',
                });

            }

        }

    });


});


$("button.hacerDeposito").on("click", function()  {


    var control = this;

    var fields = $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea, select");

    var form = $(control).closest(".panel");


    var arrayFields = [];


    $.each(fields, function(key, value) {


        arrayFields[value.id] = $(value).val();


    });

    var obj = $.extend({}, arrayFields);


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();


        $.ajax({

        url: "../classes/Api.php?action=hacerDeposito",
        method: "POST",
        data: { "data": obj, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function(r) {

            if(r == "Inserted") {

                $(form).closest(".panel").find(".inputs_wrapper").find("input, textarea, select").val("");
                switchUD(control, false);
                refreshDetail(form);

                $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                    data: { id: "" }
                });

            }

            if (r[0] == "CAJA_CERRADA") {

                swal({
                    title: 'Error!',
                    html: "Caja Cerrada",
                    type: 'error',
                    cancelButtonText: 'Aceptar',
                });


            }


        }


    });


});

$("button.update").on("click", function () {


    var control = this;

    var fields = $(this).closest(".panel").find(".inputs_wrapper").find("input, textarea, select");

    var arrayFields = [];


    $.each(fields, function (key, value) {


        arrayFields[value.id] = $(value).val();


    });

    var obj = $.extend({}, arrayFields);


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();


    $.ajax({

        url: "../classes/Api.php?action=update",
        method: "POST",
        data: { "data": obj, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function (r) {


            if (r == "Updated") {

                refreshDetail(control);
                switchUD(control, false);

                $(control).closest(".panel").find(".inputs_wrapper").find("input, textarea").val("");

                $(control).closest(".panel").find(".inputs_wrapper").find("select").select2("trigger", "select", {
                    data: { id: "nothing" }
                });

                showCam(true);


            }
            else {

                swal({
                    title: 'Error',
                    html: "Revise porfavor que haya ingresado todos los datos necesarios en este formulario e intente de nuevo.",
                    type: 'error',
                    cancelButtonText: 'Aceptar',
                });

            }


        }


    });


});

$("button.delete").on("click", function () {


    var control = this;

    var fields = $(this).closest(".panel").find("input").first().val();

    var form = $(control).closest(".panel");


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();


    swal({
        title: 'Eliminar Registro',
        text: "¿Seguro que quieres eliminar este registro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {

            $.ajax({

                url: "../classes/Api.php?action=delete",
                method: "POST",
                data: { "data": fields, "table": table, "key": key, "cod": cod },
                dataType: "JSON",
                success: function (r) {


                    if (r == "Deleted") {

                        switchUD(control, false);
                        refreshDetail(control);

                        $(form).closest(".panel").find("input, textarea").val("");

                        $(form).closest(".panel").find("select").select2("trigger", "select", {
                            data: { id: "nothing" }
                        });

                        
                    }
                    else {

                        swal({
                            title: 'Error',
                            html: "Este registro contiene información relacionada que depende entre si, borre la información relacionada e intente de nuevo. <br><br>",
                            type: 'error',
                            cancelButtonText: 'Aceptar',
                        });
        
                    }

                }


            });



        }
    });


});


$("button.deleteVenta").on("click", function () {


    var control = this;

    var fields = $(this).closest(".panel").find("input").first().val();

    var form = $(control).closest(".panel");


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();


    swal({
        title: 'Eliminar Registro',
        text: "¿Seguro que quieres eliminar este registro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {

            $.ajax({

                url: "../classes/Api.php?action=delete",
                method: "POST",
                data: { "data": fields, "table": table, "key": key, "cod": cod },
                dataType: "JSON",
                success: function (r) {


                    if (r == "Deleted") {

                        refreshDetailVenta(control);

                        $(".detalle_venta_table").DataTable().clear().draw();

                        $(form).closest(".panel").find("input, textarea").val("");

                        $(form).closest(".panel").find("select").select2("trigger", "select", {
                            data: { id: "nothing" }
                        });
                        

                    }

                }


            });



        }
    });


});


$("button.prev").on("click", function () {

    var control = this;

    var fields = $(this).closest(".panel").find("input").first().val();

    var form = $(control).closest(".panel");


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();


    $.ajax({

        url: "../classes/Api.php?action=prev",
        method: "POST",
        data: { "data": fields, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function (r) {

            if (r != "") {

                switchUD(control, true);
                refreshDetail(control);

                EnableUpdatePic(false);

                clientImage(r[0]["idcliente"]);

            }

            $.each(r[0], function (key, value) {

                $(form).find("#" + key).val(value);

                if ($(form).find("#" + key).data("select2")) {
                   
                    $(form).find("#" + key).select2("trigger", "select", {
                       
                        data: { id: value ? value : "nothing" }
                    });

                }

            });

        }


    });



});


$("button.next").on("click", function () {


    var control = this;

    var fields = $(this).closest(".panel").find("input").first().val();

    var form = $(control).closest(".panel");


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();


    $.ajax({

        url: "../classes/Api.php?action=next",
        method: "POST",
        data: { "data": fields, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function (r) {


            if (r != "") {

                switchUD(control, true);
                refreshDetail(form);
                showCam(false);

                EnableUpdatePic(false);

                clientImage(r[0]["idcliente"]);

            }

            $.each(r[0], function (key, value) {

                $(form).find("#" + key).val(value);

                if ($(form).find("#" + key).data("select2")) {

                    $(form).find("#" + key).select2("trigger", "select", {
                        data: { id: value ? value : "nothing" }
                    });

                }

            });

        }

    });

});



printManager = $("button.print").on("click", function () {


    var template = $(this).attr("template");

    if (template === undefined) {

        template = "default";

    }

    var fields = $(this).closest(".panel").find("input, textarea, select");

    var arrayFields = [];


    $.each(fields, function (key, value) {

        arrayFields[value.id] = $(value).val();


    });

    var obj = $.extend({}, arrayFields);


    var table = $(this).closest(".panel").attr("id");

    var key = $(this).closest(".panel").find("input").first().attr("id");

    var cod = $(this).closest(".panel").find("input").first().val();

    printWindow('../classes/Api.php?action=print&template=' + template, { "data": JSON.stringify(obj), "table": table, "key": key, "cod": cod });


});


function printWindow(path, params, method) {

    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    form.setAttribute("target", "_blank");

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);

        }
    }

    document.body.appendChild(form);
    form.submit();
}


function refreshDetail(control) {


    var fields = $(control).closest(".panel").find("input").first().val();

    var table = $(control).closest(".panel").attr("id");

    var key = $(control).closest(".panel").find("input").first().attr("id");

    var cod = $(control).closest(".panel").find("input").first().val();


    $.ajax({

        url: "../classes/Api.php?action=all",
        method: "POST",
        data: { "data": fields, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function (r) {


            if (r) {


                $(control).closest(".panel").find('.detail_table').DataTable().clear().draw();

                $(control).closest(".panel").find('.detail_table').DataTable().rows.add(r).draw();

                adjustHeaders();


            }

        }

    });



}


function refreshDetailVenta(control) {


    var fields = $(control).closest(".panel").find("input").first().val();

    var table = $(control).closest(".panel").attr("id");

    var key = $(control).closest(".panel").find("input").first().attr("id");

    var cod = $(control).closest(".panel").find("input").first().val();


    $.ajax({

        url: "../classes/Api.php?action=all",
        method: "POST",
        data: { "data": fields, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function (r) {


            if (r) {


                $(control).closest(".panel").find('.detail_table_venta').DataTable().clear().draw();

                $(control).closest(".panel").find('.detail_table_venta').DataTable().rows.add(r).draw();

                adjustHeaders();


            }

        }

    });



}


function switchUD(control, option) {

    if (option) {

        $(control).closest(".panel").find(".delete").removeAttr("disabled");
        $(control).closest(".panel").find(".update").removeAttr("disabled");
        $(control).closest(".panel").find("#create").attr("disabled", true);
        $(control).closest(".panel").find(".print").removeAttr("disabled");

        $(".update-pic").removeAttr("disabled");

        EnableUpdatePic(true);

    }
    else {

        $(control).closest(".panel").find(".delete").attr("disabled", true);
        $(control).closest(".panel").find(".update").attr("disabled", true);
        $(control).closest(".panel").find("#create").removeAttr("disabled");
        $(control).closest(".panel").find(".print").attr("disabled", true);

        $(".update-pic").attr("disabled", true);

        EnableUpdatePic(false);


    }


}

function switchUDVenta(control, option) {

    if (option) {

        $(control).closest(".panel").find(".deleteVenta").removeAttr("disabled");
        $(control).closest(".panel").find(".update").removeAttr("disabled");
        $(control).closest(".panel").find(".create").attr("disabled", true);
        $(control).closest(".panel").find(".print").removeAttr("disabled");

    }
    else {

        $(control).closest(".panel").find(".deleteVenta").attr("disabled", true);
        $(control).closest(".panel").find(".update").attr("disabled", true);
        $(control).closest(".panel").find(".create").removeAttr("disabled");
        $(control).closest(".panel").find(".print").attr("disabled", true);


    }


}

function refreshDetailDevolucion(control) {


    var fields = $(control).closest(".panel").find("input").first().val();

    var table = $(control).closest(".panel").attr("id");

    var key = $(control).closest(".panel").find("input").first().attr("id");

    var cod = $(control).closest(".panel").find("input").first().val();


    $.ajax({

        url: "../classes/Api.php?action=all",
        method: "POST",
        data: { "data": fields, "table": table, "key": key, "cod": cod },
        dataType: "JSON",
        success: function (r) {


            if (r) {

                $(control).closest(".panel").find('.detail_table_'+table).DataTable().clear().draw();

                $(control).closest(".panel").find('.detail_table_'+table).DataTable().rows.add(r).draw();

                adjustHeaders();

            }

        }

    });


}

function switchUDDevolucion(control, option) {

    if (option) {

        $(control).closest(".panel").find(".deleteVenta").removeAttr("disabled");
        $(control).closest(".panel").find(".update").removeAttr("disabled");
        $(control).closest(".panel").find(".create").attr("disabled", true);
        $(control).closest(".panel").find(".print").removeAttr("disabled");

    }
    else {

        $(control).closest(".panel").find(".deleteVenta").attr("disabled", true);
        $(control).closest(".panel").find(".update").attr("disabled", true);
        $(control).closest(".panel").find(".create").removeAttr("disabled");
        $(control).closest(".panel").find(".print").attr("disabled", true);


    }


}



$('input').on('keyup', function (e) {

    if (e.which === 13) {

        $(this).next('input').focus();

    }


});


$(".loginForm").on("submit", function (e) {


    e.preventDefault();


    var arrayFields = [];


    var fields = $(this).find("input");



    $.each(fields, function (key, value) {


        arrayFields[value.id] = $(value).val();


    });


    var obj = $.extend({}, arrayFields);


    $.ajax({

        url: "../classes/Api.php?action=login",
        method: "POST",
        data: { "data": obj, "table": 'login', "key": 'user', "cod": 0 },
        dataType: "JSON",
        success: function (r) {


            if (r == "OK") {

                location.replace("../app/dashboard.php");


            }
            else {

                alert("Login Incorrecto!");

            }


        }


    });



});

$(".btnLogout").on("click", function (e) {


    $.ajax({

        url: "../classes/Api.php?action=logout",
        method: "POST",
        data: { "data": 1, "table": 'login', "key": 'user', "cod": 0 },
        dataType: "JSON",
        success: function (r) {


            if (r == "OK") {

                location.replace("../app/login.php")


            }


        }


    });





});


$.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['es']);


$(".datepicker").datepicker({

    dateFormat: "yy-mm-dd",
    changeMonth: true,
    changeYear: true

});

$(document).ready(function () {
    $(".date_mensual").datepicker({
        dateFormat: 'yy-mm',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,

        onChangeMonthYear: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('yy-mm', new Date(year, month, 1)));
        }
    });

    $(".date_mensual").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });
});


$('input, button, select').keydown(function (e) {
    if (e.which === 13) {

        var index = $('input, button, select').index(this) + 1;
        $('input, button, select').eq(index).focus();

    }
});

$(document).on('ready', function () {

    showCam(true);

});



/* Custom JS for Views */
