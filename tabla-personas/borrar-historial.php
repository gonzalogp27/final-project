<?php
/**
 * Página para eliminar todos los registros del historial
 */
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

try {
    // Eliminar todos los registros de la tabla historial
    $stmt = $pdo->prepare("DELETE FROM historial");
    $stmt->execute();

    // Mostrar mensaje de confirmación
    cabecera("Delete history");
    echo "<h2>History deleted correctly</h2>";
    echo "<a href='ver-historial.php'>Go back to history</a>";
    pie();
} catch (PDOException $e) {
    cabecera("Error");
    echo "<h2>Error deleting history</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='historial.php'>Go back to history</a>";
    pie();
}
?>
