<?php

/**
 * Created by PhpStorm.
 * User: RSpro
 * Date: 22/05/16
 * Time: 14:01
 */
class Controller extends App
{


    static function values($array) {


        $string = "VALUES(";


        foreach($array as $key => $value) {

            if($value == "NULL") {

                $string .= "NULL, ";

            }
            else {
                
                $string .= "'$value', ";

            }

           

        }

        $string = substr($string, 0, -2).')';

        return $string;


    }



    static function updateValues($array) {


        $string = 'SET ';

        foreach ($array as $key => $value) {


            $string .= "$key = '$value', ";

        }

        $string = substr($string, 0, -2).' WHERE '.array_keys($array)[0].' = '.'"'.reset($array).'"';


        return $string;

    }


    static function makePDF($template) {


        require_once('../assets/libs/tcpdf/tcpdf.php');

        require_once('../assets/layouts/reports/'.$template.'.php');


    }

    static function getChartData($query) {


        $query = Controller::$connection->query($query);

        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        return $data;

    }



}

new Controller();
