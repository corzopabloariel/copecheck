<style type="text/css">
	.modal-footer { display: block !important; }
</style>
<nav aria-label="breadcrumb">
  	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="../">Gestión de cheques</a></li>
		<li class="breadcrumb-item active">Ingreso de cheques</li>
  	</ol>
</nav>
<!--
 - PÁGINA 1
-->
<section class="">
	<div class="container-fluid justify-content-center">
		<div class="row justify-content-md-center __datos_cheque">
			<div class="col col-6">
				<div class="card datos__portador">
					<h4 class="card-header card-title text-center">INGRESO</h4>
					<div class="card-body">
					    <div class="__portador"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center d-none __datos_cheque">
			<div class="col col-12 col-sm-5">
				<div class="card datos__portador margin__bottom__10">
					<h4 class="card-header card-title text-center">Datos cliente</h4>
					<div class="card-body">
						<h3 class="__postador_dato" style="margin: 0">
							<span></span>
							<i style="cursor: pointer;" class="fas fa-edit" onclick="javascript:userBean.anterior(this);"></i>
						</h3>
					</div>
					<div class="card-footer text-muted" id="__cheques_cantidad">
						Total de cheques: 0
					</div>
				</div>

				<button type="button" class="btn btn-block btn-primary margin__bottom__10 position-relative" onclick="javascript:userBean.nuevoCheque(this);">CHEQUE <span style="border-top-right-radius: inherit; border-bottom-right-radius: inherit; width: 32px; background: rgba(0,0,0,0.2); height: 100%; top: 0; right: 0;" class="d-flex position-absolute align-items-center justify-content-center"><i class="fas fa-plus"></i></span></button>
				<button type="button" class="btn btn-block btn-success margin__bottom__10 position-relative" onclick="javascript:userBean.confirmar();">ASIGNAR <span style="border-top-right-radius: inherit; border-bottom-right-radius: inherit; width: 32px; background: rgba(0,0,0,0.2); height: 100%; top: 0; right: 0;" class="d-flex position-absolute align-items-center justify-content-center"><i class="fas fa-check"></i></span></button>
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
var _cheque = new Pyrus("cheque");
var _chequeaccion = new Pyrus("chequeaccion");
var _p = new Pyrus("persona");
var _m = new Pyrus("movimiento");
let n_cheque = 0;
var aux = null, aux2 = null, aux_a = null, aux2_a = null;
var validar_fechas = 1;

Amodal = [];
Acheques = [];

userBean.validarForm = function() {
	var arr = $("#modal-form").serializeArray();
	console.log(arr)
	if(userBean.validar("#modal-form")) {
		id = aux.guardar();
		switch(aux.entidad) {
			case "persona":
				if(arr[0].value != "")
					var newState = new Option(arr[0].value + " " + arr[1].value, id, true, true);
				else
					var newState = new Option(arr[3].value, id, true, true);//razon social
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

userBean.confirmar = function() {
	if($("#cheques").html() == "")
		userBean.notificacion("sin <strong>cheques</strong> para ingresar","error");
	else {
		if(userBean.validar("#cheques")) {
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
		
			f = 1;
			id_portador = $(".__portador select").val();
			$("#cheques .card").each( function() {
				id_librador = $(this).find("select.id_librador").val();
				if(id_librador == id_portador) {
					f = 0;
				}
			});

			//$("#cheques .card").data("fecha")
			$("#cheques .card").each(function() {
				if($(this).data("fecha") == "no") validar_fechas = 0;
			})
			if(validar_fechas) {
				if(f) {
					$("#cheques").find("input,select,textarea,button").attr("disabled",true);
					$("#cheques").find(".select__2").select2();
					
					$.MessageBox({
					    buttonDone  : {
					        yes         : "Si",
					    },
					    buttonFail  : {
					        no          : "No"
					    },
					    message   : "Finalizar <strong>operación</strong>?"
					}).done(function(data, button){
						userBean.notificacion("Cargando... espere","warning",false);
						setTimeout(function() {
							userBean.confirmar_behavior();
							setTimeout(function() {
								userBean.notificacion("Ingreso generado","sucess");
							},2000);
						},700);
					}).fail(function(data, button){
						$("#cheques").find("input,select,textarea,button").removeAttr("disabled");
						$("#cheques").find("input[type='date']").each(function() {
							if($(this).val() == "") $(this).prev().attr("disabled",true);
						});
						$("#cheques").find("select.i_cuenta_destino").each(function() {
							if($(this).val() == "") $(this).attr("disabled",true);
						});
						$("#cheques").find(".select__2").select2();
					});
				} else userBean.notificacion("<strong>Cliente</strong> y <strong>librador</strong> no pueden ser el mismo","error");
			} else userBean.notificacion("verifique <strong>fechas</strong> de algunos cheques","error");
		} else userBean.notificacion("faltan datos","error");
	}
}
userBean.confirmar_behavior = function() {
	var did_movimiento = _m.busqueda__DID(); // obtiene el ultimo DID + 1 - elemento unico de movimientos
	var fecha = userBean.fechaYYYYMMDD();
	for(var i in Acheques) {
		flag = true;
		var c = Acheques[i]["c"];
		//$("." + c).append("<input class=\"id_portador\" type=\"hidden\" value=\"" + $(".id_portador").val() + "\" />");
		if(_cheque.busqueda("n_serie",$("." + c).find(".n_serie").val()) === undefined) {
			var o = _cheque.guardar("nulo","." + c);
			console.log(o)
			/*
			 * 2) A depositar: La fecha no puede superar los 30 días / Necesario: Cuenta Destino
			 * 3) Guardar: La fecha no puede superar los 365 / Desactivar: Cuenta Destino
			 * 4) Pasar: La fecha entre 30 y 365 / Desactivar: cuenta Destino
			 * 5) Negociar c/ Banco: La fecha no puede superar los 365 / Desactivar: Cuenta Destino
			 */
			var accion_id = $("."+ c + " .id_accion").val();
			var accion = {};
			accion["id"] = "nulo";
			accion["id_cheque"] = o;
			accion["id_accion"] = accion_id;
			accion["i_cuenta_origen"] = $("."+ c + " .i_cuenta_origen").val(); // cuenta externa al sistema
			accion["i_cuenta_destino"] = (accion_id == 2 ? $("."+ c + " .i_cuenta_destino").val() : null);
			accion["e_cuenta_origen"] = null; // campo solo en egreso
			accion["e_cuenta_destino"] = null; // campo solo en egreso

			_cheque.query('guardar_uno_generico',{'entidad' : 'chequeaccion', 'objeto' : accion}, function(m) {}, function(m) {},null,false);

			if(accion_id != 3) {//Ya estaria fuera del sistema
				var chequefuera = {};
				chequefuera["id"] = "nulo";
				chequefuera["id_cheque"] = o;
				_cheque.query('guardar_uno_generico',{'entidad' : 'chequefuera', 'objeto' : chequefuera}, function(m) {}, function(m) {},null,false);
			}
			
			var movimiento = {};
			movimiento["id"] = "nulo";
			movimiento["did"] = did_movimiento;
			movimiento["id_cheque"] = o;
			movimiento["id_destinatario"] = null;
			movimiento["fecha"] = fecha;
			movimiento["id_portador"] = $(".__portador select").val();
			movimiento["accion"] = accion_id; // 
			movimiento["estado"] = 2; // ACTIVO
			movimiento["tipo_movimiento"] = 2;// INGRESO
			movimiento["id_user"] = user_datos["user_id"];

			n_serie = $("." + c + " .n_serie").val();
			fecha_cobro = $("."+ c + " .fecha_cobro").val();
			fecha_cobro = _cheque.convertirYYYYMMDDToFecha(fecha_cobro);
			switch(accion_id) {
				case "2":
					cuenta_destino = $("."+ c + " .i_cuenta_destino option[value='" + $("."+ c + " .i_cuenta_destino").val() + "']").text();
					detalle = "Cheque #" + n_serie + " | DEPOSITAR el día " + fecha_cobro + " en Cuenta " + cuenta_destino;
					break;
				case "3":
					detalle = "Cheque #" + n_serie + " | en CARTERA";
					break;
				case "4":
					detalle = "Cheque #" + n_serie + " | PASAR a proveedor";
					break;
				case "5":
					detalle = "Cheque #" + n_serie + " | NEGOCIAR con el BANCO - antes del día " + fecha_cobro;
			}
			
			movimiento["detalle"] = "Cheque #" + n_serie + " | GUARDADO";
			if(accion_id != 3) {
				var movimiento2 = {};
				movimiento2["id"] = "nulo";
				movimiento2["did"] = did_movimiento;
				movimiento2["id_cheque"] = o;
				movimiento2["id_destinatario"] = null;
				movimiento2["fecha"] = fecha;
				movimiento2["id_portador"] = $(".__portador select").val();
				movimiento2["accion"] = accion_id; // 
				movimiento2["estado"] = 8; // CERRADO / FINALIZADO
				movimiento2["tipo_movimiento"] = 4;// EGRESO
				movimiento2["id_user"] = user_datos["user_id"];
				movimiento2["detalle"] = detalle;
			} else 
				movimiento["detalle"] = detalle;
			
			_m.query('guardar_uno_generico',{'entidad' : 'movimiento', 'objeto' : movimiento}, function(m) {}, function(m) {},null,false);
			if(movimiento2 !== undefined)
				_m.query('guardar_uno_generico',{'entidad' : 'movimiento', 'objeto' : movimiento2}, function(m) {}, function(m) {},null,false);

			$("#cheques ." + c).remove();
		} else {
			flag = false;
			userBean.notificacion("Número repetido","error");
		}
	}
	$("#cheques").html("");
	$(".__portador select").val("").trigger("change");
	$("#__cheques_cantidad").text("Total de cheques: 0");
	$(".__datos_cheque.d-none").removeClass("d-none").next().addClass("d-none");
	Acheques = [];
	n_cheque = 0;
}
userBean.siguiente = function(t) {
	if(userBean.validar(".datos__portador")) {
		$(t).closest(".__datos_cheque").addClass("d-none")
		$(t).closest(".__datos_cheque").next().removeClass("d-none");
	}
}
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
		    message   : "Hay cheques agregados ¿Está seguro de cambiar de <strong>portador</strong>?"
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
userBean.asignar = function(btn) {
	$(btn).closest(".asignar").addClass("activo");
	$("#modal").modal("show");
}
/*
 *
 */
userBean.verEntidad = function(t,e) {
	//$(t).attr("disabled",true);
	if($(t).val() == "-1") {
		$("#div").removeClass("d-none");
		userBean.notificacion("Cargando... espere","warning",false);
		setTimeout(function() { userBean.verEntidad_behavior(t,e); },700);
	} else {
		console.log(e)
		if(e == "id_accion") {
			/*
			 * 2) A depositar: La fecha no puede superar los 30 días / Necesario: Cuenta Destino
			 * 3) Guardar: La fecha no puede superar los 365 / Desactivar: Cuenta Destino
			 * 4) Pasar: La fecha entre 30 y 365 / Desactivar: cuenta Destino
			 * 5) Negociar c/ Banco: La fecha no puede superar los 365 / Desactivar: Cuenta Destino
			 */
			 $(t).closest(".card-body").find(".fecha_emision").prev().removeAttr("disabled");
			 $(t).closest(".card-body").find(".fecha_cobro").prev().removeAttr("disabled");

			switch($(t).val()) {
				case "2":
					$(t).closest(".card-body").find(".i_cuenta_destino").removeAttr("disabled");
				break;
				default:
					$(t).closest(".card-body").find(".i_cuenta_destino").attr("disabled",true);
			}
		}
		if(e == "id_portador") {
			if($("#cheques").html() != "") $("#cheques").find(".id_portador").val($(t).val());
		}
	}
}
userBean.verEntidad_behavior = function(t,e) {
	$(t).addClass("asignar");
	console.log(e)
	switch(e) {
		case 'e_cuenta_origen':
		case 'i_cuenta_origen':
			aux = new Pyrus("cuentaexterna")
			title = "NUEVA <strong>CUENTA</strong>";
			aux.editor("#modal",title);
			$("#modal").find(".modal-body").find(".id_user").closest(".row").remove();
			$("#modal").find(".modal-body").append('<input type="hidden" class="id_user" value="' + user_datos["user_id"] + '">');
		break;
		case 'id_portador':
		case 'id_librador':
		case 'id_librado':
			Atipo_persona = {id_portador:'CLIENTE',id_librador:'LIBRADOR',id_librado:'LIBRADO'};
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
	//aux.editor__footer("#modal",Abtn);
	_cheque.editor__X_footer("#modal .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":null,"data":[{"dismiss":"'modal'"}]},"GUARDAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}})
	$("#modal").modal("show");
	$("#div").addClass("d-none");
}
/*
 *
 */

userBean.ready__ = function() {
    _cheque.editor__X(".__portador",[{"id_portador":"col col-8"}],null,null,null,null,true,false,null,-1);
    _cheque.editor__X_footer(".__portador",{"SIGUIENTE" : {"formato":"col col-8","tipo":"button","clase":"btn-primary btn-block","funcion":[{"onclick":"'userBean.siguiente(this)'"}]}});
    $(".__portador select.select__2").select2({width: 'resolve',placeholder: 'Seleccione',tags: true});

    $(".__portador select").change(function() {
    	if($(this).val() != "-1") {
    		sel = $(".__portador select option:selected").text();
    		$(".__postador_dato span").text(sel);
    		if($("#cheques").html() != "") {
    			$("#cheques").find("input[type='hidden'].id_portador").val($(this).val());
    		}
    	}
    });

    $("body").on("focus",".has-error *",function() {
        $(this).parent().removeClass("has-error");
    });
    $("#div").addClass("d-none");
}



////this["cheque_" + n_cheque] = new Pyrus("cheque");

/*
 * CREAR CHEQUE
 */
userBean.nuevoCheque_behavior = function(t) {
	n_cheque++;
	$("#cheques").append("<div class=\"margin__bottom__10 cheque_" + n_cheque + " card text-white bg-info\">");
	$("#cheques").find("> div.card:last-child").append("<h5 style=\"cursor:pointer\" class=\"card-header card-title\" onclick=\"javascript:userBean.toggle__(this);\">Cheque #" + n_cheque + "</h5>");
	$("#cheques").find("> div.card:last-child").append("<button style=\"top:10px; right:10px;\" class=\"btn btn-danger position-absolute\" onclick=\"javascript:userBean.eliminarCheque(this)\"><i class=\"fas fa-trash-alt\"></i></button>");
	$("#cheques").find("> div.card:last-child").append("<div class=\"card-body\">");

	contenedor = "#cheques > div.card:last-child div.card-body";
	_cheque.editor__X(contenedor,[{"id":""},{"id_portador":""},{"fecha_ingreso":""},{"espejo":""},{"id_user":""}],{"id_user":user_datos["user_id"],"espejo":0,"fecha_ingreso":userBean.fechaYYYYMMDD(),"id_portador":$(".id_portador").val()},null,null,null,false,true);
	_chequeaccion.editor__X(contenedor,[{"id_cheque":""},{"e_cuenta_origen":""},{"e_cuenta_destino":""}],null,null,null,null,false,true);
	//valor = {},desactivado = [],funcion = {},data = null,visible = true,hidden = false,required = [],select_value_default = null
	_cheque.editor__X(contenedor,[{"n_serie":"col col-8"}]);
	_chequeaccion.editor__X(contenedor,[{"id_accion":"col col-8"}]);
	_cheque.editor__X(contenedor,[{"id_moneda":"col col-6"},{"monto":"col col-6"}],null,null,null,null,true);
	_cheque.editor__X(contenedor,[{"id_banco":"col col-6"},{"id_librador":"col col-6"}],null,null,null,null,true,false,null,-1);
	_cheque.editor__X(contenedor,[{"fecha_emision":"col col-6"},{"fecha_cobro":"col col-6"}],null,["fecha_emision","fecha_cobro"],{"fecha_emision":[{"onblur":"'userBean.convertir(this); userBean.comprobarFechas(this)'"}],"fecha_cobro":[{"onblur":"'userBean.convertir(this); userBean.comprobarFechas(this)'"}]},null,true);
	_cheque.editor__X(contenedor,[{"id_librado":"col col-6"},{"imagen":"col col-6"}],null,null,null,null,true,false,["imagen"],-1);
	_chequeaccion.editor__X(contenedor,[{"i_cuenta_origen":"col col-6"},{"i_cuenta_destino":"col col-6"}],{},["i_cuenta_destino"],null,null,true,false,null,-1);
	//this.editor__X = function(contenedor,formato,valor = {},desactivado = [],funcion = {},data = null,visible = true,hidden = false,required = [],select_value_default = null) {
	_cheque.editor__X(contenedor,[{"obs":"col col-12"}],null,null,null,null,true,false,["obs"]);

	Acheques.push({ "c":"cheque_" + n_cheque });
	//------------------

	$(".cheque_" + n_cheque + " .select__2").select2();
	$("#__cheques_cantidad").text("Total de cheques: " + $("#cheques > div.card").length);
	$(".cheque_" + n_cheque + " .monto").priceFormat({
	    prefix: '',
	    centsSeparator: ',',
	    thousandsSeparator: '.'
	});
	$(t).removeAttr("disabled");
}

userBean.cancelarForm = function(t) {
	if($(".asignar").length && $(".asignar").is(":visible")) {
		if($(".asignar").val() == "-1")
			$(".asignar").val("").trigger("change");
		$(".asignar").removeClass("asignar");
		$('#modal').modal("hide");
	} else {
		if($(".asignar_2").length) {
			if($(".asignar_2").val() == "-2")
				$(".asignar_2").val("").trigger("change");
			
			$(t).closest(".modal").find(".modal-content[data-tipo='copia']").prev().removeClass("d-none");
			$(t).closest(".modal").find(".modal-content[data-tipo='copia']").remove();

			$(".asignar_2").removeClass("asignar_2");
		}
	}
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
