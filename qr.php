<?php
//Agregamos la libreria para genera códigos QR
require "third-party/phpqrcode/qrlib.php";

//Parametros de Condiguración
$contenido = "localhost/profile/list?id_cuenta=".$_GET['id']; //Texto

$ecc = 'H';
$pixel_size = 1000;
$frame_size = 2;

//Enviamos los parametros a la Función para generar código QR
QRcode::png($contenido,null, $ecc, $pixel_size, $frame_size);

?>