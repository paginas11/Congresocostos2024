<?php
// Configuración del correo
$destinatario = 'yordanfernando.arayameneses@gmail.com';
$asunto = 'Registro al Congreso';
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$archivo = $_FILES['archivo'];

// Mensaje del correo
$mensaje = "Nombre: " . $nombre . "\nCorreo: " . $email;

// Comprobación de archivo y extensión
if ($archivo['type'] == 'application/pdf') {
    // Ruta donde se guardará el archivo
    $ruta_destino = 'uploads/' . basename($archivo['name']);

    // Subir archivo
    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        // Crear el encabezado del correo
        $encabezado = "From: " . $email . "\r\n";
        $encabezado .= "MIME-Version: 1.0\r\n";
        $encabezado .= "Content-Type: multipart/mixed; boundary=\"_1_\"\r\n";

        // Crear cuerpo del correo con el archivo adjunto
        $mensaje_correo = "--_1_\r\n";
        $mensaje_correo .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
        $mensaje_correo .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $mensaje_correo .= $mensaje . "\r\n\r\n";
        $mensaje_correo .= "--_1_\r\n";
        $mensaje_correo .= "Content-Type: application/pdf; name=\"" . $archivo['name'] . "\"\r\n";
        $mensaje_correo .= "Content-Disposition: attachment; filename=\"" . $archivo['name'] . "\"\r\n";
        $mensaje_correo .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $mensaje_correo .= chunk_split(base64_encode(file_get_contents($ruta_destino))) . "\r\n";
        $mensaje_correo .= "--_1_--";

        // Enviar correo
        if (mail($destinatario, $asunto, $mensaje_correo, $encabezado)) {
            echo "Formulario enviado exitosamente.";
        } else {
            echo "Error al enviar el formulario.";
        }
    } else {
        echo "Error al subir el archivo.";
    }
} else {
    echo "Solo se permiten archivos PDF.";
}
?>
