<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi primera página php - 250910</title>
</head>
<body>
    <?php
    //declaracion de una variable de tipo string
    $nombre = "Jessica Gutierrez Lecona";
    $carrera_estudia = "T.S.U en Desarrollo de Software Multiplataforma";
    echo "<h1> Hola Mundo! </h1>";
    //esta linea imprime la version de php que se esta utilizando
    echo "<h2> Estas usando la versión de PHP" . phpversion() . "</h2>";
    ?>
    <hr>
    <?php 
     /*despues de asignarle un valor a la variable $nombre podemos imprimir en */
    echo"<h3> Mi nombre es $nombre y estudio: $carrera_estudia </h3>"
    ?>

    <?php 
    $x=10;
    $y=5;
    $z= '3';

    echo "<h3> la suma de $x + $y es: " . ($x+$y) . "</h3>";
    echo "<h3> la suma de $x + $y + $z es: " . ($x+$y+$z) . "</h3>";  

    function multiplicar ($a, $b)
    {
        $resultado = $a * $b;
        echo"<h4> La multiplicacion de $a * $b es: $resultado </h4>";
        return $resultado;

    }

    echo"<h4>  La multiplicacion de de 3 x 3 es:" . multiplicar(3, 3) . "</h4>";
    echo"<h4>  La multiplicacion de de $x x $z es:" . multiplicar($x, $z) . "</h4>";
    echo"<h4>  La multiplicacion de de 1250 x 325 es:" . multiplicar(1250, 325) . "</h4>";
    echo"<hr>";
    echo"<p> El resultado de la ultima multiplicacion es: $resultado </p>";

    /*Static Scope - ALACANCE <ESTATICO*/
    function myTest()
    {
        static $w = 0; //static scope
        echo "$w <br>";
        $w++;
    }
    myTest();
    myTest();
    myTest();
    myTest();
    myTest();
    echo "$w";
    myTest();
    myTest();

    #función para sabeer el tipo de dato de una variable
    /*var_dump() es una función  que nis permite reconocer
    el tipo de datos de una cariable y su valor, no es necesario programarla
    porque esta implementada en php*/

    echo"<p> la variable <strong>nombre</strong> es de tipo:";
    var_dump($nombre);
    echo"</p>";

    echo"<p> la variable <strong>Carrera</strong> es de tipo:";
    var_dump($carrera_estudia);
    echo"</p>";

    $fecha_nacimiento = date("2007/02/12");

    echo"<p> la variable <strong>fecha</strong> es de tipo:";
    var_dump($fecha_nacimiento);
    echo"</p>";

    $fecha_hoy = date ("Y/m/d");
    $edad_actual = $fecha_hoy - $fecha_nacimiento;
    echo"<p> La edad actual es: $edad_actual </p>";

    ?>
</body>
</html>