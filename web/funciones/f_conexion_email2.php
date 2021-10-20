<?php
//necesario que este incluido el f_conexion_email
$mail2 = new PHPMailer;
$mail2->IsSMTP();
$mail2->SMTPAuth = true;
$mail2->SMTPSecure = "ssl";
$mail2->Host = $datos->getValor('c2');
$mail2->Port = 465;
$mail2->Username = $datos->getValor('c1');
$mail2->Password = $datos->getValor('password');
?>