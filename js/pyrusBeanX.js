window.log_array = [];

/**
 * PyrusBean bajo objetos, a diferencia de UserBean, no debe ser instanciado
 * pyrusBean hace uso de UserBean como si fuese una estatica
 * 
 * @version 0.1 minor
 * @param string e Entidad
 * @return pyrusBean
 */
Pyrus = function(e = null,ppyrus = {}){
    
    this.entidad = e; // entidad que se pasa por parametro
    this.especificacion = null; // especificacion de la entidad
    this.tipos = null; // la entidad conserva su copia de tipos
    this.resultado = null; // resultados de un listado generico
    this.dom_listador = null; // elemento DOM donde se dibujara un listador
    this.dom_editor = null; // elemento DOM donde se dibujara un editor
    this.columnas = null;
    this.formato = null; // formatos de las columnas -> solo hay 3 (normal / fecha / moneda)
    /**
     * Constructor del PyrusBean
     * 
     * @param string e Entidad a buscar
     * @return NO RETORNA
     */
    this.constructor = function(){
        if(this.entidad === null || this.entidad === ""){
            console.log("AVISO: No se ha pasado ninguna entidad. Uso limitado");
            // no hago ninguna operacion de carga
            return false;
        }
        // cargo el objeto
        this.getTipos();
        this.getEspecificacion();
        this.getColumnas(); // formatea el nombre de las columnas a nombre bonito
        this.getContenidoGenerico();
    };
    
    /**
     * Setea el nombre de las columnas desde la especificacion
     */
    this.getColumnas = function(){
        this.columnas = [];
        this.formato = {};
        for(var x in this.especificacion) {
            if(this.especificacion[x]['nombre_bonito'] === undefined)
                this.columnas.push(x);
            else 
                this.columnas.push(this.especificacion[x]['nombre_bonito']);

            if(this.especificacion[x]['tipo'] !== undefined)
                this.formato[x] = this.especificacion[x]['tipo'];
        }
    };
    /**
    *
    */
    this.reverse_ = function(){
        arr = Object.keys(this.resultado);
        arr = arr.reverse();
        aux = {};
        for(var i in arr) {
            aux[arr[i] + "_"] = this.resultado[arr[i]];
        }
        this.resultado = aux;
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
            function(m){ this.resultado = m; console.log(m) },null,false);
    };
    
    this.editor = function(t,title){//Abtn [{"nombre":x,"onclick":x,"class":}]
        this.dom_editor = t;// el id del modal
        // ya tengo los tipos y la especificacion
        $(this.dom_editor).find(".modal-title").text(title);
        $(this.dom_editor).find("div.modal-body").html("");
        $(this.dom_editor).find("div.modal-footer").html("");
        for (var x in this.especificacion) {
            dom = this.inputAdecuado(x,this.especificacion[x]);
            if(dom !== 'nulo'){
                $(this.dom_editor).find("div.modal-body").append(dom);
            }
        }
    };
    this.editor__footer = function(t,Abtn) {
        this.dom_editor = t;// el id del modal
        $(this.dom_editor).find(".modal-footer").html("");
        for(var i = 0; i < Abtn.length;i++) {
            var btn = "";
            if(Abtn[i].onclick == "-1")
                btn = "<button type='"+Abtn[i].type+"' data-dismiss='modal' class='"+Abtn[i].class+"'>"+Abtn[i].nombre+"</button>";
            else if(Abtn[i].onclick == "")
                btn = "<button type='"+Abtn[i].type+"' class='"+Abtn[i].class+"'>"+Abtn[i].nombre+"</button>";
            else
                btn = "<button type='"+Abtn[i].type+"' onclick="+Abtn[i].onclick+" class='"+Abtn[i].class+"'>"+Abtn[i].nombre+"</button>";
            $(this.dom_editor).find(".modal-footer").append(btn);
        }
        $(this.dom_editor).find(".modal-footer").append("<input class='d-none' type='reset' value='reset'>");
    }
    
    this.editor__ = function(t,title = ""){
        this.dom_editor = t;// el id del modal
        if(title != "") $(this.dom_editor).closest(".modal-content").find(".modal-title").text(title);
        $(this.dom_editor).html("");
        for (var x in this.especificacion) {
            dom = this.inputAdecuado(x,this.especificacion[x]);
            if(dom !== 'nulo'){
                $(this.dom_editor).append(dom);
            }
        }
    };
    /**
     * format = [[{"column":xx,"tam":xx}]]
     * @param {type} t
     * @return {undefined}
     */
    this.editor__especial = function(t,format,Abtn = [],label = true,value = {},disabled = [],function__ = {}) {
        this.dom_editor = t;
        var html = "";
        for(var x in format) {
            html += "<div class=\"row padding__bottom__10\">";
            for(var y in format[x]) {
                if(format[x][y]['contenedor'] === undefined) {
                    i = format[x][y].especificacion;
                    t = format[x][y].tam;
                    d = "";f = {};
                    if(this.existeEn(i,disabled)) d = "disabled='true'";
                    if(function__[i] != null) f = function__[i];
                    dom = this.inputAdecuado__(i,this.especificacion[i],t,label,value,d,f);
                    if(dom !== 'nulo'){
                        html += dom;
                    }
                } else html += "<div class=\"" +  format[x][y]['contenedor'] + "\"></div>";
            }
            html += "</div>";
        }

        if(Abtn.length > 0) {
            html += "<div class=\"row padding__bottom__10 justify-content-md-center\">";
            for(var i = 0; i < Abtn.length;i++) {
                var btn = "";
                if(Abtn[i].onclick == "-1")
                    btn = "<button type='"+Abtn[i].type+"' data-dismiss='modal' class='"+Abtn[i].class+"'>"+Abtn[i].nombre+"</button>";
                else if(Abtn[i].onclick == "")
                    btn = "<button type='"+Abtn[i].type+"' class='"+Abtn[i].class+"'>"+Abtn[i].nombre+"</button>";
                else
                    btn = "<button type='"+Abtn[i].type+"' onclick="+Abtn[i].onclick+" class='"+Abtn[i].class+"'>"+Abtn[i].nombre+"</button>";
                html += "<div class=\"col col-12 col-md-6\">" + btn + "</div>";
            }
            html += "</div>";
        }
        $(this.dom_editor).html(html);
    }
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
        res = [];
        if(Object.keys(ppyrus).length == 0) {
            for(var x in this.columnas) col.push({title: this.columnas[x]});
            for(var x in this.resultado){
                tmp = [];
                for(var z in this.especificacion){
                        if(this.formato[z] === undefined)
                            v = this.convertirAVisible(
                                    this.resultado[x][z],
                                    this.especificacion[z]);
                        else {
                            switch(this.formato[z]) {
                                case "moneda": r = userBean.formatearNumero(this.resultado[x][z]);break;
                                default: r = this.resultado[x][z];
                            }
                            v = this.convertirAVisible(
                                    r,
                                    this.especificacion[z]);
                        }
                        tmp.push(v);
                    }
                res.push(tmp);
            }
        } else {
            for(var x in ppyrus["columnas"]) col.push({title: ppyrus["columnas"][x]});
            for(var x in this.resultado){
                tmp = [];
                for(var y in ppyrus["columnas"]) {
                    if(this.especificacion[y] !== undefined) {
                        if(this.formato[y] === undefined)
                            v = this.convertirAVisible(
                                    this.resultado[x][y],
                                    this.especificacion[y]);
                        else {
                            switch(this.formato[y]) {
                                case "moneda": r = userBean.formatearNumero(this.resultado[x][y]);break;
                                default: r = this.resultado[x][y];
                            }
                            v = this.convertirAVisible(
                                    r,
                                    this.especificacion[y]);
                        }
                        tmp.push(v);
                    }
                }
                res.push(tmp);
            }
        }
        userBean.listarEntidad(col,res,t);
    };

    this.listado_combinado = function(t) {
        this.dom_listador = t;
        // {"resultado":_m.busqueda__arrr(),"columnas": columnas}
        col = [];
        rr = [];
        mov = {};

        for(var i in ppyrus["resultado"]) {
            if(!mov[ppyrus["resultado"][i]["id_cheque"]]) {
                mov[ppyrus["resultado"][i]["id_cheque"]] = {};//id_cheque N° SERIE
                mov[ppyrus["resultado"][i]["id_cheque"]] = ppyrus["resultado"][i];
            }
        }

        for(var x in ppyrus["columnas"]) col.push({title: ppyrus["columnas"][x]});

        arr = this.busqueda__arrr();
        for(var x in arr) {
            tmp = [];
            for(var y in ppyrus["columnas"]) {
                if(arr[x][y] !== undefined) tmp.push(arr[x][y]);
                else {
                    if(mov[arr[x]["n_serie"]][y] !== undefined) tmp.push(mov[arr[x]["n_serie"]][y]);
                    else break;
                }
            }
            if(tmp.length != 0) rr.push(tmp);
        }
        userBean.listarEntidad(col,rr,t);
    }

    this.listador__ = function(t,columnas = []){
        this.dom_listador = t;
        // t es donde se dibujara
        // convierto las especificaciones en columnas validas para datatables
        col = [];
        for(var x in this.columnas) col.push({title: this.columnas[x]});
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
        userBean.listarEntidad__(col,res,t,columnas);
    };

    /**
     * Guarda el objeto siendo modificado actualmente
     * TODO: en $('.') agregar tambien la clase o id del div que lo contiene
     * para poder diferenciarlo de otros campos de nombre igual
     * 
     * @param integer id ID del objeto a ser modificado ('nulo' para nuevo)
     * @return {undefined}
     */
    this.guardar = function(id = "nulo",c = "",obj = {}){
        let flag = false;
        if(Object.keys(obj).length == 0) {
            // dado un id, guardo lo que tenga en el editor, primero creo
            // un objeto igual a la entidad 
            e = new Object;
            // copio especificacion, y elimino id
            esp = Object.assign({},this.especificacion);
            delete esp['id'];
            // supongo que el formato ya esta cargado en la pagina, traigo todo
            for(var x in esp) {
    			console.log("entro a guardar " + x);
    			// si es un file, cargo la imagen a la variable
    			if($(c + ' .' + x).attr("type") == "file"){
                    e[x] = "";
                    if($(c + ' .' + x).val() != "")
    				    e[x] = document.querySelector(".cheque_1 .imagen").imagen_base64;
    			} else {
    				e[x] = $(c + ' .' + x).val();
    				if($(c + ' .' + x).data("tipo") !== undefined && $(c + ' .' + x).data("tipo") == "moneda")
    					e[x] = userBean.limpiarMoneda(e[x]);
    				}
            }
            e['id'] = id;
        } else e = obj;
        // guardo crudamente
        this.query('guardar_uno_generico',
            {'entidad':this.entidad,'objeto':e},
            function (m) { /*userBean.notificacion("se guardo exitosamente");*/ window.log_array.push("guardado " + this.entidad + " id " + m); flag = m; },
            function (m) { /*userBean.notificacion("hubo un problema al guardar, reintente");*/ window.log_array.push("guardado " + this.entidad + " error " + m); console.log(m) },null,false);

        return flag;
    };
    
    /**
     * Dado un id, lo carga en el modal para ser editado
     * TODO: en $('.') agregar tambien la clase o id del div que lo contiene
     * para poder diferenciarlo de otros campos de nombre igual
     * 
     * @param integer id ID del elemento a ser modificado
     * @return {undefined}
     */
    this.cargarAEditar = function(id,class_ = ""){
        // se trae un elemento y se lo muestra en el modal
        if(id == 'nulo'){
            window.id_ = 'nulo';
            for(var x in this.especificacion){
                    try { $("." + x).val(''); }
                    catch(err){console.log("no se cargo " + x + " err:" + err); }
                }
        }else {
            console.log(id);
            this.query('mostrar_uno_generico',
                {'entidad':this.entidad,'id':id},
                function(m){
                    // saco a id
                    this.id = m['id'];

                    for(var x in this.especificacion){
                        try {
                            if($(class_ + " ." + x).is("input")) {
                                if($(class_ + " ." + x).attr("type") == "hidden") {
                                    if($(class_ + " ." + x).parent().find("input[type='date']").length)
                                        $(class_ + " ." + x).parent().find("input[type='date']").val(this.convertirYYYYMMDDToDate(m[x])).trigger("change");
                                }
                            }
                            if(this.especificacion[x]["tipo"] === undefined)
                                $(class_ + " ." + x).val(m[x]).trigger("change");
                            else if(this.especificacion[x]["tipo"] == "moneda")
                                $(class_ + " ." + x).val(userBean.formatearNumero(m[x])).trigger("change");
                            else $(class_ + " ." + x).val(m[x]).trigger("change");
                        } catch(err){console.log("no se cargo " + x + " err:" + err); }
                    }
                },
                function(m){ console.log("un error"); });
        }
    };

    /*
     *
     */
    this.selectDatos = function(t,nombre,identificador,tam = "col col-12",dato = "[nombre] [apellido]",estado = "agregar",disabled = "",function__ = {},formato = []){
        /*
         * estado ['agregar']: Permite agregar elementos / entidades
         * tam: tamaño del contenedor del select
         * disabled: Array de elementos desactivados
         * function_: Objeto de funciones de un elemento
         * formato: formato a mostrar en la vista (normal, fecha o num c/ decimales)
         */
         console.log(t)
        $(t).append(userBean.inputSelector__(identificador,this.entidad,dato,nombre,estado,tam,false,disabled,function__,formato));
    }
    
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
            if(l['tipo'] === undefined)
                l['tipo'] = 'normal';
            // empiezo a preguntar por los tipos
            if(this.existeEn(this.tipos['TP_STRING'],l))
                return userBean.inputString(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_ENTERO'],l))
                return userBean.inputEntero(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_FLOTANTE'],l))
                return userBean.inputFlotante(a,l['nombre_bonito'],l['tipo']);
            if(this.existeEn(this.tipos['TP_FECHA_CORTA'],l))
                return userBean.inputFechaCorta(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_FECHA_LARGA'],l))
                return userBean.inputFechaLarga(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_IMAGEN'],l))
                return userBean.inputImagen(a,l['nombre_bonito']);
            if(this.existeEn(this.tipos['TP_RELACION'],l))
                return userBean.inputSelector(a,l['relacion'],l['formato_visible'],l['nombre_bonito'],l['estado']);
            else // si no encaja con ninguno, va por string
                return userBean.inputString(a);
        } else return "<input type='hidden' class='" + a + "'>";
    };
    this.inputAdecuado__ = function(a,l,t,label,value,disabled,function__){
        // se puede mostrar
        if(!this.existeEn(this.tipos['TP_VISIBLE_NUNCA'],l)){
            // si no existe un 'nombre_bonito' le asigno el mismo que
            // el nombre de la variable 
            if(l['nombre_bonito'] === undefined)
                l['nombre_bonito'] = a;
            if(l['tipo'] === undefined)
                l['tipo'] = 'normal';
            // empiezo a preguntar por los tipos
            if(this.existeEn(this.tipos['TP_STRING'],l))
                return userBean.inputString__(a,l['nombre_bonito'],t,label,disabled,function__);
            if(this.existeEn(this.tipos['TP_ENTERO'],l))
                return userBean.inputEntero__(a,l['nombre_bonito'],t,label,disabled,function__);
            if(this.existeEn(this.tipos['TP_FLOTANTE'],l))
                return userBean.inputFlotante__(a,l['nombre_bonito'],t,label,disabled,function__,l['tipo']);
            if(this.existeEn(this.tipos['TP_FECHA_CORTA'],l))
                return userBean.inputFechaCorta__(a,l['nombre_bonito'],t,label,disabled,function__);
            if(this.existeEn(this.tipos['TP_FECHA_LARGA'],l))
                return userBean.inputFechaLarga(a,l['nombre_bonito'],t,label,disabled,function__);
            if(this.existeEn(this.tipos['TP_IMAGEN'],l))
                return userBean.inputImagen__(a,l['nombre_bonito'],t,label,disabled,function__);
            if(this.existeEn(this.tipos['TP_RELACION'],l)) {
                if(value[a] != null) return "<input type='hidden' class='" + a + "' value='" + value[a] + "'>";
                else return userBean.inputSelector__(a,l['relacion'],l['formato_visible'],l['nombre_bonito'],l['estado'],t,label,disabled,function__);
            }
            else // si no encaja con ninguno, va por string
                return userBean.inputString__(a);
        } else {
            if(value[a] != null) return "<input type='hidden' class='" + a + "' value='" + value[a] + "'>";
            else return "<input type='hidden' class='" + a + "'>";  
        } 
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
        if(valor != null || valor != "") {
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
        }
        // ningun valor anterior
        return valor; 
    };
    /**
    *
    */
    this.convertirDateToYYYYMMDD = function(d) {
        var date = d.val();
        var date__ = date.split("-");

        d.next().val(date.replace(/-/g,""));
    }
    this.convertirYYYYMMDDToDate = function(valor) {
        valor += " ";
        return valor.substr(0,4) + "-" + valor.substr(4,2) + "-" + valor.substr(6,2);
    }
    this.convertirYYYYMMDDToFecha = function(valor) {
        valor += " ";
        return valor.substr(6,2) + "/" + valor.substr(4,2) + "/" + valor.substr(0,4);
    }
    
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
    this.imprimirConFormato = function (formato, datos, formato_mostrar = []) {
        // obtengo todos los elementos
        s = formato.split(" "); // separo por espacios
        k = [];
        ret = formato;
        for (var x in s) {
            if (s[x].indexOf("[") > -1) // si contiene
                k.push(s[x].replace("[", "").replace("]", "")); // obtengo el key
        }
        // una vez que tengo el key, reemplazo
        for (var i in k) {
			if(k[i].indexOf("/") > -1){ // contiene relacion mas adentro
				tx = k[i].split("/"); // ["fk"/"key remota 1lvl"/"key remota 2lvl"]
                console.log(tx);
				this.query("mostrar_uno_generico",{'entidad':tx[0],'id': datos[tx[1]]},
					function(m){
						ret = ret.replace("[" + k[i] + "]", m[tx[2]]);
						// ret = m[tx[2]];
					},null,false);
			} else if(datos != null) {
                if(formato_mostrar.length > 0) {
                    switch(formato_mostrar[i]) {
                        case "normal": ret = ret.replace("[" + k[i] + "]", datos[k[i]]); break;
                        case "fecha": ret = ret.replace("[" + k[i] + "]", this.convertirYYYYMMDDToFecha(datos[k[i]])); break;
                        case "moneda": ret = ret.replace("[" + k[i] + "]", userBean.formatearNumero(datos[k[i]])); break;
                    }
                } else ret = ret.replace("[" + k[i] + "]", datos[k[i]]);
            }
            else ret = "";
        }
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
	
	/**
	 * Consulta en el array de resultados un valor dado por val en
	 * en el atributo dado por atr
	 *
	 * @param string atr Atributo a seleccionar 
	 * @param string val Valor a ser encontrado
	 */
	this.busqueda = function(atr,val){
		var arr_res = $.map(this.resultado, function(value, index) { return [value]; });
		return arr_res.find(x => x[atr] === val);
	}

    this.busqueda__DID = function() {
        var arr_res = $.map(this.resultado, function(value, index) { return [value]; });
        if(arr_res.length == 0) return 1;
        else return parseInt(arr_res[arr_res.length - 1]["did"]) + 1;
    }

    this.busqueda__X = function(atr,id) {
        var r = "";
        var arr_res = $.map(this.resultado, function(value, index) { return [value]; });
        for(var i in arr_res) {
            if(arr_res[i][atr] == id) {
                r = arr_res[i]["id"];
                break;
            }
        }
        return r;
    }

    this.busqueda__XX = function(atr,search = {}) {
        var r = "";
        var arr_res = $.map(this.resultado, function(value, index) { return [value]; });
        for(var i in arr_res) {
            for(var j in search) {
                f = 1;
                if(arr_res[i][j] != search[j]) {
                    f = 0;
                    break;
                    /*
                     * Buscamos un elemento
                     */
                }
                if(f) {
                    r = arr_res[i][atr];
                    break;
                }
            }
        }
        return r;
    }    

    this.objeto = function(id) {
        var r = {};
        var arr_res = $.map(this.resultado, function(value, index) { return [value]; });
        for(var i in arr_res) {
            if(arr_res[i]["id"] == id) {
                r = arr_res[i];
                break;
            }
        }
        return r;
    }
    this.busqueda__arr = function(attr = "",val = "") {
        var r = [];
        var arr_res = $.map(this.resultado, function(value, index) { return [value]; });
        if(attr == "") return arr_res;
        for(var i in arr_res) {
            if(arr_res[i][attr] == val)
                r.push(arr_res[i]);
        }
        if(attr == "id") return r[0];
        return r;
    }

    this.busqueda__arrr = function(attr = "",val = ""){
        res = [];
        for(var x in this.resultado){
            tmp = {};

            for(var z in this.especificacion){
                    if(this.formato[z] === undefined)
                        v = this.convertirAVisible(
                                this.resultado[x][z],
                                this.especificacion[z]);
                    else {
                        switch(this.formato[z]) {
                            case "moneda": r = userBean.formatearNumero(this.resultado[x][z]);break;
                            default: r = this.resultado[x][z];
                        }
                        v = this.convertirAVisible(
                                r,
                                this.especificacion[z]);
                    }
                    tmp[z] = v;
                }
            if(attr == "") res.push(tmp);
            else {
                if(this.resultado[x][attr] == val) res.push(tmp);
            }
        }
        if(attr == "id") return res[0];
        return res;
    };

    this.busqueda__ = function(atr,val,class_){
        var arr_res = $.map(this.resultado, function(value, index) { return [value]; });
        var id = 0;
        if(atr == "id") id = val;
        else {
            for(var i in arr_res) {
                if(arr_res[i][atr] == val) {
                    id = arr_res[i]["id"];
                    break;
                }
            }
        }
        if(id != 0) this.cargarAEditar(id,class_);
        else $(class_).find("*").removeAttr("disabled");
        
        return id;
    }
    // hago el llamado al constructor
    return this.constructor();
    
};


