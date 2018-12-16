<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Usuarios</li>
  </ol>
</nav>
<section class="">
	<div class="container-fluid d-flex justify-content-center">
		<div class="flex__body col-12">
			<table id="usuarios" class="table table-striped table-hover flex__table" style="width: 100%"></table>
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
  for(var i in res)
    res[i].pop();
  tabla = $("#"+target).DataTable({
      "columns": col,
      "data": res,
      "columnDefs": [
          { className: "text-center", "targets": [ 0 ] }
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
      //"processing": true,
      //"serverSide": true,
      "bProcessing": true,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
      //"buttons": {
      "buttons": [
          {
            text: '<i class="fas fa-plus"></i> Librador',
            className: 'btn-info',
            action: function ( e, dt, node, config ) {
              userBean.librador();
            }
          },
          {
            extend: 'selected',
            text: '<i class="fas fa-eye"></i>',
            className: 'btn-dark',
            action: function ( e, dt, node, config ) {
              var rows = dt.rows( { selected: true } ).count();
              userBean.show__(tabla);
            }
          },
          {
            extend: 'selected',
            text: '<i class="fas fa-trash-alt"></i>',
            className: 'btn-danger',
            action: function ( e, dt, node, config ) {
              var rows = dt.rows( { selected: true } ).count();
              userBean.delete__(tabla);
            }
          }
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
    _p = new Pyrus("usuarios");
    _p.listador("usuarios");

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
  userBean.librador = function() {
    var Abtn = [];
    Abtn.push({"nombre":"Cancelar","type":"button","onclick":"-1","class":"btn btn-danger"});
    Abtn.push({"nombre":"Guardar","type":"submit","onclick":"","class":"btn btn-success"});
    aux = new Pyrus("persona");
    $("#modal").find(".modal-body").html("");
    $("#modal").find(".modal-title").text("Nuevo librador");

    $("#modal").find(".modal-body").append('<input type="hidden" class="id" name="id" value="nulo">');
    $("#modal").find(".modal-body").append('<input type="hidden" class="id_user" value="' + user_datos["user_id"] + '">');
    $("#modal").find(".modal-body").append('<input type="hidden" class="nombre_mostrar" name="nombre_mostrar">');
    $("#modal").find(".modal-body").append('<div class="margin__bottom__10"><button class="mx-auto d-block btn btn-primary" type="button" title="Cambiar a Persona jurídica" onclick="javascript:userBean.cambio(this);">a <strong>Persona jurídica</strong> <i class="fas fa-exchange-alt"></i></button></div>');
    $("#modal").find(".modal-body").append('<div data-dato="normal" class="margin__bottom__10 form-group row"><label class="col-4 col-form-label">NOMBRE</label><div class="col-8"><input required="true" name="nombre" type="text" class="form-control val_string nombre" onblur="javascript:userBean.concatenar(this,\'persona\')"></div></div>');
    $("#modal").find(".modal-body").append('<div data-dato="normal" class="margin__bottom__10 form-group row"><label class="col-4 col-form-label">APELLIDO</label><div class="col-8"><input required="true" name="apellido" type="text" class="form-control val_string apellido" onblur="javascript:userBean.concatenar(this,\'persona\')"></div></div>');
    $("#modal").find(".modal-body").append('<div data-dato="normal" class="margin__bottom__10 form-group row"><label class="col-4 col-form-label">DNI</label><div class="col-8"><input required="true" name="dni" type="number" min="0" class="form-control val_integer dni"></div></div>');

    $("#modal").find(".modal-body").append('<div data-dato="juridica" class="form-group row d-none"><label class="col-4 col-form-label">RAZÓN SOCIAL</label><div class="col-8"><input required="true" name="razon_social" type="text" class="form-control val_string razon_social" onblur="javascript:userBean.concatenar(this,\'empresa\')"></div></div>');
    $("#modal").find(".modal-body").append('<div data-dato="juridica" class="form-group row d-none"><label class="col-4 col-form-label">CUIT</label><div class="col-8"><input required="true" name="cuit" type="number" min="0" class="form-control val_integer cuit"></div></div>');

    $("#modal").find(".modal-body").append('<hr/>');
    $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">CALLE</label><div class="col-8"><input required="true" name="calle" type="text" class="form-control val_string calle"></div></div>');
    $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">ALTURA</label><div class="col-4"><input required="true" name="altura" type="number" min="0" class="form-control val_integer altura"></div></div>');
    $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">LOCALIDAD</label><div class="col-8"><input required="true" name="localidad" type="text" class="form-control val_string localidad"></div></div>');
    aux.editor__footer("#modal",Abtn);
    $("#modal").modal("show");
  }
userBean.delete__ = function(t) {
  $.MessageBox({
    buttonDone  : "Si",
    buttonFail  : "No",
    message   : "¿Está seguro de eliminar el registro?"
  }).done(function(){
    var adata = t.rows( { selected: true } );
    console.log(adata.data()[0])
    t.rows( '.selected' ).remove().draw();
  }).fail(function(){});
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
//
userBean.show__ = function(t) {
  var adata = t.rows( { selected: true } );
  var data = adata.data()[0];
  aux_p = new Pyrus("persona");
  aux = new Pyrus("librador");
  arr = aux.busqueda__arr("id",data[0]);
  console.log(arr)

  var Abtn = [];
    Abtn.push({"nombre":"Cancelar","type":"button","onclick":"-1","class":"btn btn-danger"});
    Abtn.push({"nombre":"Guardar","type":"submit","onclick":"","class":"btn btn-success"});
    aux = new Pyrus("persona");
    $("#modal").find(".modal-body").html("");
    $("#modal").find(".modal-title").text("Librador");

    $("#modal").find(".modal-body").append('<input type="hidden" class="id" name="id" value="nulo">');
    $("#modal").find(".modal-body").append('<input type="hidden" class="id_user" value="' + user_datos["user_id"] + '">');
    $("#modal").find(".modal-body").append('<input type="hidden" class="nombre_mostrar" name="nombre_mostrar">');
    $("#modal").find(".modal-body").append('<div class="margin__bottom__10"><button class="mx-auto d-block btn btn-primary" type="button" title="Cambiar a Persona jurídica" onclick="javascript:userBean.cambio(this);">a <strong>Persona jurídica</strong> <i class="fas fa-exchange-alt"></i></button></div>');
    $("#modal").find(".modal-body").append('<div data-dato="normal" class="margin__bottom__10 form-group row"><label class="col-4 col-form-label">NOMBRE</label><div class="col-8"><input required="true" name="nombre" type="text" class="form-control val_string nombre" onblur="javascript:userBean.concatenar(this,\'persona\')"></div></div>');
    $("#modal").find(".modal-body").append('<div data-dato="normal" class="margin__bottom__10 form-group row"><label class="col-4 col-form-label">APELLIDO</label><div class="col-8"><input required="true" name="apellido" type="text" class="form-control val_string apellido" onblur="javascript:userBean.concatenar(this,\'persona\')"></div></div>');
    $("#modal").find(".modal-body").append('<div data-dato="normal" class="margin__bottom__10 form-group row"><label class="col-4 col-form-label">DNI</label><div class="col-8"><input required="true" name="dni" type="number" min="0" class="form-control val_integer dni"></div></div>');

    $("#modal").find(".modal-body").append('<div data-dato="juridica" class="form-group row d-none"><label class="col-4 col-form-label">RAZÓN SOCIAL</label><div class="col-8"><input required="true" name="razon_social" type="text" class="form-control val_string razon_social" onblur="javascript:userBean.concatenar(this,\'empresa\')"></div></div>');
    $("#modal").find(".modal-body").append('<div data-dato="juridica" class="form-group row d-none"><label class="col-4 col-form-label">CUIT</label><div class="col-8"><input required="true" name="cuit" type="number" min="0" class="form-control val_integer cuit"></div></div>');

    $("#modal").find(".modal-body").append('<hr/>');
    $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">CALLE</label><div class="col-8"><input required="true" name="calle" type="text" class="form-control val_string calle"></div></div>');
    $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">ALTURA</label><div class="col-4"><input required="true" name="altura" type="number" min="0" class="form-control val_integer altura"></div></div>');
    $("#modal").find(".modal-body").append('<div class="form-group row"><label class="col-4 col-form-label">LOCALIDAD</label><div class="col-8"><input required="true" name="localidad" type="text" class="form-control val_string localidad"></div></div>');
    aux.editor__footer("#modal",Abtn);
    aux_p.cargarAEditar(arr["id_persona"],"#modal");
    $("#modal").modal("show");
}

$(document).ready(userBean.ready__());
</script>
