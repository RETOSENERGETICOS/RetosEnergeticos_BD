<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
// Aquí va el contenido de tu panel de usuario
?>
<?php
require_once '../includes/db_connection.php';
require_once 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Obtener los valores de los filtros de búsqueda
$busqueda = $_POST['busqueda'] ?? '';
$tipoDocumento = $_POST['tipoDocumento'] ?? '';
$tecnologiaAsociada = $_POST['tecnologiaAsociada'] ?? '';
$tipoArchivo = $_POST['tipoArchivo'] ?? '';
$areaAsociada = $_POST['areaAsociada'] ?? '';
$propietario = $_POST['propietario'] ?? '';
$disponible = $_POST['disponible'] ?? '';

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
$result = $conn->query($sql);

// Crear un nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados de columna
$columnas = ['ITEM', 'TIPO_DE_DOCUMENTO', 'TECNOLOGIA_ASOCIADA', 'TIPO_DE_ARCHIVO', 'AREA_ASOCIADA', 'PROPIETARIO', 'DISPONIBLE', 'CODIGO', 'DESCRIPCION', 'REVISION', 'AUTOR', 'IDIOMA', 'PUBLICACION', 'ARCHIVO'];

// Establecer encabezados
$columnIndex = 1;
foreach ($columnas as $columna) {
    $sheet->setCellValueByColumnAndRow($columnIndex, 1, $columna);
    $columnIndex++;
}

// Datos de la tabla
$fila = 2;
while ($row = $result->fetch_assoc()) {
    $columnIndex = 1;
    foreach ($columnas as $columna) {
        $sheet->setCellValueByColumnAndRow($columnIndex, $fila, $row[$columna]);
        $columnIndex++;
    }
    $fila++;
}

// Guardar el archivo Excel en la carpeta "downloads"
$archivo = 'exports/documentacion_tecnica_con_formato.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($archivo);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/excel.css">

    <title>Descargar Archivo</title>
</head>
<body>
    <!-- Enlace para descargar el archivo -->
    <a href="<?php echo $archivo; ?>" download>Descargar Archivo</a>
    <a href="doc_tecnica_admin.php">Volver al Panel de Administrador</a>
</body>
</html>