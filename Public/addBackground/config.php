<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>empresa.com.co</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preload" as="image" href="./assets/images/<?php echo $fila['background_image_path']; ?>?v=<?php echo time(); ?>" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .preview-button {
            transition: all 0.3s ease-in-out;
            display: inline-block;
            cursor: pointer;
        }

        .wizard-step {
            animation: fade 0.3s ease-in-out;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Modal Heading</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="index.php" method="POST" enctype="multipart/form-data">
                        <fieldset class="mb-3 mt-3">
                            <label for="image">image</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </fieldset>
                        <fieldset class="mb-3 mt-3">
                            <button type="submit">Subir Imagen</button>
                        </fieldset>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <!-- The Modal -->
    <div class="modal" id="myModalGlobo">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Modal Heading</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="globo.php" method="POST" enctype="multipart/form-data">
                        <fieldset class="mb-3 mt-3">
                            <label for="image">image de globo</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </fieldset>
                        <fieldset class="mb-3 mt-3">
                            <button type="submit">Subir Imagen de globo</button>
                        </fieldset>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <!-- MODAL -->
    <!-- Modal -->
    <div class="modal fade" id="myModalBtn" tabindex="-1" aria-labelledby="customizeBtnLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg rounded-4">

                <!-- Modal Header -->
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="customizeBtnLabel">🎨 Personaliza tu botón</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <!-- Vista previa -->
                    <div class="text-center mb-4">
                        <div id="colorBox" class="preview-button" style="background-color: #007bff; color: white; font-size: 1.2rem;">
                            Siguiente
                        </div>
                    </div>

                    <!-- Formulario Wizard -->
                    <form id="wizardForm">
                        <!-- Paso 1 -->
                        <div class="wizard-step" data-step="1">
                            <label for="backgroundColor" class="form-label">Color de fondo</label>
                            <input type="color" id="backgroundColor" name="backgroundColor" class="form-control form-control-color" value="#007bff">
                        </div>

                        <!-- Paso 2 -->
                        <div class="wizard-step d-none" data-step="2">
                            <label for="textColor" class="form-label">Color del texto</label>
                            <input type="color" id="textColor" name="textColor" class="form-control form-control-color" value="#ffffff">
                        </div>

                        <!-- Paso 3 -->
                        <div class="wizard-step d-none" data-step="3">
                            <label for="fontSize" class="form-label">Tamaño de fuente: <span id="fontSizeVal">1.2rem</span></label>
                            <input type="range" id="fontSize" name="fontSize" class="form-range" min="0.5" max="3" step="0.1" value="1.2">
                        </div>

                        <!-- Paso 4 -->
                        <div class="wizard-step d-none" data-step="4">
                            <label for="padding" class="form-label">Padding</label>
                            <input type="text" id="padding" name="padding" value="1rem 1.5rem" class="form-control">
                        </div>

                        <!-- Paso 5 -->
                        <div class="wizard-step d-none" data-step="5">
                            <label for="borderRadius" class="form-label">Border Radius: <span id="borderRadiusVal">32px</span></label>
                            <input type="range" id="borderRadius" name="borderRadius" class="form-range" min="0" max="100" step="1" value="32">
                        </div>

                        <!-- Paso 6 -->
                        <div class="wizard-step d-none" data-step="6">
                            <label for="border" class="form-label">Grosor del borde: <span id="borderVal">0px</span></label>
                            <input type="range" id="border" name="border" class="form-range" min="0" max="10" step="1" value="0">
                        </div>

                        <!-- Paso 7: Sombra -->
                        <div class="wizard-step d-none" data-step="7">
                            <fieldset class="border rounded p-3 mb-3">
                                <legend class="float-none w-auto px-2">Sombra</legend>

                                <div class="mb-2">
                                    <label for="shadowOffsetX" class="form-label">Desplazamiento X: <span id="shadowOffsetXVal">0px</span></label>
                                    <input type="range" id="shadowOffsetX" class="form-range" min="-50" max="50" value="0">
                                </div>
                            </fieldset>
                        </div>

                        <!-- Paso 8: Sombra -->
                        <div class="wizard-step d-none" data-step="8">
                            <fieldset class="border rounded p-3 mb-3">
                                <legend class="float-none w-auto px-2">Sombra</legend>

                                <div class="mb-2">
                                    <label for="shadowOffsetY" class="form-label">Desplazamiento Y: <span id="shadowOffsetYVal">4px</span></label>
                                    <input type="range" id="shadowOffsetY" class="form-range" min="-50" max="50" value="4">
                                </div>
                            </fieldset>
                        </div>

                        <!-- Paso 9: Sombra -->
                        <div class="wizard-step d-none" data-step="9">
                            <fieldset class="border rounded p-3 mb-3">
                                <legend class="float-none w-auto px-2">Sombra</legend>

                                <div class="mb-2">
                                    <label for="shadowBlur" class="form-label">Desenfoque: <span id="shadowBlurVal">15px</span></label>
                                    <input type="range" id="shadowBlur" class="form-range" min="0" max="100" value="15">
                                </div>
                            </fieldset>
                        </div>

                        <!-- Paso 10: Sombra -->
                        <div class="wizard-step d-none" data-step="10">
                            <fieldset class="border rounded p-3 mb-3">
                                <legend class="float-none w-auto px-2">Sombra</legend>

                                <div class="mb-2">
                                    <label for="shadowSpread" class="form-label">Propagación: <span id="shadowSpreadVal">0px</span></label>
                                    <input type="range" id="shadowSpread" class="form-range" min="-50" max="50" value="0">
                                </div>
                            </fieldset>
                        </div>

                        <!-- Paso 11: Sombra -->
                        <div class="wizard-step d-none" data-step="11">
                            <fieldset class="border rounded p-3 mb-3">
                                <legend class="float-none w-auto px-2">Sombra</legend>

                                <div class="mb-2">
                                    <label for="shadowAlpha" class="form-label">Transparencia: <span id="shadowAlphaVal">20%</span></label>
                                    <input type="range" id="shadowAlpha" class="form-range" min="0" max="100" value="20">
                                </div>
                            </fieldset>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" id="prevStep">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextStep">Siguiente</button>
                </div>

            </div>
        </div>
    </div>


    <?php
    include_once '../../business_modules/Core/conexion.php';
    $conn = conexion();
    $sql = 'SELECT 
                `user_id`, 
                `background_image_path`, 
                `avatar_image_path`, 
                `background_color`, 
                `button_color`, 
                `button_background_color` 
            FROM `user_preferences`';
    $result = mysqli_query($conn, $sql);
    while ($fila = mysqli_fetch_assoc($result)) {
    ?>
        <main class="container">
            <section class="hero">
                <img data-bs-toggle="modal" data-bs-target="#myModal" src="./assets/images/<?php echo $fila['background_image_path']; ?>?v=<?php echo time(); ?>" alt="Portada principal" class="hero-image" loading="lazy">
                <div data-bs-toggle="modal" data-bs-target="#myModalGlobo" class="globo"></div>
            </section>

            <div class="button-grid">
                <a data-bs-toggle="modal" data-bs-target="#myModalBtn" class="menu-button" aria-label="Ver operaciones asignadas" style="width: 200px; font-size: 30px;">Siguiente
            </div>
            <a href="../menu/index.html" class="menu-button" aria-label="Ver operaciones asignadas" style="width: 20px;font-family: fantasy;font-size: 30px;">
                < </a>
                    </div>
        </main>
    <?php
    }
    mysqli_close($conn);
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentStep = 1;
            const totalSteps = document.querySelectorAll('.wizard-step').length;
            const colorBox = document.getElementById('colorBox');

            const showStep = (step) => {
                document.querySelectorAll('.wizard-step').forEach(stepDiv => {
                    stepDiv.classList.add('d-none');
                    if (parseInt(stepDiv.dataset.step) === step) {
                        stepDiv.classList.remove('d-none');
                    }
                });

                // Botones
                document.getElementById('prevStep').disabled = (step === 1);
                document.getElementById('nextStep').textContent = (step === totalSteps) ? 'Finalizar' : 'Siguiente';
            };

            const updateStyles = () => {
                const bg = document.getElementById('backgroundColor').value;
                const text = document.getElementById('textColor').value;
                const fontSize = document.getElementById('fontSize').value;
                const padding = document.getElementById('padding').value;
                const borderRadius = document.getElementById('borderRadius').value;
                const border = document.getElementById('border').value;
                const shadowOffsetX = document.getElementById('shadowOffsetX').value;
                const shadowOffsetY = document.getElementById('shadowOffsetY').value;
                const shadowBlur = document.getElementById('shadowBlur').value;
                const shadowSpread = document.getElementById('shadowSpread').value;
                const shadowAlpha = document.getElementById('shadowAlpha').value;

                // Actualizar estilos del botón
                colorBox.style.backgroundColor = bg;
                colorBox.style.color = text;
                colorBox.style.fontSize = fontSize + 'rem';
                colorBox.style.padding = padding;
                colorBox.style.borderRadius = borderRadius + 'px';
                colorBox.style.borderWidth = border + 'px';
                colorBox.style.borderStyle = 'solid';
                colorBox.style.boxShadow = `${shadowOffsetX}px ${shadowOffsetY}px ${shadowBlur}px ${shadowSpread}px rgba(0, 0, 0, ${shadowAlpha / 100})`;

                // Actualizar valores visibles
                document.getElementById('fontSizeVal').textContent = fontSize + 'rem';
                document.getElementById('borderRadiusVal').textContent = borderRadius + 'px';
                document.getElementById('borderVal').textContent = border + 'px';
                document.getElementById('shadowOffsetXVal').textContent = shadowOffsetX + 'px';
                document.getElementById('shadowOffsetYVal').textContent = shadowOffsetY + 'px';
                document.getElementById('shadowBlurVal').textContent = shadowBlur + 'px';
                document.getElementById('shadowSpreadVal').textContent = shadowSpread + 'px';
                document.getElementById('shadowAlphaVal').textContent = shadowAlpha + '%';
            };

            // Avanzar paso
            document.getElementById('nextStep').addEventListener('click', () => {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                } else {
                    // Finaliza: puedes cerrar modal o emitir un evento
                    alert('🎉 Personalización completada');
                }
            });

            // Retroceder paso
            document.getElementById('prevStep').addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Listeners para cambios
            ['backgroundColor', 'textColor', 'fontSize', 'padding', 'borderRadius', 'border', 'shadowOffsetX', 'shadowOffsetY', 'shadowBlur', 'shadowSpread', 'shadowAlpha'].forEach(id => {
                document.getElementById(id).addEventListener('input', updateStyles);
            });

            // Mostrar primer paso y aplicar estilos iniciales
            showStep(currentStep);
            updateStyles();
        });
    </script>



</body>

</html>