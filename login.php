<?php
session_start();
include 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = $_POST['username'];
	$password = md5($_POST['password']);


	$sql = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'";
	$result = $conn->query($sql);
	if($result->num_rows >0){
		$_SESSION['username'] = $username;
		header("Location: ventas.php");
		exit();
	} else{
		echo "nombre de usuario o contraseña incorrectos";
	}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="estilo_login.css">
<meta charset="UTF-8">
<title>LOGIN</title>
</head>
<body>
<div>

</div>
<br>
<br>
<h2>INICIAR SESIÓN</h2>
<form method="POST" action="login.php">
<label> USUARIO: </label>
<input type="text" name="username" required>
<label>CONTRASEÑA</label>
<input type="password" name="password" required>
<button type="submit" class="boton"> INGRESAR </button>
</form>
</body>
</html>