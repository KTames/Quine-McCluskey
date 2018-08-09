<?php
@session_start();
@session_unset();
function limpiarLista($listaTemp){
    $lista = [];
    foreach($listaTemp as $filaActual){
        $agregar = TRUE;
        foreach($lista as $filaNueva){
            if($filaNueva == $filaActual){
                $agregar = FALSE;
            }
        }
        if ($agregar){
            $lista[] = $filaActual;
        }
    }
    return $lista;
}

function inicializarMatriz($listaBase){
    $terminos = &$_SESSION['terminos'];
    $matriz[0][0] = '';
    for ($y=0; $y <= count($listaBase); $y++) { 
        for ($x=0; $x <= count($terminos); $x++) { 
            if($y != $x || $y != 0){
                if($y == 0){
                    $matriz[$y][$x] = $terminos[$x-1];
                }elseif ($x == 0) {
                    $matriz[$y][$x] = $listaBase[$y-1];
                }else{
                    $encontrado = FALSE;
                    foreach($matriz[$y][0] as $numero){
                        if($matriz[0][$x] == $numero){
                            $encontrado = TRUE;
                        }
                    }
                    $matriz[$y][$x] = $encontrado ? 'X' : '';
                }
            }
        }
    }
    return $matriz;
}

function obtenerDatos($lista){
    $numeros = '';
    foreach($lista as $key => $numero){
        $numeros .= $numero;
        $numeros .= ($key != count($lista) - 1) ? ' - ' : '';
    }
    return $numeros;
}

function marcarColumna($matriz, $columna){
    $columna = array_search($columna, $matriz[0]);
    $contador = 0;
    foreach($matriz as $key => $fila){
        if($key != 0){
            if($fila[$columna] == 'X'){
                $contador++;
            }
        }
    }
    return $contador == 1;
}

function marcarFila($matriz, $fila){
    $marcar = FALSE;
    $fila = $fila[0];
    foreach($fila as $numero){
        if(marcarColumna($matriz, $numero)){
            $marcar = TRUE;
        }
    }
    return $marcar;
}

function marcarColumnaTotal($matriz, $columna){
    $marcar = FALSE;
    foreach($matriz as $keyFila=>$fila){
        if ($keyFila != 0){
            if(marcarFila($matriz, $fila) && in_array($columna, $fila[0])){
                $marcar = TRUE;
                break;
            }
        }
    }
    return $marcar;
}

function imprimirMatrizPrimas($matriz){
    echo '<div>';
    echo '<table id="fase2">';
    $terminos = &$_SESSION['terminos'];
    $terminos = [];
    $escenciales = &$_SESSION['listaPrimosEscenciales'];
    $noEscenciales = &$_SESSION['matrizPrimosNoEscenciales'];
    $noEscenciales = null;
    $noEscenciales[0][0] = '';
    foreach($matriz as $keyFila => $fila){
        if ($keyFila == 0){
            echo '<tr>';
            foreach($fila as $keyColumna => $columna){
                echo '<td>';
                if($keyColumna > 0){
                    if(marcarColumnaTotal($matriz, $columna)){
                        echo '<i class="gato">#</i>';
                    }else{
                        $terminos[] = $columna;
                    }
                }
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '<tr>';
        foreach($fila as $keyColumna => $celda){
            echo '<td>';
            if ($keyColumna == 0){
                if($keyFila != 0){
                    if(marcarFila($matriz, $fila)){
                        echo '<i class="gato">#</i>     ';
                        $escenciales[] = $fila[0];
                    }else{
                        $noEscenciales[] = $fila[0];
                    }
                }
            }
            echo (is_array($celda)) ? obtenerDatos($celda) : $celda;
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '<tr>';
    foreach($matriz[0] as $keyColumna => $columna){
        echo '<td>';
        if($keyColumna > 0){
            if(marcarColumna($matriz, $columna)){
                echo '<i class="fa fa-arrow-circle-up"></i>';
            }
        }
        echo '</td>';
    }
    echo '</tr>';
    echo '</table>';
    echo '</div>';
}

function imprimirTablaPrimas($lista){
    foreach($lista as &$fila){
        sort($fila);
    }
    $lista = limpiarLista($lista);
    $matriz = inicializarMatriz($lista);
    imprimirMatrizPrimas($matriz);
}

function abarcaTodosTerminos($matriz){
    $terminos = [];
    foreach($matriz as $lista){
        foreach($lista as $numero){
            if(!in_array($numero, $terminos)){
                $terminos[] = $numero;
            }
        }
    }
    $count = 0;
    foreach($terminos as $numero){
        if(in_array($numero,$_SESSION['terminos'])){
            $count++;
        }
    }
    return $count == count($_SESSION['terminos']);
}

function pc_array_power_set($array, $limit){
	$subset = array();
	$results = array(array()); 
	foreach ($array as $element){
		foreach ($results as $combination){
			$result = array_merge(array($element), $combination);
			array_push($results, $result);
			if(count($result) == $limit){
				$subset[] = $result;
			}
		}
	}
	return $subset;
}

function obtenerLetra($indice){
    $letras = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'Z');
    return $letras[$indice];
}


function obtenerValidos($powerset){
    $listaFinal = [];
    foreach($powerset as $termino){
        if(abarcaTodosTerminos($termino)){
            $listaFinal[] = $termino;
        }
    }
    return $listaFinal;
}

function obtenerCombinaciones($set, $count = 1){
    if($count == count($set)){
        return array($set);
    }else{
        $powerset = pc_array_power_set($set, $count);
        $powerset = obtenerValidos($powerset);
        if(count($powerset) != 0){
            return $powerset;
        }else{
            return obtenerCombinaciones($set, $count+1);
        }
    }
    
}


function dibujarMatrizNoPrimos($cabeceras, $filas, $mejores){
    $noEscenciales = &$_SESSION['listaPrimosNoEscenciales'];
    echo '<table id="noPrima">';
    echo '<tr><td colspan=3></td>';
    foreach($cabeceras as $head){
        echo '<td>' . $head . '</td>';
    }
    foreach($filas as $key => $fila){
        echo '<tr>' . '<td>' . $key . '</td>';
        echo '<td>';
        if(in_array($key, $mejores)){
            echo '<i class="gato">#</i>';
            $noEscenciales[] = $fila;
        }
        echo '</td>';
        echo '<td>';
        echo obtenerDatos($fila);
        echo '</td>';
        foreach($cabeceras as $head){
            echo '<td>';
            if(in_array($head, $fila)){
                echo 'X';
            }
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</tr>';
    echo '';
    echo '</table>';
}

function seleccionMarcada(){
    $return = FALSE;
    if(isset($_SESSION['chk_len'])){
        for ($i=1; $i <= $_SESSION['chk_len']; $i++) {
            if(isset($_POST['radio'.$i])){
                
                $return = TRUE;
                break;
            }
        }
    }
    return $return;
}

function calcularMatrizNoPrimas($matriz){
    $set = [];
    foreach($matriz as $key=>$fila){
        if($key != 0){
            if(strlen(obtenerDatos($fila[0]))>0){
                $set[] = $fila[0];
            }
        }
    }
    if(count($_SESSION['terminos']) == 0 || count($set) == 0){
        echo '<h4>No disponible<h4>';
    }else{
        $mejorSet = obtenerCombinaciones($set);
        $setNuevo = [];
        foreach($set as $key => $valor){
            $setNuevo[obtenerLetra($key)] = $valor;
        }
        $set = $setNuevo;
        $setNuevo = [];
        foreach($mejorSet as $key => $valor){
            $indice = '';
            foreach($valor as $fila){
                $indice .= array_search($fila, $set);
            }
            $setNuevo[$indice] = $valor;
        }
        $mejorSet = $setNuevo;
        unset($setNuevo);

        /////////////// MEJOR SET CONTIENE LAS COMBINACIONES POSIBLES //////////////////////
        
        echo '<p>Soluciones equivalentes</p>';
        echo '<div id="radioset">';
        $_SESSION['chk_len'] = count($mejorSet);
        $contadorRadio = 1;
        $seleccionMarcada = seleccionMarcada();
        foreach($mejorSet as $key => $fila){
            echo '<input type="radio" id="radio'.$contadorRadio.'" name="radio"';
            if($seleccionMarcada){
                if(isset($_POST['radio'.$contadorRadio])){
                    $_SESSION['check'] = $key;
                    echo ' checked="checked" ';
                }
            }else{
                if($contadorRadio == 1){
                    echo ' checked="checked" ';
                    $_POST['radio1'] = 'on';
                    $_SESSION['check'] = $key;
                }
            }
            echo '><label for="radio'.$contadorRadio.'" onclick=lanzar("radio'.$contadorRadio.'")  >'.$key.'</label>';
            $contadorRadio++;
        }
        echo '<input type="submit" value="Actualizar tabla" />';
        echo '</div>';
        dibujarMatrizNoPrimos($_SESSION['terminos'], $set, str_split($_SESSION['check']));
    }
}

function imprimirTablaNoPrimas($lista){
    $matriz = inicializarMatriz($lista);
    calcularMatrizNoPrimas($matriz);
}

function imprimirPrimos(){
    if(isset($_SESSION['listaPrimosEscenciales'])){
        if(count($_SESSION['listaPrimosEscenciales'])> 0){
            echo '<table class="tablaPrimos">';
            foreach($_SESSION['listaPrimosEscenciales'] as $termino){
                echo '<tr><td>';
                echo obtenerDatos($termino);
                echo '</td></tr>';
            }
            echo '</table>';
        }else{
            echo '<t4>No disponible</t4>';
        }
    }else{
        echo '<t4>No disponible</t4>';
    }
}

function imprimirPrimosN(){
    if(isset($_SESSION['listaPrimosNoEscenciales'])){
        if(count($_SESSION['listaPrimosNoEscenciales'])> 0){
            echo '<table class="tablaPrimos">';
            foreach($_SESSION['listaPrimosNoEscenciales'] as $termino){
                echo '<tr><td>';
                echo obtenerDatos($termino);
                echo '</td></tr>';
            }
            echo '</table>';
        }else{
            echo '<t4>No disponible</t4>';
        }
    }else{
        echo '<t4>No disponible</t4>';
    }
}

function simplificar(){
    require_once "funcionesFase1.php";
    $array = $_SESSION['listaPrimosEscenciales'];
    if(isset($_SESSION['listaPrimosNoEscenciales'])){
        $array = array_merge($_SESSION['listaPrimosEscenciales'], $_SESSION['listaPrimosNoEscenciales']);
    }
    $matrizSumatorias = [];
    foreach($array as $fila){
        $sumatoria = [];
        echo obtenerDatos($fila);
        echo '<table>';

        for ($key=-1; $key < count($fila); $key++){
            echo '<tr>';
            for ($i=-1; $i < $_SESSION['cantidadVariables']; $i++) {
                echo '<td>';
                if($i != -1){
                    if($key == -1){
                        echo obtenerLetra($i);
                    }else{
                        echo str_split(getBin($fila[$key], $_SESSION['cantidadVariables']))[$i];
                        $sumatoria[$key][$i] = getBin($fila[$key], $_SESSION['cantidadVariables'])[$i];
                    }
                }else{
                    if($key != -1){
                        echo $fila[$key];
                    }
                }
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        $matrizSumatorias[] = $sumatoria;
    }
    echo '<h2>Resultado final</h2>';
    foreach($matrizSumatorias as $keyBloque => $bloque){
        $sumaBinario = [];
        foreach($bloque as $fila){
            foreach($fila as $key => $dato){
                if(isset($sumaBinario[$key])){
                    $sumaBinario[$key] += $dato;
                }else{
                    $sumaBinario[$key] = $dato;
                }
            }
        }
        foreach($sumaBinario as $key => $bin){
            if($bin == 0){
                echo obtenerLetra($key) . "'";
            }elseif($bin == count($bloque)){
                echo obtenerLetra($key);
            }
        }
        if(count($matrizSumatorias) - 1 != $keyBloque){
            echo ' + ';
        }
    }
    echo '<br><br>';
}