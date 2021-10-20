<?php
session_start();
if( (!empty($_POST['ancho']) && $_POST['ancho'] > 0) && (!empty($_POST['alto']) && $_POST['alto'] > 0) ){
	if($_SESSION['ancho'] != $_POST['ancho']){$_SESSION['ancho'] = $_POST['ancho'];}
	if($_SESSION['alto'] != $_POST['alto']){$_SESSION['alto'] = $_POST['alto'];}
}
?>