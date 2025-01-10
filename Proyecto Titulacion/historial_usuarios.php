<?php
include 'conectar_db.php';

$sql = "SELECT * FROM registro_usuarios ORDER BY fecha_registro DESC";
$result = mysqli_query($enlace, $sql);

if (!$result) {
    die("Error al ejecutar la consulta: " . mysqli_error($enlace));
}

echo "<h1>Historial de Usuarios</h1>";
echo "<table border='1'>
        <tr>
            <th>Nombre Completo</th>
            <th>Correo Electrónico</th>
            <th>Rol</th>
            <th>Fecha de Registro</th>
        </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['nombre_completo']}</td>
            <td>{$row['correo']}</td>
            <td>{$row['rol']}</td>
            <td>{$row['fecha_registro']}</td>
          </tr>";
}

echo "</table>";
mysqli_close($enlace);
?>
