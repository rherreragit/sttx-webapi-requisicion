<?php
include('../config.php');
$request_method = $_SERVER["REQUEST_METHOD"];
$dirip = $_SERVER["REMOTE_ADDR"];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

 switch($request_method)
    {
        case 'GET':
            //Metodo GET
            if(isset($_GET['dominio'],$_GET['id'],$_GET['ikey1'],$_GET['ikey2'])){
                $respuesta = obtenerCorreos($_GET['dominio'],$_GET['id'],$_GET['ikey1'],$_GET['ikey2'],$ip);
                echo $respuesta;
            }
            if(isset($_GET['userid'])){
                $respuesta = obtenercorreoxuserid($_GET['userid'],$ip);
                echo $respuesta;
            }
            break;
        case 'POST':
            //Metodo POST
            if(isset($_POST['mail'],$_POST['titulo'],$_POST['html'],$_POST['usuario'],$_POST['pie'],$_POST['link'])){
                $respuesta = enviaCorreo($_POST['mail'],$_POST['titulo'],$_POST['html'],$_POST['usuario'],$_POST['pie'],$_POST['link'],$ip);
                echo $respuesta;     
            }
            break;
        case 'PUT':
            //Metodo PUT 
            echo "Metodo PUT";
            break;
        case 'DELETE':
            //Metodo DELETE
            echo "Metodo DELETE";
            break;
    }

FUNCTION obtenercorreoxuserid($userid,$ip){
    $clienteSOAP = new SoapClient($ip.'=urn:utilerias');
    $request = array(
        "usuario"=> $userid,
    );
    return json_encode($clienteSOAP->wsgetmailxuser($request));
}


FUNCTION obtenerCorreos($dominio,$id,$ikey1,$ikey2,$ip){
    $clienteSOAP = new SoapClient($ip.'=urn:utilerias');
    $request = array(
        "idomain"=> $dominio,
        "iid"=> $id,
        "ikey1"=> $ikey1,
        "ikey2"=> $ikey2);
    $response = $clienteSOAP->wsemaildef($request);
    $arr = array('titulo' => $response->osubject,'cuerpo' => $response->obody, 'pie' => $response->ofooter, 'activo' => $response->oactive, 'correos' => $response->oemail);
    return json_encode($arr);
}

FUNCTION enviaCorreo($correos,$titulo,$html,$usuario,$pie,$link,$ip){
    $clienteSOAP = new SoapClient($ip.'=urn:utilerias');
    $request = array(
        "IDOMAIN"=> "sttx"
    );
    $response = $clienteSOAP->wsgetexchangeserver($request);  
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host= strval($response->SERVER);
    $mail->Port = $response->PORT;
    $mail->SMTPAuth = ($response->SMTPAUTH === 'true'? true: false);
    $mail->SMTPOptions = array(
              'ssl' => array(
              'verify_peer' => ($response->PEER === 'true'? true: false),
              'verify_peer_name' => ($response->PEERNAME === 'true'? true: false),
              'allow_self_signed' => ($response->SIGN === 'true'? true: false)
              )
    );
    $mail->CharSet = 'UTF-8';
    $mail->IsHTML(true);
    $mail->From = 'info@steeltechnologies.com.mx';
    $mail->FromName = 'Notificaciones';
    $arrcorroes = explode(",", $correos);
    for($i=0; $i < count($arrcorroes); $i++){
       $mail->addAddress($arrcorroes[$i]);
    }
    $mail->Subject = $titulo;
    
    $mail->Body =
              "
              <html>
              <head>
                  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
              </head>
              <style>
                  #boton {
                      width: 200px;
                      heigth: 20px;
                      background-color: #374850;
                  }
              </style>
              <body link='white'>
                  <table border='0' cellpadding='0' cellspacing='0' height='100%' width='100%' id='bodyTable'>
                      <tr>
                          <td align='center' valign='top'>
                              <table border='0' cellpadding='20' cellspacing='0' width='100%' id='emailContainer'>
                                  <tr>
                                      <td align='center' valign='top'>
                                          <table border='0' cellpadding='20' cellspacing='0' width='100%' id='emailHeader'>
                                              <tr>
                                                  <td bgcolor='#ad4102' align='center' valign='top'>
                                                      <font color='white' size='20'>Recibiste una notificacion!</font>
                                                  </td>
                                              </tr>
                                          </table>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td align='center' valign='top'>
                                          <table border='0' cellpadding='20' cellspacing='0' width='100%' id='emailBody'>
                                              <tr>
                                                  <td align='center' valign='top'>
                                                      <font size='6'>El Usuario: ".$usuario." Te envia una notificacion:</font><br><br>
                                                      <font size='4'>".$html."</font>
                                                  </td>
                                              </tr>
                                          </table>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td align='center' valign='top'>
                                          <table border='0' cellpadding='20' cellspacing='0' width='100%' id='emailFooter'>
                                          <tr>
                                          <td align='center' valign='top'>
                                              <font size='4' >".$pie."</font>
                                          </td>
                                          </tr>
                                              <tr>

                                                  <td bgcolor='#3A01DF' align='center' valign='top'>
                                                  <!--
                                                  <font color='white' size='4'><a href='".$link."'>Click Aqui!</a></font>
                                                  -->
                                                  </td>
                                              </tr>
                                          </table>
                                      </td>
                                  </tr>
                              </table>
                          </td>
                      </tr>
                  </table>
              </body>
              </html>
              ";
              try{
              $mail->send();
              }
              catch(Exception $e){
                echo $e->errorMessage();
              }
              return json_encode(array('Status' => 200 ,'mensaje' => 'Correo enviado exitosamente.'));
    
}
