<?php
$servername = "localhost";
$username = "root"; // Cambia esto si tienes otra configuración
$password = ""; // Cambia esto si tienes otra configuración
$dbname = "mi_aplicacion"; // Asegúrate de que esto coincida con tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$accion = "";
$resultado = [];

// Manejar los botones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];
    
    if ($accion == 'ver_usuarios') {
        $sql = "SELECT * FROM usuarios";
        $resultado = $conn->query($sql);
    } elseif ($accion == 'ver_pedidos') {
        $sql = "SELECT * FROM pedidos";
        $resultado = $conn->query($sql);
    } elseif ($accion == 'ver_pedidos_usuario') {
        $usuario_id = (int) $_POST['usuario_id']; // Asegúrate de convertirlo a un entero
        $sql = "SELECT * FROM pedidos WHERE usuario_id = $usuario_id";
        $resultado = $conn->query($sql);
    }
}

// Cerrar la conexión al final
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App PHP</title>
</head>
<body>
    <h1>VENTAS SEMANA 1</h1>

    <form method="POST">
        <button type="submit" name="accion" value="ver_usuarios">Ver Usuarios</button>
        <button type="submit" name="accion" value="ver_pedidos">Ver Pedidos</button>
        <select name="usuario_id">
            <?php
            // Reabrir la conexión para obtener usuarios
            $conn = new mysqli($servername, $username, $password, $dbname);
            $sql = "SELECT id, nombre FROM usuarios";
            $usuarios = $conn->query($sql);
            while ($usuario = $usuarios->fetch_assoc()) {
                echo "<option value='{$usuario['id']}'>{$usuario['nombre']}</option>";
            }
            $conn->close();
            ?>
        </select>
        <button type="submit" name="accion" value="ver_pedidos_usuario">Ver Pedidos de Usuario</button>
    </form>

    <?php if (!empty($resultado)): ?>
        <h2>Resultados:</h2>
        <table border="1">
            <tr>
                <?php if ($accion == 'ver_usuarios'): ?>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                <?php elseif ($accion == 'ver_pedidos'): ?>
                    <th>ID</th>
                    <th>Usuario ID</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                <?php elseif ($accion == 'ver_pedidos_usuario'): ?>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                <?php endif; ?>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <?php if ($accion == 'ver_usuarios'): ?>
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <td><?= htmlspecialchars($fila['nombre']) ?></td>
                        <td><?= htmlspecialchars($fila['email']) ?></td>
                    <?php elseif ($accion == 'ver_pedidos'): ?>
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <td><?= htmlspecialchars($fila['usuario_id']) ?></td>
                        <td><?= htmlspecialchars($fila['producto']) ?></td>
                        <td><?= htmlspecialchars($fila['cantidad']) ?></td>
                    <?php elseif ($accion == 'ver_pedidos_usuario'): ?>
                        <td><?= htmlspecialchars($fila['id']) ?></td>
                        <td><?= htmlspecialchars($fila['producto']) ?></td>
                        <td><?= htmlspecialchars($fila['cantidad']) ?></td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</body>
</html>
