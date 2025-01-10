<?php
include 'conectar_db.php'; // Archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Validar que los campos no estén vacíos
    if (empty($nombre_completo) || empty($email) || empty($password) || empty($rol)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Cifrar la contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar datos en la tabla usuarios
    $sql = "INSERT INTO usuarios (nombre_completo, email, password, rol)
            VALUES ('$nombre_completo', '$email', '$password_hash', '$rol')";

    if (mysqli_query($enlace, $sql)) {
        // Registro exitoso en la tabla usuarios

        // Registrar en la tabla de historial
        $historial_sql = "INSERT INTO registro_usuarios (nombre_completo, correo, rol)
                          VALUES ('$nombre_completo', '$email', '$rol')";
        if (mysqli_query($enlace, $historial_sql)) {
            echo "Usuario registrado exitosamente y añadido al historial.";
        } else {
            echo "Usuario registrado, pero ocurrió un error al registrar en el historial: " . mysqli_error($enlace);
        }
    } else {
        echo "Error al registrar el usuario: " . mysqli_error($enlace);
    }

    mysqli_close($enlace);
} else {
    echo "Método de solicitud no válido.";
}
?>
