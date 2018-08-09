<?php
@session_start();
require_once 'funcionesFase1.php';
require_once 'funcionesFase2.php';
// Hecho por Kendall Tames Fernández, 2018
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quine-McLuskey</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
    <h1>Simplificador Quine-McCluskey</h1>
        <nav>
        <form action="./" method="post" id='mainForm'>


            <input type="hidden" name="<?php
                if(isset($_SESSION['chk_len'])){
                    for ($i=1; $i <= $_SESSION['chk_len']; $i++) {
                        if(isset($_POST['radio'.$i])){
                            echo 'radio'.$i;
                            break;
                        }
                    }
                }
            ?>" id='hidden'>
            
            <table id = "tablaHeader">
            <tr>
                <td>
                Ingrese los términos separados por comas
                </td>
                <td>
                <input type="text" name='terminos' id='terminos' value = <?php
                echo "'";
                if(isset($_POST['terminos'])){
                    echo $_POST['terminos'];
                }
                echo "'";
                ?>>
                </td>
            </tr>
            </table>
            <input type="submit" value="Calcular" id="submit">
        </nav>
    </header>
    <?php
    if(isset($_POST['terminos'])){
        echo '<h1>Fase 1</h1>';
        $terminos = obtenerTerminos(str_split($_POST['terminos']));
        $_SESSION['terminos'] = $terminos;
        $terminosBinarios = [];
        $_SESSION['cantidadVariables'] = 0;
        foreach($terminos as $indice => $termino){
            $terminosBinarios[$indice] = decToBin($termino);
            if(strlen($terminosBinarios[$indice]) > $_SESSION['cantidadVariables']){
                $_SESSION['cantidadVariables'] = strlen($terminosBinarios[$indice]);
            }
        }
        $matrizBinarios = [];
        for ($i=0; $i <= $_SESSION['cantidadVariables']; $i++) { 
            $matrizBinarios[$i] = [];
        }
        foreach($terminosBinarios as $binario){
            $array = &$matrizBinarios[obtenerUnos($binario)][];
            $array['Numeros'] = [bindec($binario)];
            $array['Diferencia'] = '';
            $array['Pasado'] = '';
        }
        imprimirTablas($matrizBinarios);
    }
    /*//////////////// FASE 2 y 3/////////////////*/
    if(isset($_SESSION['matrizPrimas'])){
        echo '<h1>Fase 2</h1>';
        echo '<h3>Tabla de términos primos</h3>';
        imprimirTablaPrimas($_SESSION['matrizPrimas']);
        echo '<h3>Tabla de términos primos no escenciales</h3>';
        imprimirTablaNoPrimas($_SESSION['matrizPrimosNoEscenciales']);
        echo '<h1>Fase 3</h1>';
        echo '<h3>Primos seleccionados</h3>';
        imprimirPrimos();
        echo '<h3>Primos no escenciales seleccionados</h3>';
        imprimirPrimosN();
        echo '<h3>Simplificación</h3>';
        simplificar();
    }
    ?>
    <link rel="stylesheet" href="css/fontawesome-all.css">
    <style>
    .gato{
        font-size: 10pt;
        font-weight: 900;
    }
    </style>
    <script src="jQuery/external/jquery/jquery.js"></script>
    <script src="jQuery/jquery-ui.js"></script>
    <link rel="stylesheet" href="jQuery/jquery-ui.css">
    <script type='text/javascript'>
        $( "#radioset" ).buttonset();
        $( "#buttons" ).buttonset();

        function lanzar(id){
            const hidden = document.getElementById('hidden');
            hidden.name = id;
        }
    </script>
    </form>
</body>
</html>