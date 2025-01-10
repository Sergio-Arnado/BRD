<?php
include 'conectar_db.php'; // Archivo de conexión

// Verifica que el método sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre_completo = $_POST['nombre_completo'];
    $rut = $_POST['rut'];
    $cargo = $_POST['cargo'];
    $sueldo = $_POST['sueldo'];
    $tipo_contrato = $_POST['tipo_contrato'];
    $horario = $_POST['horario'];
    $sede = $_POST['sede'];
    $status = $_POST['status'];
    $fecha_registro = $_POST['fecha_registro'];
    $registrado_por = $_POST['registrado_por'];
    $nombre_documento = $_POST['nombre_documento'];

    // Validar si se subió un archivo
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        $archivoTmp = $_FILES['documento']['tmp_name'];
        $nombreArchivo = basename($_FILES['documento']['name']);
        $rutaDestino = 'documentos/' . $nombreArchivo;

        // Mover el archivo a la carpeta 'documentos'
        if (move_uploaded_file($archivoTmp, $rutaDestino)) {
            // Verifica la conexión
            if (!$enlace) {
                die("Error en la conexión a la base de datos: " . mysqli_connect_error());
            }

            // Insertar datos en la tabla `trabajadores`
            $sqlTrabajador = "INSERT INTO trabajadores (nombre_completo, rut, cargo, sueldo, tipo_contrato, horario, sede, status, fecha_registro, registrado_por)
                              VALUES ('$nombre_completo', '$rut', '$cargo', '$sueldo', '$tipo_contrato', '$horario', '$sede', '$status', '$fecha_registro', '$registrado_por')";

            if (mysqli_query($enlace, $sqlTrabajador)) {
                // Obtener el ID del trabajador recién insertado
                $idTrabajador = mysqli_insert_id($enlace);

                // Insertar datos en la tabla `documentos`
                $sqlDocumento = "INSERT INTO documentos (id_trabajador, nombre_documento, descripcion, ruta_documento)
                                 VALUES ('$idTrabajador', '$nombre_documento', 'Documento adjunto del trabajador', '$nombreArchivo')";

                if (mysqli_query($enlace, $sqlDocumento)) {
                    echo "Trabajador y documento registrados exitosamente.";
                } else {
                    echo "Error al registrar el documento: " . mysqli_error($enlace);
                }
            } else {
                echo "Error al registrar el trabajador: " . mysqli_error($enlace);
            }
        } else {
            echo "Error al subir el archivo. Por favor, inténtelo de nuevo.";
        }
    } else {
        echo "Debe adjuntar un archivo PDF.";
    }

    // Cerrar la conexión
    mysqli_close($enlace);
} else {
    echo "Método de solicitud no permitido.";
}