<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index de Categorias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php

    error_reporting( E_ALL );
    ini_set( "display_errors", 1 );  

    require("../util/conexion.php");

/*
    session_start();
    if(isset($_SESSION["usuario"])) {
        echo "<h2>Bienveni@" . $_SESSION["usuario"] .  "</h2>";
    } else {
        header("location: usuarios/iniciar_sesion.php"); // nunca usar esta funcion en el body o al menos siempre antes de que haya codigo
        exit;
    }*/

    ?>
</head>
<body>

    <div class="container">

      <!--  <a class="btn btn-info" href="usuarios/cerrar_sesion.php">Cerrar sesion</a> -->

        <h1>Tabla de Categorías</h1>
        
        <?php

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $categoria = $_POST["categoria"];
                //echo "<h1>$id_anime</h1>";
                //borrar anime
                $sql = "DELETE FROM categorias WHERE categoria = '$categoria'";
                $_conexion -> query($sql);
            }

            $sql = "SELECT * FROM categorias";

            $resultado = $_conexion -> query($sql);

            /**
             * Aplicamos la funcion query a la conexion, donde se ejecuta la sentencia SQL hecha
             * 
             * El resultado se almacena en $resultado, que es un objeto con
             * estructura parecida a un array
            */


        ?>
        <a class="btn btn-secondary" href="nueva_categoria.php">Crear una nueva categoria</a><br><br>
        <table class="table table-striped table-hover align-middle">
            <thead class="table-info">
                <tr>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($fila = $resultado -> fetch_assoc()){ // trata el resultado como un array asociativo
                        echo "<tr>";
                        echo "<td>" . $fila["categoria"] . "</td>";
                        echo "<td>" . $fila["descripcion"] . "</td>";
                       ?> 
                        <td>
                            <a class="btn btn-warning"
                                href="editar_categoria.php?categoria=<?php echo $fila["categoria"] ?>">Editar</a>
                        </td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="categoria" value="<?php echo $fila["categoria"] ?>">
                                <input class="btn btn-danger" type="submit" value="Borrar">
                            </form>
                        </td>
                        <?php
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>