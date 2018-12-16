<script src="http://200.58.123.122/js/pyrusBean_2.js"></script>

<div id="div" class="position-absolute d-none"></div>
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
					<h5 class="card-header card-title text-center">Seleccione Cliente</h5>
					<div class="card-body">
					    <div class="__portador"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center d-none __datos_cheque">
			<div class="col col-12 col-sm-5">
				<div class="card datos__portador margin__bottom__10">
					<h5 class="card-header card-title text-center">Datos cliente</h5>
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
	/*
	 * CUENTA ORIGEN -> PERMITIR CREAR - externo
	 * CUENTA DESTINO -> CUENTAS DEL SISTEMA (tabla cuenta)
	 */
init = function(){
	_cheque = new PyrusX("cheque");
	_p = new PyrusX("persona");
	_m = new PyrusX("movimiento");
	n_cheque = 0;
	aux = null, aux2 = null, aux_a = null, aux2_a = null;
	validar_fechas = 1;
	Acheques = [];
	Acheques_accion = [];
	userBean.ready__();
	console.log('ya pase');
};

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
        		var _s = new PyrusX("sucursal");
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
				var _s = new PyrusX("sucursal");
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
		userBean.notificacion("no agrego cheques","error");
	else {
		// cargo todas las imagenes
		window.arr_img = $('input[type="file"]');
		for(z = 0; z < arr_img.length; ++z){
			fa = window.arr_img[z].files[0];
			getBase64(fa,z,function(pos,f){
				// lo pongo como imagen en vista previa
				// lo asigno a una variable
				window.arr_img[pos].imagen_base64 = f;
			});
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
				if(userBean.validar("#cheques")) {
					$.MessageBox({
					    buttonDone  : {
					        yes         : "Si",
					    },
					    buttonFail  : {
					        no          : "No"
					    },
					    message   : "Finalizar <strong>operación</strong>"
					}).done(function(data, button){
						$("#div").removeClass("d-none");
						$(".val_fechaCorta").each(function() {
							_cheque.convertirDateToYYYYMMDD($(this));
						});
						var did_movimiento = _m.busqueda__DID(); // obtiene el ultimo DID + 1 - elemento unico de movimientos
						var fecha = userBean.fechaYYYYMMDD();
						$(".fecha_ingreso").val(fecha);
						for(var i in Acheques) {
							var c = Acheques[i]["c"];
							var o = Acheques[i]["o"].guardar("nulo","." + c);
							
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

							n_serie = $("." + c + " .n_serie").val();
							switch(accion_id) {
								case "2":
									fecha_cobro = $("."+ c + " .fecha_cobro").prev().val();
									fecha_cobro = fecha_cobro.split("-");
									cuenta_destion = $("."+ c + " .i_cuenta_destino option[value='" + $("."+ c + " .i_cuenta_destino").val() + "']").text();
									detalle = "Cheque #" + n_serie + " depositar el día " + fecha_cobro[2] + "/" + fecha_cobro[1] + "/" + fecha_cobro[0] + " en Cuenta #" + cuenta_destion;
									break;
								case "3":
									detalle = "Cheque #" + n_serie + " guardado";
									break;
								case "4":
									obs = $("."+ c + " .obs").prev().val();
									detalle = "Cheque #" + n_serie + " para pagar";
									if(obs != "") detalle += ": " + obs;
								case "5":
									fecha_cobro = $("."+ c + " .fecha_cobro").prev().val();
									fecha_cobro = fecha_cobro.split("-");
									detalle = "Cheque #" + n_serie + " para negociar c/ banco - antes del día " + fecha_cobro[2] + "/" + fecha_cobro[1] + "/" + fecha_cobro[0];
							}

							movimiento["detalle"] = detalle;
							console.log(movimiento)
							
							_m.query('guardar_uno_generico',{'entidad' : 'movimiento', 'objeto' : movimiento}, function(m) {}, function(m) {},null,false);

							userBean.notificacion("Ingreso generado","sucess");
						}

						$("#cheques").html("");
						$(".__portador select").val("").trigger("change");
						$("#__cheques_cantidad").text("Total de cheques: 0");
						$(".__datos_cheque.d-none").removeClass("d-none").next().addClass("d-none");
						Acheques = [];
						n_cheque = 0;
						$("#div").addClass("d-none");
					}).fail(function(data, button){});
				} else userBean.notificacion("faltan datos","error");
			} else userBean.notificacion("<strong>Cliente</strong> y <strong>librador</strong> no pueden ser el mismo","error");
		} else userBean.notificacion("verifique <strong>fechas</strong> de algunos cheques","error");
	}
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
userBean.inputSelector__ = function(identificador,fuente_opciones,formato,nombre,estado,tam,label,disabled,function__){
	ff = "";
    for(var o in function__) {
        if(ff != "") ff + " ";
        ff = o + "='" + function__[o] + "'";
    }

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
    _cheque.query("listar_generico",{'entidad':fuente_opciones },
    function(m){
        for(var x in m){
            selector += "<option value='" + m[x]['id'] + "'>" +
            _cheque.imprimirConFormato(formato,m[x])
            + "</option>";
    	}
    },null,false);
    selector += "</select>" + 
                "</div>";
    return window.selector;
};
userBean.inputSelector = function(identificador,fuente_opciones,formato,nombre,estado){
 	selector = "<div class='form-group row'>" +
             "<label class='col-4 col-form-label'>" + nombre.toUpperCase() + "</label>";
    selector += "<div class='col-8'>";
    selector += "<select required='true' name='" + identificador + "' style='width:100%' data-placeholder='Seleccione " + nombre + "' data-allow-clear='true' class='form-control select__2 val_selector " + identificador + "' onchange=\"userBean.verEntidad(this,'"+ identificador +"')\">";
    selector += "<option value=''></option>";
    if(estado == "agregar") {
	    selector += "<optgroup label=\"Nuevo\">";
	    selector += "<option value='-2'>Registro</option>";
	    selector += "</optgroup>";
	}
    selector += "<optgroup label=\"Datos\">";
    _cheque.query("listar_generico",{'entidad':fuente_opciones },
    function(m){
        for(var x in m){
            selector += "<option value='" + m[x]['id'] + "'>" +
            _cheque.imprimirConFormato(formato,m[x])
            + "</option>";
    	}
    },null,false);
    selector += "</select></div>";
    return window.selector;
};
userBean.asignar = function(btn) {
	$(btn).closest(".asignar").addClass("activo");
	$("#modal").modal("show");
}
userBean.verEntidad = function(t,e) {
	var Abtn = [];
	Abtn.push({"nombre":"Cancelar","type":"button","onclick":"-1","class":"btn btn-danger"});
	Abtn.push({"nombre":"Guardar","type":"submit","onclick":"","class":"btn btn-success"});
	if($(t).val() == "-1") {
		$(t).addClass("asignar");
		console.log(e)
		switch(e) {
			case 'id_tipo_pago':
				aux = new PyrusX("tipopago");
				title = "Nuevo tipo de pago";
				aux.editor("#modal",title);
			break;
			case 'e_cuenta_origen':
			case 'i_cuenta_origen':
				aux = new PyrusX("cuentaexterna")
				title = "Nueva cuenta origen";
				aux.editor("#modal",title);
			break;
			case 'id_cuenta':
			case 'i_cuenta_destino':
				aux = new PyrusX("cuenta")
				title = "Nueva cuenta destino";
				aux.editor("#modal",title);
			break;
			case 'id_portador':
			case 'id_librador':
			case 'id_librado':
				aux = new PyrusX("persona");
				$("#modal").find(".modal-body").html("");
				$("#modal").find(".modal-title").text("Nuevo");
				$("#modal").find(".modal-body").append('<input type="hidden" class="id" name="id">');
				$("#modal").find(".modal-body").append('<input type="hidden" class="nombre_mostrar" name="nombre_mostrar">');
				$("#modal").find(".modal-body").append('<div class="margin__bottom__10"><button class="mx-auto d-block btn btn-primary" type="button" title="Cambiar a Persona jurídica" onclick="javascript:userBean.cambio(this);">a <strong>Persona jurídica</strong> <i class="fas fa-exchange-alt"></i></button></div>');

				$("#modal").find(".modal-body").append('<div data-dato="normal" class="margin__bottom__10 form-group row"><label class="col-4 col-form-label">NOMBRE</label><div class="col-8"><input required="true" name="nombre" type="text" class="form-control val_string nombre" onblur="javascript:userBean.concatenar(this,\'persona\')"></div></div>');
				$("#modal").find(".modal-body").append('<div data-dato="normal" class="margin__bottom__10 form-group row"><label class="col-4 col-form-label">APELLIDO</label><div class="col-8"><input required="true" name="apellido" type="text" class="form-control val_string apellido" onblur="javascript:userBean.concatenar(this,\'persona\')"></div></div>');
				$("#modal").find(".modal-body").append('<div data-dato="normal" class="margin__bottom__10 form-group row"><label class="col-4 col-form-label">DNI</label><div class="col-8"><input required="true" name="dni" type="number" min="0" class="form-control val_integer dni"></div></div>');

				$("#modal").find(".modal-body").append('<div data-dato="juridica" class="form-group row d-none"><label class="col-4 col-form-label">RAZÓN SOCIAL</label><div class="col-8"><input required="true" name="razon_social" type="text" class="form-control val_string razon_social" onblur="javascript:userBean.concatenar(this,\'empresa\')"></div></div>');
				$("#modal").find(".modal-body").append('<div data-dato="juridica" class="form-group row d-none"><label class="col-4 col-form-label">CUIT</label><div class="col-8"><input required="true" name="cuit" type="number" min="0" class="form-control val_integer cuit"></div></div>');

				aux.editor__footer("#modal",Abtn);
			break;
			case 'id_banco':
				aux = new PyrusX("banco");
				aux_a = new PyrusX("domicilio");
				$("#modal").find(".modal-body").html("");
				$("#modal").find(".modal-title").text("Nuevo banco");

		        $("#modal").find(".modal-body").append('<input type="hidden" class="id">');
		        $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">NOMBRE</label><div class="col-8"><input required="true" name="nombre" type="text" class="form-control val_string nombre"></div></div>');
		        $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">SUCURSAL</label><div class="col-8"><input required="true" name="sucursal" type="text" class="form-control val_string sucursal"></div></div>');
		        $("#modal").find(".modal-body").append('<hr>');
		        $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">CALLE</label><div class="col-8"><input required="true" name="calle" type="text" class="form-control val_string calle"></div></div>');
		        $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">ALTURA</label><div class="col-8"><input required="true" name="altura" type="number" class="form-control val_integer altura"></div></div>');
		        $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">LOCALIDAD</label><div class="col-8"><input required="true" name="localidad" type="text" class="form-control val_string localidad"></div></div>');
		        $("#modal").find(".modal-body").append('<hr>');
		        $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">TELÉFONO</label><div class="col-8"><input required="true" name="telefono" type="text" class="form-control val_string telefono"></div></div>');
		        aux.editor__footer("#modal",Abtn);
			break;
		}
		aux.editor__footer("#modal",Abtn);
		$("#modal").modal("show");
	}
	if($(t).val() == "-2") {
		$(t).addClass("asignar_2");
		$(t).closest(".modal-content").data("tipo","original");
		$(t).closest(".modal-content").addClass("d-none");
		$(t).closest(".modal-content").parent().append("<div class=\"modal-content\" data-tipo='copia'>");
		console.log(e)
		
		switch(e) {
			case 'id_banco':
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<div class="modal-header"><h5 class="modal-title">Nuevo banco</h5></div>');
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<form method="POST" id="modal-form2" onsubmit="event.preventDefault(); userBean.validarForm2();" novalidate="">');
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-body">');
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-footer">');

				aux2 = new PyrusX("banco");
				aux2_a = new PyrusX("domicilio");
				Abtn2 = [];
				Abtn2.push({"nombre":"Cancelar","type":"button","onclick":"userBean.cancelarForm(this)","class":"btn btn-danger"});
				Abtn2.push({"nombre":"Guardar","type":"submit","onclick":"","class":"btn btn-success"});

				$("#modal .modal-content[data-tipo='copia'] form .modal-body").append('<input type="hidden" class="id">');
		        $("#modal .modal-content[data-tipo='copia'] form .modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">NOMBRE</label><div class="col-8"><input required="true" name="nombre" type="text" class="form-control val_string nombre"></div></div>');
		        $("#modal .modal-content[data-tipo='copia'] form .modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">SUCURSAL</label><div class="col-8"><input required="true" name="sucursal" type="text" class="form-control val_string sucursal"></div></div>');
		        $("#modal .modal-content[data-tipo='copia'] form .modal-body").append('<hr>');
		        $("#modal .modal-content[data-tipo='copia'] form .modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">CALLE</label><div class="col-8"><input required="true" name="calle" type="text" class="form-control val_string calle"></div></div>');
		        $("#modal .modal-content[data-tipo='copia'] form .modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">ALTURA</label><div class="col-8"><input required="true" name="altura" type="number" class="form-control val_integer altura"></div></div>');
		        $("#modal .modal-content[data-tipo='copia'] form .modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">LOCALIDAD</label><div class="col-8"><input required="true" name="localidad" type="text" class="form-control val_string localidad"></div></div>');
		        $("#modal .modal-content[data-tipo='copia'] form .modal-body").append('<hr>');
		        $("#modal .modal-content[data-tipo='copia'] form .modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">TELÉFONO</label><div class="col-8"><input required="true" name="telefono" type="text" class="form-control val_string telefono"></div></div>');


				aux2.editor__footer("#modal .modal-content[data-tipo='copia']",Abtn2);
			break;
			case 'id_moneda':
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<div class="modal-header"><h5 class="modal-title">Nueva tipo de moneda</h5></div>');
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<form method="POST" id="modal-form2" onsubmit="event.preventDefault(); userBean.validarForm2();" novalidate="">');
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-body">');
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-footer">');

				aux2 = new PyrusX("moneda");
				Abtn2 = [];
				Abtn2.push({"nombre":"Cancelar","type":"button","onclick":"userBean.cancelarForm(this)","class":"btn btn-danger"});
				Abtn2.push({"nombre":"Guardar","type":"submit","onclick":"","class":"btn btn-success"});
        		aux2.editor__("#modal .modal-content[data-tipo='copia'] form .modal-body");
				aux2.editor__footer("#modal .modal-content[data-tipo='copia']",Abtn2);
			break;
			case 'id_titular':
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<div class="modal-header"><h5 class="modal-title">Nuevo titular</h5></div>');
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia']").append('<form method="POST" id="modal-form2" onsubmit="event.preventDefault(); userBean.validarForm2();" novalidate="">');
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-body">');
				$(t).closest(".modal-content").parent().find(".modal-content[data-tipo='copia'] form").append('<div class="modal-footer">');

				aux2 = new PyrusX("persona");
				Abtn2 = [];
				Abtn2.push({"nombre":"Cancelar","type":"button","onclick":"userBean.cancelarForm(this)","class":"btn btn-danger"});
				Abtn2.push({"nombre":"Guardar","type":"submit","onclick":"","class":"btn btn-success"});
        		aux2.editor__("#modal .modal-content[data-tipo='copia'] form .modal-body");
				aux2.editor__footer("#modal .modal-content[data-tipo='copia']",Abtn2);
			break;
		}
	}

	console.log(e)
	if(e == "id_accion") {
		/*
		 * 2) A depositar: La fecha no puede superar los 30 días / Necesario: Cuenta Destino
		 * 3) Guardar: La fecha no puede superar los 365 / Desactivar: Cuenta Destino
		 * 4) Pasar: La fecha entre 30 y 365 / Desactivar: cuenta Destino
		 * 5) Negociar c/ Banco: La fecha no puede superar los 365 / Desactivar: Cuenta Destino
		 */
		switch($(t).val()) {
			case "2":
				$(t).closest(".card-body").find(".i_cuenta_destino").removeAttr("disabled");
			break;
			default:
				$(t).closest(".card-body").find(".i_cuenta_destino").attr("disabled",true);
		}
	}
}
userBean.concatenar = function(t,tipo) {
	if(tipo == "empresa")
		$(t).closest(".modal").find(".nombre_mostrar").val($(t).val())
	else {
		n = $(t).closest(".modal").find(".nombre").val();
		a = $(t).closest(".modal").find(".apellido").val();
		$(t).closest(".modal").find(".nombre_mostrar").val(n + " " + a);
	}
}
userBean.cambio = function(t) {
	if($(t).closest(".modal-body").find("*[data-dato='normal']").is(":visible")) {
		$(t).closest(".modal-body").find("*[data-dato='normal']").addClass("d-none");
		$(t).closest(".modal-body").find("*[data-dato='normal'] input").val("");
		$(t).closest(".modal-body").find("*[data-dato='juridica']").removeClass("d-none");
		$(t).find("strong").text("Persona");
		$(t).attr("title","Persona");
	} else {
		$(t).closest(".modal-body").find("*[data-dato='juridica']").addClass("d-none");
		$(t).closest(".modal-body").find("*[data-dato='juridica'] input").val("");
		$(t).closest(".modal-body").find("*[data-dato='normal']").removeClass("d-none");
		$(t).find("strong").text("Persona jurídica");
		$(t).attr("title","Persona jurídica");
	}
}
userBean.ready__ = function() {
	var Abtn = [];
	var format = [];
    Abtn.push({"nombre":"SIGUIENTE","onclick":"userBean.siguiente(this)","class":"d-block mx-auto btn btn-primary","type":"button"});
    format.push([{"especificacion":"id_portador","tam":"col col-12"}]);
    _cheque.editor__especial(".__portador",format,Abtn,false);
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
}

userBean.eliminarCheque = function(cheque) {
	i = $(cheque).closest(".margin__bottom__10.card").index();
	$(cheque).closest(".margin__bottom__10.card").remove();
	Acheques.splice(i, 1);
	$("#__cheques_cantidad").text("Total de cheques: " + $("#cheques > div.card").length);
}
userBean.toggle__ = function(t) {
	$(t).closest(".card").find(".card-body").toggle("fast");
}

userBean.nuevoCheque = function(t){
	userBean.notificacion("Cargando... espere","warning",false);
	setTimeout(function() { userBean.nuevoCheque_behavior(t); },1000);
};

userBean.nuevoCheque_behavior = function(t) {
	//Acheques
	$("#div").removeClass("d-none");
	n_cheque++;

	this["cheque_" + n_cheque] = new PyrusX("cheque");
	$("#cheques").append("<div class=\"margin__bottom__10 cheque_" + n_cheque + " card text-white bg-info\">");
	$("#cheques").find("> div.card:last-child").append("<h5 style=\"cursor:pointer\" class=\"card-header card-title\" onclick=\"javascript:userBean.toggle__(this);\">Cheque #" + n_cheque + "</h5>");
	$("#cheques").find("> div.card:last-child").append("<button style=\"top:10px; right:10px;\" class=\"btn btn-danger position-absolute\" onclick=\"javascript:userBean.eliminarCheque(this)\"><i class=\"fas fa-trash-alt\"></i></button>");
	$("#cheques").find("> div.card:last-child").append("<div class=\"card-body\">");

	cheque_html = "";
	cheque_html += '<div class="row padding__bottom__10">' + 
					'<input type="hidden" class="id">' +
					'<input type="hidden" class="id_portador" value="">' +
					'<input type="hidden" class="fecha_ingreso" value="">' +
					'<div class="col col-12 col-md-12">' +
						'<label>N° SERIE</label>' +
						'<input required="true" name="n_serie" type="number" min="0" class="form-control val_integer n_serie">' +
					'</div></div>';
	cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-12 __accion">' +
							'<label>ACCIÓN</label>' +
						'</div>' +
					'</div>';
	cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-6 __moneda">' +
							'<label>MONEDA</label>' +
						'</div>' +
						'<div class="col col-12 col-md-6">' +
							'<label>MONTO</label>' +
							'<input data-tipo="moneda" onkeyup="javascript:userBean.inputKeyup(event);" onfocusout="javascript:userBean.inputFocusOut(event);" required="true" name="monto" type="text" class="form-control val_float monto text-right">' +
						'</div>' +
					'</div>';
	cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-6 __banco">' +
							'<label>BANCO</label>' +
						'</div>' +
						'<div class="col col-12 col-md-6 __librador">' +
							'<label>LIBRADOR</label>' +
						'</div>' +
					'</div>';
	cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-6">' +
							'<label>FECHA DE EMISIÓN</label>' +
							'<input onblur="userBean.comprobarFechas(this);" required="true" type="date" class="form-control val_fechaCorta">' +
							'<input type="hidden" class="fecha_emision">' +
						'</div>' +
						'<div class="col col-12 col-md-6">' +
							'<label>FECHA DE COBRO</label>' +
							'<input onblur="userBean.comprobarFechas(this);" required="true" type="date" class="form-control val_fechaCorta">' +
							'<input type="hidden" class="fecha_cobro">' +
						'</div>' +
					'</div>';
	cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-6 __librado">' +
							'<label>LIBRADO</label>' +
						'</div>' +
						'<div class="col col-12 col-md-6">' +
							'<label>IMAGEN</label>' +
							'<input name="imagen" type="file" class="form-control val_imagen imagen">' +
						'</div>' +
					'</div>';
	cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-6 __cuenta_origen">' +
							'<label>CUENTA ORIGEN</label>' +
						'</div>' +
						'<div class="col col-12 col-md-6 __cuenta_destino">' +
							'<label>CUENTA DESTINO</label>' +
						'</div>' +
					'</div>';
	cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-12">' +
							'<label>OBSERVACIONES</label>' +
							'<textarea class="form-control obs"></textarea>' +
						'</div>' +
					'</div>';
	$("#cheques > div.card:last-child div.card-body").append(cheque_html);

	_moneda = new PyrusX("moneda");
	_accion = new PyrusX("accion");
	_banco = new PyrusX("banco");
	_librador = new PyrusX("persona");
	_librado = new PyrusX("persona");
	_cuentaorigen = new PyrusX("cuentaexterna");
	_cuentadestino = new PyrusX("cuenta");

	//(t,nombre,identificador,tam,dato,estado,disabled,function__)

	_moneda.selectDatos(".cheque_" + n_cheque + " .__moneda","moneda","id_moneda","","[designacion]","normal");
	_accion.selectDatos(".cheque_" + n_cheque + " .__accion","acción","id_accion","","[designacion]","normal");
	_banco.selectDatos(".cheque_" + n_cheque + " .__banco","banco","id_banco","","[nombre] - [sucursal]");
	_librador.selectDatos(".cheque_" + n_cheque + " .__librador","librador","id_librador","","[nombre_mostrar]");
	_librado.selectDatos(".cheque_" + n_cheque + " .__librado","librado","id_librado","","[nombre_mostrar]");
	_cuentaorigen.selectDatos(".cheque_" + n_cheque + " .__cuenta_origen","cuenta origen","i_cuenta_origen","","[n_cuenta]");
	_cuentadestino.selectDatos(".cheque_" + n_cheque + " .__cuenta_destino","cuenta destino","i_cuenta_destino","","[n_cuenta]","agregar","disabled='true'");
	Acheques.push({"o":this["cheque_" + n_cheque],"c":"cheque_" + n_cheque});
	//------------------

	$(".select__2").select2({width: 'resolve',placeholder: 'Seleccione',tags: true});

	$("#__cheques_cantidad").text("Total de cheques: " + $("#cheques > div.card").length);
	$("#div").addClass("d-none");
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
userBean.comprobarFechas = function(t) {
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
		if(diff > 365) {
			$(t).parent().addClass("has-error");
			$(t).closest(".card-body").find("input[type='date'] + input[type='hidden']:not(." + this__class + ")").parent().addClass("has-error");
			userBean.notificacion("<strong>Fecha de cobro</strong> incorrecta, supera los 365 días","error");
			$(t).closest(".card").data("fecha","no");
		} else {
			$(t).closest(".card").data("fecha","ok");
			$(t).parent().removeClass("has-error");
			$(t).closest(".card-body").find("input[type='date'] + input[type='hidden']:not(." + this__class + ")").parent().removeClass("has-error");
		}
	}
}

$(document).ready(
function(){
	setTimeout(function(){
		init();
		// userBean.ready__();
		},0);
});
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
