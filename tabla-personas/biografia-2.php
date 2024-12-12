<?php
/**
 * @author  
 * @license https://www.gnu.org/licenses/agpl-3.0.txt AGPL 3 or later
 * @link    
 */

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Artist biography");

$id = recoge("id");
$nombre = recoge("nombre");

// Comprobamos que el ID no esté vacío
if ($id === "") {
    print "<p class=\"aviso\">No artist selected.</p>\n";
    pie();
    exit;
}

// Verificamos si el artista y su biografía existen en la tabla `artistas`
$consulta = "SELECT biografia FROM artistas WHERE id = :id";
$sentencia = $pdo->prepare($consulta);

if (!$sentencia) {
    print "<p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    pie();
    exit;
}

$sentencia->execute([":id" => $id]);

$biografia = $sentencia->fetchColumn();

if ($biografia === false) {
    print "<p class=\"aviso\">No data about the artist selected.</p>\n";
} else {
    print "<h2>Artist biography</h2>\n";
    print "<p>$biografia</p>\n";
    
    // Mostrar la imagen asociada al artista
    print "<img src='../img/$id.jpg' style='max-width: 100%; height: auto;'>\n";
}

pie();
?>