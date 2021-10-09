<?php

/**
 * Created by PhpStorm.
 * User: RSpro
 * Date: 4/07/16
 * Time: 10:04
 */


class Security extends App  {

    private static $user;

    private static $token;
    private static $status;

    static $permission;


    public function __construct()   {

        session_start();

        if(isset($_SESSION["token"])) {

            Security::$token = $_SESSION["token"];
            Security::$permission = $_SESSION["permission"];

        }


        $scriptName = strtolower(basename($_SERVER["SCRIPT_FILENAME"], '.php'));

        Security::checkPermissionsRoute($scriptName);

        if(!Security::$token && $scriptName != 'login' && $scriptName != 'api') {

           header("Location: ../app/login.php");

        }
        else if(Security::$token && $scriptName == 'login') {

            header("Location: ../app/dashboard.php");

        }


    }

    public static function checkPermissionsRoute($scriptName) {

        include("permissions.inc");

        if(isset($permissions->modulo[$scriptName])) {

            if(!in_array(Security::$permission, $permissions->modulo[$scriptName])) {

                header("Location: ../app/dashboard.php");

            }

        }

    }

    public static function login($loginData) {


        $user = $loginData["user"];
        $pass = $loginData["pass"];


        $query = Controller::$connection->query("SELECT * FROM login WHERE user = '$user' AND pass = '".md5($pass)."'");


        if($query->rowCount()) {

            $query = $query->fetchAll(PDO::FETCH_ASSOC);


            $_SESSION["token"] = session_id();

 
            Security::$permission = $query[0]["rol"];

            $_SESSION["permission"] =  Security::$permission;

            return true;

        }

        return false;


    }

    public static function logout() {

        session_destroy();

        session_unset();

        unset($_SESSION);


        return true;


    }


}


new Security();





