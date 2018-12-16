<?php //personas || LIBRADOR | PORTADOR | TITULAR ?>
<div id="menu" class="position__fixed">
	<div class="d-flex align-items-stretch">
		<aside class="background__ffffff border-right position-relative">
			<span class="p-2 position-absolute __btn_close_menu d-block d-sm-none"><i class="fas fa-hand-point-left"></i></span>
			<h3 class="text-center position-relative" id="user"><i class="fas fa-user-alt"></i> <span></span><small class="position-absolute" style="right: 5px; bottom: 0; color: #ccc"><i style="cursor: pointer;" title="Editar usuario" class="fab fa-whmcs"></i></small></h3>
			<div>
				<ul class="nav nav-pills flex-column">
					<li class="nav-item" id="link-cuentas"><a href="<?php echo __URL__ ?>cuentas/" class="nav-link" title=""><i class="fas fa-warehouse"></i> Cuentas</a></li>
					<li class="nav-item"><a href="<?php echo __URL__ ?>cheques/" class="nav-link" title=""><i class="fas fa-chart-areafas fa-money-check"></i> Cheques</a></li><li class="nav-item"></li>
					<li class="nav-item"><a href="<?php echo __URL__ ?>librador/" class="nav-link" title=""><i class="fas fa-address-card"></i> Librador</a></li>
					<li><hr/></li>
					<li class="nav-item" id="link-espejo"><a href="<?php echo __URL__ ?>espejo/" class="nav-link" title=""><i class="fas fa-columns"></i> Espejo</a></li>
					<li class="nav-item"><a href="<?php echo __URL__ ?>gestion_cheques/" class="nav-link" title=""><i class="fas fa-project-diagram"></i> Gestión de cheques</a></li>
					<li class="nav-item" id="link-informes"><a href="<?php echo __URL__ ?>index/" class="nav-link" title=""><i class="fas fa-chart-area"></i> Informes</a></li>
					<li class="nav-item" id="link-user"><a href="<?php echo __URL__ ?>usuarios/" class="nav-link" title="" onclick="event.preventDefault(); userBean.usuarios();"><i class="fas fa-users"></i> Usuarios</a></li>
				</ul>
			</div>
			<button class="btn btn-block btn-danger position-absolute logout">Salir <i class="fas fa-sign-out-alt"></i></button>
		</aside>
	</div>
</div>
<header class="background__2196f3 position__fixed">
	<div class="container-fluid d-flex align-items-stretch">
		<div class="__logo d-flex justify-content-start">
			<span class="align-self-center">COPECHECK</span>
		</div>
		<div id="btn_menu" class="__btn__menu d-flex justify-content-center cursor__pointer position__relative transition__800">
			<i class="fas fa-bars align-self-center"></i>
		</div>
		<div class="__btn__menu d-flex justify-content-center cursor__pointer position__relative transition__800 dropdown" data-toggle="dropdown" disabled="true">
			<i class="fas fa-bell align-self-center"></i><!-- badge-danger -->
			<span class="badge badge-secondary position-absolute" style="bottom: 12px; right: 5px">0</span>
			<div class="dropdown-menu dropdown-menu-left p-2 arrowTop">
              <p style="margin:0">Sin notificaciones</p>
            </div>
		</div>
	</div>
</header><!-- /header -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_chico">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body"></div>
		</div>
	</div>
</div>
<!-- BEGIN Edit - New modal -->
<div id="modal_otro" class="modal" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="modal-formUSER" onsubmit="event.preventDefault(); userBean.usuarioForm();" novalidate>
            	<div class="modal-body"></div>
            	<div class="modal-footer"></div>
            </form>
        </div>
    </div>
</div>
<!-- END Edit - New modal -->
<div id="div" class="position-absolute">
	<div class="spinner">
		<div class="bounce1"></div>
		<div class="bounce2"></div>
		<div class="bounce3"></div>
	</div>
</div>

<script>

var aux_user = new Pyrus();
var user_datos = null;
aux_user.query("obtener_sesion",null,function(m) { user_datos = m; },null,false);

if(user_datos["user_lvl"] != 1) $("#link-user,#link-cuentas").remove();
if(user_datos["user_lvl"] == 3) $("#link-espejo,#link-informes").remove();

$("body").on("keypress",".val_integer", function(e) {
    userBean.permite(e,'0123456789');
}).on("keypress",".val_float", function(e) {
    userBean.permite(e,'0123456789,.');
}).on("keypress",".val_string", function(e) {
    console.log(e)
    userBean.permite(e,'qwertyuiopasdfghjklñzxcvbnmáéíóú ,.-/()[]0123456789');
});

$("#user span").text(user_datos["user_name"])
	var click = 0;
	$("#menu aside").on("mouseover", function() {
		click = 1;
	}).on("mouseout", function() {
		click = 0;
	});

	$(".__btn_close_menu").click(function() { click = 0; })

	$("#menu").click( function() {
		if(!click) {
			$("#menu").fadeOut(200)
		}
	});
	$("header #btn_menu").click( function() {
	    $("#menu").fadeIn(200)
	});
	$(".logout").on("click",function(){
		x = new Pyrus();
		x.query("NS_matar_sesion",null,
		function(m){
			window.location = "/login/";
		});
	});

userBean.usuarios = function(){
	console.log("DDD")
 	$("#div").removeClass("d-none");
	userBean.notificacion("Cargando... espere","warning",false);
	setTimeout(function() { userBean.usuarios_behavior(); },700);
};
userBean.usuario = function() {
	var Abtn = [];
	var select = "<option value='1'>Administrador</option>";
	//select += "<option value='2'>Directivo</option>";
	select += "<option value='3'>Operador</option>";
	$("#modal_chico").modal("hide");
	user_OBJ = new Pyrus("usuarios");


	user_OBJ.editor__X("#modal_otro .modal-body",[{"id":""}],null,null,null,false);
	user_OBJ.editor__X("#modal_otro .modal-body",[{"user":"col col-12"}]);
	user_OBJ.editor__X("#modal_otro .modal-body",[{"pass":"col col-12"}]);
	user_OBJ.editor__X("#modal_otro .modal-body",[{"nivel":"col col-12"}]);

	$("#modal_otro").find(".modal-title").text("USUARIO NUEVO");

	user_OBJ.editor__X_footer("#modal .modal-footer",{"CANCELAR" : {"formato":"col col-6","tipo":"button","clase":"btn-danger btn-block","funcion":null,"data":[{"dismiss":"'modal'"}]},"GUARDAR" : {"formato":"col col-6","tipo":"submit","clase":"btn-success btn-block","funcion":null}})
	$("#modal_otro").modal("show");
}
userBean.usuarioForm = function() {
	if(userBean.validar("#modal_chico")) {
		user_OBJ = new Pyrus("usuarios");
		user = {};
		user["id"] = "nulo";
		user["user"] = $(".user").val();
		user["pass"] = userBean.MD5($(".pass").val());
		user["nivel"] = $(".nivel").val();

		user_OBJ.query('guardar_uno_generico',{'entidad' : 'usuarios', 'objeto' : user}, function(m) {}, function(m) {},null,false);
		userBean.notificacion("Usuario creado","success");
		$("#modal_chico").modal("hide");
	}
}
userBean.usuarios_behavior = function() {
	$("#modal_chico").find(".modal-title").text("Usuarios");
	$("#modal_chico").find(".modal-title").append(' <i style="cursor:pointer" class="fas fa-user-plus" onclick="userBean.usuario();"></i>')
	user_OBJ = new Pyrus("usuarios");
	arr_users = user_OBJ.busqueda__arrr();
	arr_nivel = {"1":"Administrador","2":"Directivo","3":"Operador"}
	html_modal = "";
	html_modal += "<table class='table'>";
	html_modal += "<thead>";
		html_modal += "<th>User</th>";
		html_modal += "<th>Nivel</th>";
		html_modal += "<th>Acciones</th>";
	html_modal += "</thead>";
	html_modal += "<tbody>";
	for(var x in arr_users) {
		html_modal += "<tr data-id='" + arr_users[x]["id"] + "'>";
			html_modal += "<td>" + arr_users[x]["user"] + "</td>";
			html_modal += "<td>" + arr_nivel[arr_users[x]["nivel"]] + "</td>";
			html_modal += "<td><i onclick=\"userBean.mostrarUSER(this)\" class=\"fas fa-user-edit text-warning\"></i> <i onclick=\"userBean.borrarUSER(this)\" class=\"fas fa-trash-alt text-danger\"></i></td>";
		html_modal += "</tr>";
	}
	html_modal += "</tbody>";
	html_modal += "</table>";
	$("#modal_chico").find(".modal-body").html(html_modal);
	$("#modal_chico").modal("show");
	$("#menu").fadeOut(200);
	$("#div").addClass("d-none")
}
userBean.mostrarUSER = function(t) {
	var id = $(t).closest("tr").data("id");
}
</script>