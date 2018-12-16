<span class="body__struct background__2196f3 position__absolute"></span>
<div class="d-flex body__login position__absolute justify-content-center">
	<div class="form__container box__shadow__01 border__radius__4 background__ffffff position__relative align-self-center col col-sm-4 col-10">
		<form method="POST" id="frm_login" onsubmit="event.preventDefault(); userBean.validarForm();" novalidate>
			<div class="form-group">
				<label for="InputEmail">Usuario</label>
				<input type="text" class="form-control" id="InputEmail" aria-describedby="emailHelp" placeholder="Usuario" required="true">
			</div>
			<div class="form-group">
				<label for="InputPassword">Clave</label>
				<input type="password" class="form-control" id="InputPassword" placeholder="Clave" required="true">
			</div>
			<button type="submit" class="btn btn-primary">Ingresar</button>
		</form>
	</div>
</div>
<script>
	userBean.ready__ = function() {
	}
	userBean.detalle = function() { return false; };
	userBean.listarEntidad = function() { return false; };
	
	userBean.validarForm = function() {
	    if(userBean.validar()) {
	        userBean.login();
	    }
	}

	$(document).ready(userBean.ready__());
</script>
