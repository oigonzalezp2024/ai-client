function agregardatos(id_pregunta, su_pregunta, respuesta, persona_id){
    cadena = "id_pregunta=" + id_pregunta +
    "&su_pregunta=" + su_pregunta +
    "&respuesta=" + respuesta +
    "&persona_id=" + persona_id;

    accion = "insertar";
    mensaje_si = "Pregunta agregada con éxito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function agregaform(datos) {
    d = datos.split('||');
    $('#id_preguntau').val(d[0]);
    $('#su_preguntau').val(d[1]);
    $('#respuestau').val(d[2]);
    $('#persona_idu').val(d[3]);
}

function modificarRegistro(){
    id_pregunta = $('#id_preguntau').val();
    su_pregunta = $('#su_preguntau').val();
    respuesta = $('#respuestau').val();
    persona_id = $('#persona_idu').val();
    cadena = "id_pregunta=" + id_pregunta +
    "&su_pregunta=" + su_pregunta +
    "&respuesta=" + respuesta +
    "&persona_id=" + persona_id;

    accion = "modificar";
    mensaje_si = "Pregunta modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_pregunta) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_pregunta);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_pregunta) {
    cadena = "id_pregunta=" + id_pregunta;

    accion = "borrar";
    mensaje_si = "Pregunta borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/preguntas_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r > 0) {
            $('#tabla').load('../vista/componentes/vista_preguntas.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
