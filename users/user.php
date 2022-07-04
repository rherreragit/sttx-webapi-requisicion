<?php
    include('../config.php');
    $request_method = $_SERVER["REQUEST_METHOD"];

    switch($request_method)
    {
        case 'GET':
            //Metodo GET
            echo "Metodo GET";
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
        default:
            header("HTTP/1.0 405 Method Not Allowed");
            echo "Invalid Method";
            break;
    }
?>