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

cabecera("List songs");

// Recogemos el ID del artista desde el formulario
$id = recoge("id");

// Comprobamos que el ID no esté vacío
if ($id === "") {
    print "<p class=\"aviso\">No artist selected.</p>\n";
    pie();
    exit;
}

// Verificamos si el artista existe en la base de datos
$consulta = "SELECT COUNT(*) FROM artistas WHERE id = :id";
$sentencia = $pdo->prepare($consulta);

if (!$sentencia) {
    print "<p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    pie();
    exit;
}

$sentencia->execute([":id" => $id]);
if ($sentencia->fetchColumn() == 0) {
    print "<p class=\"aviso\">The artist selected does not exists.</p>\n";
    pie();
    exit;
}

// Consultamos las canciones del artista
$consulta = "SELECT nombre, duracion FROM canciones WHERE artista_id = :id";
$sentencia = $pdo->prepare($consulta);

if (!$sentencia) {
    print "<p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    pie();
    exit;
}

$sentencia->execute([":id" => $id]);
$canciones = $sentencia->fetchAll();

if (count($canciones) === 0) {
    print "<p class=\"aviso\">The artist selected does not have any song recorded on the database.</p>\n";
} else {
    print "<h2>Songs from the selected artist</h2>\n";
    print "<table class=\"conborde franjas\">\n";
    print "  <thead>\n";
    print "    <tr><th>Name</th><th>Duration</th></tr>\n";
    print "  </thead>\n";
    print "  <tbody>\n";

    foreach ($canciones as $cancion) {
        print "    <tr>\n";
        print "      <td>{$cancion['nombre']}</td>\n";
        print "      <td>{$cancion['duracion']}</td>\n";
        print "    </tr>\n";
    }

    print "  </tbody>\n";
    print "</table>\n";
}

pie();
?>