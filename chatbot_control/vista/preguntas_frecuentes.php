<?php
$pdo = new PDO('mysql:host=localhost;dbname=chatbot', 'root', '');
?>
<!DOCTYPE html>
<html>
    <head>
	<meta charset="UTF-8">
	<title>Chatbot Control</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<?php
	include('librerias.php');
	?>
	<script src="../controlador/funciones_preguntas_frecuentes.js"></script>
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
			<h4 class="modal-title" id="myModalLabel">Agregar pregunta frecuente</h4>
		    </div>
		    <div class="modal-body">
			<label hidden="">id_pregunta_f</label>
			<input hidden="" id="id_pregunta_f">
			<label>su_pregunta</label>
			<input type="text" id="su_pregunta" class="form-control input-sm" required="">
			<label>respuesta</label>
			<input type="text" id="respuesta" class="form-control input-sm" required="">
			<?php
$option = 'nombre';
$sql = 'SELECT id_usuario, nombre, celular FROM usuarios ORDER BY id_usuario DESC;';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<label for="usuario_id">usuario_id</label>
<select name="usuario_id" id="usuario_id" class="form-control input-sm" required="">
<?php
foreach ($rows as $row) {
?>
  <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['id_usuario']; ?> - <?php echo $row['nombre']; ?></option>
<?php
}
?>
</select>
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
			<input type="number" hidden="" id="id_pregunta_fu">
			<label>su_pregunta</label>
			<input type="text" id="su_preguntau" class="form-control input-sm" required="">
			<label>respuesta</label>
			<input type="text" id="respuestau" class="form-control input-sm" required="">
			<?php
$option = 'nombre';
$sql = 'SELECT id_usuario, nombre, celular FROM usuarios ORDER BY id_usuario DESC;';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<label for="usuario_idu">usuario_id</label>
<select name="usuario_idu" id="usuario_idu" class="form-control input-sm" required="">
<?php
foreach ($rows as $row) {
?>
  <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['id_usuario']; ?> - <?php echo $row['nombre']; ?></option>
<?php
}
?>
</select>
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
		$('#tabla').load('componentes/vista_preguntas_frecuentes.php');
	    });
	</script>
	<script type="text/javascript">
	    $(document).ready(function () {
		$('#guardarnuevo').click(function () {
		    id_pregunta_f = $('#id_pregunta_f').val();
		    su_pregunta = $('#su_pregunta').val();
		    respuesta = $('#respuesta').val();
		    usuario_id = $('#usuario_id').val();
		    agregardatos(id_pregunta_f, su_pregunta, respuesta, usuario_id);
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
<?php
$pdo = null;
