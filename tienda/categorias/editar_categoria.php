<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1 );    
        require "../util/conexion.php";

       /* session_start();
        if(isset($_SESSION["usuario"])) {
            echo "<h2>Bienveni@" . $_SESSION["usuario"] .  "</h2>";
        } else {
            header("location: usuarios/iniciar_sesion.php"); // nunca usar esta funcion en el body o al menos siempre antes de que haya codigo
            exit;
        }*/
    ?>
    <style>
        .error{
            color: red;
        }
    </style>
</head>
<body>
    <?php
        function depurar($entrada) : string {
            $salida = htmlspecialchars($entrada);
            $salida = trim($salida);
            $salida = stripslashes($salida);
            $salida = preg_replace("!\s+!", " ", $salida);        
            return $salida;
        }

    ?>

    <div class="container">

        <h1>Editar categoria</h1>

        <?php

            //echo "<h1>" . $_GET["id_anime"] . "</h1>";

            $categoria = $_GET["categoria"];
            $sql ="SELECT * FROM categorias WHERE categoria = '$categoria'";
            $resultado = $_conexion -> query($sql);

        

            while($fila = $resultado -> fetch_assoc()){
                $categoria = $fila["categoria"];
                $descripcion = $fila["descripcion"];               
            }


           /* $sql = "SELECT * FROM categorias ORDER BY nombre_estudio";
            $resultado = $_conexion -> query($sql);
            $estudios = [];


            while($fila = $resultado -> fetch_assoc()){
                array_push($estudios, $fila["nombre_estudio"]);
            }*/

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $categoria = $_POST["categoria"];
                $descripcion = $_POST["descripcion"];

                $sql = "UPDATE categorias SET
                    categoria = '$categoria',
                    descripcion = '$descripcion'
                    WHERE categoria = '$categoria'
                ";

                $_conexion -> query($sql);

            }
            
        ?>        

        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <input type="text" class="form-control" name="categoria" value="<?php echo $categoria ?>">
                <?php if(isset($err_categoria)) echo "<span class='error'>$err_categoria</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <input type="text" class="form-control" name="descripcion" value="<?php echo $descripcion ?>">
                <?php if(isset($err_descripcion)) echo "<span class='error'>$err_descripcion</span>" ?>
            </div>
           
            <input type="hidden" name="categoria" value="<?php echo $categoria ?>">
            <input class="btn btn-primary" type="submit" value="Confirmar">
            <a class="btn btn-secondary" href="index.php">Volver a tabla</a>
        </form>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>