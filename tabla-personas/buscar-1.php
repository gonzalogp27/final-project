<?php
/**
 * @author Bartomeu Sintes Marco - bartolome.sintes+mclibre@gmail.com
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

cabecera("Artist Search");

// Checks if the database contains records
$hayRegistrosOk = false;

$consulta = "SELECT COUNT(*) FROM artistas";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "  <p class=\"aviso\">Error in the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() == 0) {
    print "  <p class=\"aviso\">No records have been created yet.</p>\n";
} else {
    $hayRegistrosOk = true;
}

// If all checks have been successful ...
if ($hayRegistrosOk) {
    // Display the search form
?>

<form action="buscar-2.php" method="post">
<h2>Enter the name of the artist to search</h2>
<table>
    <tr>
        <td>Name:</td>
        <td><input type="text" name="nombre" autofocus></td>
    </tr>
</table>
<p>
    <input type="submit" value="Search">
    <input type="reset" value="Reset form">
</p>
</form>
<?php
}

pie();
?>