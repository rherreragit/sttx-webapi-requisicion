<?php
    include('../config.php');
    $request_method = $_SERVER["REQUEST_METHOD"];
    $dirip = $_SERVER["REMOTE_ADDR"];

    switch($request_method)
    {
        case 'GET':
            //Metodo GET para obtener Partes
            if(isset($_GET['dominio'],$_GET['parte_1'],$_GET['parte_2'],$_GET['lp_1'],$_GET['lp_2'])){
               $respuesta = obtenerPartes($_GET['dominio'],$_GET['parte_1'],$_GET['parte_2'],$_GET['lp_1'],$_GET['lp_2'],$ip);
		       echo $respuesta;
            }
            //Metodo GET para obtener Lineas de Producto
            if(isset($_GET['domain'])){
               $respuesta = obtenerLnprod($_GET['domain'],$ip);
               echo $respuesta;
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
    }


    FUNCTION obtenerPartes($dominio,$parte_1,$parte_2,$lp_1,$lp_2,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:articulos');
        $request = array(
                        "idomain" => $dominio,
                        "iparte_1" => $parte_1,
                        "iparte_2" => $parte_2,
                        "ilp_1" => $lp_1,
                        "ilp_2" => $lp_2
                        );
        $response = $clienteSOAP->wsconpar01($request);
        $data = json_encode($response->tt_out_partes);

        return $data;
    }

    FUNCTION obtenerLnprod($dominio,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:articulos');
        $request = array(
                        "idomain" => $dominio
                        );
        $response = $clienteSOAP->wsconlnprod($request);
        $data = json_encode($response->tt_out_lnprod);

        return $data;
    }
