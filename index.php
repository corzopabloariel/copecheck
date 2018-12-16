<?php require_once("php/config.php"); ?>
<html dir="ltr">
	<head>
    <title>..:: COOPE Cheque ::..</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/bootstrap.css" >
		<link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/fontawesome/fontawesome-all.css" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/jquery-ui.css" >
		<!-- CSS dataTables -->
	    <link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/dataTables.jqueryui.css">
	    <link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/autoFill.jqueryui.css" >
	    <link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/buttons.dataTables.min.css" >
		<!-- / CSS dataTables -->
		<!-- CSS messagebox|lobibox|selec2 -->
		<link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/lobibox.css" >
		<link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/messagebox.css" >
		<link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/select2.min.css" >
		<!-- / CSS lobibox -->
		<link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/style.css" >

		<link rel="stylesheet" type="text/css" href="<?php echo __URL__; ?>css/bootstrap-toggle.css">

		<!-- ____________ -->
	    <script src="<?php echo __URL__; ?>js/jquery-3.2.1.min.js"></script>
	    <script src="<?php echo __URL__; ?>js/popper.min.js"></script>
	    <script src="<?php echo __URL__; ?>js/jquery-ui.js"></script>

	    <!-- messagebox|lobibox|selec2 -->
	    <script src="<?php echo __URL__; ?>js/lobibox.js"></script>
	    <script src="<?php echo __URL__; ?>js/messagebox.js"></script>
	    <script src="<?php echo __URL__; ?>js/select2.full.js"></script>
	    <!-- / messagebox|lobibox|selec2 -->
		<!-- dataTables -->
		<script src="<?php echo __URL__; ?>js/jquery.dataTables.min.js"></script>
		<script src="<?php echo __URL__; ?>js/dataTables.autoFill.min.js"></script>
		<script src="<?php echo __URL__; ?>js/dataTables.buttons.min.js"></script>
		<script src="<?php echo __URL__; ?>js/dataTables.bootstrap4.min.js"></script>
		<script src="<?php echo __URL__; ?>js/dataTables.select.min.js"></script>
		<script src="<?php echo __URL__; ?>js/buttons.bootstrap4.min.js"></script>
		<script src="<?php echo __URL__; ?>js/buttons.flash.min.js"></script>
		<script src="<?php echo __URL__; ?>js/jszip.min.js" ></script>
		<script src="<?php echo __URL__; ?>js/pdfmake.min.js" ></script>
		<script src="<?php echo __URL__; ?>js/vfs_fonts.js" ></script>
		<script src="<?php echo __URL__; ?>js/buttons.html5.min.js" ></script>
		<script src="<?php echo __URL__; ?>js/buttons.print.min.js" ></script>
		<!-- / dataTables -->
	    <!-- Pyrus Bean -->
	    <script src="<?php echo __URL__; ?>js/pyrusBean.js"></script>
	    <script src="<?php echo __URL__; ?>js/toolbox.js"></script>
	    <script src="<?php echo __URL__; ?>js/pyrusToolbox.js"></script>
		<script src="<?php echo __URL__; ?>js/userBean.js"></script>
	    <!-- / Pyrus Bean -->
  	</head>
	<body>
	<?php
	define("RUTA_HTTP","/");
	$page = $_GET["__p"];
	if($page != "login/") include_once("view/component/nav.php");
	switch ($page) {
		// case '':
		case 'usuarios/': include_once("view/user.php"); break;

		case 'index/': include_once("view/index.php"); break;
		case 'login/': include_once("view/login.php"); break;
		case 'cheques/': include_once("view/cheques.php"); break;
		case 'gestion_cheques/egreso/': include_once("view/algo.php"); break;
		case 'gestion_cheques/ingreso/': include_once("view/algo2.php"); break;
		case 'gestion_cheques/ingreso_2/': include_once("view/algo2_2.php"); break;

		case 'gestion_cheques/': include_once("view/gestion.php"); break;
		case 'gestion_cheques_2/': include_once("view/gestion_2.php"); break;
		case 'cuentas/': include_once("view/cuentas.php"); break;
		case 'bancos/': include_once("view/bancos.php"); break;
		case 'espejo/': include_once("view/espejo_gral.php"); break;
		case 'espejo/nuevo/': include_once("view/espejo.php"); break;
		/* <PERSONAS> */
		/*
		 * VISTAS NO VISIBLES EN EL MENU
		 */
		case 'librador/': include_once("view/librador.php"); break;
		case 'portador/': include_once("view/portador.php"); break;
		case 'titular/': include_once("view/titular.php"); break;
		/* </PERSONAS> */
		case 'cuentas_test/': include_once("view/cuentas_test.php"); break; // prueba
		default: include_once("view/404.php"); break;
	}
	/*
	 * var val = objetos.find( function(item) { return item.key === 1232 } );
	 * var v = $.grep(objetos, function(obj){ return obj.key === 1234; })[0]; alert(v);
	 */
	?>
	<script src="<?php echo __URL__; ?>js/bootstrap.js"></script>
	<script src="<?php echo __URL__; ?>js/bootstrap-toggle.js"></script>
	<script src="<?php echo __URL__; ?>js/jquery.priceformat.js"></script>
	<script>
		var is_root =/^\/(?:|index\.aspx?)$/i.test(location.pathname);

		x = new Pyrus();
		x.query("obtener_sesion",null,
			function(){ console.log("sesion correcta"); },
			function(){ 
				console.log("sesion no iniciada"); 
				if(window.location.href.indexOf("redireccion") == -1)
					window.location = "/login/#redireccion";
				});
	</script>
	</body>
</html>
