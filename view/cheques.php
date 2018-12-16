<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Cheques</li>
  </ol>
</nav>
<section class="">
	<div class="container-fluid d-flex justify-content-center">
		<div class="flex__body col-12">
			<table id="cheques" class="table table-striped table-hover flex__table" style="width: 100%"></table>
		</div>
	</div>
</section>
<!-- BEGIN Edit - New modal -->
<div id="modal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
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
var tabla = null;
var cheque = new Pyrus("cheque");
var chequeaccion = new Pyrus("chequeaccion");
var mov = new Pyrus("movimiento");
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
userBean.listarEntidad = function(col,res,target){
  col.pop();
  	for(var i in res) {
    	res[i].pop();
		l = res[i].length
		if(res[i][l - 1] == 0 || res[i][l - 1] == null) res[i][l - 1] = "NO";
		else res[i][l - 1] = "SI"
	}
  tabla = $("#"+target).DataTable({
      "columns": col,
      "data": res,
      "columnDefs": [
          { className: "text-center", "targets": [ 0,1,2,3,13 ] },
          { className: "text-right", "targets": [ 4,5 ] }
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
      "select": 'single',
      "destroy": true,
      //"sDom": 'BRfrltip',
        "sDom": "<'row padding__bottom__10'"+
                    "<'col col-12 col-sm-6 d-flex justify-content-center __lenght_buttons'l>"+
                    "<'col col-12 col-sm-6'f>r>"+
                "<'table-scrollable padding__bottom__10't>"+
                "<'row'"+
                    "<'col col-12 col-sm-6'i>"+
                    "<'col col-12 col-sm-6 d-flex justify-content-center __paginate'p>>",
      "scrollX":true,
      "order": [[ 0, "desc" ]],
      "bProcessing": true,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
      //"buttons": {
      /*"buttons": [
          {
            extend: 'selected',
            text: '<i class="fas fa-eye"></i>',
            className: 'btn-dark',
            action: function ( e, dt, node, config ) {
              var rows = dt.rows( { selected: true } ).count();
              userBean.show__(tabla);
            }
          },{
            extend: 'selected',
            text: '<i class="fas fa-trash-alt"></i>',
            className: 'btn-danger',
            action: function ( e, dt, node, config ) {
              var rows = dt.rows( { selected: true } ).count();
              userBean.delete__(tabla);
            }
          },
      ],*/
      "language": translate_spanish
    });
    tabla.buttons().container().appendTo( $('.col-sm-6:eq(0)', tabla.table().container() ) );
    $("div.dt-buttons button").removeClass("btn-secondary");
  };
	userBean.ready__ = function() {
		$(".dataTables_filter").addClass("d-flex justify-content-center align-items-center");
    var Abtn = [];
    //Abtn [{"nombre":x,"onclick":x,"class":}]
    Abtn.push({"nombre":"Guardar","onclick":"javascript:Pyrus.guardar(window.id_)","class":"btn btn-success"});
    Abtn.push({"nombre":"Cancelar","onclick":"","class":"btn btn-danger"});
		// PYRUS
    _p = new Pyrus("cheque");
    _p.listador("cheques");
    $("#div").addClass("d-none");

	}
userBean.validar = function() {
	var flag = 1;
	$('*[required="true"]').each(function(){
		if($(this).is(":visible")) {
			if($(this).is(":invalid")) {
				flag = 0;
				$(this).parent().addClass("has-error");
			}
		}
	});
	return flag;
}
userBean.delete__ = function(t) {
	$.MessageBox({
	    buttonDone  : "Si",
	    buttonFail  : "No",
	    message   : "¿Está seguro de eliminar el registro?"
	}).done(function(){
	    var adata = t.rows( { selected: true } );
	    var data = adata.data()[0];
	    var arr = cheque.objeto(data[0]);
	    //var arr_acc = chequeaccion.busqueda__arr("id_cheque",data[0]);
	    arr["activo"] = "0";
	    console.log(arr)
	    cheque.query('guardar_uno_generico',{'entidad' : 'cheque', 'objeto' : arr}, function(m) {}, function(m) {},null,false);
	    /*for(var i in arr_acc) {
	    	arr_acc[i]["activo"] = 0;
	    	chequeaccion.query('guardar_uno_generico',{'entidad' : 'chequeaccion', 'objeto' : arr_acc[i]}, function(m) {}, function(m) {},null,false);
	    }*/

	    
	    t.rows( '.selected' ).remove().draw();
	}).fail(function(){});
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
userBean.concatenar = function(t,tipo) {
  if(tipo == "empresa")
    $(t).closest(".modal").find(".nombre_mostrar").val($(t).val())
  else {
    n = $(t).closest(".modal").find(".nombre").val();
    a = $(t).closest(".modal").find(".apellido").val();
    $(t).closest(".modal").find(".nombre_mostrar").val(n + " " + a);
  }
}
userBean.validarForm = function() {
	if(userBean.validar()) {
    var aux = new Pyrus("persona");
    var aux_d = new Pyrus("domicilio");
    var librador = {};

    id_persona = aux.guardar("nulo","#modal");
    id_domicilio = aux_d.guardar("nulo","#modal");
    
    librador["id"] = "nulo";
    librador["id_persona"] = id_persona;
    librador["id_domicilio"] = id_domicilio;

    aux.query('guardar_uno_generico',{'entidad' : 'librador', 'objeto' : librador}, function(m) {}, function(m) {});

    $("#modal").find("input").val("");
    $("#modal").modal("hide");

    var aux_l = new Pyrus("librador");
    aux_l.listador("libradores")
	}
}
userBean.verEntidad = function(t,e) {
	if($(t).closest(".modal-content").find(".fecha_cobro").next().val() == "") {
		$(t).closest(".modal-content").find(".fecha_cobro").next().val(cheque.convertirYYYYMMDDToDate($(t).closest(".modal-content").find(".fecha_cobro").val()));
		$(t).closest(".modal-content").find(".fecha_emision").next().val(cheque.convertirYYYYMMDDToDate($(t).closest(".modal-content").find(".fecha_emision").val()));
	}
}
//
userBean.show__ = function(t) {
	var adata = t.rows( { selected: true } );
	var data = adata.data()[0];
	var arr_mov = mov.busqueda__arr("id_cheque",data[0]);
	var arr_acc = chequeaccion.busqueda__arr("id_cheque",data[0]);

	arr_mov = arr_mov[arr_mov.length - 1];console.log(arr_mov)
	arr_acc = arr_acc[arr_acc.length - 1];console.log(arr_acc)
	//arr = cheque.busqueda__arr("id",data[0]);
	//console.log(arr)
	var Abtn = [];
	Abtn.push({"nombre":"Cancelar","type":"button","onclick":"-1","class":"btn btn-danger"});
	Abtn.push({"nombre":"Guardar","type":"submit","onclick":"","class":"btn btn-success"});
    //aux = new Pyrus("persona");
    $("#modal").find(".modal-body").html("");
    $("#modal").find(".modal-title").text("Cheque");

    cheque_html = "";
	cheque_html += '<div class="row padding__bottom__10">' + 
					'<input type="hidden" class="id">' +
					'<input type="hidden" class="id_portador" value="">' +
					'<input type="hidden" class="fecha_ingreso" value="">' +
					'<input type="hidden" class="espejo" value="">' +
					'<input type="hidden" class="id_user" value="' + user_datos["user_id"] + '">' +
					'<div class="col col-12 col-md-12">' +
						'<label>N° SERIE</label>' +
						'<input required="true" name="n_serie" type="number" min="0" class="form-control val_integer n_serie">' +
					'</div></div>';
	/*cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-12 __accion">' +
							'<label>ACCIÓN</label>' +
						'</div>' +
					'</div>';*/
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
							'<input onblur="userBean.comprobarFechas(this);" required="true" type="date" class="form-control val_fechaCorta" disabled="true">' +
							'<input type="hidden" class="fecha_emision">' +
						'</div>' +
						'<div class="col col-12 col-md-6">' +
							'<label>FECHA DE COBRO</label>' +
							'<input onblur="userBean.comprobarFechas(this);" required="true" type="date" class="form-control val_fechaCorta" disabled="true">' +
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
	if(arr_mov["tipo_movimiento"] == 2) {
		cheque_html += '<input type="hidden" class="e__cuenta_destino" value="">' +
						'<input type="hidden" class="e__cuenta_origen" value="">' +
						'<input type="hidden" class="id_cheque" value="">';
		cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-6 i_cuenta_origen">' +
							'<label>CUENTA ORIGEN</label>' +
						'</div>' +
						'<div class="col col-12 col-md-6 i_cuenta_destino">' +
							'<label>CUENTA DESTINO</label>' +
						'</div>' +
					'</div>';
	} else if(arr_mov["tipo_movimiento"] == 4) {
		cheque_html += '<input type="hidden" class="i__cuenta_destino" value="">' +
						'<input type="hidden" class="i__cuenta_origen" value="">' +
						'<input type="hidden" class="id_cheque" value="">';
		cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-6 e_cuenta_origen">' +
							'<label>CUENTA ORIGEN</label>' +
						'</div>' +
						'<div class="col col-12 col-md-6 e_cuenta_destino">' +
							'<label>CUENTA DESTINO</label>' +
						'</div>' +
					'</div>';
	}
	//}
	cheque_html += '<div class="row padding__bottom__10">' +
						'<div class="col col-12 col-md-12">' +
							'<label>OBSERVACIONES</label>' +
							'<textarea class="form-control obs"></textarea>' +
						'</div>' +
					'</div>';
	$("#modal").find(".modal-body").append(cheque_html);

	_moneda = new Pyrus("moneda");
	_accion = new Pyrus("accion");
	_banco = new Pyrus("banco");
	__lib = new Pyrus("librador");
	_librado = new Pyrus("persona");
	
	//(t,nombre,identificador,tam,dato,estado,disabled,function__)
	_accion.selectDatos(".__accion","acción","id_accion","","[designacion]","normal");
	__lib.selectDatos(".__librador","librador","id_librador","","[persona/id_persona/nombre_mostrar]","normal");

	_moneda.selectDatos(".__moneda","moneda","id_moneda","","[designacion]","normal");
	_banco.selectDatos(".__banco","banco","id_banco","","[nombre] - [sucursal]","normal");
	
	_librado.selectDatos(".__librado","librado","id_librado","","[nombre_mostrar]","normal");
    
	if(arr_mov["tipo_movimiento"] == 2) {
		_cuentaorigen = new Pyrus("cuentaexterna");
		_cuentadestino = new Pyrus("cuenta");
		_cuentaorigen.selectDatos(".i_cuenta_origen","cuenta origen","i_cuenta_origen","","[n_cuenta]","normal");
		_cuentadestino.selectDatos(".i_cuenta_destino","cuenta destino","i_cuenta_destino","","[n_cuenta]","normal","");
		chequeaccion.cargarAEditar(arr_acc["id"]);
	} else if(arr_mov["tipo_movimiento"] == 4) {
		_cuentaorigen = new Pyrus("cuenta");
		_cuentadestino = new Pyrus("cuentaexterna");
		_cuentaorigen.selectDatos(".e_cuenta_origen","cuenta origen","i_cuenta_origen","","[n_cuenta]","normal");
		_cuentadestino.selectDatos(".e_cuenta_destino","cuenta destino","i_cuenta_destino","","[n_cuenta]","normal","");
		chequeaccion.cargarAEditar(arr_acc["id"]);
	}

	cheque.cargarAEditar(data[0]);

	if(arr_mov["tipo_movimiento"] == 4)//EGRESO
		$("#modal").find("input,select,textarea").attr("disabled",true);

	$(".select__2").select2();
    $("#modal").modal("show");
}

$(document).ready(userBean.ready__());
</script>
