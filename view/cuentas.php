<style type="text/css">
  .modal-footer { display: block !important; }
</style>
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
          <h5 class="card-header card-title text-center">CUENTA</h5>
          <div class="">
            <form class="__cuenta" onsubmit="event.preventDefault(); userBean.validarForm();" novalidate>
              <div class="modal-body"></div>
              <div class="modal-footer"></div>
            </form>
          </div>
        </div>
      </div>
      <div class="col col-12 d-block d-sm-none d-none"><hr></div>
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
<!-- END Edit - New modal -->
<script>
if(user_datos["user_lvl"] != 1) window.location = '../gestion_cheques/';
else {
var _p = new Pyrus("cuenta");

const translate_spanish = {
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
          "rows": { _: "%d filas seleccionadas", 0: "Click en una fila para seleccionar", 1: "1 fila seleccionada" }// <i title='CTRL + CLICK para seleccionar más' class='fas fa-info-circle text-info'></i>
  }
};
var Abtn = [];
Abtn.push({"nombre":"Cancelar","type":"button","onclick":"userBean.cancelar_despleg()","class":"btn btn-danger"});
Abtn.push({"nombre":"Guardar","type":"submit","onclick":"-1","class":"btn btn-success"});

userBean.listarEntidad = function(col,res,target){
    col.pop();
    console.log(res)
    for(var i in res)
      res[i].pop();

    console.log(res)
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
        //},
        "language": translate_spanish
    });
    tabla.buttons().container().appendTo( $('.col-sm-6:eq(0)', tabla.table().container() ) );
		$("div.dt-buttons button").removeClass("btn-secondary");
};

//
userBean.show__ = function(t) {
  var adata = t.rows( { selected: true } );
  var data = adata.data()[0];
  aux = new Pyrus("cuenta");
  aux.cargarAEditar(data[0]);
  console.log(aux)
  //aux.editor(".__cuenta","",Abtn);
  $("#add__cuenta").removeClass("d-none");

  $(".__cuenta .modal-footer").html("");

  aux.editor__X_footer(".__cuenta .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":[{"onclick":"'userBean.cancelar_despleg(true)'"}],"data":null},"EDITAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}});
}
userBean.delete__ = function(t) {
  $.MessageBox({
    buttonDone  : "Si",
    buttonFail  : "No",
    message   : "¿Está seguro de eliminar el registro?"
  }).done(function(){
    var adata = t.rows( { selected: true } );
    var data = adata.data()[0];
    _p.eliminar(data[0])
    t.rows( '.selected' ).remove().draw();
  }).fail(function(){});
}
userBean.verEntidad = function(t,e) {
  $(".__aux").html("");
  if($(t).val() == "-1") {
    $(".__cuenta").find("input,select,button").attr("disabled",true);

    switch(e) {
      case "id_cooperativa":
        aux = new Pyrus("cooperativa");

        $(".__aux").append("<div class=\"card\">");
        $(".__aux").find(".card").append("<h5 class=\"card-header card-title text-center\">NUEVA <strong>COOPERATIVA</strong></h5>");
        $(".__aux").find(".card").append("<div class=\"card-body\">");
        $(".__aux").find(".card .card-body").append("<form method=\"POST\" onsubmit=\"event.preventDefault(); javascript:userBean.asignar(this,'"+e+"')\" novalidate>")
        aux.editor__(".__aux .card .card-body form");
        $(".__aux").find(".card .card-body form").append("<div class=\"foot row\">")
        $(".__aux").find(".card .card-body form").find(".foot.row").append("<div class=\"col col-6\"><button class=\"btn btn-block\" onclick=\"javascript:userBean.cancelar('"+e+"')\">Cancelar</button>");
        $(".__aux").find(".card .card-body").find(".foot.row").append("<div class=\"col col-6\"><button class=\"btn btn-block btn-success\" type=\"submit\">Asignar</button>");
        break;
      case "id_banco":
        aux = new Pyrus("banco");
        aux1 = new Pyrus("domicilio");
        aux2 = new Pyrus("sucursal");
        $(".__aux").append("<div class=\"card\">");
        $(".__aux").find(".card").append("<h5 class=\"card-header card-title text-center\">NUEVO <strong>BANCO</strong></h5>");
        $(".__aux").find(".card").append("<div class=\"card-body\">");
        $(".__aux").find(".card .card-body").append("<form method=\"POST\" onsubmit=\"event.preventDefault(); javascript:userBean.asignar(this,'"+e+"')\" novalidate>");
        contenedor = ".__aux .card .card-body form";
        aux.editor__X(contenedor,[{"id":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
        aux.editor__X(contenedor,[{"nombre":"col col-12"}]);
        aux.editor__X(contenedor,[{"sucursal":"col col-12"}]);
        $(contenedor).append('<hr>');
        aux1.editor__X(contenedor,[{"calle":"col col-8"},{"altura":"col col-4"}]);
        aux1.editor__X(contenedor,[{"localidad":"col col-12"}]);
        $(contenedor).append('<hr>');
        aux2.editor__X(contenedor,[{"telefono":"col col-12"}]);

        $(".__aux").find(".card .card-body form").append("<div class=\"foot\">");
        aux.editor__X_footer(".__aux .card .card-body form .foot",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":[{"onclick":"'userBean.cancelar(\""+e+"\")'"}],"data":null},"ASIGNAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}});

      break;
      case "id_titular":
        aux = new Pyrus("persona");

        $(".__aux").append("<div class=\"card\">");
        $(".__aux").find(".card").append("<h5 class=\"card-header card-title text-center\">NUEVO <strong>TITULAR</strong></h5>");
        $(".__aux").find(".card").append("<div class=\"card-body\">");
        $(".__aux").find(".card .card-body").append("<form method=\"POST\" onsubmit=\"event.preventDefault(); javascript:userBean.asignar(this,'"+e+"')\" novalidate>")
        contenedor = ".__aux .card .card-body form";
        
        $(contenedor).append('<div class="margin__bottom__10"><button class="mx-auto d-block btn btn-primary" type="button" title="Cambiar a Persona jurídica" onclick="javascript:userBean.cambio(this);">a <strong>Persona jurídica</strong> <i class="fas fa-exchange-alt"></i></button></div>');

        //contenedor,formato,valor = {},desactivado = [],funcion = {},data = null,visible = true
        aux.editor__X(contenedor,[{"id":""},{"nombre_mostrar":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
        aux.editor__X(contenedor,[{"nombre":"col col-12"}],null,null,{"nombre":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
        aux.editor__X(contenedor,[{"apellido":"col col-12"}],null,null,{"apellido":[{"onblur":"'userBean.concatenar(this,\"persona\")'"}]},[{"dato":"normal"}]);
        aux.editor__X(contenedor,[{"dni":"col col-12"}],null,null,null,[{"dato":"normal"}]);
        aux.editor__X(contenedor,[{"razon_social":"col col-12"}],null,null,{"razon_social":[{"onblur":"'userBean.concatenar(this,\"empresa\")'"}]},[{"dato":"juridica"}],false);
        aux.editor__X(contenedor,[{"cuit":"col col-12"}],null,null,null,[{"dato":"juridica"}],false);

        $(".__aux").find(".card .card-body form").append("<div class=\"foot\">");
        aux.editor__X_footer(".__aux .card .card-body form .foot",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":[{"onclick":"'userBean.cancelar(\""+e+"\")'"}],"data":null},"ASIGNAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}});
      break;
      case "id_moneda":
        aux = new Pyrus("moneda");

        $(".__aux").append("<div class=\"card\">");
        $(".__aux").find(".card").append("<h5 class=\"card-header card-title text-center\">NUEVA <strong>MONEDA</strong></h5>");
        $(".__aux").find(".card").append("<div class=\"card-body\">");
        $(".__aux").find(".card .card-body").append("<form method=\"POST\" onsubmit=\"event.preventDefault(); javascript:userBean.asignar(this,'"+e+"')\" novalidate>")
        aux.editor__(".__aux .card .card-body form");
        $(".__aux").find(".card .card-body form").append("<div class=\"foot row\">")
        $(".__aux").find(".card .card-body form").find(".foot.row").append("<div class=\"col col-6\"><button class=\"btn btn-block\" onclick=\"javascript:userBean.cancelar('"+e+"')\">Cancelar</button>");
        $(".__aux").find(".card .card-body").find(".foot.row").append("<div class=\"col col-6\"><button class=\"btn btn-block btn-success\" type=\"submit\">Asignar</button>");
      break;
    }
  }
}
userBean.concatenar = function(t,tipo) {
  if(tipo == "empresa")
    $(t).closest("form").find(".nombre_mostrar").val($(t).val())
  else {
    n = $(t).closest("form").find(".nombre").val();
    a = $(t).closest("form").find(".apellido").val();
    $(t).closest("form").find(".nombre_mostrar").val(n + " " + a);
  }
}
userBean.asignar = function(t,e) {
  console.log(e)
  var arr = $(t).serializeArray();
  console.log(arr)
  if(userBean.validar('.__aux')) {
    var id = aux.guardar();
    var o = $("<option/>", {value: id, text: arr[0].value});
    switch(e) {
      case "id_titular":
        var t = new Pyrus("titular");
        var titular = {};
        titular["id"] = "nulo";
        titular["id_persona"] = id;
        titular["id_user"] = user_datos["user_id"];
        //aux.query('guardar_uno_generico',{'entidad' : 'titular', 'objeto' : titular}, function(m) {}, function(m) {},null,false);
        idd = t.guardar("","",titular);

        var newState = new Option(arr[1].value, idd, true, true);
        break;
      case "id_banco":
        var _s = new Pyrus("sucursal");
        var id_dom = aux1.guardar();//DOMICILIO
        var tel = $(".telefono").val();
        var sucursal = {};
        sucursal["id"] = "nulo";
        sucursal["id_banco"] = id;
        sucursal["id_domicilio"] = id_dom;
        sucursal["telefono"] = tel;
        console.log(sucursal)
        _s.query('guardar_uno_generico',{'entidad' : 'sucursal', 'objeto' : sucursal}, function(m) {}, function(m) {},null,false);
        var newState = new Option(arr[2].value + " - " + arr[3].value, id, true, true);
      break;
      default:
        var newState = new Option(arr[0].value, id, true, true);
        break;
    }
    $("select." + e + " optgroup:last-child").append(newState).trigger("change");
    $(".__cuenta").find("input,select,button").removeAttr("disabled",true);
    $("select." + e).select2();

    $('.__aux').html('');
  }
}
userBean.cancelar = function(e) {
  if($("select."+e).val() == "-1")
    $("select."+e).val("").trigger("change");

  $(".__cuenta").find("input,select,button").removeAttr("disabled",true);
}
userBean.cancelar_despleg = function(f = false) {
  $("#add__cuenta").addClass("d-none");
  $("#add__cuenta").find("input[type=\"reset\"]").click();
  $("#add__cuenta").find(".select__2").val("").trigger("change");
  
  if(f) _p.editor__X_footer(".__cuenta .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":[{"onclick":"userBean.cancelar_despleg()"}],"data":null},"GUARDAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}});
}
	userBean.ready__ = function() {
		$(".dataTables_filter").addClass("d-flex justify-content-center align-items-center");
    // PYRUS
    _p.listador("cuenta");

    _p.editor__X(".__cuenta .modal-body",[{"id":""},{"id_user":""}],{"id_user":user_datos["user_id"]},null,null,null,false);
    _p.editor__X(".__cuenta .modal-body",[{"n_cuenta":"col col-6"},{"cbu":"col col-6"}]);
    _p.editor__X(".__cuenta .modal-body",[{"id_cooperativa":"col col-12"}],null,null,null,null,true,false,null,-1);
    _p.editor__X(".__cuenta .modal-body",[{"id_banco":"col col-12"}],null,null,null,null,true,false,null,-1);
    _p.editor__X(".__cuenta .modal-body",[{"id_moneda":"col col-12"}],null,null,null,null,true,false,null,-1);
    _p.editor__X(".__cuenta .modal-body",[{"id_titular":"col col-12"}],null,null,null,null,true,false,null,-1);
    
    _p.editor__X_footer(".__cuenta .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":[{"onclick":"userBean.cancelar_despleg()"}],"data":null},"GUARDAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}});
    $(".select__2").select2({width: 'resolve',placeholder: 'Seleccione',tags: true});
   // _p.editor__footer(".__cuenta",Abtn);
    
    $("body").on("focus",".has-error *",function() {
        $(this).parent().removeClass("has-error");
    });
    $("#div").addClass("d-none");
	}
	userBean.validarForm = function() {
    cuenta = new Pyrus("cuenta")
  	nro = $(".n_cuenta").val();
    //$("#div").removeClass("d-none");
  	existencia = true;
    if($(".__cuenta .id").val() == "")
      existencia = cuenta.existe("n_cuenta",nro);
    else {
      arr = cuenta.busqueda__arr("n_cuenta",nro);
      if(arr.length > 2) existencia = false;
    }
  	if(existencia){
  		if(userBean.validar(".__cuenta")) {
  		  cuenta.guardar(($(".__cuenta .id").val() == "" ? "nulo" : $(".__cuenta .id").val()),".__cuenta");
  		  
  		  cuenta.listador("cuenta");
  		  $("#add__cuenta").addClass("d-none");
        $("#add__cuenta").find("input[type='reset']").click();
        $("#add__cuenta").find("select.select__2").val("").trigger("change");

        location.reload();
  		  //_p.editor(".__cuenta","");
        //_p.editor__footer(".__cuenta",Abtn);
  		}
  	} else {
  		userBean.notificacion("número en uso","error");
  	}
    //$("#div").addClass("d-none");
  }
	$(document).ready(userBean.ready__());
    $('#modal').on('hidden.bs.modal', function (e) {
        $(this).find("input[type='reset']").click();
    });
    $('#modal_2').on('hidden.bs.modal', function (e) {
        $(this).find("input[type='reset']").click();
    });
}
</script>
