<style type="text/css">
	.select2-container--default .select2-selection--single .select2-selection__clear { margin-right: 5px !important; }
	table.dataTable thead th,
	table th,
	table td { vertical-align: middle !important; }
	.shown + .no-padding { background-color: #fff !important; }
	.shown + .no-padding table { margin-bottom: 0 !important; }
	td.details-control { text-align: center; color: #fff; background-color: #2196f3; cursor: pointer; }
</style>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
		<li class="breadcrumb-item active">Gestión de cheques</li>
  </ol>
</nav>
<section class="">
	<div class="container-fluid d-flex justify-content-center">
		<div class="flex__body col col-12">
			<div class="row padding__bottom__10">
				<div class="col col-12 col-md-6">
					<a class="btn btn-lg btn-block btn-danger" href="egreso/">EGRESO DE CHEQUE</a>
				</div>
				<div class="col col-12 col-md-6">
					<a class="btn btn-lg btn-block btn-success" href="ingreso/">INGRESO DE CHEQUE</a>
				</div>
			</div>
			<div class="row padding__bottom__10">
				<div class="col col-12">
					<div class="background__ffffff box__shadow__01 p-2 border__radius__4" style="min-height: 200px;">
						<h3 class="text-center">Últimos movimientos</h3>
						<table id="movimientos" class="table table-striped table-hover flex__table" style="width: 100%"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- BEGIN Edit - New modal -->
<div id="modal" class="modal bd-example-modal-lg" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="modal-form" onsubmit="event.preventDefault(); userBean.validarForm();" novalidate>
            	<div class="modal-body"></div>
            </form>
        </div>
    </div>
</div>
<!-- END Edit - New modal -->

<script>
var tabla;
estados = {};
mov = new Pyrus("movimiento");
cheque = new Pyrus("cheque");
m = new Pyrus("movimientoestado");
m_arr = m.busqueda__arr();
for(var i in m_arr) estados[m_arr[i]["designacion"]] = m_arr[i]["id"];

function sleep(milliseconds) {
  var start = new Date().getTime();
  userBean.notificacion("Cargando... espere","warning",false);
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

function format_(data){
	userBean.notificacion("Cargando... espere","warning",false);
	aux = setTimeout(function(){ format_behavior(data); },1000);
	console.log(aux)
	return aux;
}

function format(data) {
	sleep(1000);
	console.log(data)
	html = "";
	arr_mov_basico = mov.busqueda__arr("did",data[0]);//Objeto sin relaciones
	arr_mov = mov.busqueda__arrr("did",data[0]);//Objeto compuesto
	arr_mov_cheque = mov.busqueda__arr("id_cheque",arr_mov_basico[0]["id_cheque"]);
	console.log(arr_mov)

	arr_bloque = { 2:"A: Cooperativa",4:"B: Cliente"};

	/*if(arr_mov_basico[0]["tipo_movimiento"] != 6) {
		html += '<div class="row padding__bottom__10">';
			html += '<div class="col col-6" id="estado"><div class="row"></div></div>';
			html += '<div class="col col-6">';
				html += '<button';
				//if(arr_mov_cheque[arr_mov_cheque.length - 1]["id"] != id) html += ' disabled="true" ';
				html += 'onclick="userBean.estado(' + arr_mov["id"] + ')" class="btn btn-block btn-success">Guardar</button>';
			html += '</div>';
		html += '</div>';
	}*/

	html += '<div class="jumbotron jumbotron-fluid border border-succes padding__bottom__10" style="padding-top:0; padding-bottom:0;">';
	html += '<div class="p-2">';
	html += '<h2>Movimiento #' + arr_mov[0]["did"] + ' - ' + arr_mov[0]["tipo_movimiento"] + '</h2>';
	html += '<p class="lead" style="margin:0">' + arr_mov[0]["detalle"] + '</p>';
	html += '</div>';
	html += '</div>';

	if(arr_mov[0]["id_destinatario"] != "")
		html += '<h3>Destinatario: ' +arr_mov[0]["id_destinatario"]+ ' / ' + arr_mov[0]["estado"] + '</h3>';
	else if(arr_mov[0]["id_portador"] != "")
		html += '<h3>Cliente: ' +arr_mov[0]["id_portador"]+ '</h3>';

	html += '<table class="table">';
		html += '<thead class="thead-light">';
			html += '<th class="text-center">N° Serie</th>';
			html += '<th class="text-center">Monto</th>';
			html += '<th class="text-center">Fecha de Emisión</th>';
			html += '<th class="text-center">Fecha de Cobro</th>';
			if(arr_mov_basico[0]["tipo_movimiento"] == 6) html += '<th class="text-center">Bloque</th>';
		html += '</thead>';
		html += '<tbody>';
			for(var i in arr_mov) {
				arr_cheque = cheque.busqueda__arrr("n_serie",arr_mov[i]["id_cheque"])[0];
				html += '<tr>';
					html += '<td class="text-left">' + arr_cheque["n_serie"] + '</td>';
					html += '<td class="text-right">' + arr_cheque["monto"] + '</td>';
					html += '<td class="text-center">' + arr_cheque["fecha_emision"] + '</td>';
					html += '<td class="text-center">' + arr_cheque["fecha_cobro"] + '</td>';
					if(arr_mov_basico[0]["tipo_movimiento"] == 6) {
						aux = new Pyrus("espejo");
						bloque = aux.busqueda__XX("bloque",{"id_cheque": arr_cheque["id"],"did_movimiento" : arr_mov[i]["did"]});
						html += '<td class="text-center">' + arr_bloque[bloque] + '</td>';
					}
				html += '</tr>';
			}
		html += '</tbody>';
	html += '</table>';

    return {"html":'<div class="slider" name>' + html + '</div>',"movimiento":arr_mov_basico[0]["tipo_movimiento"],"estado":estados[arr_mov[0]["estado"]]};
} 
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
      "select": {
					"rows": { _: "%d filas seleccionadas", 0: "Click en una fila para seleccionar", 1: "1 fila seleccionada" }
				},
};
userBean.listarEntidad = function(col,res,target){
	for(var i in res) {
		if(res[i][2] == "") res[i][2] = "-";
		if(res[i][3] == "") res[i][3] = "-";

		//res[i] = ['<i class="far fa-eye"></i>'].concat(res[i]);
	}
	/*console.log(res)
	col = 	[{
	                "className":      'details-control',
	                "orderable":      false,
	                "data":           null,
	                "defaultContent": ''
	            }].concat(col);*/
	
  	tabla = $("#"+target).DataTable({ "columns": col, "data": res, 
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
		"columnDefs": [	{ className: "text-center", "targets": [ 1,4 ] },
						{ className: "details-control", "targets": [ 0 ] },
		],
		"order": [[ 0, "desc" ]],
		"bSort": true,
      	"destroy": true,
      	"sDom": "<'row padding__bottom__10'"+
                    "<'col col-12 col-sm-6 d-flex justify-content-center __lenght_buttons'l>"+
                    "<'col col-12 col-sm-6'f>r>"+
                "<'table-scrollable padding__bottom__10't>"+
                "<'row'"+
                    "<'col col-12 col-sm-6'i>"+
                    "<'col col-12 col-sm-6 d-flex justify-content-center __paginate'p>>",
      	"scrollX":true,
      	"bProcessing": true,
		//"select": { style: 'single' },
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
		/*"buttons": [{	extend: 'selected',
						text: '<i class="far fa-eye"></i>',
						className: 'btn-warning',
						action: function ( e, dt, node, config ) {
						  	var rows = dt.rows( { selected: true } ).count();
						  	var col_arr = $(".selected").find("td:first-child").text();
						  	userBean.ver(col_arr);
						}
					},
      	],*/
      	"language": translate_spanish
    });
    tabla.buttons().container().appendTo( $('.col-sm-6:eq(0)', tabla.table().container() ) );
    $("div.dt-buttons button").removeClass("btn-secondary");
};
userBean.ver = function(did) {
	/*
	 * TIPO
	 * 	2 - Ingreso de Cheque
	 * 	4 - Egreso de Cheque
	 * 	6 - Espejo
	 */
	title = "";
	html = ""; select = "";
	mov = new Pyrus("movimiento");
	cheque = new Pyrus("cheque");
	arr_mov_basico = mov.busqueda__arr("did",did);//Objeto sin relaciones
	arr_mov = mov.busqueda__arrr("did",did);//Objeto compuesto
	arr_mov_cheque = mov.busqueda__arr("id_cheque",arr_mov_basico[0]["id_cheque"]);
	console.log(arr_mov_cheque)

	estados = {};
	m = new Pyrus("movimientoestado");
    m_arr = m.busqueda__arr();
    for(var i in m_arr) estados[m_arr[i]["designacion"]] = m_arr[i]["id"];

	arr_bloque = { 2:"A: Cooperativa",4:"B: Cliente"};

	if(arr_mov_basico[0]["tipo_movimiento"] != 6) {
		html += '<div class="row padding__bottom__10">';
			html += '<div class="col col-6" id="estado"><div class="row"></div></div>';
			html += '<div class="col col-6">';
				html += '<button';
				if(arr_mov_cheque[arr_mov_cheque.length - 1]["id"] != id) html += ' disabled="true" ';
				html += 'onclick="userBean.estado(' + arr_mov["id"] + ')" class="btn btn-block btn-success">Guardar</button>';
			html += '</div>';
		html += '</div>';
	}

	title = "Movimiento #" + arr_mov[0]["did"];
	html += '<div class="jumbotron jumbotron-fluid border border-succes padding__bottom__10" style="padding-top:0; padding-bottom:0;">';
	html += '<div class="container p-2">';
	html += '<h2>' +arr_mov[0]["tipo_movimiento"]+ '</h2>';
	html += '<p class="lead">' + arr_mov[0]["detalle"] + '</p>';
	html += '</div>';
	html += '</div>';

	if(arr_mov[0]["id_destinatario"] != "")
		html += '<h3>Destinatario: ' +arr_mov[0]["id_destinatario"]+ '</h3>';
	else if(arr_mov[0]["id_portador"] != "")
		html += '<h3>Cliente: ' +arr_mov[0]["id_portador"]+ '</h3>';

	html += '<table class="table">';
		html += '<thead class="thead-light">';
			html += '<th class="text-center">N° Serie</th>';
			html += '<th class="text-center">Monto</th>';
			html += '<th class="text-center">Fecha de Emisión</th>';
			html += '<th class="text-center">Fecha de Cobro</th>';
			if(arr_mov_basico[0]["tipo_movimiento"] == 6) html += '<th class="text-center">Bloque</th>';
		html += '</thead>';
		html += '<tbody>';
			for(var i in arr_mov) {
				arr_cheque = cheque.busqueda__arrr("n_serie",arr_mov[i]["id_cheque"]);
				html += '<tr>';
					html += '<td class="text-left">' + arr_cheque["n_serie"] + '</td>';
					html += '<td class="text-right">' + arr_cheque["monto"] + '</td>';
					html += '<td class="text-center">' + arr_cheque["fecha_emision"] + '</td>';
					html += '<td class="text-center">' + arr_cheque["fecha_cobro"] + '</td>';
					if(arr_mov_basico[0]["tipo_movimiento"] == 6) {
						aux = new Pyrus("espejo");
						bloque = aux.busqueda__XX("bloque",{"id_cheque": arr_cheque["id"],"did_movimiento" : arr_mov[i]["did"]});
						html += '<td class="text-center">' + arr_bloque[bloque] + '</td>';
					}
				html += '</tr>';
			}
		html += '</tbody>';
	html += '</table>';

	$("#modal").find(".modal-title").text(title);
	$("#modal").find(".modal-body").html(html);

	if(arr_mov_basico[0]["tipo_movimiento"] != 6) {
		m = new Pyrus("movimientoestado");
		m.selectDatos("#estado .row","estado","id_estado","col col-12","[designacion]","normal");

		if(estados[arr_mov[0]["estado"]] == undefined) v = "";
		else v = estados[arr_mov[0]["estado"]];
		console.log(v)

		$("#estado select").val(v).trigger("change");
	}
	$("#modal").modal("show");
}

$('#modal').on('shown.bs.modal', function (e) {
	if($(".select__2").length)
		$('.select__2').select2();
});
userBean.verEntidad = function(t,tt) {}
userBean.estado = function(id) {
	var estado = $("#estado select").val();
	var aux = new Pyrus("movimiento");
	var aux_c = new Pyrus("cheque");
	var arr = aux.objeto(id);
	var arr_cheque = aux_c.objeto(arr["id_cheque"]);

	var did_movimiento = aux.busqueda__DID();

	/* */
	estados = {};
	m = new Pyrus("movimientoestado");
    m_arr = m.busqueda__arr();
    for(var i in m_arr) estados[m_arr[i]["id"]] = m_arr[i]["designacion"];
	/* */

	arr["id"] = "nulo";
	arr["did"] = did_movimiento;
	arr["estado"] = estado;

	
	arr["detalle"] = "Cheque #" + arr_cheque["n_serie"] + " - Cambio de Estado: " + estados[estado];


	aux.query('guardar_uno_generico',{'entidad' : 'movimiento', 'objeto' : arr}, function(m) {}, function(m) {});
	userBean.notificacion("Estado cambiado","success");
	$("#modal").modal("hide");
}

userBean.ready__ = function() {
	$(".dataTables_filter").addClass("d-flex justify-content-center align-items-center");
	// PYRUS
	columnas = {"did":"#","fecha":"fecha","id_portador":"cliente","id_destinatario":"destinatario","tipo_movimiento":"tipo de movimiento","detalle":"detalle"};
	var _movimientos = new Pyrus("movimiento",{"resultado":"","columnas": columnas});
	//_movimientos.reverse_();
	_movimientos.listador("movimientos");

	$('#movimientos tbody').on('click', 'td.details-control', function () {
	    var tr = $(this).closest('tr');
	    var row = tabla.row( tr );

	    if ( row.child.isShown() ) {
	        // This row is already open - close it
	        $('div.slider', row.child()).slideUp( function () {
	            row.child.hide();
	            tr.removeClass('shown');
	        } );
	    }
	    else {
	        // Open this row
	        arr = format(row.data());
	        row.child( arr["html"], 'no-padding' ).show();

	        if(arr["movimiento"] != 6) {
				m = new Pyrus("movimientoestado");
				m.selectDatos("#estado .row","estado","id_estado","col col-12","[designacion]","normal");

				if(arr["estado"] == undefined) v = "";
				else v = arr["estado"];

				$("#estado select").val(v).trigger("change");
				$("#estado select").select2();
			}

	        tr.addClass('shown');
	        $('div.slider', row.child()).slideDown();
	    }
	});
	$("#div").addClass("d-none");
}
$(document).ready(userBean.ready__());
</script>