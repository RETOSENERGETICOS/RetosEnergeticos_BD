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
// Incluir la biblioteca TCPDF
require_once('tcpdf/tcpdf.php');

// Incluir el archivo de conexión
require_once '../includes/db_connection.php';

// Obtener los valores de los filtros de búsqueda desde el formulario principal
$busqueda = isset($_POST['busqueda']) ? $_POST['busqueda'] : '';
$tipoDocumento = isset($_POST['tipoDocumento']) ? $_POST['tipoDocumento'] : '';
$tecnologiaAsociada = isset($_POST['tecnologiaAsociada']) ? $_POST['tecnologiaAsociada'] : '';
$tipoArchivo = isset($_POST['tipoArchivo']) ? $_POST['tipoArchivo'] : '';
$areaAsociada = isset($_POST['areaAsociada']) ? $_POST['areaAsociada'] : '';
$propietario = isset($_POST['propietario']) ? $_POST['propietario'] : '';
$disponible = isset($_POST['disponible']) ? $_POST['disponible'] : '';

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

// Obtener resultados de la base de datos
$result = $conn->query($sql);

// Crear instancia de TCPDF
$pdf = new TCPDF();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Configurar formato y estilo de fuente
$pdf->SetFont('helvetica', '', 12);

// Crear tabla y establecer encabezados
$html = "<table border='1'>
            <tr>
                <th>ITEM</th>
                <th>TIPO_DE_DOCUMENTO</th>
                <th>TECNOLOGIA_ASOCIADA</th>
                <th>TIPO_DE_ARCHIVO</th>
                <th>AREA_ASOCIADA</th>
                <th>PROPIETARIO</th>
                <th>DISPONIBLE</th>
                <th>CODIGO</th>
                <th>DESCRIPCION</th>
                <th>REVISION</th>
                <th>AUTOR</th>
                <th>IDIOMA</th>
                <th>PUBLICACION</th>
                <th>ARCHIVO</th>
            </tr>";

// Agregar filas a la tabla con datos de la base de datos
while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
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
            </tr>";
}

$html .= "</table>";

// Agregar tabla al PDF
$pdf->writeHTML($html, true, false, false, false, '');
ob_end_clean();
// Salida del PDF
$pdf->Output('documentacion_tecnica.pdf', 'D');

// Cerrar la conexión
$conn->close();
?>