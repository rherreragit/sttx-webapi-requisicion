<?php
    include('../config.php');
    $request_method = $_SERVER["REQUEST_METHOD"];
    $dirip = $_SERVER["REMOTE_ADDR"];

    switch($request_method)
    {
        case 'GET':
            //Metodo GET para obtener codigos generalizados
            if(isset($_GET['dominio'],$_GET['iClave'],$_GET['iSubclave'])){
            $respuesta = getcodemstr($_GET['dominio'],$_GET['iClave'],$_GET['iSubclave'],$ip);
            echo $respuesta;
            }
            //Metodo GET para obtener Tipo de Cambio
            if(isset($_GET['dominio'],$_GET['iFecha'])){
               $respuesta = obtenerExchangeRate($_GET['dominio'],$_GET['iFecha'],$ip);
               echo $respuesta;
            }
            break;
        case 'POST':
            //Metodo GET
            echo "Metodo POST";
            break;
        case 'PUT':
            //Metodo GET
            echo "Metodo PUT";
            break;
        case 'DELETE':
            //Metodo GET
            echo "Metodo DELETE";
            break;

    }


    FUNCTION getcodemstr($dominio,$iClave,$iSubclave,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:utilerias');
        $request = array(
                    "idomain" => $dominio,
                    "iclave"=> $iClave,
                    "isubclave"=> $iSubclave                   
                    );
        $response = $clienteSOAP->wscodemstr($request);
        $data = $response->vrespuesta;                 
        return $data;
    }

    FUNCTION obtenerExchangeRate($dominio,$iFecha,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:utilerias');
        $request = array(
                    "idomain" => $dominio,
                    "ifecha"=> $iFecha       
                    );
        $response = $clienteSOAP->wsgetexrate($request);
        $data = json_encode(array('ostatus' =>  $response->ostatus,'ostatus_desc' =>  $response->ostatus_desc, 'otipo_cambio' =>  $response->otipo_cambio)); 
        return $data;
    }

?>
