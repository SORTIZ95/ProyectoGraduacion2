<?php
/**
 * User: RogerDev
 * Date: 06/03/19
 */
?>

<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">

    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>

            <strong>Generar Código de Barras</strong>

        </h3>
    </div>

    <div class="panel-collapse collapse in">

        <div class="panel-body text-center">

            <div style="display:inline-block;">

                <div id="barcode" class="printable">
                    Haz click en "Generar"
                </div>
                <br>
                <button id="generar" onclick="generarBarcode()" value="Generar Barcode">Generar</button>
                <button id="imprimir" onclick="printBarCode()" value="Generar Barcode" disabled>Imprimir</button>
            </div>

        </div>
    </div>
</div>

<script>

    function generarBarcode() {

        var rand = Math.round(Math.random()*100000000);

        $("#barcode").barcode(
            rand+"", // Valor del codigo de barras
            "ean8", // tipo (cadena)
        {
            barWidth: 2,
            barHeight: 60,
            output: "css",
        });

        $("#imprimir").removeAttr("disabled");

    }

    function printBarCode() {

        $(".printable").print();
        
    }
</script> 