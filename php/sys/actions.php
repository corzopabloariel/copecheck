<?php

/* 
 * FUNCIONES DE LO QUE UN USUARIO DESDE JS PUEDE HACER
 * 
 ******************************************
 * ADVERTENCIAS: EXISTE UNA CONVENCION, EL
 * USUARIO SOLO PUEDE HACER USO DE ESTAS
 * FUNCIONES, EL LLAMADOR AGREGA EL PREFIJO
 * Y VIENEN LUEGO ACA. POR SEGURIDAD
 ******************************************
 * 
 * TODAS LAS FUNCIONES RECIBEN $d (QUE ES EL DATA).
 * 
 * SI EMPIEZAN CON NS_ Significa que pueden ser usado sin
 * necesidad de tener inicializada una session (NO SESSION)
 */

class PYRUS_ACTION{
    
    /**
     * Devuelve la lista de tipos conocidos. Use null desde js 
     * para data.
     * 
     * @param array $d No necesario
     */
    public static function obtener_tipos($d){
        // traigo todas las constantes
        $def = get_defined_constants(true)['user'];
        $ret = [];
        // saco solo los que tienen 'TP_' en el inicio
        foreach($def as $k => $v){
            if(substr($k, 0, 3) == 'TP_')
                    $ret[$k] = $v;
        }
        response(200, 'ok', $ret);
    }
    
    /**
     * Dado una entidad, devuelve su especificacion en JSON
     * 
     * @param type $d
     * @return boolean
     */
    public static function especificacion($d){
        if(!verificar_estructura($d, ['entidad'])) return false;
        $entidad = $d['entidad'];
        if(!isset(PYRUS_ENTIDADES[$entidad]))
            response(400, 'no ' . $entidad,['mensaje' => 'no existe la entidad']);
        else
            response(200,'ok ' . $entidad, (array) PYRUS_ENTIDADES[$entidad]['atributos']);
    }
    
    /**
     * Dado una entidad, lista todos sus elementos
     * 
     * @param array $d array de datos generico
     */
    public static function listar_generico($d){
        if(!verificar_estructura($d, ['entidad'])) return false;
        $entidad = $d['entidad'];
        response(200,'ok ' . $entidad, PYRUS_DB::get_todos($entidad));
    }
    
	public static function baja_generica($d){
		if(!verificar_estructura($d,['entidad','id'])) return false;
		$entidad = $d['entidad'];
		$id = $d['id'];
		response(200,'ok ' . $entidad,PYRUS_DB::remove_uno($entidad,$id));
	}
	
	/**
	 * Busca un solo elemento ordenado por una columna dado por un valor.
	 * 
	 * @param array $d array de datos generico
	 */
	public static function buscar_uno_generico($d){
		if(!verificar_estructura($d,['entidad','columna','valor'])) return false;
		$entidad = $d['entidad'];
		$columna = $d['columna'];
		$valor = $d['valor'];
		response(200, 'ok ' . $entidad, PYRUS_DB::find_uno($entidad,$columna,$valor));
	}
	
    /**
     * Dado una entidad y su id, devuelve el elemento
     * 
     * @param array $d array de datos generico
     */
    public static function mostrar_uno_generico($d){
        if(!verificar_estructura($d, ['entidad','id'])) return false;
        $entidad = $d['entidad'];
        $id = $d['id'];
        response(200,'ok ' . $entidad, PYRUS_DB::get_uno($entidad, $id));
    }
    
    /**
     * Guarda genericamente un objeto dado, recibe por
     * parametros la entidad a guardar, y el objeto 
     * que contiene la informacion a guardar. si 'id' == -1
     * se agrega un nuevo objeto (id dentro del objeto)
     * El objeto a guardar debe declarar cada key y debe
     * coincidir con el declarado en la bd y la verificacion
     * 
     * @param array $d array de datos generico
     * 
     * TODO: por seguridad, algunos id deberian no poder ser
     * accedidos por todos los usuarios, si no por quien lo creo
     */
    public static function guardar_uno_generico($d){
        if(!verificar_estructura($d, ['entidad','objeto'])) return false;
        $entidad = $d['entidad'];
        $objeto = $d['objeto'];
        // var_dump($objeto); // como recibe un objeto json
        $ret = PYRUS_DB::set_one($entidad, $objeto);
        if($ret === PYRUS_ERROR){ //WTF! 1 == 99999 => TRUE???
            response(400,'ERROR: ' . $entidad,PYRUS_NOTIFICATIONS::get_errors()); 
        } else {
            response(200,'ok ' . $entidad, $ret);
        }
    }
    
    /**
     * Recibe usuario y password, y consulta si se puede loguear
     * 
     * @param array $d array de datos generico
     */
    public static function NS_login($d){
        if(!verificar_estructura($d, ['user','pass'])) return false;
        $user = $d['user'];
        $pass = $d['pass'];
        // INICIO - MANEJO MANUAL DE DB
        $bean = R::findOne('usuarios','user LIKE ?',[$user]);
        if(md5($pass) == $bean['pass']){
            PYRUS_SESSION::set_sesion($bean);
            response(200,'ok usuario', ['login' => true,'s_id' => session_id(),$_SESSION]);
        }
        else{
            PYRUS_SESSION::kill_sesion();
            response(401,'no login',['login' => false,'md5' => md5($pass)]);
        }
        // FIN - MANEJO MANUAL DE BD
    }
    
    /**
     * Obtiene la informacion del usuario en la sesion 
     * 
     * @param array $d No necesario
     */
    public static function obtener_sesion($d){
        if(isset($_SESSION['user_id']))
            response(200,'ok usuario',$_SESSION);
        else{
            response(401,'no login, no autorizado',['s_id' => session_id()]);
        }
    }
    
    /**
     * Remueve toda la sesion, eliminando todos sus atributos y
     * haciendola irrecuperable
     * 
     * @param array $d No necesario
     */
    public static function NS_matar_sesion($d){
        // elimina incondicionalmnente la sesion
        PYRUS_SESSION::kill_sesion();
        response(200,'kill session',['mensaje' => 'sesion eliminada']);
    }
    
}