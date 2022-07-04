<?php
    include('../config.php');
    $request_method = $_SERVER["REQUEST_METHOD"];
    $dirip = $_SERVER["REMOTE_ADDR"];

    switch($request_method)
    {
        case 'GET':
            //Metodo GET para obtener Catalogo
            if(isset($_GET['dominio'],$_GET['parte1'],$_GET['parte2'],$_GET['lp1'],$_GET['type'])){
               $respuesta = obtenerCatalogo($_GET['dominio'],$_GET['parte1'],$_GET['parte2'],$_GET['lp1'],$_GET['type'],$ip);
		       echo $respuesta;
            }
            //Metodo GET para obtener Aprobador
            if(isset($_GET['dominio'],$_GET['entidad'])){
               $respuesta = obtenerAprobador($_GET['dominio'],$_GET['entidad'],$ip);
               echo $respuesta;
            }
            //Metodo GET para agregar Parte
            if(isset($_GET['dominio'],$_GET['entidadx'],$_GET['proveedor'],$_GET['parte'])){
               $respuesta = AgregarParte($_GET['dominio'],$_GET['entidadx'],$_GET['proveedor'],$_GET['parte'],$ip);
               echo $respuesta;
            }
            //Metodo GET para buscar Requisicion
            if(isset($_GET['dominio'],$_GET['requisicion'])){
               $respuesta = buscarRequisicion($_GET['dominio'],$_GET['requisicion'],$ip);
               echo $respuesta;
            }
            //Metodo GET para Consultar Requisicion
            if(isset($_GET['dominio'],$_GET['Req_number'])){
               $respuesta = consultarRequisicion($_GET['dominio'],$_GET['Req_number'],$ip);
               echo $respuesta;
            }
            //Metodo GET para Pendientes Requisicion
            if(isset($_GET['dominio'],$_GET['entidady'],$_GET['usuario'])){
               $respuesta = pendientesRequisicion($_GET['dominio'],$_GET['entidady'],$_GET['usuario'],$ip);
               echo $respuesta;
            }            
            break;
        case 'POST':
            //Metodo POST para guardar Catalogo
            if(isset($_POST['dominio'],$_POST['usuario'],$_POST['gridjson'])){
                $respuesta = saveCatalogo($_POST['dominio'],$_POST['usuario'],$_POST['gridjson'],$ip);
                echo $respuesta;
            }
            //Metodo POST para guardar Aprobador
            if(isset($_POST['dominio'],$_POST['usuario'],$_POST['entidad'],$_POST['gridjson2'])){
                $respuesta = saveAprobador($_POST['dominio'],$_POST['usuario'],$_POST['entidad'],$_POST['gridjson2'],$ip);
                echo $respuesta;
            }
            //Metodo POST para guardar Requisicion
            if(isset($_POST['dominio'],$_POST['usuario'],$_POST['accion'],$_POST['requisicion'],$_POST['entidad'],$_POST['descripcion'],$_POST['proveedor'],$_POST['gridjson_lista'])){
                $respuesta = saveRequisicion($_POST['dominio'],$_POST['usuario'],$_POST['accion'],$_POST['requisicion'],$_POST['entidad'],$_POST['descripcion'],$_POST['proveedor'],$_POST['gridjson_lista'],$ip);
                echo $respuesta;
            }
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


    FUNCTION obtenerCatalogo($dominio,$parte1,$parte2,$lp1,$type,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:requisiciones');
        $request = array(
                        "idomain" => $dominio,
                        "iparte_1" => $parte1,
                        "iparte_2" => $parte2,
                        "ilp_1" => $lp1,
                        "itype" => $type
                        );
        $response = $clienteSOAP->wsconreqc($request);
        $data = json_encode($response->tt_out_wsreqc);

        return $data;
    }

    FUNCTION saveCatalogo($dominio,$usuario,$gridjson,$ip){
        $Catalogo = json_decode($gridjson);
        $acum = array();

        for($i=0; $i < count($Catalogo); $i++){
            $row = array(
                    "ttwsreqc_in_parte"=>$Catalogo[$i]->cat_parte,
                    "ttwsreqc_in_lnprod"=>$Catalogo[$i]->cat_lnprod,
                    "ttwsreqc_in_status"=>$Catalogo[$i]->cat_status,
                    "ttwsreqc_in_cuenta"=>$Catalogo[$i]->cat_cuenta,
                    "ttwsreqc_in_subcuenta"=>$Catalogo[$i]->cat_subcuenta,
                    "ttwsreqc_in_cc"=>$Catalogo[$i]->cat_cc
            );
            array_push($acum,$row);
        }

        $tt_in_wsreqcDet = array("tt_in_wsreqcRow"=>$acum);

        $clienteSOAP = new SoapClient($ip.'=urn:requisiciones');
        $request = array(
                    "idomain" => $dominio,
                    "iuser"=>  $usuario,
                    "tt_in_wsreqc" => $tt_in_wsreqcDet
                    );
        $response = $clienteSOAP->wssavereqc($request);
        return json_encode($response);
    }

    FUNCTION obtenerAprobador($dominio,$entidad,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:requisiciones');
        $request = array(
                        "idomain" => $dominio,
                        "ientidad" => $entidad
                        );
        $response = $clienteSOAP->wsconreqn($request);
        $data = json_encode($response->tt_out_wsreqn);

        return $data;
    }

    FUNCTION saveAprobador($dominio,$usuario,$entidad,$gridjson2,$ip){
        $Aprobador = json_decode($gridjson2);
        $acum = array();

        for($i=0; $i < count($Aprobador); $i++){
            $row = array(
                    "ttwsreqn_in_entidad"=>$Aprobador[$i]->apr_entidad,
                    "ttwsreqn_in_nivel"=>$Aprobador[$i]->apr_nivel,
                    "ttwsreqn_in_usuario"=>$Aprobador[$i]->apr_usuario,
                    "ttwsreqn_in_monto_mn"=>$Aprobador[$i]->apr_monto_mn,
                    "ttwsreqn_in_monto_usd"=>$Aprobador[$i]->apr_monto_usd,
                    "ttwsreqn_in_tc"=>$Aprobador[$i]->apr_tc,
                    "ttwsreqn_in_activo"=>$Aprobador[$i]->apr_activo,
                    "ttwsreqn_in_apr_usuario"=>$Aprobador[$i]->apr_apr_usuario,
                    "ttwsreqn_in_apr_fecha"=>$Aprobador[$i]->apr_apr_fecha,
                    "ttwsreqn_in_apr_hora"=>$Aprobador[$i]->apr_apr_hora
            );
            array_push($acum,$row);
        }

        $tt_in_wsreqnDet = array("tt_in_wsreqnRow"=>$acum);

        $clienteSOAP = new SoapClient($ip.'=urn:requisiciones');
        $request = array(
                    "idomain" => $dominio,
                    "iuser"=>  $usuario,
                    "ientidad"=>  $entidad,
                    "tt_in_wsreqn" => $tt_in_wsreqnDet
                    );
        $response = $clienteSOAP->wssavereqn($request);
        return json_encode($response);
    }

    FUNCTION AgregarParte($dominio,$entidadx,$proveedor,$parte,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:requisiciones');
        $request = array(
                        "idomain" => $dominio,
                        "ientidad" => $entidadx,
                        "iproveedor" => $proveedor,
                        "ipart" => $parte
                        );
        $response = $clienteSOAP->wsreqpart($request);
        $data = json_encode($response);

        return $data;
    }

    FUNCTION saveRequisicion($dominio,$usuario,$accion,$requisicion,$entidad,$descripcion,$proveedor,$gridjson_lista,$ip){
        $Detalle_Req = json_decode($gridjson_lista);
        $acum = array();

        for($i=0; $i < count($Detalle_Req); $i++){
            $row = array(
                    "ttreqd_in_requisicion"=>$Detalle_Req[$i]->reqd_requisicion,
                    "ttreqd_in_parte"=>$Detalle_Req[$i]->reqd_parte,
                    "ttreqd_in_parte_desc"=>$Detalle_Req[$i]->reqd_parte_desc,
                    "ttreqd_in_cuenta"=>$Detalle_Req[$i]->reqd_cuenta,
                    "ttreqd_in_subcuenta"=>$Detalle_Req[$i]->reqd_subcuenta,
                    "ttreqd_in_cc"=>$Detalle_Req[$i]->reqd_cc,
                    "ttreqd_in_cantidad"=>$Detalle_Req[$i]->reqd_cantidad,
                    "ttreqd_in_um"=>$Detalle_Req[$i]->reqd_um,
                    "ttreqd_in_precio"=>$Detalle_Req[$i]->reqd_precio,
                    "ttreqd_in_total"=>$Detalle_Req[$i]->reqd_total
            );
            array_push($acum,$row);
        }

        $tt_in_wsreqdDet = array("tt_in_wsreqd_detRow"=>$acum);

        $clienteSOAP = new SoapClient($ip.'=urn:requisiciones');
        $request = array(
                    "idomain" => $dominio,
                    "iuser"=>  $usuario,
                    "iaction"=>  $accion,
                    "irequisicion"=>  $requisicion,
                    "ientidad"=>  $entidad,
                    "idescripcion"=>  $descripcion,
                    "iproveedor"=>  $proveedor,
                    "tt_in_wsreqd_det" => $tt_in_wsreqdDet
                    );
        $response = $clienteSOAP->wsreqsave($request);
        return json_encode($response);
    }

    FUNCTION buscarRequisicion($dominio,$requisicion,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:requisiciones');
        $request = array(
                        "idomain" => $dominio,
                        "irequisicion" => $requisicion
                        );
        $response = $clienteSOAP->wsreqget($request);
        $data = json_encode($response);

        return $data;
    }

    FUNCTION consultarRequisicion($dominio,$Req_number,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:requisiciones');
        $request = array(
                        "idomain" => $dominio,
                        "irequisicion" => $Req_number
                        );
        $response = $clienteSOAP->wsgetreq($request);
        $data = json_encode($response);

        return $data;
    }


    FUNCTION pendientesRequisicion($dominio,$entidady,$usuario,$ip){
        $clienteSOAP = new SoapClient($ip.'=urn:requisiciones');
        $request = array(
                        "idomain" => $dominio,
                        "ientidad" => $entidady,
                        "iuser" => $usuario
                        );
        $response = $clienteSOAP->wsreqpend($request);
        $data = json_encode($response);

        return $data;
    }
