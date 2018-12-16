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
		<li class="breadcrumb-item active">Espejos</li>
  </ol>
</nav>
<section class="">
	<div class="container-fluid d-flex justify-content-center">
		<div class="flex__body col col-12">
			<div class="row padding__bottom__10">
				<div class="col col-12">
					<a class="btn btn-lg btn-block btn-success" href="nuevo/">CREAR ESPEJO</a>
				</div>
			</div>
			<div class="row padding__bottom__10">
				<div class="col col-12">
					<div class="background__ffffff box__shadow__01 p-2 border__radius__4" style="min-height: 200px;">
						<h3 class="text-center">Historial de espejos</h3>
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

/**
 * @param did del espejo
 */
userBean.ver = function(did) {
	/*
	 * TIPO
	 * 	2 - Ingreso de Cheque
	 * 	4 - Egreso de Cheque
	 * 	6 - Espejo
	 */
	title = "";
	html = ""; select = "";
	esp = new Pyrus("espejo");
	cheque = new Pyrus("cheque");
	arr_esp_basico = esp.busqueda__arr("did",did);//Objeto sin relaciones
	arr_esp = esp.busqueda__arrr("did",did);//Objeto compuesto
	arr_esp_cheque = esp.busqueda__arr("id_cheque",arr_esp_basico["id_cheque"]);
	console.log(arr_esp_cheque)

	arr_bloque = { 2:"A: Cooperativa",4:"B: Cliente"};

	title = "Espejo #" + did + " - " + arr_esp[0]["fecha"];

	html += '<table class="table">';
		html += '<thead class="thead-light">';
			html += '<th class="text-center">N° Serie</th>';
			html += '<th class="text-center">Monto</th>';
			html += '<th class="text-center">Fecha de Emisión</th>';
			html += '<th class="text-center">Fecha de Cobro</th>';
			html += '<th class="text-center">Bloque</th>';
		html += '</thead>';
		html += '<tbody>';
			for(var i in arr_esp_basico) {
				arr_cheque = cheque.objeto(arr_esp_basico[i]["id_cheque"]);
				html += '<tr>';
					html += '<td class="text-left">' + arr_cheque["n_serie"] + '</td>';
					html += '<td class="text-right">' + userBean.formatearNumero(arr_cheque["monto"]) + '</td>';
					html += '<td class="text-center">' + cheque.convertirYYYYMMDDToFecha(arr_cheque["fecha_emision"]) + '</td>';
					html += '<td class="text-center">' + cheque.convertirYYYYMMDDToFecha(arr_cheque["fecha_cobro"]) + '</td>';
					html += '<td class="text-center">' + arr_bloque[arr_esp_basico[i]["bloque"]] + '</td>';
					
				html += '</tr>';
			}
		html += '</tbody>';
	html += '</table>';

	$("#modal").find(".modal-title").text(title);
	$("#modal").find(".modal-body").html(html);
	$("#modal").modal("show");
}
function format (data) {
	console.log(data)
	html = ""; select = "";
	esp = new Pyrus("espejo");
	cheque = new Pyrus("cheque");
	arr_esp_basico = esp.busqueda__arr("did",data[0]);//Objeto sin relaciones
	arr_esp = esp.busqueda__arrr("did",data[0]);//Objeto compuesto
	arr_esp_cheque = esp.busqueda__arr("id_cheque",arr_esp_basico["id_cheque"]);
	console.log(arr_esp_cheque)

	arr_bloque = { 2:"A: Cooperativa",4:"B: Cliente"};

	title = "Espejo #" + data[0] + " - " + arr_esp[0]["fecha"];

	html += '<table class="table">';
		html += '<thead class="thead-light">';
			html += '<th class="text-center">N° Serie</th>';
			html += '<th class="text-center">Monto</th>';
			html += '<th class="text-center">Fecha de Emisión</th>';
			html += '<th class="text-center">Fecha de Cobro</th>';
			html += '<th class="text-center">Bloque</th>';
		html += '</thead>';
		html += '<tbody>';
			for(var i in arr_esp_basico) {
				arr_cheque = cheque.objeto(arr_esp_basico[i]["id_cheque"]);
				html += '<tr>';
					html += '<td class="text-left">' + arr_cheque["n_serie"] + '</td>';
					html += '<td class="text-right">' + userBean.formatearNumero(arr_cheque["monto"]) + '</td>';
					html += '<td class="text-center">' + cheque.convertirYYYYMMDDToFecha(arr_cheque["fecha_emision"]) + '</td>';
					html += '<td class="text-center">' + cheque.convertirYYYYMMDDToFecha(arr_cheque["fecha_cobro"]) + '</td>';
					html += '<td class="text-center">' + arr_bloque[arr_esp_basico[i]["bloque"]] + '</td>';
					
				html += '</tr>';
			}
		html += '</tbody>';
	html += '</table>';

    return '<div class="slider" name>' + html + '</div>';
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
		if(res[i][4] == "2") res[i][4] = "A: Cooperativa"
		else res[i][4] = "B: Cliente";
	}
	
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
						{ className: "text-right", "targets": [ 5 ] },
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
    var tipo = {"2" : "<i class=\"badge badge-success\">ingreso</i>","4" : "<i class=\"badge badge-danger\">egreso</i>","6" : "<i class=\"badge badge-info\">espejo</i>"};
    console.log(pos)
    console.log(columnas)
    for(var i in res) {
        var tmp = "";
        for(var x in pos) {
            if(tmp == "") tmp = "<p style=\"margin:0;\">";
            console.log(pos[x] + " -- " + res[i][pos[x]])
            switch(pos[x]) {
                case "3":
                    if(res[i][pos[x]] != "")
                        tmp += "<span>" + res[i][pos[x]]+" <small>cooperativa</small></span>";
                    break;
                case "6":
                    if(res[i][pos[x]] != "")
                        tmp += "<span>" + res[i][pos[x]]+" <small>cliente</small></span>";
                    break;
                case "8":
                	tmp += "<span>" + (res[i][pos[x]] == 2 ? "Bloque A" : "Bloque B") +"</span>";
                	break;
               	case "7":
               		tmp += "<span>Cheque #" + res[i][pos[x]]+"</span>";
               		break;
                //2 -> activo / 4 -> cobrado / 6 -> acreditado / 8 -> rebotado 
                default:
                    tmp += "<span>" + res[i][pos[x]]+"</span>";
                break;
            }
        }
        /* (id,tipo) */
        tmp += '<i class="far fa-eye" style="cursor:pointer" onclick="userBean.ver(' + res[i][1] + ');"></i>';
        tmp += "</p>";
        $(target).append(tmp)
    }
}

$('#modal').on('shown.bs.modal', function (e) {
	if($(".select__2").length)
		$('.select__2').select2();
});
userBean.verEntidad = function(t,tt) {
	// body...
}
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
	columnas = {"did":"#","fecha":"fecha","id_cooperativa":"cooperativa","id_cliente":"cliente","bloque":"bloque","id_cheque":"cheque"};

	var espejo = new Pyrus("espejo",{"resultado":"","columnas": columnas});
	//espejo.reverse_();
	espejo.listador("movimientos");
	//espejo.listador__("#movimientos",["did","fecha","cooperativa","cliente","bloque","cheque"]);

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
	        row.child( format(row.data()), 'no-padding' ).show();
	        tr.addClass('shown');
	        $('div.slider', row.child()).slideDown();
	    }
	});
	$("#div").addClass("d-none");
}
$(document).ready(userBean.ready__());
</script>