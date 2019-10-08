<?php

require("cargaInicial.php");


$usuario = "pi";
$servidor = "localhost";
$basededatos = "solar";

$conexion = mysqli_connect( $servidor, $usuario, "" , $basededatos);

if(!$conexion){
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

if($_POST['password'] != "Dianasolar")
{
    echo "Error Password";
    exit;
}

$sentencia = "update aerotermiaCNF set VALOR=" . (int)$_POST['activo']. " where DATO='Activo'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record Activo: " . mysqli_error($conexion);
else
    echo "Exito modificado estado aerotermia" . "<br>";

$sentencia = "update aerotermiaCNF set VALOR=" . (int)$_POST['sensor']. " where DATO='Sonda'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record Sonda: " . mysqli_error($conexion);
else
    echo "Exito modificado valor Sonda" . "<br>";

$sentencia = "update Fechas set VALOR='" . $_POST['fechaActivo']. "' where DATO='FechaActivacion'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record fechaActivo: " . mysqli_error($conexion);
else
    echo "Exito modificado valor fechaActivo" . "<br>";

$sentencia = "update horasCNF set VALOR='" . $_POST['HoraInicioForzada']. "' where DATO='HoraInicioForzada'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia) . "<br>";
if(!$resultado)
    echo "Error updating record HoraInicioForzada: " . mysqli_error($conexion);
else
    echo "Exito modificado valor HoraInicioForzada" . "<br>";

$sentencia = "update horasCNF set VALOR='" . $_POST['HoraFinForzada']. "' where DATO='HoraFinForzada'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record HoraFinForzada: " . mysqli_error($conexion);
else
    echo "Exito modificado valor HoraFinForzada" . "<br>";

$sentencia = "update horasCNF set VALOR='" . $_POST['HoraInicioPV']. "' where DATO='horaInicioPV'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia) . "<br>";
if(!$resultado)
    echo "Error updating record HoraInicioPV: " . mysqli_error($conexion);
else
    echo "Exito modificado valor HoraInicioPV" . "<br>";

$sentencia = "update horasCNF set VALOR='" . $_POST['HoraFinPV']. "' where DATO='horaFinPV'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia) . "<br>";
if(!$resultado)
    echo "Error updating record HoraFinPV: " . mysqli_error($conexion);
else
    echo "Exito modificado valor HoraFinPV" . "<br>";

$sentencia = "update excedentesCNF set VALOR='" . $_POST['excedentesAerotermi1']. "' where DATO='excedentesAerotermi1'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record excedentesAerotermi1: " . mysqli_error($conexion);
else
    echo "Exito modificado valor excedentesAerotermi1" . "<br>";

$sentencia = "update excedentesCNF set VALOR='" . $_POST['excedentesAerotermi2']. "' where DATO='excedentesAerotermi2'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record excedentesAerotermi2: " . mysqli_error($conexion);
else
    echo "Exito modificado valor excedentesAerotermi2" . "<br>";

$sentencia = "update excedentesCNF set VALOR='" . $_POST['excedentesSonda']. "' where DATO='excedentesSonda'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record excedentesSonda: " . mysqli_error($conexion);
else
    echo "Exito modificado valor excedentesSonda" . "<br>";

$sentencia = "update excedentesCNF set VALOR='" . $_POST['minutosHisteresis']. "' where DATO='minutosHisteresis'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record minutosHisteresis: " . mysqli_error($conexion);
else
    echo "Exito modificado valor minutosHisteresis" . "<br>";

$sentencia = "update excedentesCNF set VALOR='" . $_POST['consumoDESAerotermia']. "' where DATO='consumoDESAerotermia'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record consumoDESAerotermia: " . mysqli_error($conexion);
else
    echo "Exito modificado valor consumoDESAerotermia" . "<br>";

$sentencia = "update excedentesCNF set VALOR='" . $_POST['consumoMedioArranque']. "' where DATO='consumoMedioArranque'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record consumoMedioArranque: " . mysqli_error($conexion);
else
    echo "Exito modificado valor consumoMedioArranque" . "<br>";

$sentencia = "update excedentesCNF set VALOR='" . $_POST['periodoConsumoMedio']. "' where DATO='periodoConsumoMedio'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado)
    echo "Error updating record periodoConsumoMedio: " . mysqli_error($conexion);
else
    echo "Exito modificado valor periodoConsumoMedio" . "<br>";

mysqli_close($conexion);

//echo '<script type="text/javascript>alert('mostrar mi ventana popup');</script>

?>
