<?php
@session_start();
function decToBin($num){
    if($num < 2){
        return $num;
    }
    return decToBin(floor($num/2)) . $num % 2;
}

function getBin($dec, $len){
    $bin = decToBin($dec);
    $strFinal = '';
    $lenght = strlen($bin);

    if($lenght < $len){
        for ($i=0; $i < $len - $lenght; $i++) { 
            $strFinal .= '0';
        }
    }
    return $strFinal .= $bin;
}
function obtenerUnos($termino){
    $suma = 0;
    for ($i=0; $i < strlen($termino); $i++) { 
        if($termino[$i] == '1'){
            $suma ++;
        }
    }
    return $suma;
}

function obtenerTerminos($terminos){
    $listaFinal = [''];
    $contador = 0;
    foreach($terminos as $termino){
        if($termino != ' '){
            if($termino == ','){
                $contador++;
                $listaFinal[$contador] = '';
            }else{
                $listaFinal[$contador] .= $termino;
            }
        }
    }
    return $listaFinal;
}

function potenciaDeDos($numero){
    $potencia = 1;
    while($potencia < $numero){
        $potencia *= 2;
    }
    if($potencia == $numero){
        return TRUE;
    }else{
        return FALSE;
    }
}
function imprimirTabla($lista, $count, $matrizSiguiente){
    $contadorFilasLlenas = 0;
    echo '<div class="tabla">';
    echo '<h3>Tabla #'.$count.'</h3>';
    echo '<table>';
    foreach($lista as $bloque){
        echo '<tr>';
        echo '<td colspan=3 class="linea"></td>';
        echo '</tr>';
        foreach($bloque as $fila){
            if(count($fila)>0){
                $contadorFilasLlenas++;
                echo '<tr';
                if($fila['Pasado'] != 'X'){
                    echo ' class="prima" ';
                    $_SESSION['matrizPrimas'][] = $fila['Numeros'];
                }
                echo'>';
                echo '<td>';
                $numeros = '';
                foreach($fila['Numeros'] as $key => $numero){
                    $numeros.= $numero;
                    $numeros.= ($key != count($fila['Numeros']) - 1) ? ' - ' : '';
                }
                echo $numeros;
                echo '</td>';
                echo '<td>';
                echo $fila['Diferencia'];
                echo '</td>';
                echo '<td>';
                echo $fila['Pasado'];
                echo '</td>';
                echo '</tr>';
            }
            
        }
    }
    echo '</table>';
    echo '</div>';
    if($contadorFilasLlenas != 0){
        imprimirTablas($matrizSiguiente, $count + 1);
    }
}
function imprimirTablas($lista, $count = 1){
    $matrizTotal = [];
    for ($i=0; $i < count($lista) - 1; $i++) { 
        $matrizTotal[$i] = [];
    }
    for ($i=1; $i < count($lista); $i++) { 
        echo '<tr colspan = 3 class = "linea"></tr>';
        foreach($lista[$i] as &$filaActual){
            foreach ($lista[$i-1] as &$filaAnterior) {
                if(count($filaAnterior)>0 && count($filaActual) > 0){
                    if($filaAnterior['Diferencia'] == $filaActual['Diferencia']){
                        $diferencia = $filaActual['Numeros'][0] - $filaAnterior['Numeros'][0];
                        if(potenciaDeDos($diferencia)){
                            $filaAnterior['Pasado'] = 'X';
                            $filaActual['Pasado'] = 'X';
                            $nuevaFila['Numeros'] = array_merge($filaActual['Numeros'], $filaAnterior['Numeros']);
                            $nuevaFila['Pasado'] = '';
                            $nuevaFila['Diferencia'] = $filaActual['Diferencia'];
                            $nuevaFila['Diferencia'] .= ($filaActual['Diferencia'] != '') ? '.' : '';
                            $nuevaFila['Diferencia'] .= $diferencia;
                            $matrizTotal[$i - 1][] = $nuevaFila;
                        }
                    }
                }
                
            }
        }
    }
    imprimirTabla($lista, $count, $matrizTotal);
    ?>
    </table>
    <?php
}