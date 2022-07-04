<?php
    include('../config.php');
    $request_method = $_SERVER["REQUEST_METHOD"];
    $dirip = $_SERVER["REMOTE_ADDR"];

    switch($request_method)
    {
        case 'GET':
            //Metodo GET para obtener 
            $respuesta = obtenerSesion($_GET['user'],$_GET['dominio'],$_GET['sid'],$ip,$dirip);
            echo $respuesta;
            break;
        case 'POST':
            //Metodo POST para guardar
            echo "Metodo POST";
            break;
        case 'PUT':
            //Metodo PUT para actualizar
            echo "Metodo PUT";
            break;
        case 'DELETE':
            //Metodo DELETE para borrar
            echo "Metodo DELETE";
            break;
        default:
            header("HTTP/1.0 405 Method Not Allowed");
            echo "Invalid Method";
            break;
    }


    FUNCTION obtenerSesion($user,$dominio,$sid,$ip,$dirip){
        $clienteSOAP = new SoapClient($ip.'=urn:acceso2');
        $request = array(
                    "iuserid"=>  $user,
                    "idomain" => $dominio
                    );
        $response = $clienteSOAP->wsconusrang($request);
        $data = $response->vrespuesta;
        $arr=json_decode($data,true);
        $respuesta=$arr['root']['Usuarios'][0];
        $sesionid= $respuesta['SesionId'];
        $ipsession = $respuesta['ip'];
        if($sesionid == trim($sid, " ") && trim($dirip," ") == trim($ipsession," ")){
            $respuesta= "true";
        }
        else{
            $respuesta= "false";
        }
                   
        return $respuesta;
    }
     
    
     
