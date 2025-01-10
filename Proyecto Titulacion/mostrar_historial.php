<?php
include 'conectar_db.php'; // Conexión a la base de datos

// Consulta para obtener todos los trabajadores
$sql = "SELECT * FROM trabajadores";
$resultado = mysqli_query($enlace, $sql);

if ($resultado->num_rows > 0) {
    echo "<table>";
    echo "<tr>
            <th>Nombre Completo</th>
            <th>RUT</th>
            <th>Cargo</th>
            <th>Sueldo</th>
            <th>Tipo Contrato</th>
            <th>Horario</th>
            <th>Sede</th>
            <th>Status</th>
            <th>Fecha de Registro</th>
            <th>Registrado por</th>
          </tr>";
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>
                <td>{$fila['nombre_completo']}</td>
                <td>{$fila['rut']}</td>
                <td>{$fila['cargo']}</td>
                <td>{$fila['sueldo']}</td>
                <td>{$fila['tipo_contrato']}</td>
                <td>{$fila['horario']}</td>
                <td>{$fila['sede']}</td>
                <td>{$fila['status']}</td>
                <td>{$fila['fecha_registro']}</td>
                <td>{$fila['registrado_por']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No hay registros en la base de datos.";
}
mysqli_close($enlace);
?>
