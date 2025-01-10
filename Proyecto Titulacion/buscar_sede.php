<?php
include 'conectar_db.php'; // Asegúrate de que este archivo conecta correctamente a tu base de datos

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_sede = mysqli_real_escape_string($enlace, $_POST['nombre_sede']);

    $sql = "SELECT * FROM sede WHERE nombre_sede LIKE '%$nombre_sede%'";
    $result = mysqli_query($enlace, $sql);

    if (!$result) {
        echo json_encode(['error' => 'Error en la consulta a la base de datos.']);
        exit;
    }

    $sedes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sedes[] = $row;
    }

    if (empty($sedes)) {
        echo json_encode([]);
    } else {
        echo json_encode($sedes);
    }
} else {
    echo json_encode(['error' => 'Método no permitido.']);
}
?>
