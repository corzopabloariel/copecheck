<?php
header('Access-Control-Allow-Origin: *'); 
/*
 * CONSULTAS DESDE AFUERA HACIA EL SISTEMA
 */

require_once __DIR__ . 'init.php';

$p = $_POST; // TODO: Pasar a un modo filtrado

query($p);
