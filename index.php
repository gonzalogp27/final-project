<?php
/**
 * @author    Bartolomé Sintes Marco - bartolome.sintes+mclibre@gmail.com
 * @license   https://www.gnu.org/licenses/agpl-3.0.txt AGPL 3 or later
 * @link      https://www.mclibre.org
 */

require_once "comunes/biblioteca.php";

session_name("sesiondb");
session_start();
  if (!isset($_SESSION["conectado"])) {
cabecera("LucusMusic");
pie();
}
else{
header("Location:tabla-personas/personas.php");
}

