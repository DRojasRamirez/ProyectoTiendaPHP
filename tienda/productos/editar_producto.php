<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1 );    
        require "../util/conexion.php";

        session_start();
        if(isset($_SESSION["usuario"])) {
            echo "<h2 class='display-3 text-center'>Bienveni@" . $_SESSION["usuario"] .  "</h2>";
        } else {
            header("location: ../usuarios/iniciar_sesion.php"); // nunca usar esta funcion en el body o al menos siempre antes de que haya codigo
            exit;
        }
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

        <h1>Editar producto</h1>

        <?php

            //echo "<h1>" . $_GET["id_producto"] . "</h1>";

            $id_producto = $_GET["id_producto"];
            $sql ="SELECT * FROM productos WHERE id_producto = $id_producto";
            $resultado = $_conexion -> query($sql);

        

            while($fila = $resultado -> fetch_assoc()){
                $nombre = $fila["nombre"];
                $precio = $fila["precio"];
                $categoria = $fila["categoria"];
                $stock = $fila["stock"];
                $imagen = $fila["imagen"];
                $descripcion = $fila["descripcion"];
            }


            $sql = "SELECT * FROM categorias ORDER BY categoria";
            $resultado = $_conexion -> query($sql);
            $categorias = [];


            while($fila = $resultado -> fetch_assoc()){
                array_push($categorias, $fila["categoria"]);
            }

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $id_producto = $_POST["id_producto"];
                $tmp_nombre = $_POST["nombre"];
                $tmp_precio = $_POST["precio"];
                $tmp_categoria = $_POST["categoria"];
                $tmp_stock = $_POST["stock"];
                $tmp_descripcion = $_POST["descripcion"];

                if($tmp_nombre == ""){
                    $err_nombre = "El nombre es obligatorio";
                } else {
                    $patron = "/^[0-9A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/";
                    if(!preg_match($patron, $tmp_nombre)){
                        $err_nombre = "El nombre solo puede tener letras, numeros y espacios en blanco";
                    } else {
                        if(strlen($tmp_nombre) > 50 || strlen($tmp_nombre) < 2 ){
                            $err_nombre = "El nombre no puede tener más de 50 caracteres o menos de 2";
                        } else {
                            $tmp_nombre = ucwords(strtolower($tmp_nombre));
                            $nombre = $tmp_nombre;                     
                        }
                    }
                }

                if($tmp_precio == ""){
                    $err_precio = "El precio es obligatorio";
                } else {
                    $patron = "/^[0-9]{1,4}(\.[0-9]{1,2})?$/";
                    if(!preg_match($patron, $tmp_precio)){
                        $err_precio = "Formato de precio no valido, debe ingresar un valor numerico de máximo 6 digitos con dos decimales";
                    } else {
                        if($tmp_precio <= 0 || $tmp_precio > 9999.99){
                            $err_precio = "El precio debe de estar entre 0 (sin incluir) y 9999.99 máximo";
                        } else {
                            $precio = $tmp_precio;
                        }
                    }  
                }

                if($tmp_descripcion == ""){
                    $err_descripcion = "La descripcion es obligatoria";
                } else {
                    if(strlen($tmp_descripcion) > 255){
                        $err_descripcion = "La descripcion no puede tener más de 255 caracteres";
                    } else {
                        //$tmp_descripcion= ucwords(strtolower($tmp_descripcion));
                        $descripcion = $tmp_descripcion;                     
                    }
                }

                if($tmp_categoria == ""){
                    $err_categoria = "La categoria es obligatoria";
                } else {
                    if(strlen($tmp_categoria) > 30){
                        $err_categoria = "La categoría no puede tener más de 30 caracteres";
                    } else {
                        $tmp_categoria= ucwords(strtolower($tmp_categoria));
                        $categoria = $tmp_categoria;                     
                    }
                }
                

                if($tmp_stock == ""){
                    $stock = 0;
                } else {
                    $patron = "/^[0-9]{1,3}$/";
                    if(!preg_match($patron, $tmp_stock)){
                        $err_stock = "Formato de stock no valido, debe ingresar un valor numerico de máximo 3 digitos ";
                    } else {
                        if($tmp_stock < 0 || $tmp_stock > 999){
                            $err_stock = "El stock debe de estar entre 0 y 999 máximo";
                        } else {
                            $stock = $tmp_stock;
                        }
                    }  
                }

                if(isset($nombre) && isset($precio) && 
                 isset($descripcion) && isset($categoria) 
                  && isset($stock)){ 

                    $sql = "UPDATE productos SET
                    nombre = '$nombre',
                    precio = '$precio',
                    categoria = '$categoria',
                    stock = '$stock',
                    descripcion = '$descripcion'
                    WHERE id_producto = $id_producto
                    ";

                    $_conexion -> query($sql);

                } 

            }
            
        ?>        

        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo $nombre?>">
                <?php if(isset($err_nombre)) echo "<span class='error'>$err_nombre</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input type="text" class="form-control" name="precio" value="<?php echo $precio?>">
                <?php if(isset($err_precio)) echo "<span class='error'>$err_precio</span>" ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <br>
                <select name="categoria">
                <option value="<?php echo $categoria?>" selected hidden><?php echo $categoria?></option>
                <?php
                    foreach($categorias as $categoria){ ?>
                        <option value="<?php echo $categoria ?>">
                            <?php echo $categoria;?>
                        </option>
                 <?php   }
                ?>
                </select>
                <?php if(isset($err_categoria)) echo "<span class='error'>$err_categoria</span>" ?>
            </div> 
            <div class="mb-3">
                <label class="form-label">Stock</label>
                <input type="text" class="form-control" name="stock" value="<?php echo $stock?>">
                <?php if(isset($err_stock)) echo "<span class='error'>$err_stock</span>" ?>
            </div>
            <div class="mb-3"> 
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" value="<?php echo $descripcion?>"><?php echo $descripcion?></textarea>
                <?php if(isset($err_descripcion)) echo "<span class='error'>$err_descripcion</span>" ?>
            </div>
            <input type="hidden" name="id_producto" value="<?php echo $id_producto ?>">
            <input class="btn btn-primary" type="submit" value="Confirmar">
            <a class="btn btn-secondary" href="index.php">Volver a tabla</a>
        </form>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>