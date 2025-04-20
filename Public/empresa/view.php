<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Empresa.com.co</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preload" as="image" href="../addBackground/assets/images/<?php echo $fila['background_image_path']; ?>?v=<?php echo time(); ?>" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
                <img data-bs-toggle="modal" data-bs-target="#myModal" src="../addBackground/assets/images/<?php echo $fila['background_image_path']; ?>?v=<?php echo time(); ?>" alt="Portada principal" class="hero-image" loading="lazy">
                <div class="globo"></div>
            </section>

            <div class="button-grid">
            <a href="../addBackground/config.php" class="menu-button"
            aria-label="Ver operaciones asignadas">Configuración</a>
            <a href="../menu/index.html" class="menu-button" aria-label="Ver operaciones asignadas" style="width: 20px;font-family: fantasy;font-size: 30px;">
                    < </a>
            </div>
        </main>
    <?php
    }
    mysqli_close($conn);
    ?>
</body>

</html>