

/**
 * Funcion de consulta desde JS, se comunica con el servidor 
 * y devuelve una respuesta acorde, uso generico.
 * Las funciones de callback solo conocen la respuesta en 
 * data, no el contexto de como fue recibido (ni estado, ni
 * mensaje)
 * 
 * @return {undefined}
 */
function query(accion,data,callbackOK,callbackFail = null){
    url_envio = url_query_local;
    console.log(url_envio)
    envio = { "accion":accion, "data":data };
    window.con = $.ajax({
        type: 'POST',
        // make sure you respect the same origin policy with this url:
        // http://en.wikipedia.org/wiki/Same_origin_policy
        url: url_envio,
        async: true,
        data: envio,
        success: function(msg){
            window.devolucion = JSON.parse(msg);
            callbackOK(window.devolucion['data']);
            },
        error: function(msg){
            window.error = msg;
            console.error(msg);
            if(!callbackFail)
                callbackFail(msg);
            }
        });
}

/**
 * Lista una entidad generica en una tabla usando DataTables
 * Todas las funciones relacionadas son internas
 * 
 * @param string e nombre de la entidad
 * @param string t nombre en jQuery de la tabla a escribir
 * @param funcion f_detalle funcion a llamar para pasarle el 
 *      id y mostrar detalles
 */
function listarEntidad(e,t,f_detalle = null){
    window.entidad = e;
    window.tabla = t;
    window.dataset = [];
    window.orden = [];
    window.funcion_detalle = f_detalle;
    
    /**
     * Funcion que crea un enlace 'personalizado'
     * 
     * @param {type} id
     * @param {type} texto
     * @param {type} onclick
     * @param {type} link
     * @return {String}
     */
    window.dibujar_enlace = function(id,texto,onclick,link){
        // retorno un enlace con el atributo a mostrar
        /*return "<a href='" + link + "' " +
                "onclick='javascript:" + onclick + "(" + id + ");'>" +
                texto + "</a>";*/
        var __icono = "";
        var __class = "";
        switch(texto) {
            case "ver":
                __class = "btn-success";
                __icono = '<i class="fas fa-eye"></i>';
                break;
        }

        return "<button class='btn " + __class + "' "+
                "onclick='javascript:" + onclick + "(" + id + ");'>" +
                __icono + "</button>";
    };
    
    /**
     * Funcion que llama a DataTable y se encarga de la vista basica
     * 
     * @return {undefined}
     */
    window.dibujar_tabla = function(){
        // convierto a nombre de columna de DataTables
        tmp = [];
        for(var i of window.orden) tmp.push({title: i});
        if(window.funcion_detalle !== null){
            tmp.push({title: 'Detalle',
                "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                    // oData es un array con la info de esta fila   
                    $(nTd).html(window.dibujar_enlace(sData,
                        'ver',
                        'window.function_detalle',
                        '#'));
                }
            });
        }
        $(t).DataTable({ data: window.dataset, columns: tmp });
    };
    
    /**
     * Obtengo una lista generica de las filas de la entidad
     * 
     * @param {type} m
     * @return {undefined}
     */
    window.obtener_info = function(m){
        for(var i in m){ // cada fila del resultado
            tmp = [];
            // cada columna de la especificacion
            for(var u of window.orden) tmp.push(m[i][u]);
            // agrego nueva columna para el id para pasar por parametro
            if(window.funcion_detalle !== null) tmp.push(m[i]['id']); 
            window.dataset.push(tmp);
        }
        window.dibujar_tabla(); // no paso un resultado, no es necesario parametro
    };
    
    /**
     * Obtengo la informacion de la entidad, extraigo nombre de columnas
     * 
     * @param {type} m
     * @return {undefined}
     */
    window.obtener_columnas = function(m){
        for(var i in m) window.orden.push(i);
        query("listar_generico",{"entidad" : window.entidad},function(m){ window.obtener_info(m); });
    };
    
    /*
     * Llamado generico de la entidad
     */
    query("especificacion",{"entidad" : window.entidad},function(m){ window.obtener_columnas(m); });
}