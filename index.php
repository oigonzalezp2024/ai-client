<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot de IA</title>
    <style>
        /* Estilos CSS para el chatbot */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .chat-container {
            width: 90%;
            max-width: 700px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 85vh; /* Altura para el contenedor del chat */
            min-height: 500px;
        }
        h1 {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            margin: 0;
            font-size: 1.8em;
            border-bottom: 1px solid #0056b3;
        }
        .chat-messages {
            flex-grow: 1; /* Permite que ocupe el espacio disponible */
            padding: 20px;
            overflow-y: auto; /* Permite scroll si hay muchos mensajes */
            display: flex;
            flex-direction: column;
            gap: 15px; /* Espacio entre mensajes */
        }
        .message {
            max-width: 80%;
            padding: 12px 18px;
            border-radius: 20px;
            line-height: 1.5;
            word-wrap: break-word; /* Rompe palabras largas */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        .user-message {
            align-self: flex-end; /* A la derecha */
            background-color: #007bff;
            color: white;
            border-bottom-right-radius: 5px; /* Esquina menos redondeada */
        }
        .ai-message {
            align-self: flex-start; /* A la izquierda */
            background-color: #e2e6ea;
            color: #333;
            border-bottom-left-radius: 5px; /* Esquina menos redondeada */
        }
        .chat-input {
            display: flex;
            padding: 15px 20px;
            border-top: 1px solid #eee;
            background-color: #f9f9f9;
        }
        .chat-input input[type="text"] {
            flex-grow: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 1em;
            margin-right: 10px;
            outline: none;
        }
        .chat-input button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .chat-input button:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }
        .chat-input button:active {
            transform: translateY(0);
        }
        .loading {
            text-align: center;
            font-style: italic;
            color: #666;
            padding: 10px;
        }
        /* Estilos para mensajes de estado */
        .status-message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .status-message.show {
            opacity: 1;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Estilos para el modal */
        .modal {
            display: none; /* Oculto por defecto */
            position: fixed; /* Posición fija */
            z-index: 1; /* Encima de todo */
            left: 0;
            top: 0;
            width: 100%; /* Ancho completo */
            height: 100%; /* Altura completa */
            overflow: auto; /* Habilitar scroll si es necesario */
            background-color: rgba(0,0,0,0.4); /* Fondo semi-transparente */
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            width: 80%;
            max-width: 400px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            text-align: center;
        }
        .modal-content label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }
        .modal-content input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .modal-content button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .modal-content button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .chat-container {
                height: 95vh;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h1>Asistente IA</h1>

        <div class="chat-messages" id="chat-messages">
            <div class="ai-message">¡Hola! Soy tu asistente de IA. Para empezar, necesito tu nombre y número de celular.</div>
        </div>

        <div class="chat-input">
            <input type="text" id="user-input" placeholder="Escribe tu mensaje aquí...">
            <button id="send-button">Enviar</button>
        </div>
    </div>

    <div id="user-info-modal" class="modal">
        <div class="modal-content">
            <h2>¡Hola!</h2>
            <p>Para poder ayudarte, por favor, ingresa tu nombre y número de celular.</p>
            <label for="modal-nombre">Nombre Completo:</label>
            <input type="text" id="modal-nombre" required placeholder="Ej: Oscar Ivan Gonzalez Peña">

            <label for="modal-celular">Número de Celular:</label>
            <input type="text" id="modal-celular" required placeholder="Ej: 3212962876">

            <button id="save-info-button">Comenzar Chat</button>
            <div id="modal-error-message" class="status-message status-error" style="margin-top: 15px; display: none;"></div>
        </div>
    </div>

    <script>
        const chatMessages = document.getElementById('chat-messages');
        const userInput = document.getElementById('user-input');
        const sendButton = document.getElementById('send-button');
        const userInfoModal = document.getElementById('user-info-modal');
        const modalNombreInput = document.getElementById('modal-nombre');
        const modalCelularInput = document.getElementById('modal-celular');
        const saveInfoButton = document.getElementById('save-info-button');
        const modalErrorMessage = document.getElementById('modal-error-message');

        let userName = '';
        let userCellphone = '';

        // Función para agregar un mensaje al chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            messageDiv.classList.add(sender === 'user' ? 'user-message' : 'ai-message');
            messageDiv.innerHTML = text; // Usar innerHTML para permitir etiquetas como <br>
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll al último mensaje
        }

        // Función para mostrar mensajes de estado temporales (éxito/error)
        function showTempStatusMessage(message, type) {
            const statusDiv = document.createElement('div');
            statusDiv.classList.add('status-message', `status-${type}`);
            statusDiv.textContent = message;
            chatMessages.prepend(statusDiv); // Añadir al principio del chat

            setTimeout(() => {
                statusDiv.classList.add('show');
            }, 10); // Pequeño retraso para que la transición funcione

            setTimeout(() => {
                statusDiv.classList.remove('show');
                statusDiv.style.opacity = '0';
                setTimeout(() => statusDiv.remove(), 500);
            }, 5000); // El mensaje se desvanecerá después de 5 segundos
        }


        // Mostrar el modal al cargar la página si no hay datos de usuario preestablecidos
        document.addEventListener('DOMContentLoaded', () => {
            // En un escenario real, aquí podrías cargar userName y userCellphone desde
            // sessionStorage/localStorage o una cookie si el usuario ya los ha ingresado.
            // Para este ejemplo, simplemente mostramos el modal si no están definidos.
            if (!userName && !userCellphone) { // Asumimos que están vacíos al inicio
                userInfoModal.style.display = 'flex'; // Usamos flex para centrar el contenido
                modalNombreInput.focus(); // Enfocar el primer campo del modal
            } else {
                // Si por alguna razón ya tuviéramos los datos (e.g., de una sesión previa)
                // userInfoModal.style.display = 'none';
                // addMessage(`¡Bienvenido de nuevo, ${userName}! ¿En qué puedo ayudarte?`, 'ai');
            }
        });

        // Manejar el botón "Comenzar Chat" del modal
        saveInfoButton.addEventListener('click', () => {
            const nombre = modalNombreInput.value.trim();
            const celular = modalCelularInput.value.trim();

            if (nombre && celular) {
                userName = nombre;
                userCellphone = celular;
                userInfoModal.style.display = 'none';
                addMessage(`¡Hola, ${userName}! Soy tu asistente de IA. ¿En qué puedo ayudarte?`, 'ai');
                userInput.focus(); // Enfocar el campo de entrada del chat
            } else {
                modalErrorMessage.textContent = 'Por favor, completa ambos campos.';
                modalErrorMessage.style.display = 'block';
                setTimeout(() => {
                    modalErrorMessage.style.display = 'none';
                }, 3000);
            }
        });

        // Manejar el envío de mensajes (botón y Enter)
        sendButton.addEventListener('click', sendMessage);
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        function sendMessage() {
            const question = userInput.value.trim();

            if (question === '') {
                return; // No enviar mensajes vacíos
            }

            if (!userName || !userCellphone) {
                // Si por alguna razón los datos no se cargaron, pedirlos de nuevo
                showTempStatusMessage('Por favor, ingresa tu nombre y celular para comenzar a chatear.', 'error');
                userInfoModal.style.display = 'flex';
                modalNombreInput.focus();
                return;
            }

            addMessage(question, 'user');
            userInput.value = ''; // Limpiar el input

            // Indicar que la IA está escribiendo
            const loadingMessage = document.createElement('div');
            loadingMessage.classList.add('loading');
            loadingMessage.textContent = 'La IA está pensando...';
            chatMessages.appendChild(loadingMessage);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Enviar la pregunta al script PHP usando AJAX
            fetch('chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `nombre=${encodeURIComponent(userName)}&celular=${encodeURIComponent(userCellphone)}&pregunta=${encodeURIComponent(question)}`
            })
            .then(response => {
                // Verificar si la respuesta HTTP es exitosa (código 2xx)
                if (!response.ok) {
                    // Si no es exitosa, intenta leer el texto para depurar y lanzar un error
                    return response.text().then(text => {
                        throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
                    });
                }
                // Si es exitosa, esperamos una respuesta JSON
                return response.json(); 
            })
            .then(data => {
                // Eliminar el mensaje de carga
                loadingMessage.remove();

                // Mostrar la respuesta de la IA
                if (data.status === 'success' || data.status === 'warning') {
                    addMessage(data.aiResponse, 'ai');
                    // Opcional: mostrar el mensaje de estado si es relevante para el usuario
                    if (data.message && data.status !== 'success') { // Solo muestra mensajes de estado si no es un éxito puro
                        showTempStatusMessage(data.message, data.status);
                    }
                } else {
                    // Manejar errores reportados por el backend (status: 'error')
                    addMessage(`Lo siento, hubo un problema: ${data.message}`, 'ai');
                    showTempStatusMessage(`Error: ${data.message}`, 'error');
                }

                // Si hay un debug_output, imprímelo en la consola para depuración
                if (data.debug_output) {
                    console.warn("DEBUG OUTPUT from chat.php:", data.debug_output);
                }
            })
            .catch(error => {
                // Eliminar el mensaje de carga
                loadingMessage.remove();
                console.error('Error al enviar mensaje o procesar respuesta:', error);
                addMessage('Lo siento, no pude conectar con el asistente en este momento o hubo un problema al procesar la respuesta. Inténtalo de nuevo más tarde.', 'ai');
                showTempStatusMessage(`Error de conexión/procesamiento: ${error.message}`, 'error');
            });
        }
    </script>
</body>
</html>
