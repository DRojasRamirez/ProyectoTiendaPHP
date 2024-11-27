<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php

    error_reporting( E_ALL );
    ini_set( "display_errors", 1 );  

    require("./util/conexion.php");


    /*session_start();
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

     <a class="btn btn-info" href="./usuarios/iniciar_sesion.php">Iniciar sesion</a> 

        <h1>Tabla de Productos</h1>
        
        <?php

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $id_producto = $_POST["id_producto"];
                //echo "<h1>$id_producto</h1>";
                //borrar producto
                $sql = "DELETE FROM productos WHERE id_producto = $id_producto";
                $_conexion -> query($sql);
            }

            $sql = "SELECT * FROM productos";

            $resultado = $_conexion -> query($sql);

            /**
             * Aplicamos la funcion query a la conexion, donde se ejecuta la sentencia SQL hecha
             * 
             * El resultado se almacena en $resultado, que es un objeto con
             * estructura parecida a un array
            */


        ?>
       <!-- <a class="btn btn-secondary" href="nuevo_producto.php">Crear un nuevo producto</a><br><br> -->
        <table class="table table-striped table-hover align-middle">
            <thead class="table-info">
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Categoria</th>
                    <th>Stock</th>
                    <th>Descripción</th>
                    <th>Imagen</th>  
                </tr>
            </thead>
            <tbody>
                <?php
                    while($fila = $resultado -> fetch_assoc()){ // trata el resultado como un array asociativo
                        echo "<tr>";
                        echo "<td>" . $fila["nombre"] . "</td>";
                        echo "<td>" . $fila["precio"] . "</td>";
                        echo "<td>" . $fila["categoria"] . "</td>";
                        echo "<td>" . $fila["stock"] . "</td>";
                        echo "<td>" . $fila["descripcion"] . "</td>";
                       ?> 
                        <td>
                            <img width="100" height="200" src="<?php echo $fila["imagen"]?>">
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