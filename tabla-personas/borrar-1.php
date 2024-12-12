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

cabecera("Artist Deletion");

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
    // Retrieve all records to display them in a <table>
    $consulta = "SELECT * FROM artistas";

    $resultado = $pdo->query($consulta);
    if (!$resultado) {
        print "  <p class=\"aviso\">Error in the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
?>
    <form action="borrar-2.php" method="post">
        <p>Select the record you want to delete:</p>
        <table class="conborde franjas">
            <thead>
                <tr>
                    <th>Delete</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Active</th>
                </tr>
            </thead>
<?php
        foreach ($resultado as $registro) {
            print "  <tr>\n";

            print "   <td class=\"centrado\"><input type=\"radio\" name=\"id\" value=\"$registro[id]\" checked></td>\n";
            print "   <td>$registro[nombre]</td>\n";
            print "   <td>$registro[apellidos]</td>\n";
            print "   <td>$registro[telefono]</td>\n";
            print "   <td>$registro[correo]</td>\n";
            switch($registro["activo"]){
                case 0:
                    print "<td>No</td>\n";
                    break;
                case 1:
                    print "<td>Yes</td>\n";
                    break;
            }
            print "  </tr>\n";
        }
        print "  </table>\n";
        print "\n";
        print "  <p>\n";
        print "   <input type='submit' value='Delete record' >\n";
        print "   <input type='reset' value='Reset form'>\n";
        print "  </p>\n";
        print "  </form>\n";
    }

}
pie();
?>