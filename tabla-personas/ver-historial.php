<?php
/**
 * Página para visualizar el historial de funciones realizadas
 */
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("History");

// Consulta para obtener el historial
$stmt = $pdo->query("SELECT accion, fecha FROM historial ORDER BY fecha DESC");
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Executed functions history</h2>";
echo "<table border='1' style='width:100%; text-align:left;'>";
echo "<tr><th>Action</th><th>Date</th></tr>";

// Mostrar los resultados

foreach ($resultado as $registro) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($registro['accion']) . "</td>";
    echo "<td>" . htmlspecialchars($registro['fecha']) . "</td>";
    echo "</tr>";
}
echo "</table>";

print "<button><a href='borrar-historial.php'>Borrar historial</a></button>";

pie();
