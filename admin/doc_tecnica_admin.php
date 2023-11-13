<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
// Aquí va el contenido de tu panel de usuario
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="../estilos/database.css">

    <title>Document</title>
</head>
<body>
<title>Documentación Técnica</title>
</head>
<body>
    <h1>Documentación Técnica</h1>
    <div class="options_add">
    <a href="panel_admin.php">Volver al Panel de Administrador</a>
    <a class="fcc-btn" href='doc_tecnica_nuevo.php'>Agregar</a>
    </div>
</body>
<?php
// Incluir el archivo de conexión
require_once '../includes/db_connection.php';

// Cargar la biblioteca PhpSpreadsheet
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Definir las opciones para las listas desplegables
$tiposDocumento = array(
    'CROQUIS / SKETCH',
    'DOCUMENTO COMERCIAL / COMERCIAL DOCUMENT',
    'ESPECIFICACION TECNICA / TECHNICAL SPECIFICATION',
    'FICHA TECNICA / DATA SHEET',
    'FORMATO / FORMAT',
    'GUIA / GUIDE',
    'INSTRUCCIÓN TECNICA / TECHNICAL INSTRUCTION',
    'INSTRUCTIVO / INSTRUCTIVE',
    'ITC',
    'LEY / LAW',
    'LIBRO / BOOK',
    'MANUAL DE FORMACION / TRAINING MANUAL',
    'MANUAL TECNICO / TECHNICAL MANUAL',
    'NORMA / NORME',
    'PLANO/PLANE',
    'PROCEDIMIENTO / PROCESS',
    'RECOMENDACIÓN / RECOMENDATION',
    'REGLAMENTO / REGLAMENT'
);

$tecnologiasAsociadas = array(
    '3S',
    'AVANTI',
    'AWP',
    'BOP',
    'FIBERBLADE',
    'GAMESA',
    'GE',
    'GOLDWIND',
    'INGETEAM',
    'LIBERTY',
    'LM',
    'NO APLICA/ NA',
    'NORDEX',
    'OTROS/ OTHERS',
    'SIEMENS',
    'SIEMENS GAMESA',
    'TPI',
    'VESTAS'
);

$tiposArchivo = array(
    'CONSULTA / CONSULT',
    'DESARROLLO / DEVELOP',
    'EDITABLE / EDITABLE',
    'EJECUTABLE / EXECUTABLE',
    'FABRICACION / FABRICATION'
);

$areasAsociadas = array(
    'ADMINISTRATIVO / ADMINISTRATIVE',
    'COMERCIAL / COMMERCIAL',
    'INGENIERIA / ENGINEERING',
    'OPERACIONES / OPERATIONS',
    'SEGURIDAD / SAFETY'
);

$propietarios = array(
    'CLIENTE / CLIENT',
    'OFICIAL / OFFICIAL',
    'PROPIO / OWN',
    'PUBLICO / PUBLIC',
    'SUMINISTRADOR / SUPPLY'
);

$disponibles = array(
    'NO',
    'SI'
);

// Inicializar variables
$busqueda = '';
$tipoDocumento = '';
$tecnologiaAsociada = '';
$tipoArchivo = '';
$areaAsociada = '';
$propietario = '';
$disponible = '';

// Definir la cantidad de registros por página
$registrosPorPagina = 10;

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores de los filtros de entrada de texto
    $busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';

    // Obtener los valores de los filtros por listas desplegables
    $tipoDocumento = isset($_POST['tipoDocumento']) ? $_POST['tipoDocumento'] : '';
    $tecnologiaAsociada = isset($_POST['tecnologiaAsociada']) ? $_POST['tecnologiaAsociada'] : '';
    $tipoArchivo = isset($_POST['tipoArchivo']) ? $_POST['tipoArchivo'] : '';
    $areaAsociada = isset($_POST['areaAsociada']) ? $_POST['areaAsociada'] : '';
    $propietario = isset($_POST['propietario']) ? $_POST['propietario'] : '';
    $disponible = isset($_POST['disponible']) ? $_POST['disponible'] : '';
}

// Consulta SQL con filtros de búsqueda
$sql = "SELECT * FROM documentacion_tecnica WHERE 
        (ITEM LIKE '%$busqueda%' OR
        TIPO_DE_DOCUMENTO LIKE '%$busqueda%' OR
        TECNOLOGIA_ASOCIADA LIKE '%$busqueda%' OR
        TIPO_DE_ARCHIVO LIKE '%$busqueda%' OR
        AREA_ASOCIADA LIKE '%$busqueda%' OR
        PROPIETARIO LIKE '%$busqueda%' OR
        DISPONIBLE LIKE '%$busqueda%' OR
        CODIGO LIKE '%$busqueda%' OR
        DESCRIPCION LIKE '%$busqueda%' OR
        REVISION LIKE '%$busqueda%' OR
        AUTOR LIKE '%$busqueda%' OR
        IDIOMA LIKE '%$busqueda%' OR
        PUBLICACION LIKE '%$busqueda%' OR
        ARCHIVO LIKE '%$busqueda%') AND
        TIPO_DE_DOCUMENTO LIKE '%$tipoDocumento%' AND
        TECNOLOGIA_ASOCIADA LIKE '%$tecnologiaAsociada%' AND
        TIPO_DE_ARCHIVO LIKE '%$tipoArchivo%' AND
        AREA_ASOCIADA LIKE '%$areaAsociada%' AND
        PROPIETARIO LIKE '%$propietario%' AND
        DISPONIBLE LIKE '%$disponible'";

// Obtener el número total de registros
$result = $conn->query($sql);
$totalRegistros = $result->num_rows;

// Calcular el número total de páginas
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Obtener la página actual
$paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Calcular el índice de inicio para la consulta SQL
$indiceInicio = ($paginaActual - 1) * $registrosPorPagina;

// Modificar la consulta SQL con la limitación de registros
$sql .= " LIMIT $indiceInicio, $registrosPorPagina";

$result = $conn->query($sql);

// Mostrar resultados
echo "<table border='1'>";
echo "<tr><th>ITEM</th><th>TIPO DE DOCUMENTO</th><th>TECNOLOGIA ASOCIADA</th><th>TIPO DE ARCHIVO</th><th>AREA ASOCIADA</th><th>PROPIETARIO</th><th>DISPONIBLE</th><th>CODIGO</th><th>DESCRIPCION</th><th>REVISION</th><th>AUTOR</th><th>IDIOMA</th><th>PUBLICACION</th><th>ARCHIVO</th></tr>";
while ($row = $result->fetch_assoc()) {
    $nombreArchivo = $row['ARCHIVO'];
    echo "<tr>
        <td>".$row["ITEM"]."</td>
        <td>".$row["TIPO_DE_DOCUMENTO"]."</td>
        <td>".$row["TECNOLOGIA_ASOCIADA"]."</td>
        <td>".$row["TIPO_DE_ARCHIVO"]."</td>
        <td>".$row["AREA_ASOCIADA"]."</td>
        <td>".$row["PROPIETARIO"]."</td>
        <td>".$row["DISPONIBLE"]."</td>
        <td>".$row["CODIGO"]."</td>
        <td>".$row["DESCRIPCION"]."</td>
        <td>".$row["REVISION"]."</td>
        <td>".$row["AUTOR"]."</td>
        <td>".$row["IDIOMA"]."</td>
        <td>".$row["PUBLICACION"]."</td>
        <td>".$row["ARCHIVO"]."</td>
        <td>
            <a href='archivos/$nombreArchivo' target='_blank'><i img class='fas fa-eye'></i></a>
            <a href='doc_tecnica_editar.php?ITEM=".$row["ITEM"]."' title='Editar'><i class='fas fa-edit'></i></a>
            <a href='eliminar_archivo.php?ITEM=".$row["ITEM"]."' title='Eliminar'><i class='fas fa-trash'></i></a>
        </td>
    </tr>";
}

echo "</table>";

// Mostrar la paginación
echo "<div>";
for ($i = 1; $i <= $totalPaginas; $i++) {
    echo "<a href='?pagina=$i'>$i</a> ";
}
echo "</div>";

// Botones para generar PDF y exportar a Excel
echo "<form action='generar_pdf.php' method='post'>";
echo "<input type='hidden' name='busqueda' value='$busqueda'>";
echo "<input type='hidden' name='tipoDocumento' value='$tipoDocumento'>";
echo "<input type='hidden' name='tecnologiaAsociada' value='$tecnologiaAsociada'>";
echo "<input type='hidden' name='tipoArchivo' value='$tipoArchivo'>";
echo "<input type='hidden' name='areaAsociada' value='$areaAsociada'>";
echo "<input type='hidden' name='propietario' value='$propietario'>";
echo "<input type='hidden' name='disponible' value='$disponible'>";
echo "<br>";

echo "<input type='submit' class='buton_pdf' value='Generar PDF'>";
echo "</form>";
echo "<form action='exportar_excel.php' method='post'>";
echo "<input type='hidden' name='busqueda' value='$busqueda'>";
echo "<input type='hidden' name='tipoDocumento' value='$tipoDocumento'>";
echo "<input type='hidden' name='tecnologiaAsociada' value='$tecnologiaAsociada'>";
echo "<input type='hidden' name='tipoArchivo' value='$tipoArchivo'>";
echo "<input type='hidden' name='areaAsociada' value='$areaAsociada'>";
echo "<input type='hidden' name='propietario' value='$propietario'>";
echo "<input type='hidden' name='disponible' value='$disponible'>";
echo "<br>";
echo "<input type='submit' class='buton_excel' value='Exportar a Excel'>";
echo "</form>";

// Cerrar la conexión
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/database.css">
    
    <title>Documentación Técnica</title>
</head>
<body>
<br>    <form method="post" action="">
        <label for="busqueda" >Búsqueda por texto:</label>
        <input type="text" name="busqueda" >
        <input type="submit" value="Buscar">

<br><br>
        <label for="tipoDocumento">Tipo de Documento:</label>
        <select name="tipoDocumento">
            <option value="">Seleccione...</option>
            <?php
            foreach ($tiposDocumento as $tipo) {
                echo "<option value='$tipo'>$tipo</option>";
            }
            ?>
        </select>

        <label for="tecnologiaAsociada">Tecnología Asociada:</label>
        <select name="tecnologiaAsociada">
            <option value="">Seleccione...</option>
            <?php
            foreach ($tecnologiasAsociadas as $tecnologia) {
                echo "<option value='$tecnologia'>$tecnologia</option>";
            }
            ?>
        </select>

        <label for="tipoArchivo">Tipo de Archivo:</label>
        <select name="tipoArchivo">
            <option value="">Seleccione...</option>
            <?php
            foreach ($tiposArchivo as $tipo) {
                echo "<option value='$tipo'>$tipo</option>";
            }
            ?>
        </select>

        <label for="areaAsociada">Área Asociada:</label>
        <select name="areaAsociada">
            <option value="">Seleccione...</option>
            <?php
            foreach ($areasAsociadas as $area) {
                echo "<option value='$area'>$area</option>";
            }
            ?>
        </select>

        <label for="propietario">Propietario:</label>
        <select name="propietario">
            <option value="">Seleccione...</option>
            <?php
            foreach ($propietarios as $propietario) {
                echo "<option value='$propietario'>$propietario</option>";
            }
            ?>
        </select>

        <label for="disponible">Disponible:</label>
        <select name="disponible">
            <option value="">Seleccione...</option>
            <?php
            foreach ($disponibles as $disponible) {
                echo "<option value='$disponible'>$disponible</option>";
            }
            ?>
        </select> 
</body>
</html>