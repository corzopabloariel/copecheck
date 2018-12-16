/**
 * PyrusBean bajo objetos, a diferencia de UserBean, no debe ser instanciado
 * pyrusBean hace uso de UserBean como si fuese una estatica
 * 
 * @param string e Entidad
 * @return pyrusBean
 */
Pyrus = function(e = null){
    
    this.entidad = e; // entidad que se pasa por parametro
    this.especificacion = null; // especificacion de la entidad
    this.tipos = null; // la entidad conserva su copia de tipos
    this.resultado = null; // resultados de un listado generico
    this.dom_listador = null; // elemento DOM donde se dibujara un listador
    this.dom_editor = null; // elemento DOM donde se dibujara un editor
    /**
     * Constructor del PyrusBean
     * 
     * @param string e Entidad a buscar
     * @return NO RETORNA
     */
    this.constructor = function(){
        if(this.entidad === null || this.entidad === ""){
            console.log("AVISO: No se ha pasado ninguna entidad, esto puede traer problemas");
            // no hago ninguna operacion de carga
            return false;
        }
        // cargo el objeto
        this.getTipos();
        this.getEspecificacion();
        this.getContenidoGenerico();
    };
    
    /**
    * Funcion de consulta desde JS, se comunica con el servidor 
    * y devuelve una respuesta acorde, uso generico.
    * Las funciones de callback solo conocen la respuesta en 
    * data, no el contexto de como fue recibido (ni estado, ni
    * mensaje)
    * 
    * @param string accion La accion a llamar
    * @param object data Objeto anonimo inmediato que contiene lo que se enviara
    * @param function callbackOK Funcion a llamar si no hay errores
    * @param function callbackFail Funcion a llamar si hay errores
    */
    this.query = function (accion,data,callbackOK,callbackFail = null,async = true){
       url_envio = url_query_local;
       envio = { "accion":accion, "data":data };
       window.con = $.ajax({
           context: this,
           type: 'POST',
           // make sure you respect the same origin policy with this url:
           // http://en.wikipedia.org/wiki/Same_origin_policy
           url: url_envio,
           async: async,
           data: envio,
           success: function(msg){
               window.devolucion = JSON.parse(msg);
               // uso call para pasarle el contexto this
               callbackOK.call(this,window.devolucion['data']);
               },
           error: function(msg){
               window.PYRUS_ERROR_ARR.push(msg);
               console.error(msg);
               if(callbackFail !== null)
                   callbackFail.call(this,msg);
               }
           });
    };
    
    /**
     * Trae la especificacion de la entidad actual
     * 
     * @return {undefined}
     */
    this.getEspecificacion = function(){
        this.query(
            "especificacion",
            {"entidad" : this.entidad},
            function(m){ this.especificacion = m; },null,false);
        
    };
    
    /**
     * Obtiene los tipos desde la definicion de sistema
     * 
     * @return {undefined}
     */
    this.getTipos = function(){
        this.query('obtener_tipos',null,
            function(m){ this.tipos = m;},null,false);
    };
    
    /**
     * Obtiene los resultados genericamente
     * 
     * @return {undefined}
     */
    this.getContenidoGenerico = function(){
        this.query("listar_generico",
            {"entidad" : this.entidad},
            function(m){ this.resultado = m; },null,false);
    };
    
    this.editor = function(t){
        this.dom_editor = t;
        // ya tengo los tipos y la especificacion
        for (var x in this.especificacion) {
            // console.log(x + ": ");
            dom = this.inputAdecuado(x,this.especificacion[x]);
            if(dom !== 'nulo'){
                $(this.dom_editor).append(dom);
            }
        }
        
        $(this.dom_editor).append("<button onclick='javascript:Pyrus.guardar(window.id_)';> guardar</button>");
        $(this.dom_editor).append("<button onclick='javascript:Pyrus.cancelar()';>cancelar</button>");
        
    };
    
    /**
     * Dado un objeto DOM, se transfiere a una funcion definida dentro
     * de userBean (listarEntidad) lo requerido para poder listar 
     * una tabla generica de dataTables
     * 
     * @param {type} t
     * @return {undefined}
     */
    this.listador = function(t){
        this.dom_listador = t;
        // t es donde se dibujara
        // convierto las especificaciones en columnas validas para datatables
        col = [];
        for(var x in this.especificacion) col.push({title: x});
        res = [];
        for(var x in this.resultado){
            tmp = [];
            for(var z in this.especificacion){
                    v = this.convertirAVisible(
                            this.resultado[x][z],
                            this.especificacion[z]);
                    tmp.push(v);
                }
            res.push(tmp);
        } 
        userBean.listarEntidad(col,res,t);
    };
    
    /**
     * Guarda el objeto siendo modificado actualmente
     * TODO: en $('.') agregar tambien la clase o id del div que lo contiene
     * para poder diferenciarlo de otros campos de nombre igual
     * 
     * @param integer id ID del objeto a ser modificado ('nulo' para nuevo)
     * @return {undefined}
     */
    this.guardar = function(id){
        // dado un id, guardo lo que tenga en el editor, primero creo
        // un objeto igual a la entidad 
        e = new Object;
        // copio especificacion, y elimino id
        esp = Object.assign({},this.especificacion);
        delete esp['id'];
        // supongo que el formato ya esta cargado en la pagina, traigo todo
        for(var x in esp)
            e[x] = $('.' + x).val();
        e['id'] = id;
        // guardo crudamente
        this.query('guardar_uno_generico',
            {'entidad':this.entidad,'objeto':e},
            function (m) { userBean.notificacion("se guardo exitosamente, id = " + m); },
            function (m) { userBean.notificacion("hubo un problema al guardar, reintente"); });
    };
    
    /**
     * Dado un id, lo carga en el modal para ser editado
     * TODO: en $('.') agregar tambien la clase o id del div que lo contiene
     * para poder diferenciarlo de otros campos de nombre igual
     * 
     * @param integer id ID del elemento a ser modificado
     * @return {undefined}
     */
    this.cargarAEditar = function(id){
        // se trae un elemento y se lo muestra en el modal
        if(id == 'nulo'){
            window.id_ = 'nulo';
            for(var x in this.especificacion){
                    try { $("." + x).val(''); }
                    catch(err){console.log("no se cargo " + x + " err:" + err); }
                }
        }else {
            this.query('mostrar_uno_generico',
                {'entidad':this.entidad,'id':id},
                function(m){
                    // saco a id
                    this.id = m['id'];
                    for(var x in this.especificacion){
                        try { $("." + x).val(m[x]); }
                        catch(err){console.log("no se cargo " + x + " err:" + err); }
                    }
                },
                function(m){ console.log("un error"); });
        }
    };
    
    /**
     * Un input adecuado devuelve un input adecuado y tambien le
     * setea el validador adecuado en la clase 
     * 
     * @param string a Nombre del atributo
     * @param array/object l Array u Object con la lista de propiedades
     * @return string Cadena que contiene el html adecuado
     */
    this.inputAdecuado = function(a,l){
        // se puede mostrar
        if(!this.existeEn(this.tipos['TP_VISIBLE_NUNCA'],l)){
            // si no existe un 'nombre_bonito' le asigno el mismo que
            // el nombre de la variable 
            if(l['nombre_bonito'] === undefined)
                l['nombre_bonito'] = a;
            // empiezo a preguntar por los tipos
            if(this.existeEn(this.tipos['TP_STRING'],l))
                return userBean.inputString(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_ENTERO'],l))
                return userBean.inputEntero(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_FLOTANTE'],l))
                return userBean.inputFlotante(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_FECHA_CORTA'],l))
                return userBean.inputFechaCorta(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_FECHA_LARGA'],l))
                return userBean.inputFechaLarga(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_IMAGEN'],l))
                return userBean.inputImagen(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_RELACION'],l))
                return userBean.inputSelector(a,l['relacion'],l['formato_visible'],l['nombre_bonito']);
            else // si no encaja con ninguno, va por string
                return userBean.inputString(a);
        } else return "<input type='hidden' class='" + a + "'>";
    };
    
    /**
     * Dado un valor y una especificacion de su atributo, lo convierte
     * a un valor legible humanamente o para el frente
     * 
     * @param object valor Variable a ser convertida
     * @param array/object esp Array u Objeto que lo especifica
     * @return {Object} Objeto ya convertido
     */
    this.convertirAVisible = function(valor,esp){
        if(this.existeEn(this.tipos['TP_RELACION'],esp)){
            relacion = esp['relacion'];
            ret = "";
            this.query("mostrar_uno_generico",
            {'entidad':relacion, 'id' : valor },
            function(m){
                ret += this.imprimirConFormato(esp['formato_visible'],m);
            },null,false);
            return ret;
            }
        if(this.existeEn(this.tipos['TP_FECHA_CORTA'],esp)){
            valor += " ";
            return valor.substr(6,2) + "/" + valor.substr(4,2) + "/" + valor.substr(0,4);
        }
        // ningun valor anterior
        return valor; 
    };
    
    /**
    * Dado una cadena formateada y una fuente de datos, devuelve una
    * cadena completa y bien rellenada de informacion, por ejemplo
    * si se envia "[id] : [nombre]", y se envia un array con {"id":2,
    * "nombre":"uno","apellido":"tal"} se devolvera un string con 
    * "2: uno". Modo de uso, el string tiene que tener espacios entre
    * palabras y simbolos claves
    * 
    * @param array/object datos Fuente de datos a leer
    * @param string formato Formato a devolver correctamente
    * @return string
    */
    this.imprimirConFormato = function (formato, datos) {
        // obtengo todos los elementos
        s = formato.split(" "); // separo por espacios
        k = [];
        ret = formato;
        for (var x in s) {
            if (s[x].indexOf("[") > -1) // si contiene
                k.push(s[x].replace("[", "").replace("]", "")); // obtengo el key
        }
        // una vez que tengo el key, reemplazo
        for (var i in k)
            ret = ret.replace("[" + k[i] + "]", datos[k[i]]);
        return ret;
    };
    
    /**
     * Consulta en un array u objeto si existe un elemento e en la lista 
     * o (array u objeto)
     * 
     * @param Object e Elemento a buscar
     * @param Object/Array o Objeto o Array a hacer la busqueda
     * @return bool
     */
    this.existeEn = function (e, o) {
        // asumo que me pasan un objeto, o lo que fuese
        if (Array.isArray(o))
            return o.includes(e);
        else {
            for (var x in o)
                if (o[x] === e)
                    return true;
            return false;
        }
    };

    // hago el llamado al constructor
    return this.constructor();
    
};