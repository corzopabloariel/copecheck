<?php

/* 
 * SCRIPTS DE BASE DE DATOS
 */

class PYRUS_DB {
    
    
    /**
     * Contiene la lista de elementos que tienen que estar si o
     * si para formar parte de una entidad
     */
    static public $tipos_obligatorios = PYRUS_TIPO_PERMITIDO_ATRIBUTO_BD;
    
    /**
     * Inicializa la base de datos haciendo llamados iniciales 
     * de base de datos y relacionados, si se indica la variable crear
     * tambien crea los elementos en la base de datos (deberia ser
     * automatico)
     * 
     * @return void No retorna ningun elemento
     */
    static function inicializar($crear = false){
        PYRUS_DB::conectar();
        if($crear)
            PYRUS_DB::parse();
    }
    
    /**
     * Crea la coneccion a la base de datos
     * 
     * @return toolbox Retorna lo que mande RedBean
     */
    static function conectar(){
        R::setup('mysql:host=' . DB_HOST .';dbname=' . DB_NAME, DB_USER, DB_PASS);
        PYRUS_DB::parse();
    }
    
    /**
     * Parsea los objetos y los pasa a la base de datos
     * 
     * @return bool Retorna False si hubo un error y
     * lo detalla en la salida de errores del sistema
     */
    static function parse(){
        // parseo todos los elementos y saco su nombre y valor
        foreach(PYRUS_ENTIDADES as $k => $v){
            // si no existe atributos, no hago nada
            if(!isset($v['atributos'])) continue;
			// agrego a mano la baja logica
			$v['atributos']['activo'] = [TP_BOLEANO];
            // mando a parsear los atributos, si devuelve falso 
            // hubo error y sigo con el siguiente
            if(PYRUS_DB::parse_atributos($k,$v['atributos']) == PYRUS_ERROR)
                continue;
        }
    }
    
    /**
     * Parsea los atributos para cada entidad
     * 
     * @param string $n Nombre de la entidad
     * @param array $e los atributos de esta entidad
     */
    static function parse_atributos($n,$e){
        $bean = R::dispense($n);
        foreach($e as $k => $v){
            // le otorgo un valor apropiado
            $bean[$k] = PYRUS_DB::valor_apropiado($v);
            if($bean[$k] == PYRUS_ERROR) return PYRUS_ERROR;
        }
        R::store($bean); // guardo
        // VERIFICAR QUE NO SE CREE UN ELEMENTO NULO
        R::trash($bean); // elimino 
    }
    
    /**
     * Obtiene un valor apropiado dada la lista de elementos 
     * que se le envien para rellenar el RedBean y crear la
     * tabla
     * 
     * @param array $v contiene el array de tipos y propiedades
     */
    static function valor_apropiado($v){
        // recorro todos los elementos y verifico que sea de los
        // tipos permitidos, si no hay ninguno , retorno error
        foreach($v as $e){
            // esta este elemento dentro de los necesarios
            if(in_array($e,PYRUS_DB::$tipos_obligatorios))
                return PYRUS_DB::get_valor_apropiado($e);
        }
        // no lo encontro, entonces retorno error
        return PYRUS_NOTIFICATIONS::error('el atributo ' . $e . ' no posee candidato validos');
    }
    
    /**
     * Dado un tipo de PyrusBean, devuelve un valor apropiado
     * 
     * @param type $v
     */
    static function get_valor_apropiado($v){
        switch ($v) {
            case TP_BOLEANO:
                return true;            break;
            case TP_ENTERO:
                return 1;               break;
            case TP_FECHA_CORTA:
                return 20180426;        break;
            case TP_FECHA_LARGA:
                return 20180426202613;  break;
            case TP_FLOTANTE:
                return 1.1;             break;
            case TP_PK;
                return 1;               break;
            case TP_RELACION:
                return 1;               break;
            case TP_STRING:
                return "string";        break;
            case TP_MONEDA:
                return 1.1;             break;
			case TP_IMAGEN:
				return "/";				break;
        }
    }
    
    /**
     * Trae todos los elementos de una entidad
     * 
     * @param string $e nombre de la entidad dispense
     */
    static function get_todos($e,$ord = "ASC"){
        return R::findAll($e,"activo LIKE ? ORDER BY id {$ord}",[true]);
    }
    
    /**
     * Trae un elemento de una entidad dada por un id
     * 
     * @param string $e entidad
     * @param integer $id id
     */
    static function get_uno($e,$id){
        return R::findOne($e,'id LIKE ?', [$id]);
    }
	
	/**
	 * Busca un elemento de una entidad dada por un valor 
	 * de una columna dada.
	 *
	 * @param string $e Entidad
	 * @param string $col Columna a buscar
	 * @param string $val Valor a encontrar
	 */
	static function find_uno($e,$col,$val){
		return R::findOne($e,$col . ' LIKE ? and activo LIKE ?',[$val,true]);
	}
	
	/**
	 * Baja logica
	 *
	 */
	static function remove_uno($e,$id){
		$b = R::findOne($e,'id LIKE ?',[$id]);
		$b['activo'] = 0;
		R::store($b);
	}
    
    static function set_one($e,$obj){
        // return $obj;
        $attr = PYRUS_ENTIDADES[$e]['atributos'];
        if($obj['id'] == 'nulo'){
            $bean = R::dispense($e);
            unset($obj['id']); // lo elimino para que no lo parsee
            unset($attr['id']); // igual que antes
        }
        else $bean = R::findOne($e,'id LIKE ?',[$obj['id']]);
            // Deberia recorrer el objeto y conciliarlo con el
            // PYRUS ENTIDADES, ninguno deberia quedar afuera
            // Vamos a suponer que siempre se envio correctamente
            // la entidad, obtengo la key y la saco de obj
		// var_dump($obj);
        foreach($attr as $k => $v){
            // TODO: Revisar atributos enviados, ej; si se envia modificar
            // un constante de un elemento cuyo id existe, entonces NO DEBO
            // GUARDARLO, y asi con cada elemento 
            
            if(isset($obj[$k])){
				// si viene una imagen, la guardo y guardo la url dentro del valor
				$valor = $obj[$k];
				if(in_array(TP_IMAGEN,$v)){
//					$d = base64_decode(explode(',',$p['archivo'])[1]);
					if(!empty($valor)) {
    					$d = base64_decode(explode(',',$valor)[1]);
    					// obtengo un nombre acorde
    					$name = ((String) date("YmdGis")) . ((String) rand(0,100));
    					file_put_contents(IMAGENES_BAG . $name, $d);
    					$valor = $name;
                    }
				}
                $bean[$k] = $valor;
				}
            // termino con toda la ejecucion si hay error
            else
                return PYRUS_NOTIFICATIONS::error('el atributo ' . $k . ' no existe '
                    . 'en la declaracion de entidad o no se ha enviado');
        }
		// agrego que esta activo
		$bean['activo'] = true;
        // lo guardo
        return R::store($bean);
        //return true;
    }
}
