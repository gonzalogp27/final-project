<?php
/**
 * @author Bartomeu Sintes Marco
 * @license https://www.gnu.org/licenses/agpl-3.0.txt AGPL 3 or later
 * @link https://www.mclibre.org
 */

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Artist Addition");

// Collect data from the form
$name = recoge("nombre");
$surname = recoge("apellidos");
$phone = recoge("telefono");
$email = recoge("correo");
$active = recoge("activo");
switch ($active) {
    case "on":
        $active = "1";
        break;
    case "off":
        $active = "0";
        break;
}

// Check if the form is empty
$isNotEmpty = false;
if ($name !== "" || $surname !== "" || $phone !== "" || $email !== "" || $active !== "") {
    $isNotEmpty = true;
} else {
    print "  <p class=\"aviso\">At least one field must be filled. Record not saved.</p>\n";
}

// Check if the record already exists
$isUnique = false;
if ($isNotEmpty) {
    $consulta = "SELECT COUNT(*) FROM artistas
                 WHERE nombre = :nombre
                 AND apellidos = :apellidos
                 AND telefono = :telefono
                 AND correo = :correo
                 AND activo = :activo";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "  <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":nombre" => $name, ":apellidos" => $surname, ":telefono" => $phone, ":correo" => $email, ":activo" => $active])) {
        print "  <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() > 0) {
        print "  <p class=\"aviso\">The record already exists.</p>\n";
    } else {
        $isUnique = true;
    }
}

// If all checks pass, insert the record
if ($isNotEmpty && $isUnique) {
    $consulta = "INSERT INTO artistas
                 (nombre, apellidos, telefono, correo, activo)
                 VALUES (:nombre, :apellidos, :telefono, :correo, :activo)";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "  <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":nombre" => $name, ":apellidos" => $surname, ":telefono" => $phone, ":correo" => $email, ":activo" => $active])) {
        print "  <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "  <p>Record created successfully.</p>\n";

        // Log the action
        $action = "Added a new artist: $name $surname";
        registrarAccion($pdo, $action);
    }
}

pie();