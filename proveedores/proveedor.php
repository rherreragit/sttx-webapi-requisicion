<?php
    include('../config.php');
    $request_method = $_SERVER["REQUEST_METHOD"];
    $dirip = $_SERVER["REMOTE_ADDR"];

    switch($request_method)
    {
        case 'GET':
            //Metodo GET para obtener listado de proveedores
            if(isset($_GET['dominio'],$_GET['tipo'],$_GET['tipo2'])){
               $respuesta = obtenerProveedores($_GET['dominio'],$_GET['tipo'],$_GET['tipo2'],$ip);
			   echo $respuesta;
            }
            //Metodo GET para obtener impuesto de proveedor
            if(isset($_GET['dominio'],$_GET['proveedor'])){
               $respuesta = obtenerImpuesto($_GET['dominio'],$_GET['proveedor'],$ip);
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


    FUNCTION obtenerProveedores($dominio,$tipo,$tipo2,$ip){
        $proveedorSOAP = new SoapClient($ip.'=urn:molino');
        $request = array(
                    "itype"=>   $tipo,
                    "itype1" => $tipo2
                    );
        $response = $proveedorSOAP->wsgetprov($request);
        $data = $response->vrespuesta;
       
        return $data;
    }
     
    
    FUNCTION obtenerImpuesto($dominio,$proveedor,$ip){
        $proveedorSOAP = new SoapClient($ip.'=urn:requisiciones');
        $request = array(
                    "idomain" => $dominio,
                    "iproveedor"=>   $proveedor                    
                    );
        $response = $proveedorSOAP->wsreqprov($request);
         $data = json_encode($response);

        return $data;
    }     
