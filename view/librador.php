<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Libradores</li>
  </ol>
</nav>
<section class="">
	<div class="container-fluid d-flex justify-content-center">
		<div class="flex__body col-12">
			<table id="libradores" class="table table-striped table-hover flex__table" style="width: 100%"></table>
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
var lib = new Pyrus("librador");
var arr_lib = lib.busqueda__arrr();
var aux_lib = [];

for(var x in arr_lib) {
  tmp = [];
  for(var y in arr_lib[x]) 
    tmp.push(arr_lib[x][y]);
  aux_lib.push(tmp);
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
};

userBean.listarEntidad = function(col,res,target){
  col.pop();
  for(var i in aux_lib) {
    aux_lib[i].pop();
  }
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
      "select": 'single',
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
	// PYRUS
  _p = new Pyrus("librador");
  _p.listador("libradores");
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
//
userBean.librador = function() {
  $("#div").removeClass("d-none");
  userBean.notificacion("Cargando... espere","warning",false);
  setTimeout(function() { userBean.librador_behavior(); },700);
}
userBean.librador_behavior = function() {
  aux = new Pyrus("persona");
  aux_a = new Pyrus("domicilio");
  $("#modal").find(".modal-body,.modal-footer").html("");
  $("#modal").find(".modal-title").html("NUEVO <strong>LIBRADOR</strong>");
  
  $("#modal").find(".modal-body").append('<div class="margin__bottom__10"><button class="mx-auto d-block btn btn-primary" type="button" title="Cambiar a Persona jurídica" onclick="javascript:userBean.cambio(this);">a <strong>Persona jurídica</strong> <i class="fas fa-exchange-alt"></i></button></div>');

  //contenedor,formato,valor = {},desactivado = [],funcion = {},data = null,visible = true
  aux.editor__X("#modal .modal-body",[{"id":""},{"nombre_mostrar":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
  aux.editor__X("#modal .modal-body",[{"nombre":"col col-12"}],null,null,{"nombre":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
  aux.editor__X("#modal .modal-body",[{"apellido":"col col-12"}],null,null,{"apellido":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
  aux.editor__X("#modal .modal-body",[{"dni":"col col-12"}],null,null,null,[{"dato":"normal"}]);
  aux.editor__X("#modal .modal-body",[{"razon_social":"col col-12"}],null,null,{"razon_social":[{"onblur":"'userBean.concatenar(this,\"empresa\")'"}]},[{"dato":"juridica"}],false);
  aux.editor__X("#modal .modal-body",[{"cuit":"col col-12"}],null,null,null,[{"dato":"juridica"}],false);

  $("#modal").find(".modal-body").append('<hr>');
  aux_a.editor__X("#modal .modal-body",[{"calle":"col col-8"},{"altura":"col col-4"}]);
  aux_a.editor__X("#modal .modal-body",[{"localidad":"col col-12"}]);

  
  aux.editor__X_footer("#modal .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":null,"data":[{"dismiss":"'modal'"}]},"GUARDAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}})
  $("#modal").modal("show");
  $("#div").addClass("d-none");
}
//
userBean.delete__ = function(t) {
  $.MessageBox({
    buttonDone  : "Si",
    buttonFail  : "No",
    message   : "¿Está seguro de eliminar el registro?"
  }).done(function(){
    var adata = t.rows( { selected: true } );
    var data = adata.data()[0];
    lib.eliminar(data[0])
    t.rows( '.selected' ).remove().draw();
  }).fail(function(){});
}

userBean.validarForm = function() {
  $("#div").removeClass("d-none");
  userBean.notificacion("Cargando... espere","warning",false);
  setTimeout(function() { 
    if(userBean.validar()) {
      var aux = new Pyrus("persona");
      var aux_d = new Pyrus("domicilio");

      if($("#modal").find(".id").val() == "") {
        var librador = {};
        id_persona = aux.guardar("nulo","#modal");
        id_domicilio = aux_d.guardar("nulo","#modal");
        
        librador["id"] = "nulo";
        librador["id_persona"] = id_persona;
        librador["id_domicilio"] = id_domicilio;
        librador["id_user"] = user_datos["user_id"];

        aux.query('guardar_uno_generico',{'entidad' : 'librador', 'objeto' : librador}, function(m) {}, function(m) {});
        userBean.notificacion("Librador creado","success",false);
      } else {
        arr = lib.busqueda__arr("id_persona",$("#modal").find(".id").val())[0];
        var persona = {};
        persona["id"] = $("#modal").find(".id").val();
        persona["razon_social"] = $("#modal").find(".razon_social").val();
        persona["cuit"] = $("#modal").find(".cuit").val();
        persona["nombre"] = $("#modal").find(".nombre").val();
        persona["apellido"] = $("#modal").find(".apellido").val();
        persona["dni"] = $("#modal").find(".dni").val();
        persona["nombre_mostrar"] = $("#modal").find(".nombre_mostrar").val();
        persona["id_user"] = $("#modal").find(".id_user").val();
        var domicilio = {};
        domicilio["id"] = arr["id_domicilio"];
        domicilio["calle"] = $("#modal").find(".calle").val();
        domicilio["altura"] = $("#modal").find(".altura").val();
        domicilio["localidad"] = $("#modal").find(".localidad").val();
        aux.guardar("","",persona);
        aux_d.guardar("","",domicilio);
        userBean.notificacion("Librador editado","success",false);
      }
      $("#modal").find("input").val("");
      $("#modal").modal("hide");
      $("#div").addClass("d-none");
      setTimeout(function() {
        location.reload();
      },600)
      //var aux_ll = new Pyrus("librador");
      //aux_ll.listador("libradores")
    }

  },700);
}
//
userBean.show__ = function(t) {
  var adata = t.rows( { selected: true } );
  var data = adata.data()[0];
  arr = lib.busqueda__arr("id",data[0]);
  aux_p = new Pyrus("persona");
  aux_a = new Pyrus("domicilio");
  arr_p = aux_p.busqueda__arr("id",arr["id_persona"]);

  $("#modal").find(".modal-body,.modal-footer").html("");
  $("#modal").find(".modal-title").html("LIBRADOR");
  
  $("#modal").find(".modal-body").append('<div class="margin__bottom__10"><button class="mx-auto d-block btn btn-primary" type="button" title="Cambiar a Persona jurídica" onclick="javascript:userBean.cambio(this);">a <strong>Persona jurídica</strong> <i class="fas fa-exchange-alt"></i></button></div>');

  //contenedor,formato,valor = {},desactivado = [],funcion = {},data = null,visible = true
  aux_p.editor__X("#modal .modal-body",[{"id":""},{"nombre_mostrar":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
  aux_p.editor__X("#modal .modal-body",[{"nombre":"col col-12"}],null,null,{"nombre":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
  aux_p.editor__X("#modal .modal-body",[{"apellido":"col col-12"}],null,null,{"apellido":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
  aux_p.editor__X("#modal .modal-body",[{"dni":"col col-12"}],null,null,null,[{"dato":"normal"}]);
  aux_p.editor__X("#modal .modal-body",[{"razon_social":"col col-12"}],null,null,{"razon_social":[{"onblur":"'userBean.concatenar(this,\"empresa\")'"}]},[{"dato":"juridica"}],false);
  aux_p.editor__X("#modal .modal-body",[{"cuit":"col col-12"}],null,null,null,[{"dato":"juridica"}],false);

  $("#modal").find(".modal-body").append('<hr>');
  aux_a.editor__X("#modal .modal-body",[{"calle":"col col-8"},{"altura":"col col-4"}]);
  aux_a.editor__X("#modal .modal-body",[{"localidad":"col col-12"}]);
  
  aux_p.editor__X_footer("#modal .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":null,"data":[{"dismiss":"'modal'"}]},"EDITAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}})

  aux_p.cargarAEditar(arr["id_persona"],"#modal");
  aux_a.cargarAEditar(arr["id_domicilio"],"#modal");
  console.log(arr_p)
  if(arr_p["razon_social"] != "") {
    console.log("D")
    $("#modal *[data-dato='normal']").addClass("d-none");
    $("#modal *[data-dato='juridica']").removeClass("d-none");
    $("#modal .modal-body button strong").text("Persona");
  }
  $("#modal").modal("show");
}

	$(document).ready(userBean.ready__());
</script>
