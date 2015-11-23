<?php
$direccion_envio= 'juanm_ramallo@hotmail.com' ; //la direccion a la que se enviara el email.
$url= ''; //la URL donde esta publicado el formulario. SIN la barra al final
?>
<?PHP
//proceso del formulario
// si existe "enviar"...
if (isset ($_POST['enviar'])) {
// vamos a hacer uso de la clase phpmailer, 
require("class.phpmailer.php");
$mail = new PHPMailer();//recogemos las variables y configuramos PHPMailer
$mail->From     = $_POST['email'];$mail->FromName = $_POST['nombre'];
$mail->AddAddress($direccion_envio); 
$mail->Subject = "Desde CONTACTO";
$mail->AddReplyTo($_POST['email'],$_POST['nombre']);
$mail->IsHTML(true);                              
$telefono=$_POST['telefono'];
$mensaje=$_POST['mensaje'];
$fecha=$_POST['fecha'];
//comprobamos si se adjunto un archivo, y si su tamano es menor al permitido
if (isset($_FILES['archivo']['tmp_name'])) {
$aleatorio = rand(); 
$nuevonombre=$aleatorio.'-'.$_FILES['archivo']['name'];
}
//comprobamos si todos los campos fueron completados
if ($_POST['email']!='' && $_POST['nombre']!=''  && $error_archivo=='') {
// copiamos el archivo en el servidor
//copy($_FILES['archivo']['tmp_name'],'archivos/'.$nuevonombre);
$tieneAdjunto = false;
if( !empty($_FILES['archivo']['tmp_name']) || 
    !empty($_FILES['archivo']['size']))
{
    if (move_uploaded_file($_FILES['archivo']['tmp_name'],'archivos/'.$nuevonombre)){ 
               $tieneAdjunto = true;
                                 //echo "El archivo ha sido cargado correctamente."; 
           }else{ 
                                 $tieneAdjunto = false;         
               //echo "Ocurri󠡬g򮠥rror al subir el fichero. No pudo guardarse. "; 
           }                 
}
//para mostrar loq ue esta guardando la variable _FILES
//echo '<pre>';
//echo ' debugging info:';
//print_r($_FILES);
//print "</pre>";
//armamos el html
$contenido = '<html><body>';
$contenido .= '<h2>Mensaje desde ²ea Contacto</h2>';
$contenido .= '<p>Enviado el '.  date("d M Y").'</p>';
$contenido .= '<hr />';
$contenido .= '<p>Nombre: <strong>'.$_POST['nombre'].'</strong>';
$contenido.='<p>Telꧯno: <strong>'.$_POST['telefono'].'</strong>';
$contenido.='<p>Correo electr󮩣o: <strong>'.$_POST['email'].'</strong>';
$contenido.='<p>Mesnsaje: <strong>'.$_POST['mensaje'].'</strong>';
$contenido .= '<hr />';
$contenido .= '</body></html>';
$mail->Body    = $contenido;
$mail->AddAttachment('archivos/'.$nuevonombre.'', $nuevonombre);  // optional name
// si todos los campos fueron completados enviamos el mail
$mail->Send();
$flag='ok';
$mensaje=' <div id="ok"> 
<h4>Su mensaje fue enviado</h4>
<strong>
Estamos en contacto con usted<br>
</strong>
 </div>';
} else {
        
//si no todos los campos fueron completados se frena el envio y avisamos al usuario        
$flag='err';
$mensaje='<div id="error">- Los campos marcados con * son requeridos. '.$error_archivo.'</div>';
}
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Formulario Dorado - Contacto</title>
</head>
<body>
<strong>Formulario de Contacto:</strong>

<? echo $mensaje; /*mostramos el estado de envio del form */ ?>
<? if ($flag!='ok') { ?>
<form action="#" method="post" enctype="multipart/form-data">
Nombre*:<br>
<input  <? if (isset ($flag) && $_POST['nombre']=='') { echo 'class="error"';} else {echo 'class="campo"';}?>  type="text" name="nombre" value="<? echo $_POST['nombre'];?>" >
<br>
Telefono*:<br>
<input  <? if (isset ($flag) && $_POST['telefono']=='') { echo 'class="error"';} else {echo 'class="campo"';} ?>  type="text" name="telefono" value="<? echo $_POST['telefono'];?>" >
<br>
Correo electronico*:<br>
<input   pattern="^[a-zA-Z0-9.!#$%'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$"  required  <? if (isset ($flag) && $_POST['email']=='') { echo 'class="error"';} else {echo 'class="campo"';} ?>  type="text" name="email" value="<? echo $_POST['email'];?>" >
<br>
Mensaje:<br>
<textarea  }
 <? if (isset ($flag) && $_POST['mensaje']=='') { echo 'class="com-error"';} else {echo 'class="campo"';} ?>  name="mensaje"><? echo $_POST['mensaje'];?></textarea>
<br>
<br>
<input class="boton" type="submit" name="enviar" value="Enviar" >
</form>
<? } ?>
<p>*Responderemos su consulta en breve</p>
 
</body>
</html>
