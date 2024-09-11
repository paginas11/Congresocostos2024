<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $archivoPDF = $_FILES['pdf'];

    // Configuración del correo
    $destino = "yordanara3@gmail.com"; // Cambia esto por tu correo
    $asunto = "Nuevo formulario enviado";
    $mensaje = "Nombre: $nombre\nApellido: $apellido\nCorreo: $email\n";
    
    // Verificar si el archivo es PDF
    if ($archivoPDF['type'] == "application/pdf") {
        // Mover el archivo subido a un directorio temporal
        $archivoTemp = $archivoPDF['tmp_name'];
        $archivoNombre = $archivoPDF['name'];

        // Adjuntar el archivo PDF en el correo
        $content = chunk_split(base64_encode(file_get_contents($archivoTemp)));

        // Encabezados del correo
        $headers = "From: $email";
        $headers .= "\nMIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\n\n";
        
        // Contenido del mensaje
        $body = "--boundary\n";
        $body .= "Content-Type: text/plain; charset=\"UTF-8\"\n\n";
        $body .= "$mensaje\n";
        $body .= "--boundary\n";
        $body .= "Content-Type: application/pdf; name=\"$archivoNombre\"\n";
        $body .= "Content-Disposition: attachment; filename=\"$archivoNombre\"\n";
        $body .= "Content-Transfer-Encoding: base64\n\n";
        $body .= "$content\n";
        $body .= "--boundary--";

        // Enviar correo
        if (mail($destino, $asunto, $body, $headers)) {
            echo "Formulario enviado correctamente.";
        } else {
            echo "Error al enviar el formulario.";
        }
    } else {
        echo "El archivo subido no es un PDF.";
    }
}
?>