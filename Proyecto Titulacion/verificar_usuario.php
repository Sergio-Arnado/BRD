<?php
include 'conectar_db.php';

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establecer el encabezado para devolver JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se enviaron los datos necesarios
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Faltan datos en la solicitud.'
        ]);
        exit;
    }

    $email = $_POST['email']; // Cambiar variable a 'email'
    $password = $_POST['password'];

    // Consulta para buscar al usuario por email
    $sql = "SELECT * FROM usuarios WHERE email = '$email'"; // Cambiar 'correo' a 'email'
    $result = mysqli_query($enlace, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);

        // Verificar la contraseña
        if (password_verify($password, $usuario['password'])) {
            // Contraseña correcta, devolver rol
            echo json_encode([
                'success' => true,
                'rol' => $usuario['rol']
            ]);
        } else {
            // Contraseña incorrecta
            echo json_encode([
                'success' => false,
                'error' => 'Contraseña incorrecta.'
            ]);
        }
    } else {
        // Usuario no encontrado
        echo json_encode([
            'success' => false,
            'error' => 'Correo no encontrado.'
        ]);
    }

    // Cerrar la conexión
    mysqli_close($enlace);
} else {
    // Método no válido
    echo json_encode([
        'success' => false,
        'error' => 'Método de solicitud no válido.'
    ]);
}
?>
