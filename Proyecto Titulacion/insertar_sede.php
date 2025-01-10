<?php
include 'conectar_db.php'; // Archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_sede = $_POST['nombre_sede'];
    $direccion_sede = $_POST['direccion_sede'];
    $director_sede = $_POST['director_sede'];
    $secretaria_sede = $_POST['secretaria_sede'];
    $horario_sede = $_POST['horario_sede'];

    $sql = "INSERT INTO sede (nombre_sede, direccion_sede, director_sede, secretaria_sede, horario_sede)
            VALUES ('$nombre_sede', '$direccion_sede', '$director_sede', '$secretaria_sede', '$horario_sede')";

    if (mysqli_query($enlace, $sql)) {
        echo "Sede registrada exitosamente.";
        header("Location: admin.html"); // Redirigir de vuelta al panel
    } else {
        echo "Error al registrar la sede: " . mysqli_error($enlace);
    }

    mysqli_close($enlace);
} else {
    echo "Método no permitido.";
}

