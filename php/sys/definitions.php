<?php

/*
 * DEFINICIONES DE TIPOS DE DATOS POSIBLES PARA CONTROLADOR, VISTA Y MODELO
 * Tambien esta disponible en JS para que cuando se transfiera el objeto a la
 * vista, este pueda comprender lo que el usuario queria.
 */

/*
 * TODO: no usar define en las que se comparte con JS, si no una funcion que 
 * tambien haga define pero que luego las pueda listar y enviar comodamente a JS
 */

/**********************************************
 * TIPOS DE DATOS
 **********************************************/

/**
 * Declara un booleano
 */
define( 'TP_BOLEANO',               90001);

/**
 * Declara una cadena de texto
 */
define( 'TP_STRING',                90002);

/**
 * Declara un entero
 */
define( 'TP_ENTERO',                90003);

/**
 * Declara un flotante NO real (real no disponible)
 */
define( 'TP_FLOTANTE',              90004);

/**
 * Declara un flotante NO real (real no disponible)
 * con formato numérico (x.xxx,xx)
 */
define( 'TP_STRING_LARGO', 	        90019);


define( 'TP_PASSWORD', 	        	90020);

/**
 * Declara una fecha en formato ISO8601 corta sin
 * niguna barra o simbolo separador (YYYYMMDD) 
 */
define( 'TP_FECHA_CORTA',           90009);

/**
 * Declara una fecha en formato ISO8601 detallada
 * sin ninguna barra o simbolo separador
 * (YYYYMMDDHHMMSS)
 */
define( 'TP_FECHA_LARGA',           90010);

/**
 * Declara un string, que significa en ruta relativa
 * donde se aloja una imagen
 */
define( 'TP_IMAGEN',                90017);

/**
 * Declara un flotante que contiene un valor moneda, igual
 * que TP_FLOTANTE de este lado, solo que del otro lado se
 * ejecuta un callback distinto.
 */
define( 'TP_MONEDA',                90018);

/**********************************************
 * OBJETOS
 **********************************************/

/**
 * Declara un objeto cualquiera no relacional
 */
define( 'TP_OBJETO',                90005);

/**********************************************
 * MODELO O BASE DE DATOS
 **********************************************/

/**
 * Declara un Primary Key, automaticamente es un entero.
 */
define( 'TP_PK',                    90006);

/**
 * Declara una relacion de uno a uno. Se debe agregar un
 * atributo de nombre 'relacion' indicando la entidad 
 * relacionada, si no se declara devolvera error
 */
define( 'TP_RELACION',              90016);

/**
 * [NO USE EN ATRIBUTO] Declara una relacion de uno a muchos. 
 * Se debe agregar una propiedad de nombre 'relacion' en el
 * apartado relacion_externa que contiene a la entidad a relacionar
 * si no se declara devolvera error
 */
define( 'TP_RELACION_1aN',         90007);

/**
 * [NO USE EN ATRIBUTO] Declara una relacion de muchos a uno.
 * Se debe agregar una propiedad de nombre 'relacion' en el
 * apartado relacion_externa que contiene a la entidad a relacionar
 * si no se declara devolvera error
 */
define( 'TP_RELACION_Na1',         90008);

/**********************************************
 * VISTA O VISIBILIDAD
 **********************************************/

/**
 * Declara que el atributo siempre sera visible cuando se
 * lo liste o muestre de manera automatica (todos los 
 * atributos por default estan en este valor)
 */
define( 'TP_VISIBLE_SIEMPRE',       90011);

/**
 * Declara que el atributo nunca sera visible cuando se
 * lo liste o muestre de manera automatica, pero será 
 * transferido al cliente
 */
define( 'TP_VISIBLE_NUNCA',         90012);

/**
 * Declara que el atributo solo es visible cuando se lo
 * edita o muestra a detalle
 */
define( 'TP_VISIBLE_SOLO_DETALLE',  90013);

/**
 * Declara que el atributo jamas será transferido al lado
 * del cliente (y por consecuencia, visible para el)
 */
define( 'TP_INTRANSFERIBLE',        90014);

/**********************************************
 * OTROS
 **********************************************/



/**
 * Declara que el atributo es constante y no se puede
 * modificar (los TP_PK automaticamente toman esta 
 * accion)
 */
define( 'TP_CONSTANTE',             90015);

/**
 * Declara un error, si se encuentra este valor es por que
 * se devolvio error
 */
define( 'PYRUS_ERROR',              99999);

/**
 * Tipos de elementos permitidos como atributos para la base 
 * de datos
 */
define( 'PYRUS_TIPO_PERMITIDO_ATRIBUTO_BD', [TP_BOLEANO,
    TP_ENTERO, 
    TP_FECHA_CORTA, 
    TP_FECHA_LARGA, 
    TP_IMAGEN,
    TP_FLOTANTE, 
    TP_PK, 
    TP_RELACION, 
    TP_STRING,
	TP_STRING_LARGO,
	TP_PASSWORD]);
