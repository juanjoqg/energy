<?php
$patron=array(0,2,2,3,4,5,6,7,8,9);

function dame_trazas()
{
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


$sentencia = "select * from trazasBD";

  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
//echo $resultado;
//echo $sentencia;


//mysqli_free_result($resultado);
mysqli_close($conexion);
    return  $resultado;

}


function dame_valor($tabla,$dato)
{


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

$sentencia = "select VALOR from $tabla where DATO='$dato'";
  // Ejecuta la sentencia SQL
$resultado = mysqli_query($conexion,$sentencia);
if(!$resultado){
    echo "Error select record Activo: " . mysqli_error($conexion);
    exit;
}

$fila = $resultado->fetch_assoc();
mysqli_free_result($resultado);

mysqli_close($conexion);
    return  $fila['VALOR'];

}


function dame_ruta()
{

    $numeros10=array(0,1,2,3,4,5,6,7,8,9);
    global $patron;
    $numeros9=array_rand($numeros10,9);
    $numeros8=array_rand($numeros9,8);
    $numeros7=array_rand($numeros8,7);
    $numeros6=array_rand($numeros7,6);
    $numeros5=array_rand($numeros6,5);
    $numeros4=array_rand($numeros5,4);
    $numeros3=array_rand($numeros4,3);
    $numeros2=array_rand($numeros3,2);
    $numeros1=array_rand($numeros2,1);

    print_r(array_diff($numeros10,$numeros9));
    print_r(array_diff($numeros9,$numeros8));
    print_r(array_diff($numeros8,$numeros7));
    print_r(array_diff($numeros7,$numeros6));
    print_r(array_diff($numeros6,$numeros5));
    print_r(array_diff($numeros5,$numeros4));
    print_r(array_diff($numeros4,$numeros3));
    print_r(array_diff($numeros3,$numeros2));
    print_r(array_diff($numeros2,$numeros1));

    $valor_devuelto[0]="pass.jpg";
    $valor_devuelto[1]="pass2.jpg";
    $patron[0]=1;
    $patron[1]=0;
    return $valor_devuelto;
}

function dame_patron()
{
    global $patron;
    return $patron;
}



?>
