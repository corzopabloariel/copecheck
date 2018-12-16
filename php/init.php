<?php

/**
 * INICIALIZADOR DEL SISTEMA:
 * Se hacen las declaraciones iniciales, y los llamados
 * a todos los requirimentos del sistema como de la
 * configuracion.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// sistema - requisitos externos y frameworks
require_once __DIR__ . '/sys/ext/rb.php';           // redbeans orm
// sistema - core y herramientas
require_once __DIR__ . '/sys/definitions.php';      // definiciones de tipos
require_once __DIR__ . '/sys/db.php';               // base de datos
require_once __DIR__ . '/sys/toolbox.php';          // caja de herramientas
require_once __DIR__ . '/sys/notifications.php';    // notificaciones
require_once __DIR__ . '/sys/session.php';          // manejo de sesiones
require_once __DIR__ . '/sys/actions.php';          // acciones genericas
// configuraciones
require_once __DIR__ . '/config.php';               // configuraciones varias
require_once __DIR__ . '/declaration.php';          // declaracion de entidades

// Inicializacion de la base de datos, si es la primera vez, o si se
// quiere aplicar un cambio, se debe enviar true como parametro en
// inicializar
PYRUS_DB::inicializar(true);
PYRUS_SESSION::inicializar();
