<?php
/**
 * This example shows sending a message using PHP's mail() function.
 */

require '../PHPMailer/PHPMailerAutoload.php';
require '../PHPMailer/class.smtp.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;
$mail->Username = "miligadepadel.manuelbdv.es@gmail.com";
$mail->Password = "EJA8WNSdBF";

//Set who the message is to be sent from
$mail->setFrom('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');
//Set an alternative reply-to address
$mail->addReplyTo('info@miligadepadel.manuelbdv.es', 'miligadepadel.manuelbdv.es');
//Set who the message is to be sent to
$mail->addAddress('manu_oamuf@hotmail.com', 'Manuel');
//Set the subject line
$mail->Subject = 'El administrador de tu liga de padel le envia este e-mail';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML(file_get_contents('para_enviar_email.php'), dirname(__FILE__));
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//$mail­>CharSet = "UTF­8";
//$mail­>Encoding = "quoted­printable";

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}


?>







<?php
/*
//incluimos la clase PHPMailer
require_once('../PHPMailer/class.phpmailer.php');

//instancio un objeto de la clase PHPMailer
$mail = new PHPMailer(); // defaults to using php "mail()"

//defino el cuerpo del mensaje en una variable $body
//se trae el contenido de un archivo de texto
//también podríamos hacer $body="contenido...";
$body = file_get_contents('para_enviar_email.php');
//Esta línea la he tenido que comentar
//porque si la pongo me deja el $body vacío
// $body = preg_replace('/[]/i','',$body);

//$mail­>CharSet = "UTF­8";
//$mail­>Encoding = "quoted­printable";


//defino el email y nombre del remitente del mensaje
$mail­>SetFrom('manuel.berdelva@gmail.com', 'Manuel Berenguer');

//defino la dirección de email de "reply", a la que responder los mensajes
//Obs: es bueno dejar la misma dirección que el From, para no caer en spam
$mail­>AddReplyTo("manuel.berdelva@gmail.com","Manuel Berenguer");
//Defino la dirección de correo a la que se envía el mensaje
$address = "manu_oamuf@hotmail.com";
//la añado a la clase, indicando el nombre de la persona destinatario
$mail­>AddAddress($address, "Juan Antonio");

//Añado un asunto al mensaje
//$mail­>Subject = "Envío de email con PHPMailer en PHP";

//Puedo definir un cuerpo alternativo del mensaje, que contenga solo texto
//$mail­>AltBody = "Cuerpo alternativo del mensaje";

//inserto el texto del mensaje en formato HTML
$mail­>MsgHTML($body);

//asigno un archivo adjunto al mensaje
//$mail­>AddAttachment("ruta/archivo_adjunto.gif");

//envío el mensaje, comprobando si se envió correctamente
if(!$mail­>Send()) {
echo "Error al enviar el mensaje: " . $mail­>ErrorInfo;
} else {
echo "Mensaje enviado!!";
}
*/
?>