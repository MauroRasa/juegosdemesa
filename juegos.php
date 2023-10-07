<?php
include("conexion.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f8ff;
        margin: 0;
        padding: 0;
    }

    form {
        background-color: #fff;
        padding: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }
</style>

<body>




    <!-- INSERTAR PRODCUTOS FORM -->
    <h1>Insertar Producto</h1>
    <div class="Insert">
        <form action="" method="POST">
            <label for="Nombre">Nombre del Producto: </label>
            <input type="text" name="NombreJuego" id="Nombre">
            <label for="Categoria">Categoría del Producto: </label>
            <select name="Categoria_ID" id="Categoria">
                <?php
                $consultaCategorias = "SELECT CategoriaID, NombreCategoria FROM categorias";
                $resultadoCategorias = mysqli_query($conexion, $consultaCategorias);

                if ($resultadoCategorias && mysqli_num_rows($resultadoCategorias) > 0) {
                    while ($filaCategoria = mysqli_fetch_assoc($resultadoCategorias)) {
                        echo "<option value='" . $filaCategoria['CategoriaID'] . "'>" . $filaCategoria['NombreCategoria'] . "</option>";
                    }
                }
                ?>
            </select>
            <label for="Proveedor">Proveedor del Producto: </label>
            <select name="Proveedor_ID" id="Proveedor">
                <?php
                $consultaProveedores = "SELECT ProveedorID, NombreProveedor FROM proveedores";
                $resultadoProveedores = mysqli_query($conexion, $consultaProveedores);

                if ($resultadoProveedores && mysqli_num_rows($resultadoProveedores) > 0) {
                    while ($filaProveedor = mysqli_fetch_assoc($resultadoProveedores)) {
                        echo "<option value='" . $filaProveedor['ProveedorID'] . "'>" . $filaProveedor['NombreProveedor'] . "</option>";
                    }
                }
                ?>
            </select>
            <label for="PrecioC">Precio de compra del Producto: </label>
            <input type="text" name="PrecioCompra" id="PrecioC">
            <label for="PrecioV">Precio de venta del Producto: </label>
            <input type="text" name="PrecioVenta" id="PrecioV">
            <label for="Stockk">Stock del Producto: </label>
            <input type="text" name="Stock" id="Stockk">

            <input type="submit" name="insertar" value="INSERTAR">
        </form>
    </div>






    <!-- BUSCAR PRODUCTOS FORM -->
    <h1>Buscar Producto</h1>
    <div class="Buscar">
        <form action="" method="POST">
            <label for="busquedaN">Buscar Producto por Nombre:</label>
            <input type="text" name="busquedaNombre" id="busquedaN">
            <label for="busquedaP">Buscar por Proveedor:</label>
            <input type="text" name="busquedaProveedor" id="busquedaP">
            <label for="busquedaC">Buscar por Categoría:</label>
            <input type="text" name="busquedaCategoria" id="busquedaC">

            <input type="submit" name="buscar" value="Buscar">
        </form>
    </div>






    <!-- TABLA DE PRODUCTOS -->
    <h1>Productos</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Proveedor</th>
                <th>Precio de Compra</th>
                <th>Precio de Venta</th>
                <th>Stock</th>
                <th>Acción</th>

            </tr>
        </thead>
        <tbody>
            <?php
            // COMBINO TABLAS
            $sqlSE = "SELECT j.JuegoID, j.NombreJuego, c.NombreCategoria, p.NombreProveedor, j.PrecioCompra, j.PrecioVenta, j.Stock
                 FROM juegosdemesa j
                 LEFT JOIN categorias c ON j.CategoriaID = c.CategoriaID
                 LEFT JOIN proveedores p ON j.ProveedorID = p.ProveedorID";
            $resultado = mysqli_query($conexion, $sqlSE);

            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td>" . $fila["NombreJuego"] . "</td>";
                    echo "<td>" . $fila["NombreCategoria"] . "</td>";
                    echo "<td>" . $fila["NombreProveedor"] . "</td>";
                    echo "<td>" . $fila["PrecioCompra"] . "</td>";
                    echo "<td>" . $fila["PrecioVenta"] . "</td>";
                    echo "<td>" . $fila["Stock"] . "</td>";
                    echo "<td>";



                    // BOTON ELIMINAR
                    echo '<form action="" method="POST">';
                    echo '<input type="hidden" name="id" value="' . $fila["JuegoID"] . '">';
                    echo '<input type="submit" name="eliminar" value="Eliminar">';



                    // BOTON ACTUALIZAR
                    echo '</form> <form action="" method="POST">';
                    echo '<input type="hidden" name="id" value="' . $fila["JuegoID"] . '">';
                    echo '<input type="submit" name="actualizar" value="Actualizar">';
                    echo '</form></td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay Productos registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>






    <?php
    // INSERTAR PRODUCTOS
    if (isset($_POST['insertar'])) {
        $nombre = $_POST["NombreJuego"];
        $cate = $_POST["Categoria_ID"];
        $proveed = $_POST["Proveedor_ID"];
        $precioC = $_POST["PrecioCompra"];
        $precioV = $_POST["PrecioVenta"];
        $stock = $_POST["Stock"];

        $sql = "INSERT INTO juegosdemesa (NombreJuego, CategoriaID, ProveedorID, PrecioCompra, PrecioVenta, Stock)
            VALUES ('$nombre', $cate, $proveed, $precioC, $precioV, $stock)";

        $insertar = mysqli_query($conexion, $sql)? print ("<script> alert ('Registro insertado correctamente'); window.location = 'juegos.php?id=$id_editar'</script>") : print ("<script> alert ('Error al insertar registro')</script>");
    }





    // BUSCAR PRDUCTOS
    if (isset($_POST['buscar'])) {
        $nombre = $_POST["busquedaNombre"];
        $proveedor = $_POST["busquedaProveedor"];
        $categoria = $_POST["busquedaCategoria"];

        // COMBINO TABLAS
        $sqlB = "SELECT j.NombreJuego, c.NombreCategoria, p.NombreProveedor, j.PrecioCompra, j.PrecioVenta, j.Stock
             FROM juegosdemesa j
             LEFT JOIN categorias c ON j.CategoriaID = c.CategoriaID
             LEFT JOIN proveedores p ON j.ProveedorID = p.ProveedorID
             WHERE j.NombreJuego LIKE '$nombre' OR p.NombreProveedor LIKE '$proveedor' OR c.NombreCategoria LIKE '$categoria'";

        $resultadoBuscar = mysqli_query($conexion, $sqlB);

        if ($resultadoBuscar && mysqli_num_rows($resultadoBuscar) > 0) {
            echo '<h1>Busqueda de Productos</h1>';
            echo '<table>';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>Nombre</th>';
                        echo '<th>Categoría</th>';
                        echo '<th>Proveedor</th>';
                        echo '<th>Precio de Compra</th>';
                        echo '<th>Precio de Venta</th>';
                        echo '<th>Stock</th>';
                        echo '<th>Acción</th>';
        
                    echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
            while ($fila = mysqli_fetch_assoc($resultadoBuscar)) {
                echo "<tr>";
                echo "<td>" . $fila["NombreJuego"] . "</td>";
                echo "<td>" . $fila["NombreCategoria"] . "</td>";
                echo "<td>" . $fila["NombreProveedor"] . "</td>";
                echo "<td>" . $fila["PrecioCompra"] . "</td>";
                echo "<td>" . $fila["PrecioVenta"] . "</td>";
                echo "<td>" . $fila["Stock"] . "</td>";
                echo "<td>";



                // BOTON ELIMINAR
                echo '<form action="" method="POST">';
                echo '<input type="hidden" name="id" value="' . $fila["JuegoID"] . '">';
                echo '<input type="submit" name="eliminar" value="Eliminar">';



                // BOTON ACTUALIZAR
                echo '</form> <form action="" method="POST">';
                echo '<input type="hidden" name="id" value="' . $fila["JuegoID"] . '">';
                echo '<input type="submit" name="actualizar" value="Actualizar">';
                echo '</form></td>';
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";

        } else {
            echo "No se encontró ningún producto.";
        }
    }






    // ELIMINAR PRODCUTOS
    if (isset($_POST['eliminar'])) {
        $id_eliminar = $_POST["id"];

        $eliminar = "DELETE FROM juegosdemesa WHERE JuegoID = '$id_eliminar'";
        $eliminar = mysqli_query($conexion, $eliminar)? print ("<script> alert ('Registro eliminado correctamente'); window.location = 'juegos.php?id=$id_editar'</script>") : print ("<script> alert ('Error al actualizar registro')</script>");;
    }






    // ACTUALIZAR PRODCUTOS
    function obtenerDatosJuego($conexion, $juegoID) {
        $sql = "SELECT * FROM juegosdemesa WHERE JuegoID = $juegoID";
        $resultado = mysqli_query($conexion, $sql);
    
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            return mysqli_fetch_assoc($resultado);
        } else {
            return false;
        }
    }
    
    // Función para actualizar un juego en la base de datos
    function actualizarJuego($conexion, $juegoID, $nuevoNombre, $nuevaCategoria, $nuevoProveedor, $nuevoPrecioCompra, $nuevoPrecioVenta, $nuevoStock) {
        $sql = "UPDATE juegosdemesa SET NombreJuego = '$nuevoNombre', CategoriaID = '$nuevaCategoria', ProveedorID = '$nuevoProveedor', PrecioCompra = '$nuevoPrecioCompra', PrecioVenta = '$nuevoPrecioVenta', Stock = '$nuevoStock' WHERE JuegoID = $juegoID";
        $resultado = mysqli_query($conexion, $sql);
    
        return $resultado;
    }
    
    if (isset($_POST['actualizar'])) {
        $juegoID = $_POST['id'];
    
        // Obtener los datos actuales del juego
        $datosJuego = obtenerDatosJuego($conexion, $juegoID);
    
        if ($datosJuego) {
            // Mostrar formulario de actualización con los datos actuales
            echo '<form action="" method="POST">';
            echo '<input type="hidden" name="juegoID" value="' . $juegoID . '">';
    
            echo 'Nuevo Nombre: <input type="text" name="nuevoNombre" value="' . $datosJuego['NombreJuego'] . '"><br>';
            echo 'Nueva Categoría: <input type="text" name="nuevaCategoria" value="' . $datosJuego['CategoriaID'] . '"><br>';
            echo 'Nuevo Proveedor: <input type="text" name="nuevoProveedor" value="' . $datosJuego['ProveedorID'] . '"><br>';
            echo 'Nuevo Precio de Compra: <input type="text" name="nuevoPrecioCompra" value="' . $datosJuego['PrecioCompra'] . '"><br>';
            echo 'Nuevo Precio de Venta: <input type="text" name="nuevoPrecioVenta" value="' . $datosJuego['PrecioVenta'] . '"><br>';
            echo 'Nuevo Stock: <input type="text" name="nuevoStock" value="' . $datosJuego['Stock'] . '"><br>';
    
            echo '<input type="submit" name="guardar_actualizacion" value="Guardar Actualización">';
            echo '</form>';
        } else {
            echo "Error: Juego no encontrado.";
        }
    } elseif (isset($_POST['guardar_actualizacion'])) {
        $juegoID = $_POST['juegoID'];
        $nuevoNombre = $_POST['nuevoNombre'];
        $nuevaCategoria = $_POST['nuevaCategoria'];
        $nuevoProveedor = $_POST['nuevoProveedor'];
        $nuevoPrecioCompra = $_POST['nuevoPrecioCompra'];
        $nuevoPrecioVenta = $_POST['nuevoPrecioVenta'];
        $nuevoStock = $_POST['nuevoStock'];
    
        // Actualizr
        $actualizacionExitosa = actualizarJuego($conexion, $juegoID, $nuevoNombre, $nuevaCategoria, $nuevoProveedor, $nuevoPrecioCompra, $nuevoPrecioVenta, $nuevoStock)? print ("<script> alert ('Registro actualizado correctamente'); window.location = 'juegos.php?id=$id_editar'</script>") : print ("<script> alert ('Error al actualizar registro')</script>");;
    }
?>    

</body>

</html>