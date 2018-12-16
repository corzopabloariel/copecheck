<style type="text/css">
	.modal-footer { display: block !important; }
</style>
<nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="../">Gestión de cheques</a></li>
		<li class="breadcrumb-item active">Egreso de cheques</li>
  	</ol>
</nav>
<section class="">
	<div class="container-fluid justify-content-center">
		<div class="row justify-content-md-center __datos_cheque">
			<div class="col col-6">
				<div class="card datos__portador">
					<h5 class="card-header card-title text-center">EGRESO</h5>
					<div class="card-body">
					    <div class="__destinatario"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center d-none __datos_cheque">
			<div class="col col-12 col-sm-5">
				<div class="card datos__portador margin__bottom__10">
					<h5 class="card-header card-title text-center">Datos destinatario</h5>
					<div class="card-body">
						<h3 class="__destinatario_dato" style="margin: 0">
							<span></span>
							<i style="cursor: pointer;" class="fas fa-edit" onclick="javascript:userBean.anterior(this);"></i>
						</h3>
					</div>
					<div class="card-footer text-muted" id="__cheques_cantidad">
						Total de cheques: 0
					</div>
				</div>

				<button type="button" class="btn btn-block btn-primary margin__bottom__10 position-relative" onclick="javascript:userBean.nuevoCheque(this); window.mouse = 1">CHEQUE <span style="border-top-right-radius: inherit; border-bottom-right-radius: inherit; width: 32px; background: rgba(0,0,0,0.2); height: 100%; top: 0; right: 0;" class="d-flex position-absolute align-items-center justify-content-center"><i class="fas fa-plus"></i></span></button>
				<button type="button" class="btn btn-block btn-danger margin__bottom__10 position-relative" onclick="javascript:userBean.confirmar();">ASIGNAR <span style="border-top-right-radius: inherit; border-bottom-right-radius: inherit; width: 32px; background: rgba(0,0,0,0.2); height: 100%; top: 0; right: 0;" class="d-flex position-absolute align-items-center justify-content-center"><i class="fas fa-check"></i></span></button>
			</div>
			<div class="col col-12 col-sm-7">
				<div id="cheques"></div>
			</div>
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
            	<div class="modal-body"></div>
            	<div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>
<!-- END Edit - New modal -->

<script>
	/*
	 * CUENTA ORIGEN -> CUENTAS DEL SISTEMA (tabla cuenta)
	 * CUENTA DESTINO -> PERMITIR CREAR - externo
	 */
	 //espejo/movimiento/persona/librador/banco/cooperativa/cuenta
window.mouse = 0;
var _cheque = new Pyrus("cheque");
var _chequeaccion = new Pyrus("chequeaccion");
var _persona = new Pyrus("persona");
var _m = new Pyrus("movimiento");
var chequefuera = new Pyrus("chequefuera");

var aux = null, aux2 = null, aux_a = null, aux2_a = null;
let n_cheque = 0;
var Acheques_espejo = _cheque.busqueda__arr("espejo","1");//ARRAY de cheques creados en espejo que NO pueden verse
Acheques = [];
cheques_comprobar = [];
f_hoy = userBean.fechaYYYYMMDD();

Acheques_accion = chequefuera.busqueda__arr();
for(var u in Acheques_accion) cheques_comprobar.push(Acheques_accion[u]["id_cheque"]);

userBean.confirmar = function() {
	if($("#cheques").html() == "")
		userBean.notificacion("no agrego cheques","error");
	else {
		f = 1;
		// cargo todas las imagenes
		window.arr_img = $('input[type="file"]');
		for(z = 0; z < arr_img.length; ++z){
			fa = window.arr_img[z].files[0];
			if(fa !== undefined) {
				getBase64(fa,z,function(pos,f){
					// lo pongo como imagen en vista previa
					// lo asigno a una variable
					window.arr_img[pos].imagen_base64 = f;
				});
			}
		}
		id_destinatario = $(".__destinatario select").val();

		$("#cheques .card").each(function() {
			if($(this).data("fecha") == "no") f = 0;
		});
		if(f) {
			if(userBean.validar("#cheques")) {
				$.MessageBox({
				    buttonDone  : {
				        yes         : "Si",
				    },
				    buttonFail  : {
				        no          : "No"
				    },
				    message   : "Finalizar <strong>operación</strong>?"
				}).done(function(data, button){
					if(button == "yes") {
						userBean.notificacion("Cargando... espere","warning",false);
						setTimeout(function() {
							userBean.confirmar_behavior();
							setTimeout(function() {
								userBean.notificacion("Ingreso generado","sucess");
							},2000);
						},700);
					}
				}).fail(function(data, button){});
			} else userBean.notificacion("faltan datos","error");
		} else userBean.notificacion("verifique <strong>fechas</strong> de algunos cheques","error");
	}
}
userBean.confirmar_behavior = function() {
	$(".val_fechaCorta").each(function() {
		_cheque.convertirDateToYYYYMMDD($(this));
	});
	var did_movimiento = _m.busqueda__DID(); // obtiene el ultimo DID + 1 - elemento unico de movimientos
	var fecha = userBean.fechaYYYYMMDD();
	flag = true;
	for(var i in Acheques) {
		var c = Acheques[i]["c"];
		var e = Acheques[i]["nuevo"];

		if(e == 0) {
			if(_cheque.busqueda("n_serie",$("." + c).find(".n_serie").val()) !== undefined) {
				userBean.notificacion("Número repetido","error");
				flag = false;
				continue;
			}
		}
		if(e == 0) {
			o = _cheque.guardar("nulo","." + c);
			n_serie = $("." + c).find(".n_serie").val();
		}
		else {
			//MANDO OBS AL CHEQUE -> EDIT
			arr_cheque = _cheque.busqueda("id",e);
			arr_cheque["obs"] = $("."+ c + " .obs").val();
			//arr_cheque["espejo"] = 0;
			_cheque.query('guardar_uno_generico',{'entidad' : 'cheque', 'objeto' : arr_cheque}, function(m) {}, function(m) {},null,false);
			n_serie = arr_cheque["n_serie"];
			o = e;
		}

		c_aux = _cheque.busqueda__arr("id",o);

		var accion_id = $("."+ c + " .id_accion").val();
		var accion = {};
		accion["id"] = "nulo";
		accion["id_cheque"] = o;
		accion["id_accion"] = accion_id;
		accion["i_cuenta_origen"] = null;
		accion["i_cuenta_destino"] = null;
		accion["e_cuenta_origen"] = null; // campo solo en egreso
		accion["e_cuenta_destino"] = ($("."+ c + " .__cuenta_destino").val() === undefined ? null : $("."+ c + " .__cuenta_destino").val());

		_cheque.query('guardar_uno_generico',{'entidad' : 'chequeaccion', 'objeto' : accion}, function(m) {}, function(m) {},null,false);
		var chequefuera = {};
		chequefuera["id"] = "nulo";
		chequefuera["id_cheque"] = o;
		_cheque.query('guardar_uno_generico',{'entidad' : 'chequefuera', 'objeto' : chequefuera}, function(m) {}, function(m) {},null,false);

		var movimiento = {};
		movimiento["id"] = "nulo";
		movimiento["did"] = did_movimiento;
		movimiento["id_cheque"] = o;
		movimiento["fecha"] = fecha;
		movimiento["id_destinatario"] = $(".__destinatario select").val();
		movimiento["id_portador"] = null;
		movimiento["accion"] = accion_id; // 
		movimiento["estado"] = 2; // ACTIVO
		movimiento["tipo_movimiento"] = 4;// EGRESO
		movimiento["id_user"] = user_datos["user_id"];

		fecha_cobro = $("."+ c + " .fecha_cobro").val();
		fecha_cobro = _cheque.convertirYYYYMMDDToFecha(fecha_cobro);
		switch(accion_id) {
			case "2":
				cuenta_destino = $("."+ c + " .e_cuenta_destino option[value='" + $("."+ c + " .e_cuenta_destino").val() + "']").text();
				detalle = "Cheque #" + n_serie + " | DEPOSITAR el día " + fecha_cobro + " en Cuenta " + cuenta_destino;
				break;
			case "4":
				//obs = $("."+ c + " .obs").val();
				detalle = "Cheque #" + n_serie + " | PASAR a proveedor";
				//if(obs != "") detalle += ": " + obs;
				break;
			case "5":
				detalle = "Cheque #" + n_serie + " | NEGOCIAR con el BANCO - antes del día " + fecha_cobro;
		}
		movimiento["detalle"] = detalle;
		
		_m.query('guardar_uno_generico',{'entidad' : 'movimiento', 'objeto' : movimiento}, function(m) {}, function(m) {});
		Acheques[i] = null;
		$("#cheques ."+ c).remove()
	}
	if(flag) {
		userBean.notificacion("Egreso generado","sucess");
		$("#cheques").html("");
		$(".id_destinatario").val("").trigger("change");
		$("#__cheques_cantidad").text("Total de cheques: 0");
		$(".__datos_cheque.d-none").removeClass("d-none").next().addClass("d-none");
		Acheques = [];
		n_cheque = 0;
		$("#div").addClass("d-none");
		userBean.notificacion("operación finalizada","success");
	} else {
		/*
		 * Si se encuentra un nro de serie en la base de dato
		 * no lo guarda y manda mensaje.
		 * mantiene el bloque del cheque con nro repetido y los
		 * que pasaron el condinional, se eliminan del array general
		 */
		for(var i in Acheques) {
			if(Acheques[i] == null) Acheques.splice(i,1);
		}
	}
}
userBean.validarForm = function() {
	var arr = $("#modal-form").serializeArray();
	if(userBean.validar()) {
		id = aux.guardar();
		switch(aux.entidad) {
			case "persona":
				var newState = new Option(arr[0].value + " " + arr[1].value, id, true, true);
			break;
			case "banco":
        		var _s = new Pyrus("sucursal");
				id_dom = aux_a.guardar();
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

		$("select.asignar optgroup:last-child").append(newState).trigger("change");

		$("select.asignar").select2();
		$("#modal").modal("hide");
	}
}
userBean.validarForm2 = function() {
	var arr = $("#modal-form2").serializeArray();
	if(userBean.validar("#modal-form2")) {
		id = aux2.guardar();
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
	}
}

userBean.ready__ = function() {
	_persona.selectDatos_X(".__destinatario","destinatario","id_destinatario","col col-8","[nombre_mostrar]","agregar");
	_persona.editor__X_footer(".__destinatario",{"SIGUIENTE" : {"formato":"col col-8","tipo":"button","clase":"btn-primary btn-block","funcion":[{"onclick":"'userBean.siguiente(this)'"}]}});
    $(".__destinatario select.select__2").select2({width: 'resolve',placeholder: 'Seleccione',tags: true});

    $(".__destinatario select").change(function() {
    	if($(this).val() != "-1") {
    		sel = $(".__destinatario select option:selected").text();
    		$(".__destinatario_dato span").text(sel);
    		if($("#cheques").html() != "") {
    			//$("#cheques").find("input[type='hidden'].id_portador").val($(this).val());
    		}
    	}
    });
    $("body").on("focus",".has-error *",function() {
        $(this).parent().removeClass("has-error");
    });
    $("#div").addClass("d-none");
}
/*
 *
 */
userBean.verEntidad = function(t,e) {
	//$(t).attr("disabled",true);
	if(window.mouse) {
		window.mouse = 0;
		userBean.notificacion("Cargando... espere","warning",false);
		setTimeout(function() { userBean.verEntidad_behavior(t,e); },700);
	} else userBean.verEntidad_behavior(t,e);
}
userBean.verEntidad_behavior = function(t,e) {
	var Abtn = [];
	Abtn.push({"nombre":"Cancelar","type":"button","onclick":"-1","class":"btn btn-danger"});
	Abtn.push({"nombre":"Guardar","type":"submit","onclick":"","class":"btn btn-success"});
	console.log(e)
	if(e == "id_cheque") {
		if($(t).val() == "-1") {
			class__ = $(t).closest(".card").attr("class");
			class__ = class__.replace(/margin__bottom__10|card|text-white|bg-info/gi, function (x) {
				return "";
			});
			class__ = class__.trim();
			if($(t).closest(".card-body").find("."+class__ + ".cheque_datos").length) $(t).closest(".card-body").find("."+class__ + ".cheque_datos").remove();
			$(t).closest(".card-body").append("<div class='" + class__ + " cheque_datos'>");

			$(t).closest(".card-body").addClass("nuevo_cheque");
			contenedor = "." + class__ + ".cheque_datos";
			

			_cheque.editor__X(contenedor,[{"id":""},{"id_portador":""},{"fecha_ingreso":""},{"espejo":""},{"id_user":""}],{"id_user":user_datos["user_id"],"espejo":0,"fecha_ingreso":userBean.fechaYYYYMMDD()},null,null,null,false,true);
			_chequeaccion.editor__X(contenedor,[{"id_cheque":""},{"e_cuenta_origen":""},{"e_cuenta_destino":""}],null,null,null,null,false,true);
			//valor = {},desactivado = [],funcion = {},data = null,visible = true,hidden = false,required = [],select_value_default = null
			_cheque.editor__X(contenedor,[{"n_serie":"col col-8"}]);
			_chequeaccion.editor__X(contenedor,[{"id_accion":"col col-8"}]);
			$(contenedor).append("<div class='__cuenta_destino padding__bottom__10 d-none'></div>");
			_cheque.editor__X(contenedor,[{"id_moneda":"col col-6"},{"monto":"col col-6"}],null,null,null,null,true);
			_cheque.editor__X(contenedor,[{"id_banco":"col col-6"},{"id_librador":"col col-6"}],null,null,null,null,true,false,null,-1);
			_cheque.editor__X(contenedor,[{"fecha_emision":"col col-6"},{"fecha_cobro":"col col-6"}],null,["fecha_emision","fecha_cobro"],{"fecha_emision":[{"onblur":"'userBean.convertir(this); userBean.comprobarFechas(this)'"}],"fecha_cobro":[{"onblur":"'userBean.convertir(this); userBean.comprobarFechas(this)'"}]},null,true);
			_cheque.editor__X(contenedor,[{"id_librado":"col col-6"},{"imagen":"col col-6"}],null,null,null,null,true,false,["imagen"],-1);
			//_chequeaccion.editor__X(".nuevo_cheque",[{"e_cuenta_origen":"col col-6"},{"e_cuenta_destino":"col col-6"}],{},null,null,null,true,false,null,-1);
			_cheque.editor__X(contenedor,[{"obs":"col col-12"}],null,null,null,null,true,false,["obs"]);
			$(contenedor).find(".id_accion option[value='3']").remove();
			$(contenedor).find(".monto").priceFormat({
			    prefix: '',
			    centsSeparator: ',',
			    thousandsSeparator: '.'
			});
			$(contenedor).find(".select__2").select2();
		} else if($(t).val() != "") {
			class__ = $(t).closest(".card").attr("class");
			class__ = class__.replace(/margin__bottom__10|card|text-white|bg-info/gi, function (x) {
				return "";
			});
			class__ = class__.trim();
			if($(t).closest(".card-body").find("."+class__ + ".cheque_datos").length) $(t).closest(".card-body").find("."+class__ + ".cheque_datos").remove();
			$(t).closest(".card-body").append("<div class='" + class__ + " cheque_datos'>");
			userBean.buscarCheque(t);

			contenedor = "." + class__ + ".cheque_datos";
			_cheque.editor__X(contenedor,[{"id":""},{"id_portador":""},{"fecha_ingreso":""},{"espejo":""},{"id_user":""}],{"id_user":user_datos["user_id"],"espejo":0,"fecha_ingreso":userBean.fechaYYYYMMDD(),"id_portador":$(".id_portador").val()},null,null,null,false,true);
			_chequeaccion.editor__X(contenedor,[{"id_cheque":""},{"e_cuenta_origen":""},{"e_cuenta_destino":""}],null,null,null,null,false,true);
			_chequeaccion.editor__X(contenedor,[{"id_accion":"col col-8"}]);
			$(contenedor).append("<div class='__cuenta_destino padding__bottom__10 d-none'></div>");
			_cheque.editor__X(contenedor,[{"id_banco":"col col-6"},{"id_librador":"col col-6"}],null,["id_banco","id_librador"],null,null,true,false,null,-1);
			_cheque.editor__X(contenedor,[{"fecha_emision":"col col-6"},{"fecha_cobro":"col col-6"}],null,["fecha_emision","fecha_cobro"],{"fecha_emision":[{"onblur":"'userBean.comprobarFechas(this)'"}],"fecha_cobro":[{"onblur":"'userBean.comprobarFechas(this)'"}]},null,true);
			_cheque.editor__X(contenedor,[{"id_librado":"col col-6"},{"imagen":"col col-6"}],null,["id_librado","imagen"],null,null,true,false,["imagen"],-1);
			_cheque.editor__X(contenedor,[{"obs":"col col-12"}],null,null,null,null,true,false,["obs"]);
			_cheque.cargarAEditar($(t).val(),"." + class__)

			$("." + class__ + ".cheque_datos").find(".id_accion option[value='3']").remove();
			$("." + class__ + ".cheque_datos .select__2").select2();
		} else {

			class__ = $(t).closest(".card").attr("class");
			class__ = class__.replace(/margin__bottom__10|card|text-white|bg-info/gi, function (x) {
				return "";
			});
			class__ = class__.trim();
			if($(t).closest(".card-body").find("."+class__ + ".cheque_datos").length) $(t).closest(".card-body").find("."+class__ + ".cheque_datos").remove();
		}
	} else {
		if($(t).val() == "-1") {
			$(t).addClass("asignar");
			switch(e) {
				case 'id_portador':
				case 'id_librador':
				case 'id_librado':
				case 'id_destinatario':
					Atipo_persona = {id_portador:'CLIENTE',id_librador:'LIBRADOR',id_librado:'LIBRADO',id_destinatario:'DESTINATARIO'};
					aux = new Pyrus("persona");
					$("#modal").find(".modal-body,.modal-footer").html("");
					$("#modal").find(".modal-title").html("NUEVO <strong>" + Atipo_persona[e] + "</strong>");

					$("#modal").find(".modal-body").append('<div class="margin__bottom__10"><button class="mx-auto d-block btn btn-primary" type="button" title="Cambiar a Persona jurídica" onclick="javascript:userBean.cambio(this);">a <strong>Persona jurídica</strong> <i class="fas fa-exchange-alt"></i></button></div>');

					//contenedor,formato,valor = {},desactivado = [],funcion = {},data = null,visible = true
					aux.editor__X("#modal .modal-body",[{"id":""},{"nombre_mostrar":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
					aux.editor__X("#modal .modal-body",[{"nombre":"col col-12"}],null,null,{"nombre":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
					aux.editor__X("#modal .modal-body",[{"apellido":"col col-12"}],null,null,{"apellido":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
					aux.editor__X("#modal .modal-body",[{"dni":"col col-12"}],null,null,null,[{"dato":"normal"}]);
					aux.editor__X("#modal .modal-body",[{"razon_social":"col col-12"}],null,null,{"razon_social":[{"onblur":"'userBean.concatenar(this,\"empresa\")'"}]},[{"dato":"juridica"}],false);
					aux.editor__X("#modal .modal-body",[{"cuit":"col col-12"}],null,null,null,[{"dato":"juridica"}],false);
				break;
				case 'e_cuenta_destino':
					aux = new Pyrus("cuentaexterna");
					title = "NUEVA <strong>CUENTA</strong>";
					aux.editor("#modal",title);
					$(".id_user").closest(".row").remove();
					$("#modal").find(".modal-body").append('<input type="hidden" class="id_user" value="' + user_datos["user_id"] + '">');
				break;
				case 'id_banco':
					aux = new Pyrus("banco");
					aux_a = new Pyrus("domicilio");
					aux_b = new Pyrus("sucursal");
					$("#modal").find(".modal-body,.modal-footer").html("");
					$("#modal").find(".modal-title").html("NUEVO <strong>BANCO</strong>");

					aux.editor__X("#modal .modal-body",[{"id":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
					aux.editor__X("#modal .modal-body",[{"nombre":"col col-12"}]);
					aux.editor__X("#modal .modal-body",[{"sucursal":"col col-12"}]);
					$("#modal").find(".modal-body").append('<hr>');
					aux_a.editor__X("#modal .modal-body",[{"calle":"col col-8"},{"altura":"col col-4"}]);
					aux_a.editor__X("#modal .modal-body",[{"localidad":"col col-12"}]);
					$("#modal").find(".modal-body").append('<hr>');
					aux_b.editor__X("#modal .modal-body",[{"telefono":"col col-12"}]);
				break;
			}
			aux.editor__X_footer("#modal .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":null,"data":[{"dismiss":"'modal'"}]},"GUARDAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}});
			$("#modal").modal("show");
		} else {
			switch(e) {
				case "id_accion":
					class__ = $(t).closest(".cheque_datos").attr("class");
					if($(t).closest(".card-body").find(".id").val() == "") {
		 				$(t).closest(".card-body").find(".fecha_emision").prev().removeAttr("disabled");
		 				$(t).closest(".card-body").find(".fecha_cobro").prev().removeAttr("disabled");
		 			}

					if(class__ !== undefined) {
						class__ = class__.replace("cheque_datos","");
						class__ = class__.trim();

						$("." + class__ + ".cheque_datos .__cuenta_destino").html("");
						$("." + class__ + ".cheque_datos .__cuenta_destino").addClass("d-none");
						if($(t).val() == "2") {
							$("." + class__ + ".cheque_datos .__cuenta_destino").removeClass("d-none");
							_cuentadestino = new Pyrus("cuenta");
							_cuentadestino.selectDatos_X("." + class__ + ".cheque_datos .__cuenta_destino","cuenta destino","e_cuenta_destino","col col-8","[n_cuenta]","normal");
							//$("." + class__ + " .e_cuenta_destino").attr("disabled",true);
						}
						if($("." + class__ + " .e_cuenta_destino").length)
							$("." + class__ + " .e_cuenta_destino").select2();
					}
					break;
			}
		}
	}
}
/*
 *
 */
userBean.siguiente = function(t) {
	if(userBean.validar(".__destinatario")) {
		$(t).closest(".__datos_cheque").addClass("d-none")
		$(t).closest(".__datos_cheque").next().removeClass("d-none");
	}
};
userBean.anterior = function(t) {
	if($("#cheques").html() == "") {
		$(t).closest(".__datos_cheque").addClass("d-none")
		$(t).closest(".__datos_cheque").prev().removeClass("d-none");
	} else {
		$.MessageBox({
		    buttonDone  : {
		        yes         : "Si",
		        maybe       : "Volver a empezar"
		    },
		    buttonFail  : {
		        no          : "No"
		    },
		    message   : "Hay cheques agregados ¿Está seguro de cambiar de <strong>destinatario</strong>?"
		}).done(function(data, button){
			$(t).closest(".__datos_cheque").addClass("d-none")
			$(t).closest(".__datos_cheque").prev().removeClass("d-none");
			if(button == "yes") {
				
			} else {
				$("#cheques").html("");
				$(".__portador select").val("").trigger("change");
				$("#__cheques_cantidad").text("Total de cheques: " + $("#cheques > div.card").length);
				Acheques = [];
				n_cheque = 0;
			}
		}).fail(function(data, button){});
	}
}
userBean.cancelarForm = function(t) {
	if($(".asignar_2").val() == "-2") {
		console.log("DDD")
		$(".asignar_2").val("").trigger("change");
	}
	$(t).closest(".modal").find(".modal-content[data-tipo='copia']").prev().removeClass("d-none");
	$(t).closest(".modal").find(".modal-content[data-tipo='copia']").remove();

	$(".asignar_2").removeClass("asignar_2");
}

userBean.nuevoCheque_behavior = function(t) {
	//Acheques
	n_cheque++;

	$("#cheques").append("<div class=\"margin__bottom__10 cheque_" + n_cheque + " card text-white bg-info\">");
	$("#cheques").find("> div.card:last-child").append("<h5 style=\"cursor:pointer\" class=\"card-header card-title\" onclick=\"javascript:userBean.toggle__(this);\">Cheque #" + n_cheque + "</h5>");
	$("#cheques").find("> div.card:last-child").append("<button style=\"top:10px; right:10px;\" class=\"btn btn-danger position-absolute\" onclick=\"javascript:userBean.eliminarCheque(this)\"><i class=\"fas fa-trash-alt\"></i></button>");
	$("#cheques").find("> div.card:last-child").append("<div class=\"card-body\">");


	//(t,nombre,identificador,tam,dato,estado,disabled,function__)
	contenedor = "#cheques div.cheque_" + n_cheque + " div.card-body";
	_cheque.selectDatos_X(contenedor,"cheque","id_cheque","col col-12","Cheque: [n_serie] | F. cobro: [fecha_cobro] | Monto: [moneda/id_moneda/designacion] [monto]","agregar",["normal","fecha","normal","moneda"]);

	//_cheque.selectDatos("#cheques > div.card:last-child div.card-body div.row","cheque","id_cheque","col col-12","","agregar","",{},["normal","fecha","moneda"]);
	$("#cheques").find(".cheque_" + n_cheque + " .select__2").select2();
	Acheques.push({"c":"cheque_" + n_cheque,"nuevo":0});//Atributo NUEVO: mantiene 0 si el cheque a guardar es nuevo; != 
	$("#__cheques_cantidad").text("Total de cheques: " + $("#cheques > div.card").length);


	for(var ii in Acheques) {
		if(Acheques[ii]["nuevo"] != 0) {
			$("select.id_cheque option[value='" + Acheques[ii]["nuevo"] + "']").attr("disabled",true)
		}
	}
	for(var ii in Acheques_accion) {
		if($("select.id_cheque option[value='" + Acheques_accion[ii]["id_cheque"] + "']").length)
			$("select.id_cheque option[value='" + Acheques_accion[ii]["id_cheque"] + "']").remove();
	}
	for(var ii in Acheques_espejo) {
		if($("select.id_cheque option[value='" + Acheques_espejo[ii]["id"] + "']").length) {
			if(parseInt(f_hoy) > parseInt(Acheques_espejo[0]["fecha_emision"]))
				$("select.id_cheque option[value='" + Acheques_espejo[ii]["id"] + "']").remove();
		}
	}
	$("#cheques select.id_cheque").select2();
	$(t).removeAttr("disabled");
}
userBean.buscarCheque = function(t) {
	var value = $(t).val();
	var class__ = $(t).closest(".margin__bottom__10.card.text-white.bg-info").attr("class");//clases que siempre tienen los cheques
	var c = class__.replace(/margin__bottom__10|card|text-white|bg-info/gi, function (x) {//saco las clases habituales y obtengo la única
        return "";
    });
    c = c.trim();//quito espacios adicionales
    var index = $(t).closest("." + c).index();
	Acheques[index]["nuevo"] = value;
}

$(document).ready(userBean.ready__());
$('#modal').on('hidden.bs.modal', function (e) {
    $(this).find("input[type='reset']").click();
	if($(".asignar").val() == "-1")
		$(".asignar").val("").trigger("change");
	$(".asignar").removeClass("asignar");
	//$("#modal-form").addClass("d-none");
	if($("#modal").find(".modal-content[data-tipo='copia']").length) {
		$("#modal").find(".modal-content[data-tipo='copia']").prev().removeClass("d-none");
		$("#modal").find(".modal-content[data-tipo='copia']").remove();
	}
});

$('#modal').on('shown.bs.modal', function (e) {
	if($(".select__2").length)
		$('.select__2').select2();
});
$('#modal').on('hidden.bs.modal', function (e) {
	aux = null;
	aux2 = null;
	aux_a = null;
	aux2_a = null;
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
