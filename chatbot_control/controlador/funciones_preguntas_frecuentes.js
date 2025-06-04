function agregardatos(id_pregunta_f, su_pregunta, respuesta, usuario_id){
    cadena = "id_pregunta_f=" + id_pregunta_f +
    "&su_pregunta=" + su_pregunta +
    "&respuesta=" + respuesta +
    "&usuario_id=" + usuario_id;

    accion = "insertar";
    mensaje_si = "Pregunta frecuente agregado con éxito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function agregaform(datos) {
    d = datos.split('||');
    $('#id_pregunta_fu').val(d[0]);
    $('#su_preguntau').val(d[1]);
    $('#respuestau').val(d[2]);
    $('#usuario_idu').val(d[3]);
}

function modificarRegistro(){
    id_pregunta_f = $('#id_pregunta_fu').val();
    su_pregunta = $('#su_preguntau').val();
    respuesta = $('#respuestau').val();
    usuario_id = $('#usuario_idu').val();
    cadena = "id_pregunta_f=" + id_pregunta_f +
    "&su_pregunta=" + su_pregunta +
    "&respuesta=" + respuesta +
    "&usuario_id=" + usuario_id;

    accion = "modificar";
    mensaje_si = "Pregunta frecuente  modificada con éxito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_pregunta_f) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_pregunta_f);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_pregunta_f) {
    cadena = "id_pregunta_f=" + id_pregunta_f;

    accion = "borrar";
    mensaje_si = "Pregunta frecuente borrada con éxito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/preguntas_frecuentes_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r > 0) {
            $('#tabla').load('../vista/componentes/vista_preguntas_frecuentes.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
