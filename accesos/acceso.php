<?php
    include('../config.php');
    $request_method = $_SERVER["REQUEST_METHOD"];

    switch($request_method)
    {
        case 'GET':
            //Metodo GET para obtener
            IF(isset($_GET['path']) != "" && isset($_GET['user']) != "" && isset($_GET['domain']) != ""){
                $respuesta = revisarPermisos($_GET['user'],$_GET['domain'],$_GET['path'],$ip);
				echo $respuesta;
            }
            ELSE IF (isset($_GET['user']) != "" && isset($_GET['domain']) != "") {
                $respuesta = obtenerAccesos($_GET['user'],$_GET['domain'],$ip);
                echo $respuesta;
            }
            ELSE IF
           //Metodo GET para validar Usuario
            (isset($_GET['usuario'])){
               $respuesta = validaUsuario($_GET['usuario'],$ip);
               echo $respuesta;
            }
            ELSE{
                $respuesta = new stdClass();
                $respuesta->error_code = 500;
                $respuesta->error_description = "Sin respuesta identificada para la configuracion de parametros del cliente";
                echo json_encode($respuesta);
            }

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


    FUNCTION obtenerAccesos($user,$domain,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:acceso');
        $request = array(
                    "iuser"=>  $user,
                    "idomain" => $domain
                    );
        $response = $clienteSOAP->wsmenuuser01($request);
        return $response->vrespuesta;
    }


    FUNCTION revisarPermisos($user,$domain,$path,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:acceso2');
        $request = array(
                    "iuser"=>  $user,
                    "idomain" => $domain,
					          "ipath" => $path
                    );
        $response = $clienteSOAP->wsaccesscheck($request);
        return $response->oacceso;
    }

    FUNCTION validaUsuario($usuario,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:acceso');
        $request = array(
                        "iuserid" => $usuario
                        );
        $response = $clienteSOAP->wsctusr01($request);
        return $response->vrespuesta;
    }
