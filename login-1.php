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

cabecera("Login 1");

$aviso = recoge("aviso");

if ($aviso != "") {
    print "    <p class=\"aviso\">$aviso</p>\n";
    print "\n";
}
?>
    <form action="login-2.php" method="post">
      <p>Enter your username and your password:</p>

      <table>
        <tr>
          <td>Username:</td>
          <td><input type="text" name="usuario"  autofocus/></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><input type="password" name="password" /></td>
        </tr>
      </table>

      <p>
        <input type="submit" value="Login">
        <input type="reset" value="Delete">
      </p>
    </form>
<php 
pie();
?>
