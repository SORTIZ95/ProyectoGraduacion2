<?php




class View extends App
{

    public static function showViewFromTable($table_name, $table_title, $options = null, $view = "table_default")  {


        include("../assets/layouts/tables/".strtolower($view).".php");


    }


    public static function showView($view = "view_default")  {


        include("../assets/layouts/views/view_".strtolower($view).".php");


    }


    static function newChart($title, $array) {

        $array = $array ? $array : [["FECHA" => 0, "VENTA" => 0]];

        ?>

        <script type="text/javascript">


              google.charts.setOnLoadCallback(drawVisualization);

              var chart<?php echo $title; ?>;

              function drawVisualization() {

                // Some raw data (not necessarily accurate)
                data<?php echo $title; ?> = google.visualization.arrayToDataTable([
                 ['', ''],

                 <?php foreach ($array as $key => $value) : ?>

                 ['<?php echo $value["FECHA"]; ?>', <?php echo $value["VENTA"]; ?>],

                 <?php endforeach; ?>

              ]);


            options<?php echo $title; ?> = {
              title : '',
              vAxis: {title: ''},
              hAxis: {title: ''},
              seriesType: 'line',
              series: {2: {type: 'line'}}
            };


            chart<?php echo $title; ?> = new google.visualization.ComboChart(document.getElementById('chart_div_<?php echo $title; ?>'));
            chart<?php echo $title; ?>.draw(data<?php echo $title; ?>, options<?php echo $title; ?>);


          }

        </script>


        <div id="chart_div_<?php echo $title; ?>"></div>

        <?php


    }

}

new View();
