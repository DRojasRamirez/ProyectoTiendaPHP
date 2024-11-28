<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php
        error_reporting( E_ALL );
        ini_set( "display_errors", 1 );    
        require "../util/conexion.php";

        session_start();
        if(!isset($_SESSION["usuario"])) {
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

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $tmp_usuario = $_POST["usuario"];
            $contrasena = $_POST["contrasena"];
            $tmp_nueva_contrasena = $_POST["nueva_contrasena"];

            $sql = "SELECT * FROM usuarios WHERE usuario = '$tmp_usuario'";
            $resultado = $_conexion -> query($sql);
            //var_dump($resultado);
            

            if($resultado -> num_rows == 0){
                $err_usuario = "El usuario $usuario no existe";
            } else {
                if($tmp_usuario == ""){
                    $err_usuario = "El usuario es obligatorio";
                } else {
                    $patron = "/^[0-9A-Za-zÁÉÍÓÚáéíóúñÑÜü]+$/";
                    if(!preg_match($patron, $tmp_usuario)){
                        $err_usuario = "Formato de usuario no valido, debe ingresar solo letras y números ";
                    } else {
                        if(strlen($tmp_usuario) > 15 || strlen($tmp_usuario) < 3){
                            $err_usuario = "El usuario debe de tener entre 3 y 15 carácteres";
                        } else {
                            $usuario = $tmp_usuario;
                        }
                    }  
                }
            }

            if($tmp_nueva_contrasena == ""){
                $err_nueva_contrasena = "La contraseña es obligatoria";
            } else {
                $patron = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
                if(!preg_match($patron, $tmp_nueva_contrasena)){
                    $err_contrasena = "La contraseña debe de tener al menos 8 caracteres, alguna letra en minúscula, en mayúscula y algun numero, puede tener carácteres especiales";
                } else {
                    if(strlen($tmp_nueva_contrasena) > 15){
                        $err_contrasena = "La contraseña no puede tener más de 15 carácteres";
                    } else {
                        $nueva_contrasena = $tmp_nueva_contrasena;
                    }
                }
            }
                //$datos_usuario = $resultado -> fetch_assoc();

                /**
                 *  Podemos acceder a:
                 * 
                 *  $datos_usuario["usuario"];
                 *  $datos_usuario["contrasena"];
                 */

            if(isset($usuario) && isset($contrasena) && isset($nueva_contrasena)){
                $datos_usuario = $resultado -> fetch_assoc();
                $verificar_contrasena = password_verify($contrasena, $datos_usuario["contrasena"]);
                //var_dump($acceso_concedido); Devuelve un true o false si coincide o no.
                if(!$verificar_contrasena){
                    
                    $err_contrasena = "La contraseña introducida es incorrecta";

                } else {

                    $nueva_contrasena_cifrada = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

                    $sql = "UPDATE usuarios SET
                    usuario = '$usuario',
                    contrasena = '$nueva_contrasena_cifrada'
                    WHERE usuario = '$usuario'
                    ";

                    $_conexion -> query($sql);

                    header("location: ../usuarios/cerrar_sesion.php");
                }
            }
                

        }
        


    ?>

    <div class="container">


    <h1>Cambiar contraseña</h1>


        <form class="col-6" action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" class="form-control" name="usuario" value="<?php echo $_SESSION["usuario"]?>">
                <?php if(isset($err_usuario)) echo "<span class='error'>$err_usuario</span>" ?>
            </div>
            <div class="mb-3"> 
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="contrasena">
                <?php if(isset($err_contrasena)) echo "<span class='error'>$err_contrasena</span>" ?>   
            </div>
            <div class="mb-3"> 
                <label class="form-label">Nueva Contraseña</label>
                <input type="password" class="form-control" name="nueva_contrasena">
                <?php if(isset($err_nueva_contrasena)) echo "<span class='error'>$err_nueva_contrasena</span>" ?>   
            </div>
            <div  class="mb-3">
                <input class="btn btn-primary" type="submit" value="Cambiar contraseña">            
            </div>
            
        </form>

        <div class="mb-3">
            <h3>Volver a tienda</h3>
            <a class="btn btn-secondary" href="../index.php">Tienda</a>
        </div>


    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>