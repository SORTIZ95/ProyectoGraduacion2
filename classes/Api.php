<?php

include("App.class.php");
include("Model.class.php");
include("Controller.class.php");
include("Security.class.php");
include("permissions.inc");

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Api extends Controller  {

    // Obtiene todos los registros de una tabla
    public function allRegistries($table, $key) {

        $query = Controller::$connection->query("SELECT * FROM $table ORDER BY $key DESC");

        if($query) {

            $data = $query->fetchAll(PDO::FETCH_NUM);
        }


        header('Content-Type: application/json');

        echo json_encode($data);

    }

    // Obtiene un registro especifico de una tabla
    public function oneRegistry($table, $key, $cod) {

        $query = Controller::$connection->query("SELECT * FROM $table WHERE $key = '$cod' LIMIT 1");

        if($query) {

            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }


        header('Content-Type: application/json');

        echo json_encode($data);

    }

    //Obtiene un registro de una venta
    public function oneRegistryVenta($table, $key, $cod) {


        $query1 = Controller::$connection->query("SELECT * FROM $table WHERE $key = '$cod' LIMIT 1");

        $query2 = Controller::$connection->query("SELECT * FROM detalle_venta INNER JOIN PRODUCTO ON detalle_venta.idproducto = PRODUCTO.idproducto WHERE idventa = '$cod'");


        if($query1 && $query2) {

            $data[0] = $query1->fetchAll(PDO::FETCH_ASSOC);
            $data[1] = $query2->fetchAll(PDO::FETCH_ASSOC);

          
        }

        foreach($data[1] as $key => $value) {

            $itemVenta[$key][0] = $value["idproducto"]; // ID
            $itemVenta[$key][1] = $value["nombre"]; // PRODUCTO
            $itemVenta[$key][2] = $value["cantidad"]; // CANTIDAD
            $itemVenta[$key][3] = $value["subtotal"] / $value["cantidad"]; // PRECIO
            $itemVenta[$key][4] = $value["subtotal"];   // SUBTOTAL

        }


        $data[1] = $itemVenta;

        header('Content-Type: application/json');

        echo json_encode($data);

    }

    // Obtiene un registro de una compra
    public function oneRegistryCompra($table, $key, $cod) {


        $query1 = Controller::$connection->query("SELECT * FROM $table WHERE $key = '$cod' LIMIT 1");

        $query2 = Controller::$connection->query("SELECT * FROM detalle_compra INNER JOIN producto ON detalle_compra.idproducto = PRODUCTO.idproducto WHERE idCompra = '$cod'");


        if($query1 && $query2) {

            $data[0] = $query1->fetchAll(PDO::FETCH_ASSOC);
            $data[1] = $query2->fetchAll(PDO::FETCH_ASSOC);

        }

        foreach($data[1] as $key => $value) {

            $itemVenta[$key][0] = $value["idproducto"]; // ID
            $itemVenta[$key][1] = $value["nombre"]; // PRODUCTO
            $itemVenta[$key][2] = $value["cantidad"]; // Cantidad
            $itemVenta[$key][3] = $value["precioUnitario"] / $value["cantidad"]; // Precio
            $itemVenta[$key][4] = $value["precioUnitario"];   // Subtotal

        }

      
        $data[1] = $itemVenta;


        header('Content-Type: application/json');

        echo json_encode($data);

    }

    // Obtiene un registro de una devolucion
    public function oneRegistryDevolucion($table, $key, $cod) {


            $query1 = Controller::$connection->query("SELECT * FROM $table WHERE $key = '$cod' LIMIT 1");

            $query2 = Controller::$connection->query("SELECT * FROM detalle_devolucion WHERE id_devolucion = '$cod'");


            if($query1 && $query2) {

                $data[0] = $query1->fetchAll(PDO::FETCH_ASSOC);
                $data[1] = $query2->fetchAll(PDO::FETCH_NUM);

            }

            foreach($data[1] as $key => $value) {

                $itemVenta[$key][0] = $value[2]; // ID
                $itemVenta[$key][1] = $value[3]; // Cantidad
                $itemVenta[$key][2] = $value[4] / $value[3]; // Precio
                $itemVenta[$key][3] = $value[4];   // Subtotal

            }


            $data[1] = $itemVenta;


            header('Content-Type: application/json');

            echo json_encode($data);

        }

        // Agrega un articulo a la compra
        public function addItemCompra($table, $key, $cod, $param) {


            $query = Controller::$connection->query("SELECT * FROM $table WHERE $key = '$cod' LIMIT 1");

            if($query) {

                $data = $query->fetchAll(PDO::FETCH_NUM);
            }


            $itemVenta[0][0] = $data[0][0]; // ID
            $itemVenta[0][1] = $data[0][2]; // Nombre
            $itemVenta[0][2] = $param;      // Cantidad
            $itemVenta[0][3] = $data[0][3]; // Precio
            $itemVenta[0][4] = sprintf('%0.2f', round($param * $data[0][3], 2, 2)); // Subtotal


            header('Content-Type: application/json');

            echo json_encode($itemVenta);

        }

        public function addItemDevolucion($table, $key, $cod, $param) {
            

            $query = Controller::$connection->query("SELECT * FROM $table WHERE $key = '$cod' LIMIT 1");

            if($query) {

                $data = $query->fetchAll(PDO::FETCH_NUM);
            }

            $itemVenta[0][0] = $data[0][0]; // ID
            $itemVenta[0][1] = $param;      // Cantidad
            $itemVenta[0][2] = $data[0][4]; // Precio
            $itemVenta[0][3] = sprintf('%0.2f', round($param * $data[0][4], 2, 2));   // Subtotal


            header('Content-Type: application/json');

            echo json_encode($itemVenta);

        }


    // Agrega un articulo a la venta
    public function addItemVenta($table, $key, $cod, $param) {

        $query = Controller::$connection->query("SELECT * FROM $table WHERE $key = '$cod' LIMIT 1");

        if($query->rowCount()) {

            $data = $query->fetchAll(PDO::FETCH_NUM);
        }
        else {

            header('Content-Type: application/json');

            $msg = "error_id_product";

            echo json_encode($msg);

            return 0;
        }

        $itemVenta[0][0] = $data[0][0]; // ID


        $itemVenta[0][1] = $data[0][2]; // Producto


        $itemVenta[0][2] = $param["cantidad"]; // Cantidad

        $cantidad = $param["cantidad"];  // Cantidad


        $descuento = (empty($param["descuento"])) ? 0 : $param["descuento"];

        if($param["precioMayorista"] == 1) {

          $itemVenta[0][3] = $data[0][5]; // Precio
          $precio = $data[0][5]; // Precio

            if($param["cantidad"] < 1) {

                $itemVenta[0][3] = $itemVenta[0][3] / 100;
                $itemVenta[0][2] = $param["cantidad"] * 100;
                    
            }

        }
        else {

          $itemVenta[0][3] = $data[0][4]; // Precio
          $precio = $data[0][4]; // Precio

            if($param["cantidad"] < 1) {

                $itemVenta[0][3] = $itemVenta[0][3] / 100;
                $itemVenta[0][2] = $param["cantidad"] * 100;
                    
            }

        }

        $itemVenta[0][4] = sprintf('%0.2f', round(($cantidad * $precio) - $descuento, 2, 2)); // Subtotal

        header('Content-Type: application/json');

        if($descuento > $itemVenta[0][4]) {

          $msg = "error_descuento";

          echo json_encode($msg);


        }
        else {

          echo json_encode($itemVenta);

        }

    }

    // Agrega un producto a Productos
    public function addProducto($table, $data) {
        

        $values = Controller::values($data);

        $query = Controller::$connection->query("INSERT INTO $table $values");

        header('Content-Type: application/json');


        if($query) {

            echo json_encode($data);

        }
        else {

            echo json_encode(["Not Inserted"]);

            print_r(Controller::$connection->errorInfo());

        }


    }


    // Actualiza un producto en Productos
    public function updateProducto($table, $key, $cod, $data) {


        $setValues = Controller::updateValues($data);

        $query = Controller::$connection->query("UPDATE $table $setValues");


        header('Content-Type: application/json');


        if($query) {

            echo json_encode(["Updated"]);

        }
        else {

            echo json_encode(["Not Updated"]);

            print_r(Controller::$connection->errorInfo());

        }

    }


    // Crea un Registro nuevo
    public function create($table, $data) {


        $values = Controller::values($data);

        $query = Controller::$connection->query("INSERT INTO $table $values");


        header('Content-Type: application/json');


        if($query) {

            echo json_encode(["Inserted"]);

        }
        else {

            $out = Controller::$connection->errorInfo();

            echo json_encode($out);

        }


    }


    // Actualiza un Registro
    public function update($table, $key, $cod, $data) {

        $setValues = Controller::updateValues($data);

        $query = Controller::$connection->query("UPDATE $table $setValues");


        header('Content-Type: application/json');


        if($query) {

            echo json_encode(["Updated"]);

        }
        else {

            $out = Controller::$connection->errorInfo();

            echo json_encode($out);

        }

    }

    // Elimina un Registro
    public function delete($table, $key, $cod) {

        $query = Controller::$connection->query("DELETE FROM $table WHERE $key = '$cod' LIMIT 1");


        header('Content-Type: application/json');

        if($query) {

            echo json_encode(["Deleted"]);

        }
        else {

            $out = Controller::$connection->errorInfo();

            echo json_encode($out);

        }


    }


    // Registro Anterior
    public function prev($table, $key, $cod) {

        $query = Controller::$connection->query("SELECT * FROM $table WHERE $key < '$cod' ORDER BY $key DESC LIMIT 1");

        if($query) {

            $data = $query->fetchAll(PDO::FETCH_ASSOC);

        }


        header('Content-Type: application/json');

        echo json_encode($data);

    }

    public function prevVenta($table, $key, $cod) {

        $query1 = Controller::$connection->query("SELECT * FROM $table WHERE $key < '$cod' ORDER BY $key DESC LIMIT 1");

        if($query1) {

            $data[0] = $query1->fetchAll(PDO::FETCH_ASSOC);

            $id = $data[0][0]["idventa"];

            $query2 = Controller::$connection->query("SELECT * FROM detalle_venta WHERE idventa = '$id'");

            $data[1] = $query2->fetchAll(PDO::FETCH_NUM);

        }

        foreach($data[1] as $key => $value) {

            $itemVenta[$key][0] = $value[2]; // ID
            $itemVenta[$key][1] = $value[3]; // Cantidad
            $itemVenta[$key][2] = $value[4] / $value[3]; // Precio
            $itemVenta[$key][3] = $value[4];   // Subtotal

        }


        $data[1] = $itemVenta;


        header('Content-Type: application/json');

        echo json_encode($data);

    }

    public function prevDevolucion($table, $key, $cod) {


        $query1 = Controller::$connection->query("SELECT * FROM $table WHERE $key < '$cod' ORDER BY $key DESC LIMIT 1");

        if($query1) {

            $data[0] = $query1->fetchAll(PDO::FETCH_ASSOC);

            $id = $data[0][0]["id_devolucion"];

            $query2 = Controller::$connection->query("SELECT * FROM detalle_devolucion WHERE id_devolucion = '$id'");

            $data[1] = $query2->fetchAll(PDO::FETCH_NUM);

        }

        foreach($data[1] as $key => $value) {

            $itemVenta[$key][0] = $value[2]; // ID
            $itemVenta[$key][1] = $value[3]; // Cantidad
            $itemVenta[$key][2] = $value[4] / $value[3]; // Precio
            $itemVenta[$key][3] = $value[4];   // Subtotal

        }


        $data[1] = $itemVenta;


        header('Content-Type: application/json');

        echo json_encode($data);

    }

    public function prevCompra($table, $key, $cod) {


        $query1 = Controller::$connection->query("SELECT * FROM $table WHERE $key < '$cod' ORDER BY $key DESC LIMIT 1");

        if($query1) {

            $data[0] = $query1->fetchAll(PDO::FETCH_ASSOC);

            $id = $data[0][0]["idCompra"];

            $query2 = Controller::$connection->query("SELECT * FROM detalle_compra WHERE idCompra = '$id'");

            $data[1] = $query2->fetchAll(PDO::FETCH_NUM);

        }

        foreach($data[1] as $key => $value) {

            $itemVenta[$key][0] = $value[2]; // ID
            $itemVenta[$key][1] = $value[3]; // Cantidad
            $itemVenta[$key][2] = $value[4] / $value[3]; // Precio
            $itemVenta[$key][3] = $value[4];   // Subtotal

        }


        $data[1] = $itemVenta;


        header('Content-Type: application/json');

        echo json_encode($data);

    }

    public function actualizarBancos($monto, $operacion, $no_cuenta) {

        if($operacion == "ingreso") {

            $queryBancos = Controller::$connection->query("UPDATE CUENTABANCARIA SET SALDO = SALDO + $monto WHERE nocuenta = '$no_cuenta'");

            return true;

        }
        else if($operacion == "egreso") {

            $queryBancos = Controller::$connection->query("UPDATE CUENTABANCARIA SET SALDO = SALDO - $monto WHERE nocuenta = '$no_cuenta'");

            return true;

        }

        return false;
 

    }
    // Hacer gastos y realizar operaciones contables

public function hacerGasto($table, $param) {

    switch($param["idFormaPago"]) {

        case "1":
        $param["noCuenta"] = "NULL";
        $param["banco"] = "NULL";
                     
        $values = Controller::values($param);

        $query = Controller::$connection->query("INSERT INTO $table $values");


        if($query) {

        // Actualizar Caja
        $this->actualizarCaja2($param["total"], "egreso", $param);

        header('Content-Type: application/json');

        echo json_encode(["Inserted"]);
    
        }
        else {

            header('Content-Type: application/json');

            echo json_encode(["Not-Inserted"]);

        }


        break;
        case "2":

        $values = Controller::values($param);

        $query = Controller::$connection->query("INSERT INTO $table $values");

        if($query) {

        // Actualizar Cuenta bancaria
        $this->actualizarBancos($param["total"], "egreso", $param["noCuenta"]);
        
        header('Content-Type: application/json');

        echo json_encode(["Inserted"]);
    

        }
        else {

            header('Content-Type: application/json');

            echo json_encode(["Not-Inserted"]);

        }

        break;
        case "3":

        
            

        break;
        
    }

  
}
    // Registro Siguiente
    public function next($table, $key, $cod) {

        $query = Controller::$connection->query("SELECT * FROM $table WHERE $key > '$cod' ORDER BY $key ASC LIMIT 1");


        if($query) {

            $data = $query->fetchAll(PDO::FETCH_ASSOC);

        }


        header('Content-Type: application/json');

        echo json_encode($data);

    }

    public function nextVenta($table, $key, $cod) {


        $query1 = Controller::$connection->query("SELECT * FROM $table WHERE $key > '$cod' ORDER BY $key ASC LIMIT 1");



        if($query1) {

            $data[0] = $query1->fetchAll(PDO::FETCH_ASSOC);

            $id = $data[0][0]["idventa"];

            $query2 = Controller::$connection->query("SELECT * FROM detalle_venta WHERE idventa = '$id'");

            $data[1] = $query2->fetchAll(PDO::FETCH_NUM);

        }

        foreach($data[1] as $key => $value) {

            $itemVenta[$key][0] = $value[2]; // ID
            $itemVenta[$key][1] = $value[3]; // Cantidad
            $itemVenta[$key][2] = $value[4] / $value[3]; // Precio
            $itemVenta[$key][3] = $value[4];   // Subtotal

        }


        $data[1] = $itemVenta;


        header('Content-Type: application/json');

        echo json_encode($data);

    }


    public function nextDevolucion($table, $key, $cod) {


        $query1 = Controller::$connection->query("SELECT * FROM $table WHERE $key > '$cod' ORDER BY $key ASC LIMIT 1");



        if($query1) {

            $data[0] = $query1->fetchAll(PDO::FETCH_ASSOC);

            $id = $data[0][0]["id_devolucion"];

            $query2 = Controller::$connection->query("SELECT * FROM detalle_devolucion WHERE id_devolucion = '$id'");

            $data[1] = $query2->fetchAll(PDO::FETCH_NUM);

        }

        foreach($data[1] as $key => $value) {

            $itemVenta[$key][0] = $value[2]; // ID
            $itemVenta[$key][1] = $value[3]; // Cantidad
            $itemVenta[$key][2] = $value[4] / $value[3]; // Precio
            $itemVenta[$key][3] = $value[4];   // Subtotal

        }


        $data[1] = $itemVenta;


        header('Content-Type: application/json');

        echo json_encode($data);

    }

    public function nextCompra($table, $key, $cod) {


        $query1 = Controller::$connection->query("SELECT * FROM $table WHERE $key > '$cod' ORDER BY $key ASC LIMIT 1");



        if($query1) {

            $data[0] = $query1->fetchAll(PDO::FETCH_ASSOC);

            $id = $data[0][0]["idCompra"];

            $query2 = Controller::$connection->query("SELECT * FROM detalle_Compra WHERE idCompra = '$id'");

            $data[1] = $query2->fetchAll(PDO::FETCH_NUM);

        }

        foreach($data[1] as $key => $value) {

            $itemVenta[$key][0] = $value[2]; // ID
            $itemVenta[$key][1] = $value[3]; // Cantidad
            $itemVenta[$key][2] = $value[4] / $value[3]; // Precio
            $itemVenta[$key][3] = $value[4];   // Subtotal

        }


        $data[1] = $itemVenta;


        header('Content-Type: application/json');

        echo json_encode($data);

    }

    // Login
    public function login($loginData) {


        if(Security::login($loginData)) {


            header('Content-Type: application/json');

            echo json_encode(["OK"]);


        } else {


            header('Content-Type: application/json');

            echo json_encode(["Error"]);

        }


    }

    // Logout
    public function logout() {


        if(Security::logout()) {


            header('Content-Type: application/json');

            echo json_encode(["OK"]);


        } else {


            header('Content-Type: application/json');

            echo json_encode(["Error"]);

        }


    }

    
    // Hacer PDF
    public function getPDF($template) {


       Controller::makePDF($template);


    }

    // Hacer Pago Proveddores
    public function hacerPagoProveedores($table, $data) {


        header('Content-Type: application/json');

        $total = -$data["total_abono"];


        if($this->actualizarSaldoCreditoProveedor($data, $total)) {

            $values = Controller::values($data);

            $query = Controller::$connection->query("INSERT INTO $table $values");

            $insert = Controller::$connection->lastInsertId();


            switch ($data["idFormaPago"]) {

                case '1':
                    //$this->actualizarCaja($data["total_abono"], $data, "ingreso");
                    break;
                case '2':
                    //$this->actualizarCaja($data["total_abono"], $data, "ingreso");
                    break;
                case '3':
                    
                    //$no_CuentaDepo = $data["nocuenta"];

                    //$this->actualizarBancos($data["total_abono"], "ingreso", $no_CuentaDepo);

                    break;
            }


            $output[0] = ["Inserted"];
            $output[1] = [$insert];

            echo json_encode($output);

        }
        else {

            echo json_encode(["proveedor_saldado"]);

        }


    }


    // Hacer Pago
    public function hacerPago($table, $data) {


        header('Content-Type: application/json');

        $total = -$data["total_abono"];


        if($this->actualizarSaldo($data, $total)) {


            $values = Controller::values($data);

            $query = Controller::$connection->query("INSERT INTO $table $values");

            $insert = Controller::$connection->lastInsertId();


            switch ($data["idFormaPago"]) {

                case '1':
                    $this->actualizarCaja($data["total_abono"], $data, "ingreso");
                    break;
                case '2':
                    $this->actualizarCaja($data["total_abono"], $data, "ingreso");
                    break;
                case '3':
                    
                    $no_CuentaDepo = $data["nocuenta"];

                    $this->actualizarBancos($data["total_abono"], "ingreso", $no_CuentaDepo);

                    break;
            }


            $output[0] = ["Inserted"];
            $output[1] = [$insert];

            echo json_encode($output);

        }
        else {

            echo json_encode(["cliente_saldado"]);

        }


    }


     // Hacer Venta
    public function hacerVenta($table, $data, $data_detalle) {


        header('Content-Type: application/json');

            $tipo_venta = $data["idtipo_venta"];

            foreach($data_detalle as $key => $value) {

                $query = Controller::$connection->query("SELECT * FROM producto WHERE idproducto = '$value[0]'");

                $producto = $query->fetch(PDO::FETCH_ASSOC);

                $fecha = $data["idtipo_venta"];

                if($fecha == "") {

                    $output[0] = ["NO_FECHA"];
                    $output[1] = $producto;
    
                    echo json_encode($output);
    
                    return false;
                }

                if($tipo_venta != 3) {
    
                    if(!$this->askExistencia($data, "inventario", "idproducto", $producto["idproducto"])) {

                        $output[0] = ["NO_EXISTENCIA"];
                        $output[1] = $producto;

                        echo json_encode($output);

                        return false;
                    }
            
                }
    
            }

            foreach($data_detalle as $key => $value) {

                $query = Controller::$connection->query("SELECT * FROM producto WHERE idproducto = '$value[0]'");

                $producto = $query->fetch(PDO::FETCH_ASSOC);

                if($tipo_venta != 3) {
    
                    if($this->askExistencia($data, "inventario", "idproducto", $producto["idproducto"])) {

                        $this->manageInventario($producto["idproducto"], $data["fecha"], $value[2], "venta");

                    }

                }
    
            }

                if($data["idFormapago"] == "nothing") {

                    $data["idFormapago"] = 1;
        
                }

                if($data["nocuenta"] == "nothing") {

                    $data["nocuenta"] = "NULL";
        
                }

                if($data["banco"] == "nothing") {

                    $data["banco"] = "NULL";
        
                }


                $values = Controller::values($data);

            
                $query = Controller::$connection->query("INSERT INTO $table $values");

        
                $insert = Controller::$connection->lastInsertId();


                $totalVenta = 0;


                if($tipo_venta == 1) {


                  foreach ($data_detalle as $key => $value) {


                      $values = "('$insert', '$value[0]', $value[2], $value[4])";

                      $query = Controller::$connection->query("INSERT INTO detalle_venta (idventa, idproducto, cantidad, subtotal) VALUES $values");

                      $totalVenta = $totalVenta + $value[4];

                  }

                  $data["motivo"] = "VENTA";


                  if($data["idFormapago"] == 1 || $data["idFormapago"] == 2) {

                    $this->actualizarCaja($totalVenta, $data, "ingreso");

                  }
                  else if($data["idFormapago"] == 3) {

                    $this->actualizarBancos($totalVenta, "ingreso", $data["nocuenta"]);

                  }

               
                }
                else if($tipo_venta == 2) {


                  foreach ($data_detalle as $key => $value) {


                      $values = "('$insert', '$value[0]', $value[2], $value[4])";

                      $query = Controller::$connection->query("INSERT INTO detalle_venta (idventa, idproducto, cantidad, subtotal) VALUES $values");

                      $totalVenta = $totalVenta + $value[4];

                  }

                  $data["motivo"] = "VENTA";

                  $this->actualizarSaldoCredito($data, $totalVenta);

                }
                else if($tipo_venta == 3) {


                  foreach ($data_detalle as $key => $value) {


                      $values = "('$insert', '$value[0]', $value[2], $value[4])";

                      $query = Controller::$connection->query("INSERT INTO detalle_venta (idventa, idproducto, cantidad, subtotal) VALUES $values");

                      $totalVenta = $totalVenta + $value[4];

                  }

                  $data["motivo"] = "PROFORMA";

                }
    

            $output[0] = ["Inserted"];
            $output[1] = [$insert];

            echo json_encode($output);

    }


// Hacer Compra
public function hacerCompra($table, $data, $data_detalle) {


    header('Content-Type: application/json');
     

    foreach($data_detalle as $key => $value) {

        $query = Controller::$connection->query("SELECT * FROM producto WHERE idproducto = '$value[0]'");

        $producto = $query->fetch(PDO::FETCH_ASSOC);

        $mensaje = $this->manageInventario($producto["idproducto"], $data["fecha"], $value[2], "compra");

    }

    if($mensaje === true) {

        if($data["idFormaPago"] == "nothing") {

            $data["idFormaPago"] = "NULL";

        }

        if($data["nocuenta"] == "nothing") {

            $data["nocuenta"] = "NULL";

        }


        $values = Controller::values($data);

    
        $query = Controller::$connection->query("INSERT INTO $table $values");

        $insert = Controller::$connection->lastInsertId();


        $totalCompra = 0;

        $tipo_compra = $data["idTipoCompra"];


        if($tipo_compra == 3) {

            foreach ($data_detalle as $key => $value) {
  
                $values = "('$insert', '$value[0]', $value[2], $value[4])";
  
                $query = Controller::$connection->query("INSERT INTO detalle_compra (idcompra, idproducto, cantidad, precioUnitario) VALUES $values");
  
                $totalCompra = $totalCompra + $value[4];
  
            }
  
              $data["motivo"] = "BODEGA";
  
              if($data["idFormaPago"] == 1) {
  
                  //$this->actualizarCaja($totalCompra, $data, "egreso");
  
              }
              else if($data["idFormaPago"] == 2 || $data["idFormaPago"] == 3) {
  
                  //$this->actualizarBancos($totalCompra, "egreso", $data["nocuenta"]);
  
            }
        }

        if($tipo_compra == 2) {

          foreach ($data_detalle as $key => $value) {

              $values = "('$insert', '$value[0]', $value[2], $value[4])";

              $query = Controller::$connection->query("INSERT INTO detalle_compra (idcompra, idproducto, cantidad, precioUnitario) VALUES $values");

              $totalCompra = $totalCompra + $value[4];

          }

            $data["motivo"] = "COMPRA";

            if($data["idFormaPago"] == 1) {

                //$this->actualizarCaja($totalCompra, $data, "egreso");

            }
            else if($data["idFormaPago"] == 2 || $data["idFormaPago"] == 3) {

                //$this->actualizarBancos($totalCompra, "egreso", $data["nocuenta"]);

            }
        }

        else if($tipo_compra == 1) {

          foreach ($data_detalle as $key => $value) {


              $values = "('$insert', '$value[0]', $value[2], $value[4])";

              $query = Controller::$connection->query("INSERT INTO detalle_compra (idcompra, idproducto, cantidad, precioUnitario) VALUES $values");

              $totalCompra = $totalCompra + $value[4];
              
          }

          $this->actualizarSaldoCreditoProveedor($data, $totalCompra);

        }

        $output[0] = ["Inserted"];
        $output[1] = [$insert];

        echo json_encode($output);

    }

 }


// Hacer Devolucion

public function hacerDevolucion($table, $data, $data_detalle) {

        header('Content-Type: application/json');

               $values = Controller::values($data);

               $query = Controller::$connection->query("INSERT INTO $table $values");

               $insert = Controller::$connection->lastInsertId();

               $totalDevolucion = 0;

               foreach ($data_detalle as $key => $value) {

                   $values = "('$insert', '$value[0]', $value[1], $value[3])";

                   $query = Controller::$connection->query("INSERT INTO detalle_devolucion (id_devolucion, idproducto, cantidad, subtotal) VALUES $values");

                   $this->manageInventario($value[0], $data["fecha"], $value[1], "devolucion");
                   
                   $totalDevolucion = $totalDevolucion + $value[3];

               }

               $idVentaDevolucion = $data["idventa"];

               $data["motivo"] = "DEVOLUCION";

               $query = Controller::$connection->query("SELECT * FROM venta WHERE idventa = '$idVentaDevolucion'");

               $dataVentaDevolucion = $query->fetchAll(PDO::FETCH_ASSOC);

                if($dataVentaDevolucion[0]["idtipo_venta"] == 1) {

                    $this->actualizarCaja($totalDevolucion, $data, "egreso");

                }

               $output[0] = ["Inserted"];
               $output[1] = [$insert];


               echo json_encode($output);

    }

    // Actualiza Saldo del Cliente por medio de un pago
    public function actualizarSaldo($param) {


        $idcliente = $param["idcliente"];

        $total_abono = $param["total_abono"];



        $query = Controller::$connection->query("SELECT saldo FROM cliente WHERE idcliente = '$idcliente'");

        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        $saldo = $data[0]["saldo"];


        if(($saldo - $total_abono) >= 0 ) {

             $query = Controller::$connection->query("UPDATE cliente as C SET C.saldo  = C.saldo - $total_abono WHERE C.idcliente = '$idcliente'");

             return true;

        }
        else {

            return false;
        }


        header('Content-Type: application/json');


    }

    // Actualiza Saldo del Provedor
    public function actualizarSaldoCreditoProveedor($param, $total) {

        $idcliente = $param["idProveedor"];
    
        $query = Controller::$connection->query("SELECT * FROM proveedor WHERE idProveedor = '$idcliente'");
    
        $query = $query->fetchAll(PDO::FETCH_ASSOC);

    
        if(-$total <= $query[0]["saldo"]) {
    
            $query = Controller::$connection->query("UPDATE proveedor as P SET P.saldo  = P.saldo + $total WHERE P.idProveedor = '$idcliente'");
    
            return true;
        }
        else {
    
            return false;
    
        }
    
    }

    public function actualizarSaldoCredito($param, $total) {

    $idcliente = $param["idcliente"];

    $query = Controller::$connection->query("SELECT * FROM cliente WHERE idcliente = '$idcliente'");

    $query = $query->fetchAll(PDO::FETCH_ASSOC);

    if(-$total <= $query[0]["saldo"]) {

        $query = Controller::$connection->query("UPDATE cliente as C SET C.saldo  = C.saldo + $total WHERE C.idcliente = '$idcliente'");

        return true;
    }
    else {

        return false;

    }


    }

    public function actualizarCaja2($monto, $operacion, $params) {

        $fecha_pago = $params["fecha"];
        $total_pago = $params["total"];
        $motivo_pago = $params["motivo"];

        if($operacion == "ingreso") {

            $queryCaja = Controller::$connection->query("SELECT SALDO FROM CAJA ORDER BY ID DESC LIMIT 1");
            $total_saldo_caja = $queryCaja->fetch();


            $queryCaja = Controller::$connection->query("INSERT INTO CAJA (FECHA, INGRESO, MOTIVO, SALDO) VALUES('$fecha_pago',$total_pago,'$motivo_pago', $total_saldo_caja[0] + $total_pago)");
            return true;

        }
        else if($operacion == "egreso") {

            $queryCaja = Controller::$connection->query("SELECT SALDO FROM CAJA ORDER BY ID DESC LIMIT 1");
            $total_saldo_caja = $queryCaja->fetch();
          
            $queryCaja = Controller::$connection->query("INSERT INTO CAJA (FECHA, RETIRO, MOTIVO, SALDO) VALUES('$fecha_pago',$total_pago,'$motivo_pago', $total_saldo_caja[0] - $total_pago)");
            
            return true;

        }

    }

    public function askCajaIsOpen() {

        $cajaAsk = Controller::$connection->query("SELECT * FROM caja_estado WHERE ID = 1");

        if($cajaAsk) {

            $dataCajaAsk = $cajaAsk->fetch(PDO::FETCH_ASSOC);
   
            if($dataCajaAsk["ESTADO"] == "CERRADA") {

                return false;
            }

            if($dataCajaAsk["ESTADO"] == "ABIERTA") {

                return true;

            }

        }
        else {

            return false;
        }

    }


    // Actualiza Saldo de la Caja
    public function actualizarCaja($param, $data, $type) {

        header('Content-Type: application/json');

        $query = Controller::$connection->query("SELECT * FROM caja ORDER BY id DESC LIMIT 1");

        $dataCaja = $query->fetchAll(PDO::FETCH_ASSOC);
   
            $saldo = $dataCaja[0]["saldo"];
            $fecha = $data["fecha"];
            $motivo = "";

            if(!isset($data["motivo"])) {

                $motivo = "";
            }
            else {

                $motivo = $data["motivo"];
            }

        if($type == "ingreso") {

            $query = Controller::$connection->query("INSERT INTO caja (fecha, ingreso, saldo, motivo) VALUES ('$fecha', $param, $saldo + $param, '$motivo')");

        }
        else if($type == "egreso") {

            $query = Controller::$connection->query("INSERT INTO caja (fecha, retiro, saldo, motivo) VALUES ('$fecha', $param, $saldo - $param, '$motivo')");

        }

        else if($type == "retiro") {

          $motivo = $param["motivo"];

          $monto = $param["monto"];

          date_default_timezone_set('America/Guatemala');

          $date = date("Ymd");

          if($saldo >= $monto) {

            $query = Controller::$connection->query("INSERT INTO caja (fecha, retiro, saldo, motivo) VALUES ($date, $monto, $saldo - $monto, '$motivo')");

            echo json_encode(["Inserted"]);

          }
          else {

            echo json_encode(["Error_monto"]);

          }


        }

        else if($type == "deposito") {

            $motivo = $param["motivo"];
  
            $monto = $param["monto"];
  
            date_default_timezone_set('America/Guatemala');
  
            $date = date("Ymd");


            $query = Controller::$connection->query("INSERT INTO caja (fecha, ingreso, saldo, motivo) VALUES ($date, $monto, $saldo + $monto, '$motivo')");

            echo json_encode(["Inserted"]);
  
          }


        if($query) {

            $data = $query->fetchAll(PDO::FETCH_ASSOC);

        }

    }

    public function askExistencia($data, $table, $key, $cod) {

        $existencia = Controller::$connection->query("SELECT * FROM $table AS I RIGHT JOIN producto AS P on P.idproducto = I.idproducto WHERE P.$key = '$cod' ORDER BY I.idInventario DESC LIMIT 1");
        
        $exist = $existencia->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');

        return $exist;

    }


    public function savePhoto() {


        if(isset($_POST['data']) && !isset($_POST['id'])) {

            header('Content-Type: application/json');

            $id = Controller::$connection->query('SELECT * FROM cliente ORDER BY idcliente DESC LIMIT 1');
            $id = $id->fetchAll(PDO::FETCH_ASSOC);

            $img = $_POST['data'];

            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);


            $file = "../assets/pictures/cliente/".$id[0]['idcliente'].".jpg";
            $success = file_put_contents($file, $data);

            echo json_encode("OK");

        }

        if(isset($_POST['id'])) {
        
        
            $id = $_POST['id'];
        
            $img = $_POST['data'];
        
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
        
        
            $file = "../assets/pictures/cliente/".$id.".jpg";
            $success = file_put_contents($file, $data);
        
            echo json_encode("OK");
        
        
        }


    }

    // Carga de Gas
    public function cargaGas($table, $data) {


        $values = Controller::values($data);

        $query = Controller::$connection->query("INSERT INTO $table $values");


        header('Content-Type: application/json');


        if($query) {

            $this->cambiarExistencia($table, $data);

            echo json_encode(["Inserted"]);

            $this->actualizarCaja($data["precio_total"], $data, "egreso");

        }
        else {

            echo json_encode(["Not Inserted"]);

            print_r(Controller::$connection->errorInfo());

        }


    }


    // Cambia la existencia de un producto
    public function cambiarExistencia($table, $param) {


        $idproducto = $param["idproducto"];

        $cantidad = $param["cantidad"];



        $query = Controller::$connection->query("UPDATE producto as P SET P.cantidad = P.cantidad + $cantidad WHERE P.idproducto = '$idproducto'");

        if($query) {

            $data = $query->fetchAll(PDO::FETCH_ASSOC);

        }


        header('Content-Type: application/json');

    }


    public function retiroCaja($data) {

        header('Content-Type: application/json');

        $param["fecha"] = "curdate()";

        $this->actualizarCaja($data, $param, "retiro");

    }

    public function depositoCaja($data) {

        header('Content-Type: application/json');

        $param["fecha"] = "curdate()";

        $this->actualizarCaja($data, $param, "deposito");

    }


    public function getChartDataAPI($data) {


        header('Content-Type: application/json');
        
        
        $data = Controller::$connection->query($data);

        $data = $data->fetchAll(PDO::FETCH_NUM);

        $i = 0;

        echo json_encode($data, JSON_NUMERIC_CHECK);

    }


    public function manageInventario($id_producto, $fecha, $cantidad, $tipo_movimiento) {

        
        switch($tipo_movimiento) {
            
            case "compra":

                $query = Controller::$connection->query("SELECT * FROM producto WHERE idproducto = '$id_producto'");
            
                if($query) {

                    // Datos Producto
                    $dataProducto = $query->fetch(PDO::FETCH_ASSOC);

                    $query = Controller::$connection->query("SELECT * FROM inventario WHERE idproducto = '$id_producto' ORDER BY idInventario DESC LIMIT 1");
                   
                    // Datos Inventario
                    $dataInventario = $query->fetch(PDO::FETCH_ASSOC);

                    $existencia = 0;

                    if($dataInventario) {
                        $existencia = $dataInventario["existencia"];
                    }

                    // Nueva Existencia
                    $existencia = $existencia + $cantidad;

                    $query = Controller::$connection->query("INSERT INTO inventario (idproducto, fecha, ingreso, tipoMovimiento, existencia) VALUES('$id_producto', '$fecha', $cantidad, 'Compra', $existencia)");

       
                    return true;

                }
                else {

                    print_r(Controller::$connection->errorInfo());
                    return false;
                }

            break;
            case "venta":


            $query = Controller::$connection->query("SELECT * FROM producto WHERE idproducto = '$id_producto'");
            

            if($query) {

                // Datos Producto
                $dataProducto = $query->fetch(PDO::FETCH_ASSOC);

                $query = Controller::$connection->query("SELECT * FROM inventario WHERE idproducto = '$id_producto' ORDER BY idInventario DESC LIMIT 1");
               
                // Datos Inventario
                $dataInventario = $query->fetch(PDO::FETCH_ASSOC);

    
                $existencia = $dataInventario["existencia"];

                if($existencia >= $cantidad) {

                    // Nueva Existencia
                    $existencia = $existencia - $cantidad;

                    $query = Controller::$connection->query("INSERT INTO inventario (idproducto, fecha, egreso, tipoMovimiento, existencia) VALUES('$id_producto', '$fecha', $cantidad, 'Venta', $existencia)");

                    return true;
                }
                else {

                    echo json_encode("['producto_no_existencia']");
                    return false;
    
                }
               
            }
            else {

                print_r(Controller::$connection->errorInfo());
                return false;

            }

            break;
            case "devolucion":

            $query = Controller::$connection->query("SELECT * FROM producto WHERE idproducto = $id_producto");
        
            if($query) {

                // Datos Producto
                $dataProducto = $query->fetch(PDO::FETCH_ASSOC);

                $query = Controller::$connection->query("SELECT * FROM inventario WHERE idproducto = $id_producto ORDER BY idInventario DESC LIMIT 1");
               
                // Datos Inventario
                $dataInventario = $query->fetch(PDO::FETCH_ASSOC);

                $existencia = $dataInventario["existencia"];

                // Nueva Existencia
                $existencia = $existencia + $cantidad;

                $query = Controller::$connection->query("INSERT INTO inventario (idproducto, fecha, ingreso, tipoMovimiento, existencia) VALUES('$id_producto', '$fecha', $cantidad, 'Devolucion', $existencia)");

   
                return true;

            }
            else {

                print_r(Controller::$connection->errorInfo());
                return false;
            }

        break;
       
        }

    }

    // Cambia saldo de un banco hecho por depósitos
    public function hacerDeposito($table, $param) {

        $totalDepo = $param["total"];

        $no_CuentaDepo = $param["nocuenta"];

        $this->actualizarBancos($totalDepo, "ingreso", $no_CuentaDepo);

        $this->actualizarCaja2($param["total"], "egreso", $param);
        
    }

    // Set Estado Caja
    public function setEstadoCaja($params) {

        $estado = $params["ESTADO"];

        header('Content-Type: application/json');

        $query = Controller::$connection->query("UPDATE caja_estado SET ESTADO = '$estado' WHERE ID = 1");
           
        if($query) {

            echo json_encode("OK");

        }
        
    }

    // get Estado Caja
    public function getEstadoCaja() {

        header('Content-Type: application/json');

        $query = Controller::$connection->query("SELECT * FROM caja_estado WHERE ID = 1");
           
        if($query) {

            $data = $query->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($data);

        }

    }

    // get Saldo Caja
    public function getSaldoCaja() {

        header('Content-Type: application/json');

        $queryCaja = Controller::$connection->query("SELECT * FROM CAJA ORDER BY ID DESC LIMIT 1");
      
        if($queryCaja) {

            $total_saldo_caja = $queryCaja->fetch(PDO::FETCH_ASSOC);
            echo json_encode($total_saldo_caja);

        }

    }

    // Send Mail Cierre
    public function sendMailCierre() {

        $queryCierre = Controller::$connection->query("SELECT * FROM CAJA 
        ORDER BY ID DESC LIMIT 1
        ");


        $this->getPDF("reporte_cierre");


        if($queryCierre) {

            $mail = new PHPMailer(true);

        try {

            //Server settings
            $mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP();   
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->CharSet = "utf-8";   // set charset to utf8                                       // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'copicentroguastatoya2020@gmail.com';                     // SMTP username
            $mail->Password   = 'copicentro2020';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = '587';                                    // TCP port to connect to
        
            //Recipients
            $mail->setFrom('copicentroguastatoya2020@gmail.com', 'Sistema AGROSERVICIO EL REGADILLO');

            $mail->addAddress('roxanaalonzo131@gmail.com', 'Karla Alonzo');     // Add a recipient
            $mail->addAddress('copicentroguastatoya2020@gmail.com', 'Backup');     // Add a recipient

            // Attachments
            $mail->addAttachment(__DIR__ .'\..\assets\layouts\reports\Cierre_Hoy.pdf', 'Cierre de Hoy.pdf');    // Optional name
        
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Cierre del Día';
            $mail->Body    = 'Adjunto un .PDF con la información del cierre diario</b>Generado automáticamente por el sistema. Vid@Online Software.';
        
            $mail->send();
        } catch (Exception $e) {


        }

        }

    }

}


if(isset($_POST["data"]) && isset($_GET["action"])) {


        $data = $_POST["data"];

        if(isset($_POST["table"])) {

            $table = $_POST["table"];

            $key = $_POST["key"];

            $cod = $_POST["cod"];

        }

        $action = $_GET["action"];


        $template = "default";


        if(isset($_GET["template"]) && $_GET["template"] != 'undefined') {

                $template = $_GET["template"];
            }

        if(isset($_POST["data_detalle"]) && $_POST["data_detalle"] != 'undefined') {

                $data_detalle = $_POST["data_detalle"];
        }


            $request = new Api();


            switch ($action) {


                case 'all':

                    $request->allRegistries($table, $key);

                    break;
                case 'one':

                    $request->oneRegistry($table, $key, $cod);

                    break;
                case 'create':

                    $request->create($table, $data);

                    break;
                case 'update':

                    $request->update($table, $key, $cod, $data);

                    break;
                case 'delete':

                    $request->delete($table, $key, $cod);

                    break;
                case 'prev':

                    $request->prev($table, $key, $cod);

                    break;
                case 'next':

                    $request->next($table, $key, $cod);

                    break;
                case 'print':

                    $request->getPDF($template);

                    break;

                case 'login':

                    $request->login($data);

                    break;
                case 'logout':

                    $request->logout();

                    break;
                case 'hacerPago':

                    if($request->askCajaIsOpen()) {

                        $request->hacerPago($table, $data);

                    }
                    else {

                        echo json_encode(["CAJA_CERRADA"]);
                    }

                    break;
                case 'hacerPagoProveedor':

                    if($request->askCajaIsOpen()) {

                        $request->hacerPagoProveedores($table, $data);

                    }
                    else {

                        echo json_encode(["CAJA_CERRADA"]);
                    }

                    break;    
                case 'hacerVenta':

                    if($request->askCajaIsOpen()) {

                        $request->hacerVenta($table, $data, $data_detalle);

                    }
                    else {

                        echo json_encode(["CAJA_CERRADA"]);
                    }

                    break;
                case 'hacerCompra':

                    if($request->askCajaIsOpen()) {

                        $request->hacerCompra($table, $data, $data_detalle);

                    }
                    else {

                        echo json_encode(["CAJA_CERRADA"]);
                    }

                    break;
                case 'addItemVenta':

                    $request->addItemVenta($table, $key, $cod, $data);

                break;
                case 'oneVenta':

                    $request->oneRegistryVenta($table, $key, $cod, $data);

                break;
                case 'oneCompra':

                    $request->oneRegistryCompra($table, $key, $cod, $data);

                break;
                case 'hacerDevolucion':

                    if($request->askCajaIsOpen()) {

                        $request->hacerDevolucion($table, $data, $data_detalle);

                    }
                    else {

                        echo json_encode(["CAJA_CERRADA"]);
                    }

                    break;
                case 'addItemDevolucion':

                    $request->addItemDevolucion($table, $key, $cod, $data);

                break;
                case 'addItemCompra':

                    $request->addItemCompra($table, $key, $cod, $data);

                break;
                case 'oneDevolucion':

                    $request->oneRegistryDevolucion($table, $key, $cod, $data);

                break;
                case 'savePhoto':

                    $request->savePhoto($data);

                break;
                case 'nextVenta':

                    $request->nextVenta($table, $key, $cod);

                break;
                case 'nextCompra':

                    $request->nextCompra($table, $key, $cod);

                break;
                case 'hacerGasto':

                    if($request->askCajaIsOpen()) {

                        $request->hacerGasto($table, $data);

                    }
                    else {

                        echo json_encode(["CAJA_CERRADA"]);
                    }

                break;
                case 'prevVenta':

                    $request->prevVenta($table, $key, $cod);

                break;
                case 'prevCompra':

                    $request->prevCompra($table, $key, $cod);

                break;
                case 'nextDevolucion':

                    $request->nextDevolucion($table, $key, $cod);

                break;
                case 'prevDevolucion':

                    $request->prevDevolucion($table, $key, $cod);

                break;
                case 'retiroCaja':

                    if($request->askCajaIsOpen()) {

                        $request->retiroCaja($data);

                    }
                    else {

                        echo json_encode(["CAJA_CERRADA"]);
                    }

                break;
                case 'depositoCaja':

                    if($request->askCajaIsOpen()) {

                        $request->depositoCaja($data);

                    }
                    else {

                        echo json_encode(["CAJA_CERRADA"]);
                    }

                break;
                case 'getChartData':

                    $request->getChartDataAPI($data);

                break;
                case 'updatePic':

                    $request->savePhoto($data);

                break;
                case 'askExistencia':

                    echo json_encode($request->askExistencia($data, $table, $key, $cod));

                break;
                case 'updateProducto':

                    $request->updateProducto($table, $key, $cod, $data);

                break;
                case 'addProducto':

                    $request->addProducto($table, $data);

                break;
                case 'hacerDeposito':

                    if($request->askCajaIsOpen()) {

                        $request->hacerDeposito($table, $data);

                    }
                    else {

                        echo json_encode(["CAJA_CERRADA"]);
                    }

                break;
                case 'getStatusCaja':

                    $request->getEstadoCaja();

                break;
                case 'setStatusCaja':

                    $request->setEstadoCaja($data);

                break;
                case 'getSaldoCaja':

                    $request->getSaldoCaja();

                break;
                case 'sendMailCierre':

                    $request->sendMailCierre();

                break;
                

        }

}
