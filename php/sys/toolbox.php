<?php

/* 
 * CAJA DE HERRAMIENTAS DE PHP
 */

/******************************************
 * EXTENSIONES DE REDBEANS
 ******************************************/

/**
 * Extension de prueba
 * @test 
 */
R::ext('tester', function( $a, $b ){ 
        return $a + $b;
    });

/******************************************
 * FUNCTIONES DEL SISTEMA
 ******************************************/
    
/**
 * Envia una respuesta al cliente formateado de manera correcta.
 * Responde con un JSON con el contenido, y con un codigo HTTP de
 * respuesta indicando exito o un caso particular especificado
 * Codigo de respuestas rapidos:
 *      200 OK
 *      400 ERROR GENERICO
 *      401 ERROR, LOGIN INCORRECTO
 * 
 * @param int $status numero que representa la respuesta http
 * @param string $status_message mensaje de respuesta
 * @param array $data array con los datos de requeridos
 */
function response($status,$status_message,$data){
    header("HTTP/1.1 ".$status);
    $response['status']=$status;
    $response['status_message']=$status_message;
    $response['data']=$data;
    $json_response = json_encode($response);
    echo $json_response;
}

/**
 * Funcion de entrada, Se le envia POST directamente para que 
 * la administre. Si una funcion arranca con "NS_" quiere 
 * decir que no necesita pasar por el control de la session
 * si no lo tiene, si o si el usuario debe estar logueado 
 * para usarlo
 * 
 * @param array $p POST
 */
function query($p){
    if(isset($p['accion']) and isset($p['data'])){
        $accion =   $p['accion'];
        $data =     $p['data'];
        if(substr($accion,0,3) != "NS_"){
            if(!PYRUS_SESSION::verify_sesion()){
                response(401,'no login, no autorizado',['s_id' => session_id()]);
                // por las dudas, un return
                return false;
            }
        }
        if(method_exists('PYRUS_ACTION', $accion)) // existe metodo
            PYRUS_ACTION::{$accion}($data);
        else // no existe
            response(400,'no metodo',['mensaje' => 'no existe la accion ' . $accion]);
    } else {
        response(400, 'no parametro', ['mensaje' => 'no se recibio un parametro accion o data']);
    }
}

/**
 * Verifica la estructura de un array dado con lo que se
 * solicita, si no, retorna error y una lista de los elementos
 * faltantes
 * 
 * @param array $p array a analizar
 * @param array $e array con los nombres que debe contener
 */
function verificar_estructura($d,$e){
    $ret = [];
    foreach($e as $v){
        if(!array_key_exists($v, $d)) $ret[] = $v;
    }
    if($ret != []){ // si no esta vacio
        response(400, 'faltan los elementos listados en data', 
                ['faltantes' => $ret,'enviados' => $d]);
        return false;
    } else return true;
}