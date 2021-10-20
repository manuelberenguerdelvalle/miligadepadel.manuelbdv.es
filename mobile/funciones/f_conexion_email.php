<?php
include_once ("../../class/mysql.php");
include_once ("../../../class/datos.php");
$datos = new Datos(1,'','','','','','');
$mail = new PHPMailer;
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = $datos->getValor('c2');
$mail->Port = 465;
$mail->Username = $datos->getValor('c1');
$mail->Password = $datos->getValor('password');
?>