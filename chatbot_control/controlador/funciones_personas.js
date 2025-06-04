function agregardatos(id_persona, nombre, celular, activo){
    cadena = "id_persona=" + id_persona +
    "&nombre=" + nombre +
    "&celular=" + celular +
    "&activo=" + activo;

    accion = "insertar";
    mensaje_si = "Persona agregada exitosamente.";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function agregaform(datos) {
    d = datos.split('||');
    $('#id_personau').val(d[0]);
    $('#nombreu').val(d[1]);
    $('#celularu').val(d[2]);
    $('#activou').val(d[3]);
}

function modificarRegistro(){
    id_persona = $('#id_personau').val();
    nombre = $('#nombreu').val();
    celular = $('#celularu').val();
    activo = $('#activou').val();
    cadena = "id_persona=" + id_persona +
    "&nombre=" + nombre +
    "&celular=" + celular +
    "&activo=" + activo;

    accion = "modificar";
    mensaje_si = "Registro modificado exitosamente.";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_persona) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_persona);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_persona) {
    cadena = "id_persona=" + id_persona;

    accion = "borrar";
    mensaje_si = "Registro borrado exitosamente.";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/personas_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r > 0) {
            $('#tabla').load('../vista/componentes/vista_personas.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
