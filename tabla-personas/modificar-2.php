<?php
/**
 * @author    Bartolomé Sintes Marco - bartolome.sintes+mclibre@gmail.com
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

cabecera("Personas - Modificar 2");

$id = recoge("id");

// Comprobamos el dato recibido
$idOk = false;

if ($id == "") {
    print "    <p class=\"aviso\">No se ha seleccionado ningún registro.</p>\n";
} else {
    $idOk = true;
}

// Comprobamos que el registro con el id recibido existe en la base de datos
$registroEncontradoOk = false;

if ($idOk) {
    $consulta = "SELECT COUNT(*) FROM artistas
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">Registro no encontrado.</p>\n";
    } else {
        $registroEncontradoOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($idOk && $registroEncontradoOk) {
    // Recuperamos el registro con el id recibido para incluir sus valores en el formulario
    $consulta = "SELECT * FROM artistas
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        $registro = $resultado->fetch();

    print "<form action=\"modificar-3.php\" method=\"get\">";
    print "      <p>Modify the fields you want:</p>";

    print "      <table>";
    print "        <tr>";
    print "          <td>Name:</td>";
    print "          <td><input type=\"text\" name=\"nombre\" value=\"$registro[nombre]\" autofocus></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>Surname:</td>";
    print "          <td><input type=\"text\" name=\"apellidos\" value=\"$registro[apellidos]\"></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>Phone:</td>";
    print "          <td><input type=\"text\" name=\"telefono\" value=\"$registro[telefono]\"></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>Email:</td>";
    print "          <td><input type=\"text\" name=\"correo\" value=\"$registro[correo]\"></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>Active:</td>";
    print "          <td>";
    print "             <label>Yes</label><input type=\"radio\" name=\"activo\" value='on'>";
    print "             <label>No</label><input type=\"radio\" name=\"activo\" value='off'>";
    print "          </td>";
    print "        </tr>";
    print "      </table>";
    print "";
    print "      <p>";
    print "        <input type=\"hidden\" name=\"id\" value=\"$id\">";
    print "        <input type=\"submit\" value=\"Update\">";
    print "        <input type=\"reset\" value=\"Restart form\">";
    print "      </p>";
    print "    </form>";
  }
}

pie();
?>
