
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
		if(window.columnasx === undefined){
			window.columnasx = [];
			window.formatox = [];
		}
		if(window.columnasx[this.entidad] === undefined){
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
			window.columnasx[this.entidad] = this.columnas;
			window.formatox[this.entidad] = this.formato;
		} else {
			this.columnas = window.columnasx[this.entidad];
			this.formato = window.formatox[this.entidad];
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
		// creo especificacion si no existe
		if(window.especificacion === undefined)
			window.especificacion = [];
		// existe para esta entidad, si no es asi, la creo y la copio, si no, solo copio
		if(window.especificacion[this.entidad] === undefined){		
			this.query(
				"especificacion",
				{"entidad" : this.entidad},
				function(m){ window.especificacion[this.entidad] = m; this.especificacion = m; },null,false);
		} else {
			this.especificacion = window.especificacion[this.entidad];
		}
    };
    
    /**
     * Obtiene los tipos desde la definicion de sistema
     * 
     * @return {undefined}
     */
    this.getTipos = function(){
		// si los tipos ya fueron cargados, los copio
		if(window.tipos === undefined){
			this.query('obtener_tipos',null,
				function(m){ window.tipos = m; this.tipos = m;},null,false);
		} else {
			this.tipos = window.tipos;
		}
    };
    
    /**
     * Obtiene los resultados genericamente
     * 
     * @return {undefined}
     */
    this.getContenidoGenerico = function(){
		if(window.resultado === undefined)
			window.resultado = [];
		if(window.resultado[this.entidad] === undefined){
			this.query("listar_generico",
				{"entidad" : this.entidad},
				function(m){ window.resultado[this.entidad] = m; this.resultado = m; },null,false);
		} else {
			this.resultado = window.resultado[this.entidad];
		}
    };
    
    this.editor = function(t,title){//Abtn [{"nombre":x,"onclick":x,"class":}]
        this.dom_editor = t;// el id del modal
        // ya tengo los tipos y la especificacion
        $(this.dom_editor).find(".modal-title").html(title);
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
            html += "<div class=\"row justify-content-md-center padding__bottom__10\">";
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
     *  Crea una línea del formulario, con 1 o más elementos de la entidad
     * @param string t - contenedor donde se agrega
     * @param array formato [{xx:"col"},{yy:col}]
     * @param object valor {xx:"v1",yy:"v2"}: si tiene un valor por default
     * @param array desactivado [xx,yy]
     * @param object funcion {xx:[{},{}]}
     * @param bool visible: si el elemento se muestra o esta escondido
     * @param bool hidden: vuelve un elemento input hidden, sin importar que sea
     * @paran select_value_default: el primer valor del select
     * valor = {},desactivado = [],funcion = {},data = null,visible = true,hidden = false,required = [],select_value_default = null
     */
    this.editor__X = function(contenedor,formato,valor = {},desactivado = [],funcion = {},data = null,visible = true,hidden = false,required = [],select_value_default = null) {
        this.dom_editor = contenedor;
        var html = "";
            _tmp = "";
            _data = "";
        if(Array.isArray(data)) {//Si es array, se arma el data-x. Solo afecta al contenedor
            for(var d in data) {
                for(var e in data[d]) {
                    if(_data != "") _data += " ";
                    _data += "data-" + e + "='" + data[d][e] + "'";
                }
            }
        }
        for(var xx in formato) {
            for(var yy in formato[xx]) {
                _clase = formato[xx][yy];
                _especificacion = yy;
                _desactivado = false;
                _required = true;
                _valor = "";
                _funcion = [];

                if(this.existeEn(yy,desactivado)) _desactivado = true;
                if(this.existeEn(yy,required)) _required = false;
                if(userBean.isObject(valor)) if(valor[yy] !== undefined) _valor = valor[yy];
                if(userBean.isObject(funcion)) if(funcion[yy] !== undefined) _funcion = funcion[yy];
                
                ARR_input = {};
                ARR_input["identificador"] = _especificacion;
                ARR_input["especificacion"] = this.especificacion[yy];
                ARR_input["valor"] = _valor;
                ARR_input["funcion"] = _funcion;
                ARR_input["desactivado"] = _desactivado;
                ARR_input["hidden"] = hidden;
                ARR_input["required"] = _required;
                ARR_input["select_value_default"] = select_value_default;
                
                if(_clase != "") _tmp += "<div class=\"" + _clase + "\">";
                    _tmp += this.inputAdecuado__X(ARR_input);
                if(_clase != "") _tmp += "</div>";
            }
        }
        html += "<div class=\"row padding__bottom__10 justify-content-md-center " + (!visible ? "d-none" : "") + "\" " + _data + ">" + _tmp + "</div>";
        $(this.dom_editor).append(html);
    }
    /**
     * Agrega Botones a un formulario
     * @param array botones: {xx:{formato:"col col-12",tipo:"",clase:"",funcion:[{aa:bb},{cc,dd}]},data:[{t:v}]}
     */
    this.editor__X_footer = function(t,botones) {
        this.dom_editor = t;
        html = "<div class=\"row padding__bottom__10 justify-content-md-center\">";
        for(var xx in botones) {
            funcion = "";
            data = "";
            for(var yy in botones[xx]["funcion"]) {
                for(var zz in botones[xx]["funcion"][yy]) {
                    if(funcion != "") funcion += " ";
                    funcion += zz + "=" + botones[xx]["funcion"][yy][zz];
                }
            }
            for(var yy in botones[xx]["data"]) {
                for(var zz in botones[xx]["data"][yy]) {
                    if(data != "") funcion += " ";
                    data += "data-" + zz + "=" + botones[xx]["data"][yy][zz];
                }
            }
            html += "<div class=\"" + botones[xx]["formato"] + "\">";
                html += "<button class=\"btn " + botones[xx]["clase"] + "\" " + data + " " + funcion + " type=\"" + botones[xx]["tipo"] + "\">"
                    + xx
                    + "</button>";
            html += "</div>";
        }
        html += "</div>";
        $(this.dom_editor).append(html);
        $(this.dom_editor).append("<input class='d-none' type='reset' value='reset'>");
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
        console.log(Object.keys(ppyrus).length);
        if(Object.keys(ppyrus).length == 0) {
            for(var x in this.columnas) col.push({title: this.columnas[x]});
            for(var x in this.resultado){
                tmp = [];
                for(var z in this.especificacion){
                    if(this.formato[z] === undefined) {
                        v = this.convertirAVisible(
                                this.resultado[x][z],
                                this.especificacion[z]);
                        console.log(v)
                    }
                    else {
                        switch(this.formato[z]) {
                            case "moneda": r = userBean.formatearNumero(this.resultado[x][z]);break;
                            default: r = this.resultado[x][z];
                        }
                        console.log(r)
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
        console.log(res)
        userBean.listarEntidad(col,res,t);
    };

    this.listado_combinado = function(t) {
        this.dom_listador = t;
        // {"resultado":_m.busqueda__arrr(),"columnas": columnas}
        col = [];
        rr = [];
        mov = {};

        aux_key = Object.keys(ppyrus["resultado"]);
        console.log(ppyrus["resultado"])
        for(var i = aux_key.length - 1; i >= 0; i--) {
            if(!mov[ppyrus["resultado"][aux_key[i]]["id_cheque"]]) {
                mov[ppyrus["resultado"][aux_key[i]]["id_cheque"]] = {};//id_cheque N° SERIE
                mov[ppyrus["resultado"][aux_key[i]]["id_cheque"]] = ppyrus["resultado"][aux_key[i]];
            }
            console.log(ppyrus["resultado"][aux_key[i]]["id_cheque"])
        }
        console.log(mov)
        for(var x in ppyrus["columnas"]) col.push({title: ppyrus["columnas"][x]});
        arr = this.busqueda__arrr();
        for(var x in arr) {
            tmp = [];
            for(var y in ppyrus["columnas"]) {
                if(arr[x][y] !== undefined) tmp.push(arr[x][y]);
                else {
                    console.log(arr[x]["n_serie"]);
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
    				    e[x] = document.querySelector(c + ".imagen").imagen_base64;
    			} else {
    				e[x] = $(c + ' .' + x).val();
    				if($(c + ' .' + x).data("tipo") !== undefined && $(c + ' .' + x).data("tipo") == "moneda")
    					e[x] = userBean.limpiarMoneda(e[x]);
    				}
            }
            e['id'] = id;
        } else e = obj;
        // guardo crudamente
        console.log(e)

        this.query('guardar_uno_generico',
            {'entidad':this.entidad,'objeto':e},
            function (m) { /*userBean.notificacion("se guardo exitosamente");*/ window.log_array.push((id == "nulo" ? "guardado " : "editado ") + this.entidad + " id " + m); flag = m; },
            function (m) { /*userBean.notificacion("hubo un problema al guardar, reintente");*/ window.log_array.push((id == "nulo" ? "guardado " : "editado ") + this.entidad + " error " + m); console.log(m) },null,false);

        return flag;
    };

    this.eliminar = function(id) {
        //arr = this.busqueda__arr("id",id);
        //arr["activo"] = 0;
        console.log(id)
        this.query('baja_generica',
            {'entidad':this.entidad,'id':id},
            function (m) { window.log_array.push("eliminado " + this.entidad + " id " + m); },
            function (m) { window.log_array.push("eliminado " + this.entidad + " error " + m); console.log(m) },null,false);

    }
    
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
            this.query('mostrar_uno_generico',
                {'entidad':this.entidad,'id':id},
                function(m){
                    // saco a id
                    this.id = m['id'];
                    console.log(this.especificacion);
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
    
    /**
     * Un input adecuado devuelve un input adecuado y tambien le
     * setea el validador adecuado en la clase 
     * 
     * @param string a Nombre del atributo
     * @param array/object l Array u Object con la lista de propiedades
     * @return string Cadena que contiene el html adecuado
     */
    this.inputAdecuado__X = function(ARR_datos) {
        ARR_input = {"clase":ARR_datos["identificador"],"funcion":ARR_datos["funcion"],"valor":ARR_datos["valor"]};
        console.log(ARR_datos)
        if(!this.existeEn(this.tipos['TP_VISIBLE_NUNCA'],ARR_datos["especificacion"])) {
            if(ARR_datos["especificacion"]['nombre_bonito'] === undefined) ARR_datos["especificacion"]['nombre_bonito'] = ARR_datos["identificador"]; 
            if(ARR_datos["especificacion"]['tipo'] === undefined) ARR_datos["especificacion"]['tipo'] = 'normal';
            ARR_input["especificacion"] = ARR_datos["especificacion"];
            ARR_input["desactivado"] = ARR_datos["desactivado"];
            ARR_input["hidden"] = ARR_datos["hidden"];
            ARR_input["required"] = ARR_datos["required"];
            ARR_input["select_value_default"] = ARR_datos["select_value_default"];

            // empiezo a preguntar por los tipos
            if(this.existeEn(this.tipos['TP_STRING'],ARR_datos["especificacion"]))
                return this.inputString_X(ARR_input);
            if(this.existeEn(this.tipos['TP_STRING_LARGO'],ARR_datos["especificacion"]))
                return this.inputString_XX(ARR_input);
            if(this.existeEn(this.tipos['TP_ENTERO'],ARR_datos["especificacion"]))
                return this.inputEntero_X(ARR_input);
            if(this.existeEn(this.tipos['TP_FLOTANTE'],ARR_datos["especificacion"]))
                return this.inputFlotante_X(ARR_input);
            if(this.existeEn(this.tipos['TP_FECHA_CORTA'],ARR_datos["especificacion"]))
                return this.inputFechaCorta_X(ARR_input);
            if(this.existeEn(this.tipos['TP_FECHA_LARGA'],ARR_datos["especificacion"]))
                return this.inputFechaLarga_X(ARR_input);
            if(this.existeEn(this.tipos['TP_IMAGEN'],ARR_datos["especificacion"]))
                return this.inputImagen_X(ARR_input);
            if(this.existeEn(this.tipos['TP_PASSWORD'],ARR_datos["especificacion"]))
                return this.inputPassword(ARR_input);
            if(this.existeEn(this.tipos['TP_RELACION'],ARR_datos["especificacion"]))
                return this.inputSelector_X(ARR_input);
            else // si no encaja con ninguno, va por string
                return this.inputString_X(ARR_input);
        } else return this.inputHidden_X(ARR_input);
    }
    this.inputPassword = function(datos) {
        return "<input value='' placeholder='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' name='" + datos["clase"] + "' type='password' class='form-control " + datos["clase"] + "'/>";
    }
    this.inputHidden_X = function(datos) {
        return "<input value='" + datos["valor"] + "' name='" + datos["clase"] + "' type='hidden' class='" + datos["clase"] + "'/>";
    }
    this.inputString_X = function(datos) {
        funcion = "";
        for(var i in datos["funcion"]) {
            for(var j in datos["funcion"][i]) {
                if(funcion != "") funcion += " ";
                funcion +=  j + "=" + datos["funcion"][i][j];
            }
        }
        return "<input " + (datos["required"] ? "required='true'" : "") + " " + (datos["desactivado"] ? "disabled='true'" : "") + " value='" + datos["valor"] + "' " + funcion + " title='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' placeholder='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' name='" + datos["clase"] + "' type='text' class='form-control val_string " + datos["clase"] + "'/>";
    }
    this.inputString_XX = function(datos) {
        funcion = "";
        for(var i in datos["funcion"]) {
            for(var j in datos["funcion"][i]) {
                if(funcion != "") funcion += " ";
                funcion +=  j + "=" + datos["funcion"][i][j];
            }
        }
        return "<textarea " + (datos["required"] ? "required='true'" : "") + " " + (datos["desactivado"] ? "disabled='true'" : "") + " " + funcion + " title='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' placeholder='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' name='" + datos["clase"] + "' class='form-control val_string " + datos["clase"] + "'>" + datos["valor"] + "</textarea>";
    }
    this.inputEntero_X = function(datos) {
        return "<input " + (datos["required"] ? "required='true'" : "") + " " + (datos["desactivado"] ? "disabled='true'" : "") + " value='" + datos["valor"] + "' title='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' placeholder='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' name='" + datos["clase"] + "' type='number' min='0' class='form-control val_integer " + datos["clase"] + "'/>";
    }
    this.inputFlotante_X = function(datos) {
        dataTipo = "";
        classTipo = "";
        if(datos["especificacion"]["tipo"] == "moneda") {
            dataTipo = "data-tipo='moneda'";
            classTipo = "text-right";
        }
        funcion = "";
        for(var i in datos["funcion"]) {
            for(var j in datos["funcion"][i]) {
                if(funcion != "") funcion += " ";
                funcion +=  j + "=" + datos["funcion"][i][j];
            }
        }
        
        return "<input " + funcion + " " + (datos["required"] ? "required='true'" : "") + " " + (datos["desactivado"] ? "disabled='true'" : "") + " value='" + datos["valor"] + "' " + dataTipo + " title='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' placeholder='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' name='" + datos["clase"] + "' type='text' class='form-control val_float " + classTipo + " " + datos["clase"] + "'/>";
    }
    this.inputSelector_X = function(datos) {
        console.log(datos)
        if(datos["hidden"])
            return this.inputHidden_X(datos);
        if(datos["especificacion"]["estado"] == "uno")
            return this.inputString_X(datos);
        else {
            selector = "<select data-placeholder='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' " + (datos["required"] ? "required='true'" : "") + " " + (datos["desactivado"] ? "disabled='true'" : "") + " name='" + datos["clase"] + "' style='width:100%' data-placeholder='Seleccione " + datos["especificacion"]["nombre_bonito"] + "' data-allow-clear='true' class='form-control select__2 val_selector " + datos["clase"] + "' onchange=\"userBean.verEntidad(this,'" + datos["clase"] + "')\">";
            selector += "<option value=''></option>";
            if(datos["especificacion"]["estado"] == "agregar") {
                selector += "<optgroup label=\"Nuevo\">";
                selector += "<option value='" + datos["select_value_default"] + "'>Registro</option>";
                selector += "</optgroup>";
            }
            selector += "<optgroup label=\"Datos\">";

            estilo = [];
            if(datos["estilo"] !== undefined) estilo = datos["estilo"];
            this.query("listar_generico",{'entidad':datos["especificacion"]["relacion"] },
            function(m){
                gafsd = function () {  return false; };
                console.log(m)
                for(var x in m){
                    selector += "<option value='" + m[x]['id'] + "'>" +
                    this.imprimirConFormato(datos["especificacion"]["formato_visible"],m[x],estilo);
                    + "</option>";
                }
            },null,false);
            selector += "</select>";
        }
        return selector;
    }

    /*
     *
     */
    this.selectDatos_X = function(contenedor,nombre,identificador,formato = "col col-12",formato_visible = "[nombre] [apellido]",estado = "agregar",estilo = []){
        /*
        this.editor__X = function(contenedor,formato,valor = {},desactivado = [],funcion = {},data = null,visible = true,hidden = false,required = [],select_value_default = null) {
         * estado ['agregar']: Permite agregar elementos / entidades
         * tam: tamaño del contenedor del select
         * disabled: Array de elementos desactivados
         * function_: Objeto de funciones de un elemento
         * formato: formato a mostrar en la vista (normal, fecha o num c/ decimales)
         */
        this.dom_editor = contenedor;
        ARR_input = {};
        ARR_input["hidden"] = false;
        ARR_input["especificacion"] = {};
        ARR_input["especificacion"]["nombre_bonito"] = nombre;
        ARR_input["especificacion"]["estado"] = estado;
        ARR_input["especificacion"]["relacion"] = this.entidad;
        ARR_input["especificacion"]["formato_visible"] = formato_visible;
        ARR_input["select_value_default"] = -1;
        ARR_input["clase"] = identificador;
        ARR_input["required"] = true;
        ARR_input["desactivado"] = false;
        ARR_input["estilo"] = estilo;
        console.log(ARR_input)
        
        _tmp = this.inputSelector_X(ARR_input);
        //$(t).append(userBean.inputSelector__(identificador,this.entidad,dato,nombre,estado,tam,false,disabled,function__,formato));

        //html += "<div class=\"row padding__bottom__10 justify-content-md-center " + (!visible ? "d-none" : "") + "\" " + _data + ">" + _tmp + "</div>";
        html = "<div class=\"row padding__bottom__10 justify-content-md-center\">" +
                "<div class=\"" + formato + "\">" +
                    _tmp +
                "</div>" +
                "</div>";
        $(this.dom_editor).append(html);
    }

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
         console.log(nombre + " - " + dato)
        $(t).append(userBean.inputSelector__(identificador,this.entidad,dato,nombre,estado,tam,false,disabled,function__,formato));
    }
    this.inputFechaCorta_X = function (datos) {
        funcion = "";
        for(var i in datos["funcion"]) {
            for(var j in datos["funcion"][i]) {
                if(funcion != "") funcion += " ";
                funcion +=  j + "=" + datos["funcion"][i][j];
            }
        }

        input = "<input " + (datos["required"] ? "required='true'" : "") + " " + (datos["desactivado"] ? "disabled='true'" : "") + " " + funcion + " title='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' placeholder='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' type='date' class='form-control val_fechaCorta'/>";
        input += "<input value='" + datos["valor"] + "' type='hidden' name='" + datos["clase"] + "' class='" + datos["clase"] + "'/>";
        return input;
    };
    this.inputImagen_X = function (datos){
        return "<input " + (datos["required"] ? "required='true'" : "") + " " + (datos["desactivado"] ? "disabled='true'" : "") + " title='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' placeholder='" + (datos["especificacion"]["nombre_bonito"]).toUpperCase() + "' type='file' class='form-control val_imagen " + datos["clase"] + " xlk' name='" + datos["clase"] + "'/>";
    };

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
                console.log(esp['formato_visible'])
				if(window.resultado[relacion] === undefined){

					// traigo todas las personas
					new Pyrus(relacion); // solo me interesa traer su especificacion
					this.query("mostrar_uno_generico",
					{'entidad':relacion, 'id' : valor },
					function(m){
						ret += this.imprimirConFormato(esp['formato_visible'],m);
					},null,false);
				} else {
					// como ya tengo el resultado, solo traigo por id
					ret += this.imprimirConFormato(esp['formato_visible'],window.resultado[relacion][valor]);
				}
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
			//ret = ret.replace("[" + k[i] + "]", datos[k[i]]);
			if(k[i].indexOf("/") > -1){ // contiene relacion mas adentro
				tx = k[i].split("/"); // ["fk"/"key remota 1lvl"/"key remota 2lvl"]
				// hago la busqueda aqui
				if(window.resultado[tx[0]] === undefined) new Pyrus(tx[0]); // lo traigo
				// reemplazo
                
                ret = ret.replace("[" + k[i] + "]", window.resultado[tx[0]][datos[tx[1]]][tx[2]]);
				this.query("mostrar_uno_generico",{'entidad':tx[0],'id': datos[tx[1]]},
					function(m){
                        console.log(m);
                        console.log(k[i])
                        console.log(ret)
                        if(ret !== undefined) ret = ret.replace("[" + tx[i] + "]", m[tx[2]]);
                        else ret = m[tx[2]];
                        console.log(ret)
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
    this.existe = function(col,val) {
        existencia = true;
        this.query("buscar_uno_generico",{"entidad":this.entidad,"columna":col,"valor":val},
        function(m){ existencia = (m === null); },
        function(m){},null,false);

        return existencia;
    }
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
