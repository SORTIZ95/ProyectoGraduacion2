<?php
/**
 * Created by PhpStorm.
 * User: RSpro
 * Date: 22/05/16
 * Time: 15:31
 */


?>

<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">

    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>

            <strong>Reporte del Día</strong>

        </h3>

    </div>

    <div class="panel-collapse collapse in">

  <div class="panel-body">


      <div id="CLIENTE" class="panel panel-default">

   <!-- <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

            <a data-toggle="collapse" data-target="#CLIENTE-panel">
                <strong>Opciones de Reporte</strong>
            </a>

        </h3>

    </div> -->

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
                    <input id="fecha_dia" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
                </div>

                </div>

            </div>

        </div>

    </div>




    </div>

    <div class="col-md-4">

      <div class="form-group">
        <br>
      <button id="print" template="reporte_diario" type="button" class="print btn btn-default btn-md btn-block">
          <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte del Día
      </button>

      </div>

    </div>

  </div>

    </div>


  </div>




    </div>

    </div>


</div>

<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">

<div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>

        <strong>Reporte Mensual</strong>

    </h3>

</div>

<div class="panel-collapse collapse in">

<div class="panel-body">


  <div id="CLIENTE" class="panel panel-default">

<div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>

        <a data-toggle="collapse" data-target="#CLIENTE-panel">
            <strong>Informe Financiero del Mes y de Impuestos</strong>
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
            <br>
            <br>
                <div class="input-group">
                <span class="input-group-addon" id="basic-addon">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                </span>
                <select id="trimestreOp" class = "form-control" name="select">
                    <option value="1">Enero - Marzo</option> 
                    <option value="2">Abril - Junio</option>
                    <option value="3">Julio - Septiembre</option>
                    <option value="4">Octubre - Diciembre</option>
                </select>
            </div>

            </div>

        </div>

    </div>

</div>




</div>

<div class="col-md-4">

  <div class="form-group">
    <br>
    
  <button id="print" template="RPTMensual" type="button" class="print btn btn-default btn-md btn-block">
      <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte
  </button>
    <br>
   <div class="input-group">
                <span class="input-group-addon" id="basic-addon">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                </span>
                <input id="ivacredito" class="form-control" placeholder="IVA Crédito" aria-describedby="basic-addon" type="text">
    </div> 

  </div>

</div>

</div>

</div>


</div>




</div>

</div>


</div>

<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">


    <div class="panel-heading">
        <h3 class="panel-title"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>

            <strong>Reporte Existencia de Productos</strong>

        </h3>

    </div>

<div class="panel-collapse collapse in">

  <div class="panel-body">



  <div class="col-md-12 text-center">

    <div class="form-group">
        
    <button id="print" template="RPTExistencia" type="button" class="print btn btn-default btn-md">
        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte de Existencia
    </button>

    </div>

  </div>

</div>

  </div>





  </div>


<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">


<div class="panel-heading">
    <h3 class="panel-title"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>

        <strong>Reporte Ventas al Crédito</strong>

    </h3>

</div>

<div class="panel-collapse collapse in">

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
                  <input id="fecha_camion" class="datepicker form-control" placeholder="FECHA" aria-describedby="basic-addon" type="text">
              </div>

              </div>

          </div>

      </div>

  </div>




  </div>

  <div class="col-md-4">

    <div class="form-group">
    <br>
    <button id="print" template="RPTVentaCredito" type="button" class="print btn btn-default btn-md btn-block">
        <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir Reporte de Créditos
        </button>

    </div>

  </div>

</div>

  </div>


</div>




  </div>

</div>


</div>
