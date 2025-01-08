<?php
session_start();
ob_start();
include 'conexion.php';


if(!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$action = $_POST['action'];
	
	if($action == 'agregar'){
	   $producto = $_POST['producto'];
	   $cantidad = $_POST['cantidad'];
	   $precio = $_POST['precio'];
	   
	   $stmt = $conn->prepare("INSERT INTO ventas (producto, cantidad, precio) VALUES (?, ?, ?)");
	   $stmt->bind_param("sid", $producto, $cantidad, $precio);
	   
	   if($stmt->execute()){
		   echo "<script>window.location.href='ventas.php';</script>";
		   exit();
		   
	   }else{
		   echo "Error al agregar la venta: " . $conn->error;
	   }
	   
	   $stmt->close();
	}elseif ($action == 'modificar') {
		$id = $_POST['id'];
		$producto = $_POST['producto'];
		$cantidad = $_POST['cantidad'];
		$precio = $_POST['precio'];
		
		$stmt = $conn->prepare("UPDATE ventas SET producto=?, cantidad=?, precio=? WHERE id=?");
		$stmt->bind_param("sidi", $producto, $cantidad, $precio, $id);
		
		if ($stmt->execute()) {
			echo "<script>window.location.href='ventas.php';</script>";
			exit();
		}else {
			echo "Error al modificar la venta: " . $conn->error;
		}
		
		$stmt->close();
	} elseif ($action == 'eliminar') {
		$id = $_POST['id'];
		
		$stmt = $conn->prepare("DELETE FROM ventas WHERE id=?");
		$stmt->bind_param("i", $id);
		
		if($stmt->execute()) {
			echo "<script>window.location.href='ventas.php';</script>";
			exit();
		}else {
			echo "Error al eliminar la venta: " . $conn->error;
		}
		
		$stmt-close();
	}
}

$result = $conn->query("SELECT * FROM ventas");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="style.css">
<meta charset="UTF-8">
<title>Sistema De Ventas</title>
</head>
<div>

</div>
<body>
<br>
<br>
<h2>BIENVENIDO, <?php echo htmlspecialchars($_SESSION['username']);?></h2>
<form method="POST" action="ventas.php">
<input type="hidden" name="action" value="agregar">
<label>Producto:</label>
<input type="text" name="producto" required>
<label>Cantidad</label>
<input type="number" name="cantidad" required>
<label>Precio:</label>
<input type="number" step="0.01" name="precio" required>
<button type="submit" class="boton">AGREGAR VENTA</button>
</form>

<table border="1">
<tr>
<th>Id</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Precio</th>
<th>Fecha</th>
<th>Acciones</th>
</tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<form method="POST" action="ventas.php">
<td><?php echo htmlspecialchars($row['id']); ?></td>
<td><input type="text" name="producto" value="<?php echo htmlspecialchars($row['producto']); ?>" required></td>
<td><input type="number" name="cantidad" value="<?php echo htmlspecialchars($row['cantidad']); ?>" required></td>
<td><input type="number" step="0.01" name="precio" value="<?php echo htmlspecialchars($row['precio']); ?>" required></td>
<td><?php echo htmlspecialchars($row['fecha']); ?></td>
<td><input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
<button type="submit" name="action" value="modificar">MODIFICAR</button>
<button type="submit" name="action" value="eliminar"</button>ELIMINAR</button>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>
<br>
<br>
<a href="logout.php">CERRAR SESIÓN</a>
<br>
<br>
</body>
</html>