/*
 * UserBean es el lugar donde se utilizan cosas del framework que varian de
 * proyecto en proyecto, como por ejemplo el login y las excepciones.
 * Tienen que estar dentro del objeto userBean para que luego, el framework
 * las pueda reconocer en caso de necesitarlas
 *
 * DECLARAR SI SE UTILIZAN OTRAS LIBRERIAS EN JS (con version)
 * Librerias externas utilizadas aqui:
 *      - lobibox
 *
 */

/********* BEGIN CONFIG ****************/

url_query_local = "http://200.58.123.122/php/query.php";

// declaro el user logueado
window.log_user = [];

// declaro aqui mismo el tipo error
PYRUS_ERROR = 99999;

// declaro un ambiente donde se guardan todos los errores
window.PYRUS_ERROR_ARR = [];

userBean = new Object();
/********* END CONFIG ****************/

userBean.login = function(){
    user = $('#InputEmail').val();
    pass = $('#InputPassword').val();
    p = new Pyrus();
    p.query(
        'NS_login',
        {'user':user,'pass':pass},
        function(m){ log_user.push(m); window.location = '../gestion_cheques/'; },
        function(m){ userBean.notificacion("Usuario o clave invalidos","error");
    });
};

userBean.MD5 = function(s){function L(k,d){return(k<<d)|(k>>>(32-d))}function K(G,k){var I,d,F,H,x;F=(G&2147483648);H=(k&2147483648);I=(G&1073741824);d=(k&1073741824);x=(G&1073741823)+(k&1073741823);if(I&d){return(x^2147483648^F^H)}if(I|d){if(x&1073741824){return(x^3221225472^F^H)}else{return(x^1073741824^F^H)}}else{return(x^F^H)}}function r(d,F,k){return(d&F)|((~d)&k)}function q(d,F,k){return(d&k)|(F&(~k))}function p(d,F,k){return(d^F^k)}function n(d,F,k){return(F^(d|(~k)))}function u(G,F,aa,Z,k,H,I){G=K(G,K(K(r(F,aa,Z),k),I));return K(L(G,H),F)}function f(G,F,aa,Z,k,H,I){G=K(G,K(K(q(F,aa,Z),k),I));return K(L(G,H),F)}function D(G,F,aa,Z,k,H,I){G=K(G,K(K(p(F,aa,Z),k),I));return K(L(G,H),F)}function t(G,F,aa,Z,k,H,I){G=K(G,K(K(n(F,aa,Z),k),I));return K(L(G,H),F)}function e(G){var Z;var F=G.length;var x=F+8;var k=(x-(x%64))/64;var I=(k+1)*16;var aa=Array(I-1);var d=0;var H=0;while(H<F){Z=(H-(H%4))/4;d=(H%4)*8;aa[Z]=(aa[Z]| (G.charCodeAt(H)<<d));H++}Z=(H-(H%4))/4;d=(H%4)*8;aa[Z]=aa[Z]|(128<<d);aa[I-2]=F<<3;aa[I-1]=F>>>29;return aa}function B(x){var k="",F="",G,d;for(d=0;d<=3;d++){G=(x>>>(d*8))&255;F="0"+G.toString(16);k=k+F.substr(F.length-2,2)}return k}function J(k){k=k.replace(/rn/g,"n");var d="";for(var F=0;F<k.length;F++){var x=k.charCodeAt(F);if(x<128){d+=String.fromCharCode(x)}else{if((x>127)&&(x<2048)){d+=String.fromCharCode((x>>6)|192);d+=String.fromCharCode((x&63)|128)}else{d+=String.fromCharCode((x>>12)|224);d+=String.fromCharCode(((x>>6)&63)|128);d+=String.fromCharCode((x&63)|128)}}}return d}var C=Array();var P,h,E,v,g,Y,X,W,V;var S=7,Q=12,N=17,M=22;var A=5,z=9,y=14,w=20;var o=4,m=11,l=16,j=23;var U=6,T=10,R=15,O=21;s=J(s);C=e(s);Y=1732584193;X=4023233417;W=2562383102;V=271733878;for(P=0;P<C.length;P+=16){h=Y;E=X;v=W;g=V;Y=u(Y,X,W,V,C[P+0],S,3614090360);V=u(V,Y,X,W,C[P+1],Q,3905402710);W=u(W,V,Y,X,C[P+2],N,606105819);X=u(X,W,V,Y,C[P+3],M,3250441966);Y=u(Y,X,W,V,C[P+4],S,4118548399);V=u(V,Y,X,W,C[P+5],Q,1200080426);W=u(W,V,Y,X,C[P+6],N,2821735955);X=u(X,W,V,Y,C[P+7],M,4249261313);Y=u(Y,X,W,V,C[P+8],S,1770035416);V=u(V,Y,X,W,C[P+9],Q,2336552879);W=u(W,V,Y,X,C[P+10],N,4294925233);X=u(X,W,V,Y,C[P+11],M,2304563134);Y=u(Y,X,W,V,C[P+12],S,1804603682);V=u(V,Y,X,W,C[P+13],Q,4254626195);W=u(W,V,Y,X,C[P+14],N,2792965006);X=u(X,W,V,Y,C[P+15],M,1236535329);Y=f(Y,X,W,V,C[P+1],A,4129170786);V=f(V,Y,X,W,C[P+6],z,3225465664);W=f(W,V,Y,X,C[P+11],y,643717713);X=f(X,W,V,Y,C[P+0],w,3921069994);Y=f(Y,X,W,V,C[P+5],A,3593408605);V=f(V,Y,X,W,C[P+10],z,38016083);W=f(W,V,Y,X,C[P+15],y,3634488961);X=f(X,W,V,Y,C[P+4],w,3889429448);Y=f(Y,X,W,V,C[P+9],A,568446438);V=f(V,Y,X,W,C[P+14],z,3275163606);W=f(W,V,Y,X,C[P+3],y,4107603335);X=f(X,W,V,Y,C[P+8],w,1163531501);Y=f(Y,X,W,V,C[P+13],A,2850285829);V=f(V,Y,X,W,C[P+2],z,4243563512);W=f(W,V,Y,X,C[P+7],y,1735328473);X=f(X,W,V,Y,C[P+12],w,2368359562);Y=D(Y,X,W,V,C[P+5],o,4294588738);V=D(V,Y,X,W,C[P+8],m,2272392833);W=D(W,V,Y,X,C[P+11],l,1839030562);X=D(X,W,V,Y,C[P+14],j,4259657740);Y=D(Y,X,W,V,C[P+1],o,2763975236);V=D(V,Y,X,W,C[P+4],m,1272893353);W=D(W,V,Y,X,C[P+7],l,4139469664);X=D(X,W,V,Y,C[P+10],j,3200236656);Y=D(Y,X,W,V,C[P+13],o,681279174);V=D(V,Y,X,W,C[P+0],m,3936430074);W=D(W,V,Y,X,C[P+3],l,3572445317);X=D(X,W,V,Y,C[P+6],j,76029189);Y=D(Y,X,W,V,C[P+9],o,3654602809);V=D(V,Y,X,W,C[P+12],m,3873151461);W=D(W,V,Y,X,C[P+15],l,530742520);X=D(X,W,V,Y,C[P+2],j,3299628645);Y=t(Y,X,W,V,C[P+0],U,4096336452);V=t(V,Y,X,W,C[P+7],T,1126891415);W=t(W,V,Y,X,C[P+14],R,2878612391);X=t(X,W,V,Y,C[P+5],O,4237533241);Y=t(Y,X,W,V,C[P+12],U,1700485571);V=t(V,Y,X,W,C[P+3],T,2399980690);W=t(W,V,Y,X,C[P+10],R,4293915773);X=t(X,W,V,Y,C[P+1],O,2240044497);Y=t(Y,X,W,V,C[P+8],U,1873313359);V=t(V,Y,X,W,C[P+15],T,4264355552);W=t(W,V,Y,X,C[P+6],R,2734768916);X=t(X,W,V,Y,C[P+13],O,1309151649);Y=t(Y,X,W,V,C[P+4],U,4149444226);V=t(V,Y,X,W,C[P+11],T,3174756917);W=t(W,V,Y,X,C[P+2],R,718787259);X=t(X,W,V,Y,C[P+9],O,3951481745);Y=K(Y,h);X=K(X,E);W=K(W,v);V=K(V,g)}var i=B(Y)+B(X)+B(W)+B(V);return i.toLowerCase()};

userBean.notificacion = function (mensaje,tipo = 'info',dlay = true){
    // Available types 'warning', 'info', 'success', 'error'
    Lobibox.notify(tipo, {
        size: 'mini',
        icon: false,
		delayIndicator: dlay,
        msg: mensaje,
        sound: false
    });
};

userBean.isObject = function(item) {
    return (typeof item === "object" && !Array.isArray(item) && item !== null);
}

userBean.nuevoCheque = function(t){
    $(t).attr("disabled",true);
    userBean.notificacion("Cargando... espere","warning",false);
    setTimeout(function() { userBean.nuevoCheque_behavior(t); },1000);
};

userBean.toggle__ = function(t) {
    $(t).closest(".card").find(".card-body").toggle("fast");
};
userBean.cambio = function(t) {
    if($(t).closest(".modal-body").find("*[data-dato='normal']").is(":visible")) {
        $(t).closest(".modal-body").find("*[data-dato='normal']").addClass("d-none");
        $(t).closest(".modal-body").find("*[data-dato='normal'] *").val("");
        $(t).closest(".modal-body").find("*[data-dato='juridica']").removeClass("d-none");
        $(t).find("strong").text("Persona");
        $(t).attr("title","Persona");
    } else {
        $(t).closest(".modal-body").find("*[data-dato='juridica']").addClass("d-none");
        $(t).closest(".modal-body").find("*[data-dato='juridica'] *").val("");
        $(t).closest(".modal-body").find("*[data-dato='normal']").removeClass("d-none");
        $(t).find("strong").text("Persona jurídica");
        $(t).attr("title","Persona jurídica");
    }
};
userBean.concatenar = function(t,tipo) {
    if(tipo == "empresa")
        $(t).closest(".modal").find(".nombre_mostrar").val($(t).val())
    else {
        n = $(t).closest(".modal").find(".nombre").val();
        a = $(t).closest(".modal").find(".apellido").val();
        $(t).closest(".modal").find(".nombre_mostrar").val(n + " " + a);
    }
};
userBean.convertir = function(t) {
    $(t).next().val(userBean.fechaYYYYMMDD($(t).val()))
};
userBean.eliminarCheque = function(cheque) {
    i = $(cheque).closest(".margin__bottom__10.card").index();
    $(cheque).closest(".margin__bottom__10.card").remove();
    Acheques.splice(i, 1);
    $("#__cheques_cantidad").text("Total de cheques: " + $("#cheques > div.card").length);
};
userBean.comprobarFechas = function(t) {
    var dias = 365;
    var accion = $(t).closest(".card-body").find(".id_accion").val();
    if(accion == 2) dias = 30;

    var diff = 0;
    var this_ = $(t).val();
    var this__class = $(t).next().attr("class");
    var n = $(t).closest(".card-body").find("input[type='date'] + input[type='hidden']:not(." + this__class + ")")
    var next_ = $(n).prev().val();

    var d_this_ = Date.parse(this_);
    var d_next_ = next_ == "" ? 0 : Date.parse(next_);

    diff = d_this_ - d_next_;
    diff = diff < 0 ? diff * -1 : diff;
    diff = diff/(1000*60*60*24);
    console.log(diff)
    if(next_ != 0) {
        if(diff > dias) {
            $(t).parent().addClass("has-error");
            $(t).closest(".card-body").find("input[type='date'] + input[type='hidden']:not(." + this__class + ")").parent().addClass("has-error");
            userBean.notificacion("<strong>Fecha de cobro</strong> incorrecta, supera los "+dias+" días","error");
            $(t).closest(".card").data("fecha","no");
        } else {
            $(t).closest(".card").data("fecha","ok");
            $(t).parent().removeClass("has-error");
            $(t).closest(".card-body").find("input[type='date'] + input[type='hidden']:not(." + this__class + ")").parent().removeClass("has-error");
        }
    }
};
/**
 * Devuelve la fecha de hoy con formato YYYYMMDD
 */
userBean.fechaYYYYMMDD = function() {
    var date = new Date;
    var d_ = date.getDate();
    var m_ = date.getMonth();
    var y_ = date.getFullYear();

    if(d_ < 10) d_ = "0" + d_;
    if(m_ < 10) m_ = "0" + m_;

    return y_ + m_ + d_;
}
/**
 * Por cada tipo una funcion distinta... por que? por que por
 * cada tipo puede que exista un comportamiento muy distinto
 *
 * @param string identificador Identificador de ese elemento unico
 * @return {String}
 */

 userBean.inputString__ = function (identificador,nombre,tam,label,disabled,function__){
    ff = "";
    for(var o in function__) {
        if(ff != "") ff + " ";
        ff = o + "='" + function__[o] + "'";
    }
    return "<div class='" + tam + "'>" +
            (label ? "<label>" + nombre.toUpperCase() + "</label>": "") +
            "<input " + ff + " " + disabled + " required='true' name='" + identificador + "' type='text' class='form-control val_string " + identificador + "'></input>" +
            "</div>";
 };

 userBean.inputEntero__ = function (identificador,nombre,tam,label,disabled,function__){
    ff = "";
    for(var o in function__) {
        if(ff != "") ff + " ";
        ff = o + "='" + function__[o] + "'";
    }
    return "<div class='" + tam + "'>" +
            (label ? "<label>" + nombre.toUpperCase() + "</label>": "") +
            "<input " + ff + " " + disabled + " required='true' name='" + identificador + "' type='number' min='0' class='form-control val_integer " + identificador + "'></input>" +
            "</div>";
 };

 userBean.inputFlotante__ = function (identificador,nombre,tam,label,disabled,function__,tipo){
    ff = "";
    var tmp = "", t_tipo = "";
    switch(tipo) {
        case 'moneda':
            t_tipo = "data-tipo='moneda'";
            tmp = "onkeyup=\"javascript:userBean.inputKeyup(event);\"";
            tmp += " onfocusout=\"javascript:userBean.inputFocusOut(event);\"";
        break;
    }
    for(var o in function__) {
        if(ff != "") ff + " ";
        ff = o + "='" + function__[o] + "'";
    }
    return "<div class='" + tam + "'>" +
            (label ? "<label>" + nombre.toUpperCase() + "</label>": "") +
            "<input " + t_tipo + " " + tmp + " " + ff + " " + disabled + " required='true' name='" + identificador + "' type='text' class='form-control val_float " + identificador + " " + (tmp == "" ? "" : "text-right") + "'></input>" +
            "</div>";
 };

 userBean.inputFechaCorta__ = function (identificador,nombre,tam,label,disabled,function__){
    ff = "";
    for(var o in function__) {
        if(ff != "") ff + " ";
        ff = o + "='" + function__[o] + "'";
    }
    return "<div class='" + tam + "'>" +
            (label ? "<label>" + nombre.toUpperCase() + "</label>": "") +
            "<input " + ff + " " + disabled + " required='true' type='date' class='form-control val_fechaCorta'></input>" +
            "<input type='hidden' class='" + identificador + "'/>"+
            "</div>";
 };

 userBean.inputImagen__ = function (identificador,nombre,tam,label,disabled,function__){
    ff = "";
    for(var o in function__) {
        if(ff != "") ff + " ";
        ff = o + "='" + function__[o] + "'";
    }
    console.log(disabled)
    return "<div class='" + tam + "'>" +
            (label ? "<label>" + nombre.toUpperCase() + "</label>": "") +
            "<input " + disabled + " " + ff + " name='" + identificador + "' type='file' class='form-control val_imagen " + identificador + "'></input>" +
            "</div>";
 };

 userBean.inputSelector__ = function(identificador,fuente_opciones,formato,nombre,estado,tam,label,disabled,function__){
    ff = "";
    for(var o in function__) {
        if(ff != "") ff + " ";
        ff = o + "='" + function__[o] + "'";
    }
    console.log(identificador)

    selector =  "<div class='" + tam + "'>" +
                (label ? "<label>" + nombre.toUpperCase() + "</label>": "") +
                "<select " + disabled + " " + ff + " required='true' name='" + identificador + "' style='width:100%' data-placeholder='Seleccione " + nombre + "' data-allow-clear='true' class='form-control select__2 val_selector " + identificador + "' onchange=\"userBean.verEntidad(this,'"+ identificador +"')\">" +
                "<option value=''></option>";
    if(estado == "agregar") {
        selector += "<optgroup label=\"Nuevo\">";
        selector += "<option value='-1'>Registro</option>";
        selector += "</optgroup>";
    }
    selector += "<optgroup label=\"Datos\">";
    aux = new Pyrus();
    aux.query("listar_generico",{'entidad':fuente_opciones },
    function(m){
        gafsd = function () {  return false; };
        for(var x in m){
            selector += "<option value='" + m[x]['id'] + "'>" +
            aux.imprimirConFormato(formato,m[x])
            + "</option>";
        }
    },null,false);
    selector += "</select>" + 
                "</div>";
    return window.selector;
};
/**
 *
 */

 userBean.inputString = function (identificador,nombre){
     return "<div class='form-group row'>" +
             //"<label class='col-4 col-form-label'>" + nombre.toUpperCase() + "</label>" +
             "<div class='col col-12'><input title='" + nombre.toUpperCase() + "' placeholder='" + nombre.toUpperCase() + "' required='true' name='" + identificador + "' type='text' class='form-control val_string " + identificador + "'></input></div>" +
         "</div>";
 };

 userBean.inputEntero = function (identificador,nombre){
     return "<div class='form-group row'>" +
             //"<label class='col-4 col-form-label'>" + nombre.toUpperCase() + "</label>" +
             "<div class='col col-12'><input title='" + nombre.toUpperCase() + "' placeholder='" + nombre.toUpperCase() + "' required='true' name='" + identificador + "' type='number' min='0' class='form-control val_integer " + identificador + "'></input></div>" +
         "</div>";
 };

 userBean.inputFlotante = function (identificador,nombre,tipo){
    var tmp = "",t_tipo = "";
    switch(tipo) {
        case 'moneda':
            t_tipo = "data-tipo='moneda'";
            tmp = "onkeyup=\"javascript:userBean.inputKeyup(event);\"";
            tmp += " onfocusout=\"javascript:userBean.inputFocusOut(event);\"";
        break;
    }

     return "<div class='form-group row'>" +
             //"<label class='col-4 col-form-label'>" + nombre.toUpperCase() + "</label>" +
             "<div class='col col-12'><input title='" + nombre.toUpperCase() + "' placeholder='" + nombre.toUpperCase() + "' " + t_tipo + " " + tmp + " required='true' name='" + identificador + "' type='text' class='form-control val_float " + identificador + " " + (tmp == "" ? "" : "text-right") + "'></input></div>" +
         "</div>";
 };

 userBean.inputFechaCorta = function (identificador,nombre){
     return "<div class='form-group row'>" +
            // "<label class='col-4 col-form-label'>" + nombre.toUpperCase() + "</label>" +
            "<input title='" + nombre.toUpperCase() + "' placeholder='" + nombre.toUpperCase() + "' required='true' type='date' class='form-control val_fechaCorta'></input>" +
            "<input type='hidden' class='" + identificador + "'/>"+
         "</div>";
 };

 userBean.inputFechaLarga = function (identificador,nombre){
     return "<input name='" + identificador + "' type='text' class='val_fechaLarga "+ identificador + "'></input>";
 };

 userBean.inputImagen = function (identificador,nombre){
     return "<div class='form-group row'>" +
            // "<label class='col-4 col-form-label'>" + nombre.toUpperCase() + "</label>" +
             "<div class='col col-12'><input name='" + identificador + "' type='file' class='form-control val_imagen " + identificador + " xlk'></input></div>" +
         "</div>";
 };

 userBean.inputSelector = function(identificador,fuente_opciones,formato,nombre,mostrar){
 	selector = "<div class='form-group row'>" +
             "<label class='col-4 col-form-label'>" + nombre.toUpperCase() + "</label>";
    selector += "<div class='col-8'>";
    selector += "<select required='true' name='" + identificador + "' style='width:100%' data-placeholder='Seleccione " + identificador + "' data-allow-clear='true' class='form-control select__2 val_selector " + identificador + "'>";
    selector += "<option value=''></option>";
    /*Pyrus.query("listar_generico",
     {'entidad':fuente_opciones },
     function(m){
         for(var x in m){
             selector += "<option value='" + m[x]['id'] + "'>" +
             Pyrus.imprimirConFormato(formato,m[x])
             + "</option>";
         }
     },null,false);*/
    selector += "</select></div>";
    return window.selector;
 };

userBean.detalle = function(id){
    // seteo el id
    window.id_ = id;
    $("#modal").modal("show");
    Pyrus.cargarAEditar(id,window.entidad);

};

userBean.listarEntidad = function(col,res,target){
    var translate_spanish = {
        buttons: {
            pageLength: {
                _: "%d filas",
                '-1': "Todo"
            }
        },
        "lengthMenu": "_MENU_",
        "info": "Página _PAGE_ de _PAGES_ - _MAX_ registros",
        "infoEmpty": "Sin registros disponibles",
        "infoFiltered": "(filtrada de _MAX_ registros)",
        "loadingRecords": "Cargando...",
        "processing":     "Procesando...",
        "search": "",
        "zeroRecords":    "No se encontraron registros",
        "paginate": {
            "next":       "Siguiente",
            "previous":   "Anterior"
        },
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
    };
    var tabla = $("#"+target).DataTable({
        "columns": col,
        "data": res,
        "columnDefs": [
            { className: "text-center", "targets": [ 0,1,5,6 ] },
            { className: "text-right", "targets": [ 3 ] }
        ],
        initComplete : function() {
            var input = $('.dataTables_filter input').unbind();
            input.addClass("border-right-0");
            input.css({"border-bottom-right-radius":0,"border-top-right-radius":0})
            self = this.api(),
            $searchButton = $('<button class="btn btn-dark">')
            .html('<i class="fas fa-search"></i>').click(function() {
                self.search(input.val()).draw();
            });
            $('.dataTables_filter').addClass("d-flex");
            $('.dataTables_filter').html(input);
            $('.dataTables_filter').append($searchButton);
        },
        //"ordering": false,
        //"searching": false,
        //"colReorder": true,
        "destroy": true,
        //"sDom": 'BRfrltip',
        "sDom": "<'row padding__bottom__10'"+
                    "<'col col-12 col-sm-6 d-flex justify-content-center __lenght_buttons'l>"+
                    "<'col col-12 col-sm-6'f>r>"+
                "<'table-scrollable padding__bottom__10't>"+
                "<'row'"+
                    "<'col col-12 col-sm-6'i>"+
                    "<'col col-12 col-sm-6 d-flex justify-content-center __paginate'p>>",
        //"sPaginationType": "bootstrap",
        "scrollX":true,
        //"processing": true,
        //"serverSide": true,
        "bProcessing": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],

        "buttons": [
            {
            extend: 'collection',
            text: '<i class="fas fa-download"></i> Descargar',
            className: 'btn-danger',
            buttons: [{
                    text: 'Excel',
                    extend: 'excelHtml5',
                    footer: false,
                    exportOptions: {
                        columns: [ 1, ':visible' ]
                    },
                    title: target
                }, {
                    text: 'CSV',
                    extend: 'csvHtml5',
                    fieldSeparator: ';',
                    exportOptions: {
                        columns: [ 1, ':visible' ]
                    },
                    title: target
                }, {
                    text: 'PDF Portrait',
                    extend: 'pdfHtml5',
                    message: '',
                    exportOptions: {
                        columns: [ 1, ':visible' ]
                    },
                    title: target
                }, {
                    text: 'PDF Landscape',
                    extend: 'pdfHtml5',
                    message: '',
                    orientation: 'landscape',
                    exportOptions: {
                        columns: [ 1, ':visible' ]
                    },
                    title: target
                }]
            }
        ],
        "language": translate_spanish
    });
    tabla.buttons().container().appendTo( $('.col-sm-6:eq(0)', tabla.table().container() ) );
		$("div.dt-buttons button").removeClass("btn-secondary");
};
userBean.listarEntidad__ = function(col,res,target,columnas){
    var pos = [];
    var estados = {};
    m = new Pyrus("movimientotipo");
    m_arr = m.busqueda__arr();
    for(var i in m_arr) estados[m_arr[i]["designacion"]] = m_arr[i]["id"];

    for(var x in columnas) {
        var y = 0;
        for(y in col) {
            if(col[y]["title"] == columnas[x])
                break;
        }
        pos.push(y);//saco las posiciones de los elementos que necesito
    }
    console.log(res);
    var tipo = {"2" : "<i class=\"badge badge-success\">ingreso</i>","4" : "<i class=\"badge badge-danger\">egreso</i>","6" : "<i class=\"badge badge-info\">espejo</i>"};
    for(var i in res) {
        var tmp = "";
        for(var x in pos) {
            if(tmp == "") tmp = "<p style=\"margin:0;\">";
            switch(pos[x]) {
                case "5":
                    if(res[i][pos[x]] != "")
                        tmp += "<span>" + res[i][pos[x]]+" <small>cliente</small></span>";
                    break;
                case "6":
                    if(res[i][pos[x]] != "")
                        tmp += "<span>" + res[i][pos[x]]+" <small>destinatario</small></span>";
                    break;
                //2 -> activo / 4 -> cobrado / 6 -> acreditado / 8 -> rebotado 
                default:
                    tmp += "<span>" + res[i][pos[x]]+"</span>";
                break;
            }
        }
        /* (id,tipo) */
        tmp += '<i class="far fa-eye" style="cursor:pointer" onclick="userBean.ver(' + res[i][0] + ',' + estados[res[i][9]] + ');"></i>';
        tmp += "</p>";
        $(target).append(tmp)
    }
}

userBean.validar = function(t) {
    var flag = 1;
    $(t).find('*[required="true"]').each(function(){
        if($(this).is(":visible")) {
            if($(this).is(":invalid")) {
                flag = 0;
                console.log($(this))
                $(this).parent().addClass("has-error");
            }
        }
    });
    return flag;
}
userBean.validarForm = function() {}

userBean.limpiarMoneda = function(str) {
    return str.replace('$ ','').replace(/\./g,'').replace(',','.');
}

userBean.inputKeyup = function(t) {
    $(t.target).val(function (index, value ) {
        if(value == " " || value == "")
            return "";
        
        return value.replace(/\D/g, "")
                .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
    });
}
userBean.inputFocusOut = function(t) {
    $(t.target).val(function (index, value ) {
        var valor = value.replace("$ ","");
        if(valor == "")
            return "";
        var regx = "/[.]/g";
        valor = valor.replace(eval(regx),"");
        valor = valor.replace(",",".");
        return userBean.formatearNumero(valor);
    });
}
userBean.formatearNumero = function(nStr) {
    nStr += '';
    nStr = nStr.trim();
    var amt = parseFloat(nStr);
    var a = amt.toFixed(2);
    x = a.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) 
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    
    return x1 + x2;
}

$('select.select__2').on('select2:unselecting', function (e) {
    console.log(e)
});
userBean.permite = function(e,letras) {
    var key = e.which,
        keye = e.keyCode,
        tecla = String.fromCharCode(key).toLowerCase();
    if (keye != 13) {
        if (letras.indexOf(tecla) == -1 && keye != 9 && (key == 37 || keye != 37) && (keye != 39 || key == 39) && keye != 8 && (keye != 46 || key == 46) || key == 161)
            e.preventDefault();
    }
}
/*
$(document).on("focus","input",function(event){
    $(event.target).select();
}).on("keyup","input",function(event){
    $(event.target).val(function (index, value ) {
        var signo = "";
        if(value == "$" || value == "-$" || value == "" || value == "$ " || value == "-$ ")
          return "";
        if(value[0] == "-")
          signo = "-";
        if(value[0] == "+")
          signo = "";
        return signo+"$ "+value.replace(/\D/g, "")
                .replace(/([0-9])([0-9]{2})$/, '$1,$2')
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
    });
}).on("focusout","input",function(event){
    
}).on("keyup","input",function(e){
      if($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
          return;
      }

      if((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
}).on("keypress", "input", function(e) { //----->SOLO NUMEROS
    var letras = '0123456789-';
    var key = e.which,
        keye = e.keyCode,
        tecla = String.fromCharCode(key).toLowerCase();
    if (keye != 13) {
        if (letras.indexOf(tecla) == -1 && keye != 9 && (key == 37 || keye != 37) && (keye != 39 || key == 39) && keye != 8 && (keye != 46 || key == 46) || key == 161)
            e.preventDefault();
    }
});

*/