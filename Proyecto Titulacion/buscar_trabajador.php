<?php
include 'conectar_db.php';

// Habilitar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establecer el encabezado para devolver JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se proporcionó el RUT
    if (!isset($_POST['rut']) || empty($_POST['rut'])) {
        echo json_encode(['error' => 'No se proporcionó el RUT.']);
        exit;
    }

    $rut = $_POST['rut'];

    // Consulta para buscar al trabajador
    $sqlTrabajador = "SELECT * FROM trabajadores WHERE rut = '$rut'";
    $resultTrabajador = mysqli_query($enlace, $sqlTrabajador);

    if (!$resultTrabajador) {
        echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($enlace)]);
        exit;
    }

    if (mysqli_num_rows($resultTrabajador) > 0) {
        $trabajador = mysqli_fetch_assoc($resultTrabajador);

        // Consulta para obtener documentos asociados al trabajador
        $idTrabajador = $trabajador['id'];
        $sqlDocumentos = "SELECT nombre_documento, descripcion, ruta_documento FROM documentos WHERE id_trabajador = '$idTrabajador'";
        $resultDocumentos = mysqli_query($enlace, $sqlDocumentos);

        $documentos = [];
        if ($resultDocumentos) {
            while ($doc = mysqli_fetch_assoc($resultDocumentos)) {
                $documentos[] = $doc;
            }
        }

        // Incluir documentos en la respuesta
        $trabajador['documentos'] = $documentos;

        echo json_encode($trabajador); // Devuelve el trabajador con sus documentos
    } else {
        echo json_encode(['error' => 'No se encontró el trabajador.']);
    }
} else {
    echo json_encode(['error' => 'Método de solicitud no válido.']);
}

// Cerrar la conexión
mysqli_close($enlace);
?>
