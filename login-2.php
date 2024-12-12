<?php
/**
 * @author    Bartolomé Sintes Marco - bartolome.sintes+mclibre@gmail.com
 * @license   https://www.gnu.org/licenses/agpl-3.0.txt AGPL 3 or later
 * @link      https://www.mclibre.org
 */

require_once "comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (isset($_SESSION["conectado"])) {
    header("Location:index.php");
    exit;
}

$usuario  = recoge("usuario");
$password = recoge("password");

// Comprobamos los datos recibidos procedentes de un formulario
$usuarioOk  = true;
$passwordOk = true;

// Comprobamos que el usuario recibido con la contraseña recibida existe en la base de datos
$passwordCorrectoOk = false;

if ($usuarioOk && $passwordOk) {
    if ($usuario != "root" || $password!="root") {
        header("Location:login-1.php?aviso=Error: Incorrect username and/or password.");
    } else {
        $passwordCorrectoOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($usuarioOk && $passwordOk && $passwordCorrectoOk) {
    // Creamos la variable de sesión "conectado"
    $_SESSION["conectado"] = true;
    $pdo = conectadb();
    
    $accion = "The user has logged in the application";
    registrarAccion($pdo, $accion);
    
    header("Location:tabla-personas/personas.php");
}
