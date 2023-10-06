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
th, td {
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


                // botones
                echo '<form action="" method="POST">';
                echo '<input type="hidden" name="id" value="' . $fila["JuegoID"] . '">';
                echo '<input type="submit" name="eliminar" value="Eliminar">';
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

    $insertar = mysqli_query($conexion, $sql);
    if ($insertar) {
        echo "<script> alert('Registro insertado correctamente');</script>";
        print($respuesta);
    } else {
        echo "<script> alert('Error al insertar producto');</script>";
    }
}




// BUSCAR PRDUCTOS
if (isset($_POST['buscar'])) {
    $nombre = $_POST["busquedaNombre"];
    $proveedor = $_POST["busquedaProveedor"];
    $categoria = $_POST["busquedaCategoria"];

    // COMBINO TABLAS
    $sqlB = "SELECT j.NombreJuego, c.NombreCategoria, p.NombreProveedor, j.PrecioCompra, j.PrecioVenta, j.Stock
             FROM juegosdemesa j
             LEFT JOIN categorias c ON j.Categoria_ID = c.CategoriaID
             LEFT JOIN proveedores p ON j.Proveedor_ID = p.ProveedorID
             WHERE j.NombreJuego LIKE '%$nombre%' OR p.NombreProveedor LIKE '%$proveedor%' OR c.NombreCategoria LIKE '%$categoria%'";

    $resultado = mysqli_query($conexion, $sqlB);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Nombre</th>";
        echo "<th>Categoría</th>";
        echo "<th>Proveedor</th>";
        echo "<th>Precio de Compra</th>";
        echo "<th>Precio de Venta</th>";
        echo "<th>Stock</th>";
        echo "<th>Acción</th>"; 
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<tr>";
            echo "<td>" . $fila["NombreJuego"] . "</td>";
            echo "<td>" . $fila["NombreCategoria"] . "</td>";
            echo "<td>" . $fila["NombreProveedor"] . "</td>";
            echo "<td>" . $fila["PrecioCompra"] . "</td>";
            echo "<td>" . $fila["PrecioVenta"] . "</td>";
            echo "<td>" . $fila["Stock"] . "</td>";
            echo '<td><form>';
            echo '<input type="hidden" name="id" value="' . $fila["JuegoID"] . '">';
            echo '<input type="submit" name="eliminar" value="Eliminar">';
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
    $eliminar = mysqli_query($conexion, $eliminar);
    if ($eliminar) {
        echo "<script> alert('Producto borrado correctamente');</script>";
    } else {
        echo "<script> alert('Error al insertar producto');</script>";
    }
}
?>

</body>
</html>