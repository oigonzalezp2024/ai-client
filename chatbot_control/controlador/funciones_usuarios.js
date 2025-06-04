function agregardatos(id_usuario, nombre, celular, fecha_registro, activo, email){
    cadena = "id_usuario=" + id_usuario +
    "&nombre=" + nombre +
    "&celular=" + celular +
    "&fecha_registro=" + fecha_registro +
    "&activo=" + activo +
    "&email=" + email;

    accion = "insertar";
    mensaje_si = "Usuario agregado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}
function agregardatosI(id_usuario, nombre, celular, fecha_registro, activo, email){
    cadena = "id_usuario=" + id_usuario +
    "&nombre=" + nombre +
    "&celular=" + celular +
    "&fecha_registro=" + fecha_registro +
    "&activo=" + activo +
    "&email=" + email;

    accion = "insertar";
    mensaje_si = "Usuario agregado con exito";
    mensaje_no= "Error de registro";
    a_ajax_i(cadena, accion, mensaje_si, mensaje_no);
}
function agregaform(datos) {
    d = datos.split('||');
    $('#id_usuariou').val(d[0]);
    $('#nombreu').val(d[1]);
    $('#celularu').val(d[2]);
    $('#fecha_registrou').val(d[3]);
    $('#activou').val(d[4]);
    $('#emailu').val(d[5]);
}

function modificarRegistro(){
    id_usuario = $('#id_usuariou').val();
    nombre = $('#nombreu').val();
    celular = $('#celularu').val();
    fecha_registro = $('#fecha_registrou').val();
    activo = $('#activou').val();
    email = $('#emailu').val();
    cadena = "id_usuario=" + id_usuario +
    "&nombre=" + nombre +
    "&celular=" + celular +
    "&fecha_registro=" + fecha_registro +
    "&activo=" + activo +
    "&email=" + email;

    accion = "modificar";
    mensaje_si = "Usuario modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function modificarRegistro(){
    id_usuario = $('#id_usuariou').val();
    nombre = $('#nombreu').val();
    celular = $('#celularu').val();
    fecha_registro = $('#fecha_registrou').val();
    activo = $('#activou').val();
    email = $('#emailu').val();
    cadena = "id_usuario=" + id_usuario +
    "&nombre=" + nombre +
    "&celular=" + celular +
    "&fecha_registro=" + fecha_registro +
    "&activo=" + activo +
    "&email=" + email;

    accion = "modificar";
    mensaje_si = "Usuario modificado con exito";
    mensaje_no= "Error de registro";
    a_ajax_i(cadena, accion, mensaje_si, mensaje_no);
}

function preguntarSiNo(id_usuario) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatos(id_usuario);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function preguntarSiNoI(id_usuario) {
    var opcion = confirm("¿Esta seguro de eliminar el registro?");
    if (opcion == true) {
        alert("El registro será eliminado.");
        eliminarDatosI(id_usuario);
    } else {
        alert("El proceso de eliminación del registro ha sido cancelado.");
    }
}

function eliminarDatos(id_usuario) {
    cadena = "id_usuario=" + id_usuario;

    accion = "borrar";
    mensaje_si = "Usuario borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax(cadena, accion, mensaje_si, mensaje_no);
}

function eliminarDatosI(id_usuario) {
    cadena = "id_usuario=" + id_usuario;

    accion = "borrar";
    mensaje_si = "Usuario borrado con exito";
    mensaje_no= "Error de registro";
    a_ajax_i(cadena, accion, mensaje_si, mensaje_no);
}

function a_ajax(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/usuarios_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r > 0) {
            $('#tabla').load('../vista/componentes/vista_usuarios.php');
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}

function a_ajax_i(cadena, accion, mensaje_si, mensaje_no){
    $.ajax({
        type: "POST",
        url: "../modelo/usuarios_modelo.php?accion="+accion,
        data: cadena,
        success: function (r){
            if (r > 0) {
            $('#tabla').load('../vista/componentes/vista_usuarios_id.php?id='+r);
                alert(mensaje_si);
            } else {
                alert(mensaje_no);
            }
        }
    });
}
