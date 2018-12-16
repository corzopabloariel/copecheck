<style type="text/css">
	table.dataTable thead th,
	table th,
	table td { vertical-align: middle !important; }
</style>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Informes</li>
  </ol>
</nav>
<section class="">
	<div class="container-fluid d-flex justify-content-center">
		<div class="flex__body col col-12">
			<table id="cheque" class="table table-striped table-hover flex__table" style="width: 100%"></table>
		</div>
	</div>
</section>
<!-- BEGIN Edit - New modal -->
<div id="modal" class="modal" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Movimientos del cheque</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        	<div class="modal-body"></div>
        </div>
    </div>
</div>
<script>
//aux -> movimiento
//aux_c -> cheque

var mov__ = new Pyrus("movimiento");
var che = new Pyrus("cheque");
var mov_estado = new Pyrus("movimientoestado");
estados = {};
m_arr = mov_estado.busqueda__arr();
for(var i in m_arr) estados[m_arr[i]["id"]] = m_arr[i]["designacion"];
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
		l = res[i].length
		if(res[i][l - 1] == 0 || res[i][l - 1] == null) res[i][l - 1] = "NO";
		else res[i][l - 1] = "SI"
	}
  	var tabla = $("#"+target).DataTable({ "columns": col, "data": res, 
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
		"columnDefs": [	{ className: "text-center", "targets": [ 1,2,5,7 ] },
						{ className: "text-right", "targets": [ 4 ] },
		],
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
		select: { style: 'single' },
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
		"buttons": [{	extend: 'selected',
						text: '<i class="fas fa-map-signs"></i>',
						className: 'btn-warning',
						action: function ( e, dt, node, config ) {
						  	var rows = dt.rows( { selected: true } ).count();
						  	var col_arr = [];
						  	$(".selected").each( function() {
						  		col_arr.push($(this).find("td:first-child").text());
						  	});
							userBean.notificacion("Cargando... espere","warning",false);
							
						  	setTimeout(function() { userBean.mapa(col_arr); },1000);
						}
					},
		            {
		            extend: 'collection',
		            text: '<i class="fas fa-download"></i>',
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
userBean.verEntidad = function(t,tt) {}
userBean.mapa = function(col_arr) {	
	//
	cheque = che.busqueda__arrr("n_serie",col_arr[0]);
	arr_mov = mov__.busqueda__arr("id_cheque",cheque[0]["id"]);
	arr_m = mov__.busqueda__arrr("id_cheque",cheque[0]["id"]);
	html = "";

	html += '<div class="row padding__bottom__10">';
		html += '<div class="col col-6" id="estado"><div class="row"></div></div>';
		html += '<div class="col col-6">';
			html += '<button ';
			if(arr_mov[arr_mov.length - 1]["estado"] != "2") html += 'disabled="true" ';
			html += 'onclick="userBean.estado(' + arr_mov[arr_mov.length - 1]["id"] + ')" class="btn btn-block btn-success">Guardar</button>';
		html += '</div>';
	html += '</div>';

	html += '<div class="jumbotron jumbotron-fluid border border-succes padding__bottom__10" style="padding-top:0; padding-bottom:0;">';
	html += '<div class="container p-2">';
	html += '<h2>Cheque #' +cheque[0]["n_serie"]+ '</h2>';
	html += '<p class="lead" style="margin:0;">Monto: ' + cheque[0]["monto"] + '</p>';
	html += '</div>';
	html += '</div>';
	html += '<table class="table">';
		html += '<thead class="thead-light">';
			html += '<th class="text-center">#</th>';
			html += '<th class="text-center">Acción</th>';
			html += '<th class="text-center">Estado</th>';
			html += '<th class="text-center">Tipo de Movimiento</th>';
		html += '</thead>';
		html += '<tbody>';
			for(var i in arr_m) {
				html += '<tr>';
					html += '<td class="text-left">' + (parseInt(i) + 1) + '</td>';
					html += '<td class="text-left">' + arr_m[i]["accion"] + '</td>';
					html += '<td class="text-right">' + arr_m[i]["estado"] + '</td>';
					html += '<td class="text-center">' + arr_m[i]["tipo_movimiento"] + '</td>';
				html += '</tr>';
			}
		html += '</tbody>';
	html += '</table>';
	
	$("#modal").find(".modal-body").html(html);
	$("#modal").find(".modal-body").find("table tbody tr:last-child").addClass("table-info");

	mov_estado.selectDatos("#estado .row","estado","id_estado","col col-12","[designacion]","normal");

	$("#estado select").val(arr_mov[arr_mov.length - 1]["estado"]).trigger("change");
	if(arr_mov[arr_mov.length - 1]["estado"] != "2") $("#estado select").attr("disabled",true);
	$(".select__2").select2();

	$("#modal").modal("show");
}
userBean.ready__ = function() {
	$(".dataTables_filter").addClass("d-flex justify-content-center align-items-center");
	// PYRUS
	columnas = {"n_serie":"n° serie","fecha_emision":"fecha de emisión","fecha_cobro":"fecha de cobro","id_librador":"librador","monto":"monto","estado":"estado","accion":"acción","espejo":"espejo"};
	//_m.reverse_();//invierte ARRAY Resultado
	/*
	 * Objeto 2:
	 * Resultado de un objeto a combinar, columnas de la misma
	 * Columnas a mostrar compuesta por ambos objetos
	 */
	_p = new Pyrus("cheque",{"resultado":mov__.busqueda__arrr(),"columnas": columnas});
	_p.listado_combinado("cheque");
	$("#div").addClass("d-none");
	//Pyrus.crearEditor(entidad,".modal-body");
}
userBean.estado = function(id) {
	var estado = $("#estado select").val();
	
	var arr = mov__.objeto(id);
	var arr_cheque = che.objeto(arr["id_cheque"]);

	var did_movimiento = mov__.busqueda__DID();

	arr["id"] = "nulo";
	arr["did"] = did_movimiento;
	arr["estado"] = estado;

	
	arr["detalle"] = "Cheque #" + arr_cheque["n_serie"] + " | cambio de ESTADO: " + estados[estado];


	mov__.query('guardar_uno_generico',{'entidad' : 'movimiento', 'objeto' : arr}, function(m) {}, function(m) {});
	userBean.notificacion("Estado cambiado","success");
	$("#modal").modal("hide");
}
$(document).ready(userBean.ready__());
</script>
