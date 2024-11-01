/*
*   CONTROLADOR DE USO GENERAL EN TODAS LAS PÁGINAS WEB DEL SITIO PRIVADO.
*/

// Constante para establecer la ruta base del servidor.
const SERVER_URL = 'http://localhost/vehiclestore/api/';

/*
*   Función para mostrar un mensaje de confirmación.
*   Requiere la librería sweetalert para funcionar.
*   Parámetros: message (mensaje de confirmación).
*   Retorno: resultado de la promesa.
*/
const confirmAction = (message) => {
    return swal({
        title: 'Advertencia',
        text: message,
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            cancel: {
                text: 'No',
                value: false,
                visible: true
            },
            confirm: {
                text: 'Sí',
                value: true,
                visible: true
            }
        }
    });
}

/*
*   Función asíncrona para manejar los mensajes de notificación al usuario.
*   Requiere la librería sweetalert para funcionar.
*   Parámetros: type (tipo de mensaje), text (texto a mostrar), timer (uso de temporizador) y url (valor opcional con la ubicación de destino).
*   Retorno: ninguno.
*/
const sweetAlert = async (type, text, timer, url = null) => {
    // Se compara el tipo de mensaje a mostrar.
    let title, icon;
    switch (type) {
        case 1:
            title = 'Éxito';
            icon = 'success';
            break;
        case 2:
            title = 'Error';
            icon = 'error';
            break;
        case 3:
            title = 'Advertencia';
            icon = 'warning';
            break;
        case 4:
            title = 'Aviso';
            icon = 'info';
            break;
    }
    // Se define un objeto con las opciones principales para el mensaje.
    let options = {
        title: title,
        text: text,
        icon: icon,
        closeOnClickOutside: false,
        closeOnEsc: false,
        button: {
            text: 'Aceptar'
        }
    };
    // Se verifica el uso del temporizador.
    if (timer) options.timer = 3000;
    // Se muestra el mensaje.
    await swal(options);
    // Se direcciona a una página web si se indica.
    if (url) location.href = url;
}

/*
*   Función asíncrona para cargar las opciones en un select de formulario.
*   Parámetros: filename (nombre del archivo), action (acción a realizar), select (identificador del select en el formulario) y filter (dato opcional para seleccionar una opción o filtrar los datos).
*   Retorno: ninguno.
*/
const fillSelect = async (filename, action, select, filter = undefined) => {
    const FORM = (typeof (filter) === 'object') ? filter : null;
    const DATA = await fetchData(filename, action, FORM);
    let content = '';
    if (DATA.status) {
        content += '<option value="" selected>Seleccione una opción</option>';
        DATA.dataset.forEach(row => {
            const value = Object.values(row)[0];
            const text = Object.values(row)[1];
            const SELECTED = (typeof (filter) === 'number') ? filter : null;
            if (value !== SELECTED) {
                content += `<option value="${value}">${text}</option>`;
            } else {
                content += `<option value="${value}" selected>${text}</option>`;
            }
        });
    } else {
        content += '<option>No hay opciones disponibles</option>';
    }
    document.getElementById(select).innerHTML = content;
}

/*
*   Función asíncrona para cerrar la sesión del usuario.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const logOut = async () => {
    const RESPONSE = await confirmAction('¿Está seguro de cerrar la sesión?');
    if (RESPONSE) {
        const DATA = await fetchData(USER_API, 'logOut');
        if (DATA.status) {
            sweetAlert(1, DATA.message, true, 'index.html');
        } else {
            sweetAlert(2, DATA.exception, false);
        }
    }
}

/*
*   Función asíncrona para intercambiar datos con el servidor.
*   Parámetros: filename (nombre del archivo), action (accion a realizar) y form (objeto opcional con los datos que serán enviados al servidor).
*   Retorno: constante tipo objeto con los datos en formato JSON.
*/
const fetchData = async (filename, action, form = null) => {
    const OPTIONS = {};
    if (form) {
        OPTIONS.method = 'post';
        OPTIONS.body = form;
    } else {
        OPTIONS.method = 'get';
    }
    try {
        const PATH = new URL(SERVER_URL + filename);
        PATH.searchParams.append('action', action);
        const RESPONSE = await fetch(PATH.href, OPTIONS);
        return await RESPONSE.json();
    } catch (error) {
        console.log(error);
    }
}
