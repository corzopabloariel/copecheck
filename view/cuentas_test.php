<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Cuentas</li>
  </ol>
</nav>
<section class="">
  <div class="container-fluid d-none" style="margin-bottom: 10px;" id="add__cuenta">
    <div class="row">
      <div class="col col-12 col-md-7">
        <div class="card">
          <h5 class="card-header card-title text-center">Cuenta</h5>
          <div class="">
            <form class="__cuenta" onsubmit="event.preventDefault(); validarForm();" novalidate>
              <div class="modal-body"></div>
              <div class="modal-footer"></div>
            </form>
          </div>
        </div>
      </div>
      <div class="__aux col col-12 col-md-5"></div>
    </div>
  </div>
	<div class="container-fluid d-flex justify-content-center">
		<div class="flex__body col-12">
			<table id="cuenta" class="table table-striped table-hover flex__table" style="width: 100%"></table>
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
<div id="modal_2" class="modal" tabindex="-1" role="dialog">
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
_p = new Pyrus("cuenta");
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
              text: '<i class="fas fa-plus"></i> Cuenta',
              className: 'btn-info',
              action: function ( e, dt, node, config ) {
                $("#add__cuenta").removeClass("d-none");
              }
            },
        ],
        //},
        "language": translate_spanish
    });
    tabla.buttons().container().appendTo( $('.col-sm-6:eq(0)', tabla.table().container() ) );
		$("div.dt-buttons button").removeClass("btn-secondary");
};

userBean.verEntidad = function(e) {
  $(".__aux").html("");
  switch(e) {
    case "banco":
      aux = new Pyrus("banco");
      $(".__aux").append("<div class=\"card\">");
      $(".__aux").find(".card").append("<h5 class=\"card-header card-title text-center\">Banco</h5>");
      $(".__aux").find(".card").append("<div class=\"card-body\">");
      $(".__aux").find(".card > .card-body").append("<h6 class=\"card-subtitle mb-2 text-muted\">CUIT</h6>");
      $(".__aux").find(".card > .card-body").append("<div class=\"card-text\">");
      $(".__aux").find(".card > .card-body .card-text").append("<input type=\"number\" class=\"form-control\" name=\"cuit\">");
      $(".__aux").find(".card").append("<div class=\"card-body\" style=\"padding-top: 0;\">");
      $(".__aux").find(".card > .card-body:last-child").append("<button class=\"btn btn-block btn-success\" onclick=\"javascript:userBean.search__banco(this)\">Buscar</button>");

      $(".__aux").append("<div class=\"card d-none\">");
      $(".__aux").find(".card + .card").append("<h5 class=\"card-header card-title text-center\">Banco</h5>");
      $(".__aux").find(".card + .card").append("<div class=\"card-body\">");
      $(".__aux").find(".card + .card .card-body").append("<form method=\"POST\" onsubmit=\"event.preventDefault(); javascript:userBean.asignar(this,'"+e+"')\" novalidate>")
      aux.editor__(".__aux .card + .card .card-body form");
      $(".__aux").find(".card + .card .card-body form").append("<div class=\"foot row\">")
      $(".__aux").find(".card + .card .card-body form").find(".foot.row").append("<div class=\"col col-6\"><button class=\"btn btn-block\" onclick=\"$('.__aux').html('')\">Cancelar</button>");
      $(".__aux").find(".card + .card .card-body").find(".foot.row").append("<div class=\"col col-6\"><button class=\"btn btn-block btn-success\" type=\"submit\">Asignar</button>");
    break;
    case "titular":
      aux = new Pyrus("persona");
      $(".__aux").append("<div class=\"card\">");
      $(".__aux").find(".card").append("<h5 class=\"card-header card-title text-center\">Titular</h5>");
      $(".__aux").find(".card").append("<div class=\"card-body\">");
      $(".__aux").find(".card > .card-body").append("<h6 class=\"card-subtitle mb-2 text-muted\">DNI / CUIL /CUIT</h6>");
      $(".__aux").find(".card > .card-body").append("<div class=\"card-text\">");
      $(".__aux").find(".card > .card-body .card-text").append("<input type=\"number\" class=\"form-control\" name=\"cuit\">");
      $(".__aux").find(".card").append("<div class=\"card-body\" style=\"padding-top: 0;\">");
      $(".__aux").find(".card > .card-body:last-child").append("<button class=\"btn btn-block btn-success\" onclick=\"javascript:userBean.search__titular(this)\">Buscar</button>");

      $(".__aux").append("<div class=\"card d-none\">");
      $(".__aux").find(".card + .card").append("<h5 class=\"card-header card-title text-center\">Titular</h5>");
      $(".__aux").find(".card + .card").append("<div class=\"card-body\">");
      $(".__aux").find(".card + .card .card-body").append("<form method=\"POST\" onsubmit=\"event.preventDefault(); javascript:userBean.asignar(this,'"+e+"')\" novalidate>")
      aux.editor__(".__aux .card + .card .card-body form");
      $(".__aux").find(".card + .card .card-body form").append("<div class=\"foot row\">")
      $(".__aux").find(".card + .card .card-body form").find(".foot.row").append("<div class=\"col col-6\"><button class=\"btn btn-block\" onclick=\"$('.__aux').html('')\">Cancelar</button>");
      $(".__aux").find(".card + .card .card-body").find(".foot.row").append("<div class=\"col col-6\"><button class=\"btn btn-block btn-success\" type=\"submit\">Asignar</button>");
    break;
  }
}
userBean.asignar = function(t,e) {
  var arr = $(t).serializeArray();
  switch(e) {
    case "banco":
      $(".__"+e).html(arr[0].value+" "+arr[2].value+" ("+arr[1].value+")");
      break;
    case "titular":
      $(".__"+e).html(arr[0].value+" "+arr[1].value+" ("+arr[2].value+")");
      break;
  }
  $('.__aux').html('');
}
userBean.search__titular = function(t) {
  var cuit = $(t).closest(".card").find("input").val();
  $(t).closest(".card").addClass("d-none");
  $(t).closest(".card").find("+ .card").removeClass("d-none");
}
userBean.search__banco = function(t) {
  var cuit = $(t).closest(".card").find("input").val();
  $(t).closest(".card").addClass("d-none");
  banco = new Pyrus("banco");
  banco.busqueda("cuit",cuit);
  banco.editor("")
  $(t).closest(".card").find("+ .card").removeClass("d-none");
}
	userBean.ready__ = function() {
		$(".dataTables_filter").addClass("d-flex justify-content-center align-items-center");
        var Abtn = [];
        //Abtn [{"nombre":x,"onclick":x,"class":}] javascript:Pyrus.cancelar()
        Abtn.push({"nombre":"Cancelar","onclick":"","class":"btn btn-danger"});
        Abtn.push({"nombre":"Guardar","onclick":"javascript:Pyrus.guardar(window.id_)","class":"btn btn-success"});
        
		// PYRUS
        _p.listador("cuenta");
        _p.editor(".__cuenta","",Abtn);
        $(".select__2").select2({width: 'resolve',placeholder: 'Seleccione'});
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
	$(document).ready(userBean.ready__());
    $('#modal').on('hidden.bs.modal', function (e) {
        $(this).find("input[type='reset']").click();
    });
    $('#modal_2').on('hidden.bs.modal', function (e) {
        $(this).find("input[type='reset']").click();
    });
</script>
