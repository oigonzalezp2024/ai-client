<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Persona y Pregunta</title>
    <style>
        /* Estilos CSS para un diseño básico y responsivo */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1, h2 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: calc(100% - 24px); /* Ajuste para el padding */
            box-sizing: border-box; /* Incluye padding y borde en el ancho */
        }
        textarea {
            resize: vertical; /* Permite redimensionar verticalmente */
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            align-self: center; /* Centra el botón */
            width: auto;
            min-width: 150px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            opacity: 0; /* Inicialmente oculto */
            transition: opacity 0.5s ease;
        }
        .message.show {
            opacity: 1; /* Mostrar al aplicar clase */
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        /* Media Queries para responsividad básica */
        @media (max-width: 768px) {
            .container {
                margin: 20px 10px;
                padding: 20px;
            }
            input[type="submit"] {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Personas y Preguntas</h1>

        <?php
        // Recuperamos los parámetros de la URL enviados por chat.php
        $status = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : '';
        $message = isset($_GET['message']) ? htmlspecialchars(urldecode($_GET['message'])) : ''; // Decodificamos el mensaje

        if ($message):
            $statusClass = ($status === 'success') ? 'success' : 'error';
        ?>
            <div id="status-message" class="message <?php echo $statusClass; ?>">
                <?php echo $message; ?>
            </div>
            <script>
                // JavaScript para mostrar el mensaje con una pequeña animación y luego desvanecerlo
                document.addEventListener('DOMContentLoaded', function() {
                    const msg = document.getElementById('status-message');
                    if (msg) {
                        msg.classList.add('show'); // Añadir clase para mostrar con transición
                        setTimeout(() => {
                            msg.classList.remove('show'); // Desvanecer después de un tiempo
                            msg.style.opacity = '0'; // Asegurar que desaparezca
                            setTimeout(() => msg.remove(), 500); // Eliminar del DOM después de la transición
                        }, 5000); // El mensaje se desvanecerá después de 5 segundos
                    }
                });
            </script>
        <?php endif; ?>

        <form action="chat.php" method="post">
            <h2>Datos de la Persona</h2>

            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre"
                   value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : ''; ?>"
                   required placeholder="Ej: Oscar Ivan Gonzalez Peña">

            <label for="celular">Número de Celular:</label>
            <input type="text" id="celular" name="celular"
                   value="<?php echo isset($_GET['celular']) ? htmlspecialchars($_GET['celular']) : ''; ?>"
                   required placeholder="Ej: 3212962876">

            <h2>Datos de la Pregunta</h2>
            <label for="pregunta">Tu Pregunta:</label>
            <textarea id="pregunta" name="pregunta" rows="4"
                      required placeholder="Ej: ¿Cuál es la capital de Colombia?"><?php echo isset($_GET['pregunta']) ? htmlspecialchars($_GET['pregunta']) : ''; ?></textarea>

            <input type="submit" value="Guardar Datos">
        </form>
    </div>
</body>
</html>