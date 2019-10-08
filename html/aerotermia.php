<?php
require("cargaInicial.php");
?>

<form action="aerotermiaBD.php" method="post">

<!--
 <?php $ruta=dame_ruta();?>
 <img src=<?php echo $ruta[0];?>>
 <img src=<?php echo $ruta[1];?>>
 <p>Password: <input type="checkbox" name="pass" value="0" checked>
 <input type="checkbox" name="pass" value="1">
 <input type="checkbox" name="pass" value="2">
 <input type="checkbox" name="pass" value="3">
 <input type="checkbox" name="pass" value="4">
 <input type="checkbox" name="pass" value="5">
 <input type="checkbox" name="pass" value="6">
 <input type="checkbox" name="pass" value="7">
 <input type="checkbox" name="pass" value="8">
 <input type="checkbox" name="pass" value="9"><br>
-->

 <p>Password: <input type="password" name="password"><br>

 <?php $activo=dame_valor('aerotermiaCNF','Activo');?>
 <p>Aerotermia: <input type="radio" name="activo" value="1" <?php if($activo==1) echo checked;?>>Por excedente PV/BD
 <input type="radio" name="activo" value="0" <?php if($activo==0) echo checked;?>>Desactivada
 <input type="radio" name="activo" value="2" <?php if($activo==2) echo checked;?>>Activada<br>

 <?php $sonda=dame_valor('aerotermiaCNF','Sonda');?>
 <p>Sonda Temperatura: <input type="radio" name="sensor" value="1" <?php if($sonda==1) echo checked;?>>Por excedentes PV/BD
 <input type="radio" name="sensor" value="0" <?php if($sonda==0) echo checked;?>>Exterior
 <input type="radio" name="sensor" value="2" <?php if($sonda==2) echo checked;?>>Temperatura Fija<br>

 <?php $fechaActivo=dame_valor('Fechas','FechaActivacion');?>
 <?php $fechaActivo[10]=T;?>
 <p>Fecha Activación: <input type="datetime-local" name="fechaActivo" step="1" value="<?php echo $fechaActivo;?>">

 <p>Horario Forzado: <input type="text" name="HoraInicioForzada" size=4 value=<?php echo dame_valor('horasCNF','horaInicioForzada');?>>-
 <input type="text" name="HoraFinForzada" size=4 value=<?php echo dame_valor('horasCNF','horaFinForzada');?>><br>

 <p>Horario produccion PV: <input type="text" name="HoraInicioPV" size=4 value=<?php echo dame_valor('horasCNF','horaInicioPV');?>>-
 <input type="text" name="HoraFinPV" size=4 value=<?php echo dame_valor('horasCNF','horaFinPV');?>><br>

 <p>Limite excedentes para rearranque: Potencia(W):<input type="text" name="excedentesAerotermi1" size=2 value=<?php echo dame_valor('excedentesCNF','excedentesAerotermi1');?>> Tiempo (min):<input type="text" name="minutosHisteresis" size=1 value=<?php echo dame_valor('excedentesCNF','minutosHisteresis');?>>


<!--
 <p>Excedentes arranque Aerotermia (W): Primero del día <input type="text" name="excedentesAerotermi1" size=2 value=<?php echo dame_valor('excedentesCNF','excedentesAerotermi1');?>>  Resto: <input type="text" name="excedentesAerotermi2" size=2 value=<?php echo dame_valor('excedentesCNF','excedentesAerotermi2');?>>
 <p>Excedentes Temperatura Fija (W): <input type="text" name="excedentesSonda" size=2 value=<?php echo dame_valor('excedentesCNF','excedentesSonda');?>>

 <p>Tiempo mínimo entre arranques (min): <input type="text" name="minutosHisteresis" size=1 value=<?php echo dame_valor('excedentesCNF','minutosHisteresis');?>>
-->

 <p>Porcentaje máximo tomado de red con Aerotermia Activa: <input type="text" name="consumoDESAerotermia" size=1 value=<?php echo dame_valor('excedentesCNF','consumoDESAerotermia');?>>

 <p>Porcentaje máximo de consumo medio tomado de red para arranque Aerotermia: <input type="text" name="consumoMedioArranque" size=1 value=<?php echo dame_valor('excedentesCNF','consumoMedioArranque');?>>

 <p>Periodo cálculo consumo medio de red (min)  <input type="text" name="periodoConsumoMedio" size=1 value=<?php echo dame_valor('excedentesCNF','periodoConsumoMedio');?>>

 <p><input type="submit" /></p>

 <?php $trazas=dame_trazas();?>
<?php
     while ($row = $trazas->fetch_assoc())
     {
       echo "<pre>".$row[DATO].$row[VALOR]."</pre>";
     }
?>
</form>
