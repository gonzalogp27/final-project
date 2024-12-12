<?php
/**
 * @author    Bartolomé Sintes Marco
 * @license   https://www.gnu.org/licenses/agpl-3.0.txt AGPL 3 or later
 * @link      https://www.mclibre.org
 */

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();
cabecera("Artist Deletion");

$id = recoge("id");


// Comprobamos el dato recibido
$idOk = false;

if ($id == "") {
    print "    <p class=\"aviso\">No artist selected.</p>\n";
} else {
    $idOk = true;
}

// Si hemos recibido un id de registro
if ($idOk) {
    // Comprobamos que el registro con el id recibido existe en la base de datos
    $registroEncontradoOk = false;

    $consulta = "SELECT * FROM artistas WHERE id = :indice";
    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":indice" => $id])) {
        print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        $artista = $resultado->fetch(PDO::FETCH_ASSOC);
        if (!$artista) {
            print "    <p class=\"aviso\">No artist found.</p>\n";
        } else {
            $registroEncontradoOk = true;
        }
    }

    // Si todas las comprobaciones han tenido éxito ...
    if ($registroEncontradoOk) {
        // Borramos el registro con el id recibido
        $consulta = "DELETE FROM artistas WHERE id = :indice";
        $resultado = $pdo->prepare($consulta);
        if (!$resultado) {
            print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif (!$resultado->execute([":indice" => $id])) {
            print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } else {
            print "    <p>Artist deleted correctly.</p>\n";

            // Registrar la acción en el historial
            $nombre = $artista['nombre'] ?? 'Unknown';
            $apellidos = $artista['apellidos'] ?? 'Unknown';
            $accion = "Artist deleted: $nombre $apellidos";
            registrarAccion($pdo, $accion);
        }
    }
}

pie();
