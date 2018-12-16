<style type="text/css">
  .select2-container--default .select2-selection--single .select2-selection__clear { margin-right: 5px !important; }
  table.dataTable thead th,
  table th,
  table td { vertical-align: middle !important; }
  .shown + .no-padding { background-color: #fff !important; }
  .shown + .no-padding table { margin-bottom: 0 !important; }
  td.details-control { text-align: center; color: #fff; background-color: #2196f3; cursor: pointer; }
  .modal-footer { display: block !important; }
</style>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="../">Espejo</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
  </ol>
</nav>
<section class="">
  <div class="container-fluid" style="margin-bottom: 10px;">
    <div class="row margin__bottom__10">
      <!-- CARD COOPERATIVA -->
      <div class="col col-12 col-md-6 bloque__a">

        <div class="card margin__bottom__10 text-white bg-info">
          <div class="__coperativa card-header card-title text-center"><div class="row"></div></div>
          <div class="card-cheques d-none"></div>
          <div class="card-body overflow__200 info__cccccc"></div>
          <div class="card-footer">
            <div class="row">
              <div class="col col-6 col-md-6">
                <button class="btn btn-block btn-default" onclick="javascript:userBean.cheque(this,'a');"><i class="fas fa-plus"></i> cheque</button>
              </div>
              <div class="col col-6 col-md-6">
                <button class="btn btn-block btn-default eliminar" onclick="javascript:userBean.eliminar(this);"><i class="fas fa-trash-alt"></i> cheque</button>
              </div>
            </div>
          </div>
        </div>
        <div class="jumbotron margin__bottom__10 alert alert-info text-center">
          <h1 class="display-4">0,00</h1>
        </div>

      </div>
      <div class="col col-12 d-block d-sm-none">
        <hr>
      </div>
      <!-- CARD EMPRESA -->
      <div class="col col-12 col-md-6 bloque__b">

        <div class="card margin__bottom__10 text-white bg-dark">
          <div class="__empresa card-header card-title text-center"><div class="row"></div></div>
          <div class="card-body overflow__200 info__cccccc"></div>
          <div class="card-footer">
            <div class="row">
              <div class="col col-6 col-md-6">
                <button class="btn btn-block btn-default" onclick="javascript:userBean.cheque(this,'b');"><i class="fas fa-plus"></i> cheque</button>
              </div>
              <div class="col col-6 col-md-6">
                <button class="btn btn-block btn-default eliminar" onclick="javascript:userBean.eliminar(this);"><i class="fas fa-trash-alt"></i> cheque</button>
              </div>
            </div>
          </div>
        </div>
        <div class="jumbotron margin__bottom__10 alert alert-dark text-center">
          <h1 class="display-4">0,00</h1>
        </div>

      </div>
    </div>
    <div class="row margin__bottom__10 justify-content-md-center">
      <div class="col col-4 col-md-4"><button class="btn btn-block btn-danger" id="btn__limpiar" onclick="javascript:userBean.limpiarEspejo()"><i class="fas fa-eraser"></i><span style="margin-left: 10px;" class="d-none d-sm-block">Limpiar</span></button></div>
      <div class="col col-4 col-md-4"><button onclick="javascript:userBean.confirmar(this)" class="btn btn-block btn-success"><i class="fas fa-check"></i><span style="margin-left:10px;" class="d-none d-sm-block">Confirmar</span></button></div>
    </div>
  </div>
	<div class="container-fluid d-flex justify-content-center">
		<div class="flex__body col-12">
			<table id="cuenta" class="table table-striped table-hover flex__table" style="width: 100%"></table>
		</div>
	</div>
</section>
<!-- BEGIN Edit - New modal -->
<div id="modal" class="modal" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Librador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="modal-form" onsubmit="event.preventDefault(); userBean.validarForm();" novalidate>
              <div class="modal-body"><div></div></div>
              <div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>
<!-- END Edit - New modal -->

<script type="text/javascript">
//$("#div").removeClass("d-none");
var _persona = new Pyrus("persona");
var _cooperativa = new Pyrus("titular");
var _cheque = new Pyrus("cheque");
var _chequeaccion = new Pyrus("chequeaccion");
var _espejo = new Pyrus("espejo");
var chequefuera = new Pyrus("chequefuera");

var n_cheque = 0;
var Acheques = [];
var Aimagenes = [];
var aux = null, aux2 = null, aux3 = null;
var nivel = -1;
var Acheques_espejo = _cheque.busqueda__arr("espejo","1");//ARRAY de cheques creados en espejo que NO pueden verse
f_hoy = userBean.fechaYYYYMMDD();
cheques_comprobar = [];

Acheques_accion = chequefuera.busqueda__arr();
for(var u in Acheques_accion) cheques_comprobar.push(Acheques_accion[u]["id_cheque"]);

userBean.ready__ = function() {  
  _cooperativa.selectDatos_X(".__coperativa","cooperativa","id_cooperativa","col col-12","[persona/id_persona/nombre_mostrar]","normal");
  _persona.selectDatos_X(".__empresa","cliente","id_cliente","col col-12","[nombre_mostrar]","agregar");
  $("select.select__2").select2({width: 'resolve',placeholder: 'Seleccione',tags: true});

  $("body").on("focus",".has-error *",function() {
      $(this).parent().removeClass("has-error");
  });
  $("#div").addClass("d-none");
}
userBean.confirmar = function(t) {
  var cooperativa = $(".__coperativa select").val();
  var empresa = $(".__empresa select").val();

  if(cooperativa != "" && empresa != "" && $(".bloque__a").find(".card-body").html() != "" && $(".bloque__b").find(".card-body").html() != "") {
    if($(".bloque__a").find(".jumbotron *").text() == $(".bloque__a").find(".jumbotron *").text()) {
      $.MessageBox({
            buttonDone  : {
                yes         : "Si",
            },
            buttonFail  : {
                no          : "No"
            },
            message   : "Finalizar <strong>espejo</strong>"
        }).done(function(data, button){
		userBean.notificacion("Cargando... espere","warning",false);
    $("#div").removeClass("d-none")
		setTimeout(function(data,button){
          $(".val_fechaCorta").each(function() {
            _cheque.convertirDateToYYYYMMDD($(this));
          });
          _m = new Pyrus("movimiento");
          _espejo = new Pyrus("espejo");
          var did_movimiento = _m.busqueda__DID(); // obtiene el ultimo DID + 1 - elemento unico de movimientos
          var did_espejo = _espejo.busqueda__DID();
          var fecha = userBean.fechaYYYYMMDD();
          for(var i in Acheques) {
            var c = Acheques[i]["clase"];
            var o_cheque = Acheques[i]["objeto"];//Objeto
            var o_chequeA = Acheques[i]["accion"];//Objeto
            var t = Acheques[i]["tipo"];

            if(o_cheque["id"] == "nulo") {
              o_cheque["imagen"] = Aimagenes[i];
              id_cheque = _cheque.guardar("","",o_cheque);
              o_chequeA["id_cheque"] = id_cheque;
              _cheque.query('guardar_uno_generico',{'entidad' : 'chequeaccion', 'objeto' : o_chequeA}, function(m) {}, function(m) {});
            }
            else
              id_cheque = o_cheque["id"];

            if(t == "bloque__a")
              bloque = "2";//BLOQUE A
            else
              bloque = "4";//BLOQUE B

            var movimiento = {};
            movimiento["id"] = "nulo";
            movimiento["did"] = did_movimiento;
            movimiento["id_cheque"] = id_cheque;
            movimiento["id_destinatario"] = null;
            movimiento["fecha"] = fecha;
            movimiento["id_portador"] = null;
            movimiento["estado"] = 2; // ACTIVO
            movimiento["accion"] = 0; // 
            movimiento["tipo_movimiento"] = 6;// ESPEJO
            movimiento["detalle"] = "cheque #" + o_cheque["n_serie"] + " | en ESPEJO";
            movimiento["id_user"] = user_datos["user_id"];
            id_mov = _m.guardar("","",movimiento);
            //_m.query('guardar_uno_generico',{'entidad' : 'movimiento', 'objeto' : movimiento}, function(m) {}, function(m) {},null,false);

            var espejo = {};
            espejo["id"] = "nulo";
            espejo["did"] = did_espejo;
            espejo["fecha"] = fecha;
            espejo["bloque"] = bloque;
            espejo["did_movimiento"] = did_movimiento;
            espejo["id_movimiento"] = id_mov;
            espejo["id_cooperativa"] = cooperativa;
            espejo["id_cliente"] = empresa;
            espejo["id_cheque"] = id_cheque;
            espejo["id_user"] = user_datos["user_id"];

            _espejo.query('guardar_uno_generico',{'entidad' : 'espejo', 'objeto' : espejo}, function(m) {}, function(m) {});
          }
          userBean.limpiarEspejo();
          userBean.notificacion("Espejo generado","sucess");
          window.location = '../'
        }
		,1000); }
		).fail(function(data, button){});
    } else userBean.notificacion("Los montos no coinciden","error");
  } else {
    if(cooperativa == "") $(".__coperativa select").parent().addClass("has-error");
    if(empresa == "") $(".__empresa select").parent().addClass("has-error");
    userBean.notificacion("Faltan datos","error");
  }
}
userBean.cheque = function(t,tt){
  $("#div").removeClass("d-none");
	userBean.notificacion("Cargando... espere","warning",false);
	setTimeout(function() { userBean.cheque_behavior(t,tt); },200);
};
userBean.cheque_behavior = function(t,tipo) {
  $(t).closest(".card").addClass("cheque_activo");
  n_cheque++;
  $("#modal div.modal-body > div").addClass("cheque_" + n_cheque);
  $("#modal").find(".modal-title").text("CHEQUE");
  $("#modal .modal-footer").html("");

  contenedor = "#modal div.modal-body > div";
  _cheque.selectDatos_X(contenedor,"cheque","id_cheque","col col-12","Cheque: [n_serie] | F. cobro: [fecha_cobro] | Monto: [moneda/id_moneda/designacion] [monto]","agregar",["normal","fecha","normal","moneda"]);
  _cheque.editor__X_footer("#modal .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":null,"data":[{"dismiss":"'modal'"}]},"AGREGAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}})


  for(var x in Acheques) {
    y = x.slice(0, -1);
    $("#modal div.modal-body > div div.row select").find("option").each(function() {
      t = $("#modal div.modal-body > div div.row select option[value='" + $(this).val() + "']").text();
      if(t.indexOf(y) > 0) $(this).attr("disabled",true);
    });
  }
  for(var ii in Acheques_accion) {
    if($("select.id_cheque option[value='" + Acheques_accion[ii]["id_cheque"] + "']").length)
      $("select.id_cheque option[value='" + Acheques_accion[ii]["id_cheque"] + "']").remove();
  }

  for(var ii in Acheques_espejo) {
    if($("select.id_cheque option[value='" + Acheques_espejo[ii]["id"] + "']").length)
      if(parseInt(f_hoy) > parseInt(Acheques_espejo[0]["fecha_emision"]))
        $("select.id_cheque option[value='" + Acheques_espejo[ii]["id"] + "']").remove();
  }
  $(".select__2").select2({width: 'resolve',placeholder: 'Seleccione',tags: true});

  $("#modal").modal("show");
  $("#div").addClass("d-none");
}
userBean.comprobarFechas = function(t) {
  var diff = 0;
  var this_ = $(t).val();
  var this__class = $(t).next().attr("class");
  var n = $(t).closest(".modal-body").find("input[type='date'] + input[type='hidden']:not(." + this__class + ")")
  var next_ = $(n).prev().val();

  var d_this_ = Date.parse(this_);
  var d_next_ = next_ == "" ? 0 : Date.parse(next_);

  $(t).next().val()

  diff = d_this_ - d_next_;
  diff = diff < 0 ? diff * -1 : diff;
  diff = diff/(1000*60*60*24);
  console.log(diff)
  if(next_ != 0) {
    if(diff > 365) {
      $(t).parent().addClass("has-error");
      $(t).closest(".modal-body").find("input[type='date'] + input[type='hidden']:not(." + this__class + ")").parent().addClass("has-error");
      userBean.notificacion("<strong>Fecha de cobro</strong> incorrecta, supera los 365 días","error");
    } else {
      $(t).parent().removeClass("has-error");
      $(t).closest(".modal-body").find("input[type='date'] + input[type='hidden']:not(." + this__class + ")").parent().removeClass("has-error");
    }
  }
}
userBean.buscarCheque = function(t) {
  var value = $(t).val();
  var c = $(t).closest(".modal-body").find("> div").attr("class");//clases que siempre tienen los cheques
  var o;
  var index = 0;
  for(var x in Acheques) {
    if(Acheques[x]["c"] == c) {
      index = x;
      o = Acheques[x]["o"];break;
    }
  }
  
  if(value != "") {
    flag = true;
    for(var i in Acheques) {
      $(".card-cheques div input.n_serie").each(function() {
        if($(this).val() == value)
          flag = false;
      });
    }
    if(flag) {
      id = o.busqueda__("n_serie",value,"."+c);
      Acheques[index]["nuevo"] = id;
    } else {
      //el cheq esta repetido
      userBean.notificacion("El cheque #" + value + " está repetido","error");
      $(t).focus().select();
    }
    
  }
  else {
    $("#modal div.modal-body > div").find("input:not(:hidden)").attr("disabled",true);
    $(t).removeAttr("disabled");
  }
}
userBean.limpiarEspejo = function() {
  Acheques = [];
  $("div.card-header").find("select.select__2").val("").trigger("change");
  $("div.card-body,div.card-cheques").html("");
  $("button.eliminar").attr("disabled",true);
  $("div.jumbotron *").text("0,00");
}
userBean.eliminar = function(t) {
  $(t).closest(".card").find(".card-body .cheque").each(function() {
    if($(this).find("input[type='checkbox']").is(":checked")) {
      var class__ = $(this).attr("class");
      var serie = $(this).closest(".cheque").find("input[type='text']").data("serie");

      var t_cheque = $(this).find("input[type='text']").data("monto");

      var total = $(t).closest(".card").find("+ .jumbotron").text();
      total = total.trim();
      total = parseFloat(userBean.limpiarMoneda(total)) - t_cheque;
      $(t).closest(".card").find("+ .jumbotron *").text(userBean.formatearNumero(total));

      $(this).remove();
      delete Acheques[serie + "_"];
      
      //$(t).closest(".card").find("div.card-cheques div.cheque"+c).remove();
    }
  });
}
/*
 * VER ENTIDAD
 * Captura el evento change de los select, varia entre -1, -2 y -3; corresponde a un cierto nivel de jerarquía de elementos
 */
userBean.verEntidad = function(t,e) {
  //$(t).attr("disabled",true);
  if($(t).val() == "-1") {
    $("#div").removeClass("d-none");
    userBean.notificacion("Cargando... espere","warning",false);
    setTimeout(function() { userBean.verEntidad_behavior(t,e); },700);
  } else {
    console.log(e)
    
  }
}
userBean.verEntidad_behavior = function(t,e) {
  var Abtn = [],Abtn2 = [];
  if(e == "id_cheque") {
    $("#modal").data("tipo","cheque")
    if($(t).val() == "-1") {
      $("#modal").data("tipo","chequeNuevo")
      var bloque = $(".cheque_activo").closest(".card").parent().attr("class");
      console.log(bloque);
      bloque = bloque.replace(/col|-12|-md-6/gi, function (x) {//saco las clases habituales y obtengo la única
        return "";
      });
      bloque = bloque.trim();
      
      $("#modal .modal-body > div").find(".row").addClass("d-none");
      contenedor = "#modal .modal-body > div";
      _cheque.editor__X(contenedor,[{"id":""},{"id_portador":""},{"fecha_ingreso":""},{"espejo":""},{"id_user":""}],{"id_user":user_datos["user_id"],"espejo":1,"fecha_ingreso":userBean.fechaYYYYMMDD()},null,null,null,false,true);
      _chequeaccion.editor__X(contenedor,[{"id_accion":""},{"id_cheque":""},{"e_cuenta_origen":""}],{"id_accion":0},null,null,null,false,true);
      //valor = {},desactivado = [],funcion = {},data = null,visible = true,hidden = false,required = [],select_value_default = null
      _cheque.editor__X(contenedor,[{"n_serie":"col col-8"}]);
      _cheque.editor__X(contenedor,[{"id_moneda":"col col-6"},{"monto":"col col-6"}],null,null,null,null,true);
      _cheque.editor__X(contenedor,[{"id_banco":"col col-6"},{"id_librador":"col col-6"}],null,null,null,null,true,false,null,-2);
      _cheque.editor__X(contenedor,[{"fecha_emision":"col col-6"},{"fecha_cobro":"col col-6"}],null,null,{"fecha_emision":[{"onblur":"'userBean.convertir(this); userBean.comprobarFechas(this)'"}],"fecha_cobro":[{"onblur":"'userBean.convertir(this); userBean.comprobarFechas(this)'"}]},null,true);
      _cheque.editor__X(contenedor,[{"id_librado":"col col-6"},{"imagen":"col col-6"}],null,null,null,null,true,false,["imagen"],-2);
      if(bloque == "bloque__a") {
        _chequeaccion.editor__X(contenedor,[{"i_cuenta_origen":""},{"i_cuenta_destino":""},{"e_cuenta_destino":""}],null,null,null,null,false,true);
        _chequeaccion.editor__X(contenedor,[{"e_cuenta_origen":"col col-8"}],{},null,null,null,true,false,null,-2);
      } else {
        _chequeaccion.editor__X(contenedor,[{"e_cuenta_origen":""},{"i_cuenta_destino":""},{"e_cuenta_destino":""}],null,null,null,null,false,true);
        _chequeaccion.editor__X(contenedor,[{"i_cuenta_origen":"col col-8"}],{},null,null,null,true,false,null,-2);
      }
      _cheque.editor__X(contenedor,[{"obs":"col col-12"}],null,null,null,null,true,false,["obs"]);

      $(contenedor).find(".select__2").select2();
      $(contenedor).find(".monto").priceFormat({
          prefix: '',
          centsSeparator: ',',
          thousandsSeparator: '.'
      });
    }
  } else {
    Abtn.push({"nombre":"Cancelar","type":"button","onclick":"-1","class":"btn btn-danger"});
    Abtn.push({"nombre":"Guardar","type":"submit","onclick":"","class":"btn btn-success"});
    if($(t).val() == "-1") {
      $(t).addClass("asignar");
      switch(e) {
        case 'id_cliente':
          aux = new Pyrus("persona");
          $("#modal .modal-body > div").html("");
          $("#modal").find(".modal-title").html("NUEVO <strong>CLIENTE</strong>");
          contenedor = "#modal .modal-body > div";

          $(contenedor).append('<div class="margin__bottom__10"><button class="mx-auto d-block btn btn-primary" type="button" title="Cambiar a Persona jurídica" onclick="javascript:userBean.cambio(this);">a <strong>Persona jurídica</strong> <i class="fas fa-exchange-alt"></i></button></div>');

          //contenedor,formato,valor = {},desactivado = [],funcion = {},data = null,visible = true
          aux.editor__X(contenedor,[{"id":""},{"nombre_mostrar":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
          aux.editor__X(contenedor,[{"nombre":"col col-12"}],null,null,{"nombre":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
          aux.editor__X(contenedor,[{"apellido":"col col-12"}],null,null,{"apellido":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
          aux.editor__X(contenedor,[{"dni":"col col-12"}],null,null,null,[{"dato":"normal"}]);
          aux.editor__X(contenedor,[{"razon_social":"col col-12"}],null,null,{"razon_social":[{"onblur":"'userBean.concatenar(this,\"empresa\")'"}]},[{"dato":"juridica"}],false);
          aux.editor__X(contenedor,[{"cuit":"col col-12"}],null,null,null,[{"dato":"juridica"}],false);

          aux.editor__X_footer("#modal .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":null,"data":[{"dismiss":"'modal'"}]},"GUARDAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}})
        break;
        case 'id_cuenta_destino':
          aux = new Pyrus("cuentadestino")
          title = "Nueva cuenta destino";
          aux.editor__("#modal .modal-body > div",title);
          aux.editor__footer("#modal",Abtn); 
        break;
        case 'id_cuenta':
          aux = new Pyrus("cuenta")
          title = "Nueva cuenta";
          aux.editor__("#modal .modal-body > div",title);
          aux.editor__footer("#modal",Abtn);

          $("#modal .modal-body > div .id_user").closest(".row").remove();
          $("#modal .modal-body > div").append('<input type="hidden" class="id_user" value="' + user_datos["user_id"] + '">');
        break;
      }
      $("#modal").modal("show");
    }
    if($(t).val() == "-2") {
      $(t).addClass("asignar_2");
      $(t).closest(".modal-content").data("tipo","original");
      $(t).closest(".modal-content").addClass("d-none");
      $(t).closest(".modal-content").parent().append("<div class=\"modal-content\" data-tipo='copia'>");
      
      Abtn2.push({"nombre":"Cancelar","type":"button","onclick":"userBean.cancelarForm(this)","class":"btn btn-danger"});
      Abtn2.push({"nombre":"Guardar","type":"submit","onclick":"","class":"btn btn-success"});
      
      switch(e) {
        case 'id_banco':
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<div class="modal-header"><h5 class="modal-title">NUEVO <strong>BANCO</strong></h5></div>');
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<form method="POST" id="modal-form2" onsubmit="event.preventDefault(); userBean.validarForm2();" novalidate="">');
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-body">');
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-footer">');
          contenedor = "#modal .modal-content[data-tipo='copia'] .modal-body";
          
          aux = new Pyrus("banco");
          aux_a = new Pyrus("domicilio");
          aux_b = new Pyrus("sucursal");

          aux.editor__X(contenedor,[{"id":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
          aux.editor__X(contenedor,[{"nombre":"col col-12"}]);
          aux.editor__X(contenedor,[{"sucursal":"col col-12"}]);
          $(contenedor).append('<hr>');
          aux_a.editor__X(contenedor,[{"calle":"col col-8"},{"altura":"col col-4"}]);
          aux_a.editor__X(contenedor,[{"localidad":"col col-12"}]);
          $(contenedor).append('<hr>');
          aux_b.editor__X(contenedor,[{"telefono":"col col-12"}]);

          aux.editor__X_footer("#modal .modal-content[data-tipo='copia'] .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":[{"onclick":"userBean.cancelarForm(this)"}],"data":null},"GUARDAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}});
        break;
        case 'id_librado':
        case 'id_librador':
          Atipo_persona = {id_portador:'CLIENTE',id_librador:'LIBRADOR',id_librado:'LIBRADO',id_destinatario:'DESTINATARIO'};
          aux2 = new Pyrus("persona");
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<div class="modal-header"><h5 class="modal-title">NUEVO <strong>' + Atipo_persona[e] + '</strong></h5></div>');
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<form method="POST" id="modal-form2" onsubmit="event.preventDefault(); userBean.validarForm2();" novalidate="">');
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-body">');
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-footer">');
          contenedor = "#modal .modal-content[data-tipo='copia'] .modal-body";

          $(contenedor).append('<div class="margin__bottom__10"><button class="mx-auto d-block btn btn-primary" type="button" title="Cambiar a Persona jurídica" onclick="javascript:userBean.cambio(this);">a <strong>Persona jurídica</strong> <i class="fas fa-exchange-alt"></i></button></div>');

          //contenedor,formato,valor = {},desactivado = [],funcion = {},data = null,visible = true
          aux2.editor__X(contenedor,[{"id":""},{"nombre_mostrar":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
          aux2.editor__X(contenedor,[{"nombre":"col col-12"}],null,null,{"nombre":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
          aux2.editor__X(contenedor,[{"apellido":"col col-12"}],null,null,{"apellido":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
          aux2.editor__X(contenedor,[{"dni":"col col-12"}],null,null,null,[{"dato":"normal"}]);
          aux2.editor__X(contenedor,[{"razon_social":"col col-12"}],null,null,{"razon_social":[{"onblur":"'userBean.concatenar(this,\"empresa\")'"}]},[{"dato":"juridica"}],false);
          aux2.editor__X(contenedor,[{"cuit":"col col-12"}],null,null,null,[{"dato":"juridica"}],false);

          aux2.editor__X_footer("#modal .modal-content[data-tipo='copia'] .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":[{"onclick":"userBean.cancelarForm(this)"}],"data":null},"GUARDAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}});
        break;
        case 'i_cuenta_origen':
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<div class="modal-header"><h5 class="modal-title">NUEVA CUENTA</h5></div>');
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<form method="POST" id="modal-form2" onsubmit="event.preventDefault(); userBean.validarForm2();" novalidate="">');
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-body">');
          $(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-footer">');
          contenedor = "#modal .modal-content[data-tipo='copia'] .modal-body";

          aux2 = new Pyrus("cuentaexterna");

          aux2.editor__X(contenedor,[{"id":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
          aux2.editor__X(contenedor,[{"n_cuenta":"col col-12"}]);

          aux2.editor__X_footer("#modal .modal-content[data-tipo='copia'] .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":[{"onclick":"userBean.cancelarForm(this)"}],"data":null},"GUARDAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}});
        break;
      }
      $(".select__2").select2();
    }
  }
  $("#div").addClass("d-none");
}

/*
 * VALIDAR MODAL FORM
 * Al usar un mismo modal para el ingreso de registros de objetos, se oculta el contenedor activo y se agrega un nuevo elemento,
 * que despues es removido cuando se finaliza la acción.
 */
userBean.validarForm = function() {
  if(userBean.validar("#modal")) {
    if($("#modal").data("tipo") !== undefined) {
      $("#modal").removeData("tipo");
      var fecha = userBean.fechaYYYYMMDD();
      var tipo = $(".cheque_activo").parent().attr("class");
      tipo = tipo.replace(/col|-12|-md-6/gi, function (x) {//saco las clases habituales y obtengo la única
        return "";
      });
      tipo = tipo.trim();

      $(".fecha_ingreso").val(fecha);
      c = $("#modal .modal-body > div").attr("class");
      aux = new Pyrus("cheque");
      a = {};
      if($("#modal .modal-body > div .row:first-child").is(":visible")) {
        r = aux.objeto($("#modal .modal-body > div .row:first-child select").val())
      }
      else {
        $(".val_fechaCorta").each(function() {
          aux.convertirDateToYYYYMMDD($(this));
        });
        r = {};
        Aaux = ["id","id_portador","fecha_ingreso","espejo","n_serie","id_moneda","monto","id_banco","id_librador","fecha_emision","fecha_cobro","id_librado","imagen","obs","id_user"];
        Aaux_accion = ['id','id_cheque','id_accion','i_cuenta_origen','i_cuenta_destino','e_cuenta_origen','e_cuenta_destino'];
        
        for(var i in Aaux) {
          switch(Aaux[i]) {
            case "monto":
              r[Aaux[i]] = userBean.limpiarMoneda($("." + Aaux[i]).val());
              break;
            case "imagen":
              $("#modal .imagen").data("serie",r["n_serie"]);
              $("#modal .imagen").clone().append(".card-cheques");
              r[Aaux[i]] = "";//document.querySelector(".imagen").imagen_base64;
              Aimagenes[r["n_serie"] + "_"] = "";
              window.arr_img = $('input[type="file"]');
              fa = window.arr_img[0].files[0];
              if(fa !== undefined) {
                getBase64(fa,0,function(pos,f){
                  Aimagenes[r["n_serie"] + "_"] = f;
                });
              }
              break;
            default:
              r[Aaux[i]] = $("." + Aaux[i]).val();
          }
        }
        for(var i in Aaux_accion) a[Aaux_accion[i]] = $("." + Aaux_accion[i]).val();
        console.log(r)
      }
      Acheques[r["n_serie"] + "_"] = {"objeto": r,"accion":a,"clase":c,"tipo":tipo};

      cheque_html = "";
      cheque_html += '<div class="input-group cheque">';
        cheque_html += '<div class="input-group-prepend">';
          cheque_html += '<div class="input-group-text">';
            cheque_html += '<input type="checkbox">';
          cheque_html += '</div>';
        cheque_html += '</div>';
        cheque_html += '<input data-monto="' + r["monto"] + '" data-serie="'+ r["n_serie"] +'" type="text" class="form-control" readonly value="Cheque: #' + r["n_serie"] + ' | Monto: ' + userBean.formatearNumero(r["monto"]) + '">';
      cheque_html += '</div>';
      var total = $(".cheque_activo").find("+ .jumbotron").text();

      total = parseFloat(userBean.limpiarMoneda(total)) + parseFloat(r["monto"]);
      $(".cheque_activo").find("+ .jumbotron *").text(userBean.formatearNumero(total));
      $(".cheque_activo").find(".card-body").append(cheque_html);
    } else {
      id = aux.guardar();
      var arr = $("#modal-form").serializeArray();
      switch(aux.entidad) {
        case "persona":
          var newState = new Option(arr[0].value + " " + arr[1].value, id, true, true);
        break;
        default:
          var newState = new Option(arr[0].value, id, true, true);
        break;
      }

      $("select.asignar optgroup:last-child").append(newState).trigger("change");

      $("select.asignar").select2();
      aux = null;
    }
    $("#modal").modal("hide")
  }
}
userBean.validarForm2 = function() {
  var arr = $("#modal-form2").serializeArray();
  console.log(aux2);console.log(arr)
  if(userBean.validar("#modal-form2")) {
    id = aux2.guardar("nulo",".modal-content[data-tipo='copia']");
    switch(aux2.entidad) {
      case "persona":
        var newState = new Option(arr[0].value + " " + arr[1].value, id, true, true);
      break;
      case "banco":
        var _s = new Pyrus("sucursal");
        id_dom = aux2_a.guardar();
        tel = $(".telefono").val();

        var sucursal = {};
            sucursal["id"] = "nulo";
            sucursal["id_banco"] = id;
            sucursal["id_domicilio"] = id_dom;
            sucursal["telefono"] = tel;
            console.log(sucursal)
            _s.query('guardar_uno_generico',{'entidad' : 'sucursal', 'objeto' : sucursal}, function(m) {}, function(m) {},null,false);
            var newState = new Option(arr[0].value + " - " + arr[1].value, id, true, true);
      break;
      default:
        var newState = new Option(arr[0].value, id, true, true);
      break;
    }

    $("select.asignar_2 optgroup:last-child").append(newState).trigger("change");

    $("select.asignar_2").select2();
    $("#modal-form2").closest(".modal").find(".modal-content[data-tipo='copia']").prev().removeClass("d-none");
    $("#modal-form2").closest(".modal").find(".modal-content[data-tipo='copia']").remove();

    $(".asignar_2").removeClass("asignar_2");
    aux2 = null;
    nivel = -1;
  }
}
userBean.validarForm3 = function() {
  var arr = $("#modal-form3").serializeArray();
  if(userBean.validar("#modal-form3")) {
    id = aux3.guardar("nulo",".modal-content[data-tipo='copia2']");
    switch(aux3.entidad) {
      case "persona":
        var newState = new Option(arr[0].value + " " + arr[1].value, id, true, true);
      break;
      case "banco":
        var _s = new Pyrus("sucursal");
        id_dom = aux3_a.guardar();
        tel = $(".modal-content[data-tipo='copia2'] .telefono").val();

        var sucursal = {};
            sucursal["id"] = "nulo";
            sucursal["id_banco"] = id;
            sucursal["id_domicilio"] = id_dom;
            sucursal["telefono"] = tel;
            console.log(sucursal)
            _s.query('guardar_uno_generico',{'entidad' : 'sucursal', 'objeto' : sucursal}, function(m) {}, function(m) {},null,false);
            var newState = new Option(arr[0].value + " - " + arr[1].value, id, true, true);
      break;
      default:
        var newState = new Option(arr[0].value, id, true, true);
      break;
    }

    $("select.asignar_3 optgroup:last-child").append(newState).trigger("change");

    $("select.asignar_3").select2();
    $("#modal-form3").closest(".modal").find(".modal-content[data-tipo='copia']").removeClass("d-none");
    $("#modal-form3").closest(".modal").find(".modal-content[data-tipo='copia2']").remove();

    $(".asignar_3").removeClass("asignar_3");
    aux3 = null;
  }
}

/*
 * CANCELAR FORM
 * Al usar un mismo modal para el manejo de objetos, el cancelar de cada elemento debe ser distinto
 */
userBean.cancelarForm = function(t) {
  if($(".asignar_2").val() == "-2") {
    console.log("DDD")
    $(".asignar_2").val("").trigger("change");
  }
  $(t).closest(".modal").find(".modal-content[data-tipo='copia']").prev().removeClass("d-none");
  $(t).closest(".modal").find(".modal-content[data-tipo='copia']").remove();

  $(".asignar_2").removeClass("asignar_2");
}
userBean.cancelarForm2 = function(t) {
  if($(".asignar_3").val() == "-3") {
    console.log("DDD")
    $(".asignar_3").val("").trigger("change");
  }
  $(t).closest(".modal").find(".modal-content[data-tipo='copia2']").prev().removeClass("d-none");
  $(t).closest(".modal").find(".modal-content[data-tipo='copia2']").remove();

  $(".asignar_3").removeClass("asignar_3");
}

$(document).ready(userBean.ready__);
$('#modal').on('hidden.bs.modal', function (e) {
  $(this).find("input[type='reset']").click();
  $(".cheque_activo").removeClass("cheque_activo");
  //$("#modal-form").addClass("d-none");
  if($(".asignar").val() == "-1")
    $(".asignar").val("").trigger("change");
  if($("#modal").find(".modal-content[data-tipo='copia']").length) {
    $("#modal").find(".modal-content[data-tipo='copia']").prev().removeClass("d-none");
    $("#modal").find(".modal-content[data-tipo='copia']").remove();
  }
  if($("#modal").find(".modal-content[data-tipo='copia2']").length) {
    $("#modal").find(".modal-content[data-tipo='copia2']").remove();
  }
  $("#modal div.modal-body > div").html("");
  $("#modal div.modal-body > div").removeAttr("class");
  nivel = -1;
  aux = null, aux2 = null, aux3 = null;
});
$('#modal').on('shown.bs.modal', function (e) {
  if($(".select__2").length)
    $('.select__2').select2();

});
// convertidor a base64
function getBase64(file,pos,callback) {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            // window.archivo = reader.result;
            callback(pos,reader.result);
        };
        reader.onerror = function (error) {
            console.log('Error: ', error);
        };
    }
</script>