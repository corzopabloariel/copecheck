
// Pyrus = new Object();

// /**
//  * Funcion de consulta desde JS, se comunica con el servidor
//  * y devuelve una respuesta acorde, uso generico.
//  * Las funciones de callback solo conocen la respuesta en
//  * data, no el contexto de como fue recibido (ni estado, ni
//  * mensaje)
//  *
//  * @param string accion La accion a llamar
//  * @param object data Objeto anonimo inmediato que contiene lo que se enviara
//  * @param function callbackOK Funcion a llamar si no hay errores
//  * @param function callbackFail Funcion a llamar si hay errores
//  */
// Pyrus.query = function (accion,data,callbackOK,callbackFail = null,async = true){
//     url_envio = url_query_local;
//     envio = { "accion":accion, "data":data };
//     window.con = $.ajax({
//         type: 'POST',
//         // make sure you respect the same origin policy with this url:
//         // http://en.wikipedia.org/wiki/Same_origin_policy
//         url: url_envio,
//         async: async,
//         data: envio,
//         success: function(msg){
//             window.devolucion = JSON.parse(msg);
//             callbackOK(window.devolucion['data']);
//             },
//         error: function(msg){
//             window.PYRUS_ERROR_ARR.push(msg);
//             if(callbackFail !== null)
//                 callbackFail(msg);
//             }
//         });
// };

// /**
//  * Inicializa la pagina para una entidad, carga los datos de
//  * especificacion y lo que se crea necesario para que funcione
//  *
//  * @param string entidad Nombre de la entidad
//  */
// Pyrus.startEntidad = function(entidad){
//     window.entidad = entidad;
//     // traigo la especificacion
//     Pyrus.query(
//         "especificacion",
//         {"entidad" : window.entidad},
//         function(m){ window.especificacion = m; });
// };



// /**
//  * Dada una entidad, crea el editor adecuado
//  * TODO: esta hardcodeado, refactorizar
//  *
//  * @param string entidad
//  * @param string target Nombre en jQuery del objeto a usar
//  * @return {undefined}
//  */
// Pyrus.crearEditor = function(entidad,target){
//     // las saco a window por que puede que se salgan de contexto
//     // las funciones que se van llamando
//     window.entidad = entidad;
//     window.target = target;
//     window.tipos = null;
//     window.id = 'nulo'; // arranca como nuevo

//     /**
//      * Un Dom adecuado devuelve un input adecuado y tambien le
//      * setea el validador adecuado en la clase
//      *
//      * @param {type} a
//      * @param {type} l
//      * @return {String}
//      */
//     window.domAdecuado = function(a,l){
//         // se puede mostrar
//         if(!Pyrus.existeEn(window.tipos['TP_VISIBLE_NUNCA'],l)){
//             // empiezo a preguntar por los tipos
//             if(Pyrus.existeEn(window.tipos['TP_STRING'],l))
//                 return userBean.inputString(a);
//             if(Pyrus.existeEn(window.tipos['TP_ENTERO'],l))
//                 return userBean.inputEntero(a);
//             if(Pyrus.existeEn(window.tipos['TP_FLOTANTE'],l))
//                 return userBean.inputFlotante(a);
//             if(Pyrus.existeEn(window.tipos['TP_FECHA_CORTA'],l))
//                 return userBean.inputFechaCorta(a);
//             if(Pyrus.existeEn(window.tipos['TP_FECHA_LARGA'],l))
//                 return userBean.inputFechaLarga(a);
//             if(Pyrus.existeEn(window.tipos['TP_IMAGEN'],l))
//                 return userBean.inputImagen(a);
//             if(Pyrus.existeEn(window.tipos['TP_RELACION'],l))
//                 return userBean.inputSelector(a,l['relacion'],l['formato_visible']);
//             else // si no encaja con ninguno, va por string
//                 return userBean.inputString(a);
//         } else return "<input type='hidden' class='" + a + "'>";
//     };

//     window.parsear = function(m){
//         // ya tengo los tipos y la especificacion
//         window.especificacion = m;
//         for (var x in window.especificacion) {
//             // console.log(x + ": ");
//             dom = window.domAdecuado(x,window.especificacion[x]);
//             if(dom !== 'nulo'){
//                 $(window.target).append(dom);
//             }
//         }
//         $(".modal-title").text(window.title);
//         $(".modal-footer").append("<button type='button' class='btn btn-success' onclick='javascript:Pyrus.guardar(window.id_)';>Guardar</button>");
//         $(".modal-footer").append("<button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>");

//     };

//     window.obtener_especificacion = function(m){
//         window.tipos = m;
//         Pyrus.query("especificacion",
//             {"entidad" : window.entidad},
//             function(m){ window.parsear(m); });
//         };
//     // traigo los tipos
//     Pyrus.query('obtener_tipos',null,
//         function(m){ window.obtener_especificacion(m);});
// };

// /**
//  * Dado un id y una entidad, muestra y modifica ese registro,
//  * si se especifica 'nulo' o no se lo manda, cuando se presione
//  * guardar se creara uno nuevo (politica de PYRUS_DB::set_one
//  *
//  * @param interger/string id El id de la entidad
//  * @param string entidad Nombre de la entidad
//  */
// Pyrus.mostrarEditar = function(entidad,id = 'nulo'){
//     // previamente tengo que disponer de los elementos para
//     // dibujar lo que seria la pantalla. No los tengo asi que
//     // los creo, pero en la refactorizacion tiene que estar
//     if(id != 'nulo')
//         Pyrus.query("mostrar_uno_generico",
//             {'entidad' : entidad, 'id' : id },
//             function (m) { console.log(m); });
// };

// /**
//  * Lista una entidad generica en una tabla usando DataTables
//  * Todas las funciones relacionadas son internas
//  * TODO: esta hardcodeado, refactorizar
//  *
//  * @param string e nombre de la entidad
//  * @param string t nombre en jQuery de la tabla a escribir
//  * @param funcion f_detalle funcion a llamar para pasarle el
//  *      id y mostrar detalles
//  */

// Pyrus.init = function(){
//     // inicializa el entorno
//     // Traigo la lista generica y la especificacion
//     Pyrus.query("listar_generico",
//         {"entidad" : window.entidad},
//         function(m){ window.resultado = m; },null,false);
//     Pyrus.query("especificacion",
//         {"entidad" : window.entidad},
//         function(m){ window.especificacion = m; console.log(m) },null,false);
//     Pyrus.query('obtener_tipos',null,
//         function(m){ window.tipos = m; console.log(m) },null,false);
// };

// Pyrus.listarEntidad = function(t){
//     // t es donde se dibujara
//     // convierto las especificaciones en columnas validas para datatables
//     col = [];
//     for(var x in window.especificacion) col.push({title: x});
//     res = [];
//     for(var x in window.resultado){
//         tmp = [];
// 	for(var z in window.especificacion){
//                 v = Pyrus.convertirAVisible(window.resultado[x][z],window.especificacion[z]);
// 		tmp.push(v);
//             }
//         res.push(tmp);
//     }
//     userBean.listarEntidad(col,res,t);

// };

// Pyrus.convertirAVisible = function(valor,esp){
//     if(Pyrus.existeEn(window.tipos['TP_RELACION'],esp)){
//         relacion = esp['relacion'];
//         ret = "";
//         Pyrus.query("mostrar_uno_generico",
//         {'entidad':relacion, 'id' : valor },
//         function(m){
//             ret += Pyrus.imprimirConFormato(esp['formato_visible'],m);
//         },null,false);
//         return ret;
//         }
//     if(Pyrus.existeEn(window.tipos['TP_FECHA_CORTA'],esp)){
// 	valor += " ";
// 	return valor.substr(6,2) + "/" + valor.substr(4,2) + "/" + valor.substr(0,4);
//     }
//     else {
//         return valor;
//     }
// };

// Pyrus.listarEntidad_ = function(e,t,f_detalle = null){
//     // las saco a window por que puede que se salgan de contexto
//     // las funciones que se van llamando
//     window.entidad = e;
//     window.tabla = t;
//     window.dataset = [];
//     window.orden = [];
//     window.funcion_detalle = f_detalle;

//     /**
//      * Funcion que crea un enlace 'personalizado'
//      *
//      * @param {type} id
//      * @param {type} texto
//      * @param {type} onclick
//      * @param {type} link
//      * @return {String}
//      */
//     window.dibujar_enlace = function(id,texto,onclick,link){
//         // retorno un enlace con el atributo a mostrar
//         return "<a href='" + link + "' " +
//                 "onclick='javascript:" + onclick + "(" + id + ");'>" +
//                 texto + "</a>";
//     };

//     /**
//      * Funcion que llama a DataTable y se encarga de la vista basica
//      *
//      * @return {undefined}
//      */
//     window.dibujar_tabla = function(){
//         // convierto a nombre de columna de DataTables
//         tmp = [];
//         for(var i of window.orden) tmp.push({title: i});
//         if(window.funcion_detalle !== null){
//             tmp.push({title: 'Detalle',
//                 "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
//                     // oData es un array con la info de esta fila
//                     $(nTd).html(window.dibujar_enlace(sData,
//                         'ver',
//                         'window.funcion_detalle',
//                         '#'));
//                 }
//             });
//         }
//         console.log(t)
//         $(t).DataTable({ data: window.dataset, columns: tmp });
//     };

//     /**
//      * Obtengo una lista generica de las filas de la entidad
//      *
//      * @param {type} m
//      * @return {undefined}
//      */
//     window.obtener_info = function(m){
//         for(var i in m){ // cada fila del resultado
//             tmp = [];
//             // cada columna de la especificacion
//             for(var u of window.orden) tmp.push(m[i][u]);
//             // agrego nueva columna para el id para pasar por parametro
//             if(window.funcion_detalle !== null) tmp.push(m[i]['id']);
//             window.dataset.push(tmp);
//         }
//         window.dibujar_tabla(); // no paso un resultado, no es necesario parametro
//     };

//     /**
//      * Obtengo la informacion de la entidad, extraigo nombre de columnas
//      *
//      * @param {type} m
//      * @return {undefined}
//      */
//     window.obtener_columnas = function(m){
//         for(var i in m) window.orden.push(i);
//         Pyrus.query("listar_generico",{"entidad" : window.entidad},function(m){ window.obtener_info(m); });
//     };

//     /*
//      * Llamado generico de la entidad
//      */
//     Pyrus.query("especificacion",{"entidad" : window.entidad},function(m){ window.obtener_columnas(m); });
// };

// /**
//  * Consulta en un array u objeto si existe un elemento e en la lista
//  * o (array u objeto)
//  *
//  * @param Object e Elemento a buscar
//  * @param Object/Array o Objeto o Array a hacer la busqueda
//  * @return bool
//  */
// Pyrus.existeEn = function(e,o){
//     // asumo que me pasan un objeto, o lo que fuese
//     if(Array.isArray(o)) return o.includes(e);
//     else {
//         for(var x in o)
//             if(o[x] === e) return true;
//         return false;
//     }
// };

// /**
//  * Dado una cadena formateada y una fuente de datos, devuelve una
//  * cadena completa y bien rellenada de informacion, por ejemplo
//  * si se envia "[id] : [nombre]", y se envia un array con {"id":2,
//  * "nombre":"uno","apellido":"tal"} se devolvera un string con
//  * "2: uno". Modo de uso, el string tiene que tener espacios entre
//  * palabras y simbolos claves
//  *
//  * @param array/object datos Fuente de datos a leer
//  * @param string formato Formato a devolver correctamente
//  * @return string
//  */
// Pyrus.imprimirConFormato = function(formato,datos){
//     // obtengo todos los elementos
//     s = formato.split(" "); // separo por espacios
//     k = [];
//     ret = formato;
//     for(var x in s){
//         if(s[x].indexOf("[") > -1) // si contiene
//             k.push(s[x].replace("[","").replace("]","")); // obtengo el key
//     }
//     // una vez que tengo el key, reemplazo
//     for(var i in k)
//         ret = ret.replace("[" + k[i] + "]",datos[k[i]]);
//     return ret;
// };

// Pyrus.guardar = function(id, tipo = 'aun no en uso, se usa window.especificacion'){
//     // dado un id, guardo lo que tenga en el editor, primero creo
//     // un objeto igual a la entidad
//     e = new Object;
//     // copio especificacion, y elimino id
//     esp = Object.assign({},window.especificacion);
//     delete esp['id'];
//     // supongo que el formato ya esta cargado en la pagina, traigo todo
//     for(var x in window.especificacion)
//         e[x] = $('.' + x).val();
//     e['id'] = id;
//     // guardo crudamente
//     Pyrus.query('guardar_uno_generico',
//         {'entidad':window.entidad,'objeto':e},
//         function (m) { userBean.notificacion("se guardo exitosamente, id = " + m); },
//         function (m) { userBean.notificacion("hubo un problema al guardar, reintente"); });
// };

// Pyrus.cargarAEditar = function(id, entidad, tipo = 'aun no en uso, se usa window.especificacion'){
//     // se trae un elemento y se lo muestra en el modal
//     console.log("vbvvbv")
//     if(id == 'nulo'){
//         console.log("aaaa")
//         window.id_ = 'nulo';
//         for(var x in window.especificacion){
//             console.log(x)
//                 try { $("." + x).val(''); }
//                 catch(err){console.log("no se cargo " + x + " err:" + err); }
//             }
//     }else {
//         console.log("DDD")
//         Pyrus.query('mostrar_uno_generico',
//             {'entidad':entidad,'id':id},
//             function(m){
//                 // saco a id
//                 window.id = m[id];
//                 for(var x in window.especificacion){
//                     try { $("." + x).val(m[x]); }
//                     catch(err){console.log("no se cargo " + x + " err:" + err); }
//                 }
//             },
//             function(m){ console.log("un error"); });
//     }
// };
