<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Bancos</li>
  </ol>
</nav>
<section class="">
	<div class="container-fluid d-flex justify-content-center">
		<div class="flex__body col-12">
			<table id="banco" class="table table-striped table-hover flex__table" style="width: 100%"></table>
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
_p = new Pyrus("banco");
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
var datatable__ = function(target,translate) { // DATATABLE
    var tabla = $("#"+target).DataTable({
        "columns": col,
        "data": res,
        "columnDefs": [
            { className: "text-center", "targets": [ 0,1,3,4 ] }
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
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        "buttons": [
            {
              text: '<i class="fas fa-plus"></i> Banco',
              className: 'btn-info',
              action: function ( e, dt, node, config ) {
                $("#modal").modal("show");
              }
            },
        ],
        "language": translate
    });
    tabla.buttons().container().appendTo( $('.col-sm-6:eq(0)', tabla.table().container() ) );
	$("div.dt-buttons button").removeClass("btn-secondary");
};
userBean.listarEntidad = function(col,res,target){
    datatable__(target,translate_spanish);
};
userBean.inputSelector = function(identificador,fuente_opciones,formato){
    selector = "<div class='form-group row'>" +
             "<label class='col-4 col-form-label'>" + identificador.toUpperCase() + "</label>";
    selector += "<div class='col-8'>";
    selector += "<select style='width:100%' data-placeholder='Seleccione " + identificador + "' data-allow-clear='true' class='form-control select__2 val_selector " + identificador + "'>";
    selector += "<option value=''></option>";
    _p.query("listar_generico",
     {'entidad':fuente_opciones },
     function(m){
         for(var x in m){
             selector += "<option value='" + m[x]['id'] + "'>" +
             _p.imprimirConFormato(formato,m[x])
             + "</option>";
         }
     },null,false);
    selector += "</select></div>";
    return window.selector;
};
userBean.ready__ = function() {
	$(".dataTables_filter").addClass("d-flex justify-content-center align-items-center");
    var Abtn = [];
    //Abtn [{"nombre":x,"onclick":x,"class":}] javascript:Pyrus.cancelar()
    Abtn.push({"nombre":"Guardar","onclick":"","class":"btn btn-success","type":"submit"});
    Abtn.push({"nombre":"Cancelar","onclick":"-1","class":"btn btn-danger","type":"button"});
	// PYRUS
    _p.constructor();
    _p.listador("banco");
    _p.editor("#modal","Nueva banco",Abtn);
}
userBean.validarForm = function() {
    if(userBean.validar()) {
        if(_p.guardar()) {// guardo y esta OK
        	$("#modal").modal("hide");
        	datatable__("banco",translate_spanish);// reload DATATABLE
        }
    }
}
	$(document).ready(userBean.ready__());
    $('#modal').on('hidden.bs.modal', function (e) {
        $(this).find("input[type='reset']").click();
    });
    $('#modal_2').on('hidden.bs.modal', function (e) {
        $(this).find("input[type='reset']").click();
    });
</script>
