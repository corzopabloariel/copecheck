<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Titulares</li>
  </ol>
</nav>
<section class="">
	<div class="container-fluid d-flex justify-content-center">
		<div class="flex__body col-12">
			<table id="titular" class="table table-striped table-hover flex__table" style="width: 100%"></table>
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
      <form method="POST" id="modal-form" onsubmit="event.preventDefault(); validarForm();" novalidate>
          <div class="modal-body"></div>
          <div class="modal-footer"></div>
      </form>
    </div>
  </div>
</div>    
<!-- END Edit - New modal -->
<script>
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
          { className: "text-center", "targets": [ 0,3,4 ] }
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
      "scrollX":true,
      //"processing": true,
      //"serverSide": true,
      "bProcessing": true,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
      //"buttons": {
      "buttons": [
          {
            text: '<i class="fas fa-plus"></i> Titular',
            className: 'btn-info',
            action: function ( e, dt, node, config ) {
              $("#modal").modal('show')
            }
          },
      ],
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
    _p = new Pyrus("titular");
    _p.constructor();
    _p.listador("titular");

    _p.editor("#modal","Nuevo titular",Abtn);
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
	userBean.validarForm = function() {
		if(userBean.validar()) {
			userBean.login();
		}
	}

	$(document).ready(userBean.ready__());
</script>
