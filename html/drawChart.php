<HTML>
<BODY>

<meta charset="utf-8">

<form action = "" method = "post">
        <p>Fecha Inicio: <input type="datetime-local" name="fechaInicio" step="1" value=<?php echo date('Y-m-d\TH:i:s', time()-(3600*4));?>> Fin: <input type="datetime-local" name="fechaFin" step="1" value=<?php echo date('Y-m-d\TH:i:s', time());?>>

        <input type="submit" value="Submit">

</form>

<?php

    require_once("registroDatos.php");
//Creamos un objeto de la clase RegistroDatos
    $datos = new RegistroDatos();
    if($_POST['fechaInicio']=="")
    {
       $fechaI=date('Y-m-d\TH:i:s', time()-(3600*24*7));
       $fechaF=date('Y-m-d\TH:i:s', time());
    }
    else
    {
       $fechaI=$_POST['fechaInicio'];
       $fechaF=$_POST['fechaFin'];
    }
    $rawdata = $datos->getAllInfoFechas($fechaI,$fechaF);

//nos creamos dos arrays para almacenar el tiempo y el valor numérico
$timeArray;
$pvArray;
$gridArray;
$loadArray;
//en un bucle for obtenemos en cada iteración el valor númerico y
//el TIMESTAMP del tiempo y lo almacenamos en los arrays
for($i = 0 ;$i<count($rawdata);$i++){
    //OBTENEMOS EL TIMESTAMP
    $time= $rawdata[$i][0];
    $date = new DateTime($time);
    if ($i>0)
    {
        $timeAnt= $rawdata[$i-1][0];
        $dateAnt=new DateTime($timeAnt);
    }
    //ALMACENAMOS EL TIMESTAMP EN EL ARRAY
    $timeArray[$i] = $date->getTimestamp()*1000;
    $pvArray[$i]= $rawdata[$i][1];
 //   $gridArray[$i]= $rawdata[$i][2];
    $loadArray[$i]= $rawdata[$i][3];
    if($i>0 && $rawdata[$i][2]>0 && ($date->format('w') == $dateAnt->format('w')))
    {
        $gridArray[$i]=$gridArray[$i-1]+($rawdata[$i][2]*11/3600);
    }
    elseif($i>0 && ($date->format('w') != $dateAnt->format('w')))
    {
        $gridArray[$i]=0;
    }
    elseif($i>0)
    {
        $gridArray[$i]=$gridArray[$i-1];
    }
    else
    {
        $gridArray[$i]=0;
    }
}
?>


<div id="contenedor"></div>

<script src="https://code.jquery.com/jquery.js"></script>
    <!-- Importo el archivo Javascript de Highcharts directamente desde su servidor -->
<script src="http://code.highcharts.com/stock/highstock.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script>

Highcharts.setOptions({
    time: {
        timezoneOffset: -2 * 60
   }
});

chartDiana = new Highcharts.StockChart({
    chart: {
        renderTo: 'contenedor'
        //defaultSeriesType: 'spline'

    },
    rangeSelector : {
        enabled: false
    },
    title: {
        text: 'Generación Vs Consumo'
    },
    xAxis: {
        type: 'datetime'
        //tickPixelInterval: 150,
        //maxZoom: 20 * 1000
    },
    yAxis: {
        minPadding: 0.05,
        maxPadding: 0.05,
        title: {
            text: 'Watios',
            margin: 100
        }
    },
    series: [{
        name: 'PV',
        data: (function() {
                var data = [];
                <?php
                    for($i = 0 ;$i<count($rawdata);$i++){
                ?>
                data.push([<?php echo $timeArray[$i];?>,<?php echo $pvArray[$i];?>,<?php echo $gridArray[$i];?>]);
                <?php } ?>
                return data;
            })()},
        {
        name: 'LOAD',
        data: (function() {
                var data = [];
                <?php
                    for($i = 0 ;$i<count($rawdata);$i++){
                ?>
                data.push([<?php echo $timeArray[$i];?>,<?php echo $loadArray[$i];?>]);
                <?php } ?>
                return data;
           })()
    }],
    credits: {
            enabled: false
    }
});

</script>
</BODY>

</html>
