<?php 
    define("RUTA_HTTP","../");
    include_once("component/head.php");
?>
<script>
    var click = 0;
</script>
<body>
    <div id="menu" class="position__fixed">
        <div class="d-flex align-items-stretch">
            <aside class="background__ffffff">
                <h3 class="text-center">MENU</h3>
            </aside>
        </div>
    </div>

    <header class="background__2196f3">
        <div class="container-fluid d-flex align-items-stretch">
            <div class="__logo background__ffffff d-flex justify-content-center">
                <span class="align-self-center">LOGO c/flex</span>
            </div>
            <div class="__btn__menu d-flex justify-content-center cursor__pointer position__relative transition__800">
                <i class="fas fa-bars align-self-center"></i>
            </div>
        </div>
    </header><!-- /header -->
    <section>

        <!-- BEGIN cuerpa -->
        <div class="container-fluid d-flex justify-content-center">
            <div>
                <a href="#" onclick="javascript:userBean.detalle('nulo');">Nuevo Registro</a>
            </div>
            <!-- BEGIN tabla listador -->
            <div class="flex__body col-6">

                <table id="tabla">

                </table>


            </div>
            <!-- END tabla listador -->
            
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
                        <script>

                        </script>
                    </div>
                </div>
            </div>    
            <!-- END Edit - New modal -->
            
        </div>
        <!-- END cuerpa -->


    </section>
    <script>
        $(document).ready(function () {
            $("#menu aside").on("mouseover", function () {
                click = 1;
            }).on("mouseout", function () {
                click = 0;
            });

            $("#menu").click(function () {
                if (!click) {
                    $("#menu").fadeOut(600)
                }
            });
            
            // cargo los Pyrus
            window.entidad = "cheque";
            Pyrus.init();
            //Pyrus.listarEntidad(window.entidad,"#tabla",userBean.detalle);
            Pyrus.listarEntidad("#tabla");
            Pyrus.crearEditor(window.entidad,".modal-body");
            
        });
        $("header .__btn__menu").click(function () {
            $("#menu").fadeIn(600)
        });
    </script>
</body>
</html>