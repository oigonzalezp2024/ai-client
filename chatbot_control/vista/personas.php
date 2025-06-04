<!DOCTYPE html>
<html>
    <head>
	<meta charset="UTF-8">
	<title>Chatbot Control</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php
	include('librerias.php');
	?>
	<script src="../controlador/funciones_personas.js"></script>
    </head>
    <body id="body">
	<?php
	include 'header.php';
	?>
	<div class="container">
	    <div id="tabla"></div>
	</div>
	<!-- MODAL PARA INSERTAR REGISTROS -->
	<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="myModalLabel">Agregar persona</h4>
		    </div>
		    <div class="modal-body">
			<label hidden="">id_persona</label>
			<input hidden="" id="id_persona">
			<label>nombre</label>
			<input type="text" id="nombre" class="form-control input-sm" required="">
			<label>celular</label>
			<input type="text" id="celular" class="form-control input-sm" required="">
			<label>activo</label>
			<input type="number" id="activo" class="form-control input-sm" required="">
			</div>
		    <div class="modal-footer">
			<button type="button" class="btn btn-primary" data-dismiss="modal" id="guardarnuevo">
			    Agregar
			</button>
		    </div>
		</div>
	    </div>
	</div>
	<!-- MODAL PARA EDICION DE DATOS-->
	<div class="modal fade" id="modalEdicion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="myModalLabel">Actualizar datos</h4>
		    </div>
		    <div class="modal-body">
			<input type="number" hidden="" id="id_personau">
			<label>nombre</label>
			<input type="text" id="nombreu" class="form-control input-sm" required="">
			<label>celular</label>
			<input type="text" id="celularu" class="form-control input-sm" required="">
			<label>activo</label>
			<input type="number" id="activou" class="form-control input-sm" required="">
			</div>
		    <div class="modal-footer">
			<button type="button" class="btn btn-warning" data-dismiss="modal" id="actualizadatos">
			    Actualizar
			</button>
		    </div>
		</div>
	    </div>
	</div>
	<script type="text/javascript">
	    $(document).ready(function () {
		$('#tabla').load('componentes/vista_personas.php');
	    });
	</script>
	<script type="text/javascript">
	    $(document).ready(function () {
		$('#guardarnuevo').click(function () {
		    id_persona = $('#id_persona').val();
		    nombre = $('#nombre').val();
		    celular = $('#celular').val();
		    activo = $('#activo').val();
		    agregardatos(id_persona, nombre, celular, activo);
		});
		$('#actualizadatos').click(function () {
		    modificarRegistro();
		});
	    });
	</script>
	<?php
	include './footer.php';
	?>
    </body>
</html>
