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

// Obtener los datos enviados por el formulario
$ITEM = $_POST['ITEM'];
$TIPO_DE_DOCUMENTO = $_POST['TIPO_DE_DOCUMENTO'];
$TECNOLOGIA_ASOCIADA = $_POST['TECNOLOGIA_ASOCIADA'];
$TIPO_DE_ARCHIVO = $_POST['TIPO_DE_ARCHIVO'];
$AREA_ASOCIADA = $_POST['AREA_ASOCIADA'];
$PROPIETARIO = $_POST['PROPIETARIO'];
$DISPONIBLE = $_POST['DISPONIBLE'];
$CODIGO = $_POST['CODIGO'];
$DESCRIPCION = $_POST['DESCRIPCION'];
$REVISION = $_POST['REVISION'];
$AUTOR = $_POST['AUTOR'];
$IDIOMA = $_POST['IDIOMA'];
$PUBLICACION = $_POST['PUBLICACION'];
$nombreArchivo = $_FILES["archivo"]["name"];
$rutaArchivo = "archivos/" . $nombreArchivo; // Ajusta la ruta según tus necesidades

// Mover el archivo adjunto a la carpeta de destino
    if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $rutaArchivo))

// Insertar los datos en la tabla de la base de datos
$sql = "INSERT INTO documentacion_tecnica (ITEM, TIPO_DE_DOCUMENTO, TECNOLOGIA_ASOCIADA, TIPO_DE_ARCHIVO, AREA_ASOCIADA, PROPIETARIO, DISPONIBLE, CODIGO, DESCRIPCION, REVISION, AUTOR, IDIOMA, PUBLICACION, archivo) VALUES ('$ITEM', '$TIPO_DE_DOCUMENTO', '$TECNOLOGIA_ASOCIADA', '$TIPO_DE_ARCHIVO', '$AREA_ASOCIADA', '$PROPIETARIO', '$DISPONIBLE', '$CODIGO', '$DESCRIPCION', '$REVISION', '$AUTOR', '$IDIOMA', '$PUBLICACION', '$nombreArchivo')";
if ($conn->query($sql) === TRUE) {
  echo "Datos guardados correctamente.";
} else {
  echo "Error al guardar los datos: " . $conn->error;
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);

// Mostrar mensaje de éxito
header('Location: doc_tecnica_admin.php');
exit;
?>