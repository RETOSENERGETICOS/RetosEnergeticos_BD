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
// Incluir el archivo de conexión a la base de datos
require_once '../includes/db_connection.php';
// Función para valITEMar y sanear datos de entrada
function sanitizeInput($input) {
    // Aplicar valITEMación y saneamiento aquí según tus necesITEMades
    return htmlspecialchars(trim($input));
}

// Verificar si se ha enviado el formulario de modificación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ITEM = sanitizeInput($_POST["ITEM"]);
    $TIPO_DE_DOCUMENTO = sanitizeInput($_POST['TIPO_DE_DOCUMENTO']);
    $TECNOLOGIA_ASOCIADA = sanitizeInput($_POST["TECNOLOGIA_ASOCIADA"]);
    $TIPO_DE_ARCHIVO = sanitizeInput($_POST['TIPO_DE_ARCHIVO']);
    $AREA_ASOCIADA = sanitizeInput($_POST["AREA_ASOCIADA"]);
    $PROPIETARIO = sanitizeInput($_POST["PROPIETARIO"]);
    $DISPONIBLE = sanitizeInput($_POST["DISPONIBLE"]);
    $CODIGO = sanitizeInput($_POST["CODIGO"]);
    $DESCRIPCION = sanitizeInput($_POST["DESCRIPCION"]);
    $REVISION = sanitizeInput($_POST["REVISION"]);
    $AUTOR = sanitizeInput($_POST["AUTOR"]);
    $IDIOMA = sanitizeInput($_POST["IDIOMA"]);
    $PUBLICACION = sanitizeInput($_POST["PUBLICACION"]);

    // Manejar la subITEMa del archivo
    if ($_FILES["archivo"]["size"] > 0) {
        $nombreArchivo = $_FILES["archivo"]["name"];
        $rutaArchivo = "archivos/" . $nombreArchivo;

        if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $rutaArchivo)) {
            // Preparar la actualización de la base de datos
            $stmt = $conn->prepare("UPDATE documentacion_tecnica SET ITEM=?, TIPO_DE_DOCUMENTO=?, TECNOLOGIA_ASOCIADA=?, TIPO_DE_ARCHIVO=?, AREA_ASOCIADA=?, PROPIETARIO=?, DISPONIBLE=?, CODIGO=?, DESCRIPCION=?, REVISION=?, AUTOR=?, ITEMIOMA=?, PUBLICACION=?, archivo=? WHERE ITEM=?");
            $stmt->bind_param("ssssssssssssssi", $ITEM, $TIPO_DE_DOCUMENTO, $TECNOLOGIA_ASOCIADA, $TIPO_DE_ARCHIVO, $AREA_ASOCIADA, $PROPIETARIO, $DISPONIBLE, $CODIGO, $DESCRIPCION, $REVISION, $AUTOR, $ITEMIOMA, $PUBLICACION, $nombreArchivo, $ITEM);

            if ($stmt->execute()) {
                echo "<div align=center><br>Datos actualizados exitosamente</div>";
                echo "<div align=center><a href=doc_tecnica_admin.php><br>Volver al dashboard</a></div>";
            } else {
                echo "Error al actualizar los datos: " . $stmt->error;
            }
        } else {
            echo "Error al mover el archivo.";
        }
    } else {
        // El archivo no se ha modificado, actualiza la base de datos sin el archivo
        $stmt = $conn->prepare("UPDATE documentacion_tecnica SET ITEM=?, TIPO_DE_DOCUMENTO=?, TECNOLOGIA_ASOCIADA=?, TIPO_DE_ARCHIVO=?, AREA_ASOCIADA=?, PROPIETARIO=?, DISPONIBLE=?, CODIGO=?, DESCRIPCION=?, REVISION=?, AUTOR=?, IDIOMA=?, PUBLICACION=? WHERE ITEM=?");
        $stmt->bind_param("sssssssssssssi", $ITEM, $TIPO_DE_DOCUMENTO, $TECNOLOGIA_ASOCIADA, $TIPO_DE_ARCHIVO, $AREA_ASOCIADA, $PROPIETARIO, $DISPONIBLE, $CODIGO, $DESCRIPCION, $REVISION, $AUTOR, $IDIOMA, $PUBLICACION, $ITEM);

        if ($stmt->execute()) {
            echo "<div align=center><br><img src=img/ok.png wITEMth=100px height=auto></div>";
            echo "<div align=center><br>Se han actualizado los datos de manera exitosa</div>";
            echo "<div align=center><a href=doc_tecnica_admin.php><br>Volver al dashboard</a></div>";
        } else {
            echo "Error al actualizar los datos: " . $stmt->error;
        }
    }
}

// Obtener el ITEM del registro a modificar de la URL
if (isset($_GET["ITEM"])) {
    $ITEM = sanitizeInput($_GET["ITEM"]);

    $stmt = $conn->prepare("SELECT * FROM documentacion_tecnica WHERE ITEM=?");
    $stmt->bind_param("i", $ITEM);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Documentación Técnica</title>
            <link rel="stylesheet" href="../estilos/add.css">
        </head>
        <body>
        <div class="container">
            <form class="form" method="POST" enctype="multipart/form-data">
                <h2>Documentación Técnica</h2>
                <br><br>
                <input type="hidden" name="ITEM" value="<?php echo $row["ITEM"]; ?>">
                <!-- Resto del formulario aquí -->             
                <div class="llenado_permiso">                
                <label for="TIPO_DE_DOCUMENTO">TIPO DE DOCUMENTO/DOCUMENT TYPE:</label>
                <select ITEM="TIPO_DE_DOCUMENTO" class="box" name="TIPO_DE_DOCUMENTO">
                    <option value="PLANO/PLANE" <?php if ($row["TIPO_DE_DOCUMENTO"] === "PLANO/PLANE") echo "selected"; ?>>PLANO/PLANE</option>
                    <option value="MANUAL TECNICO / TECHNICAL MANUAL" <?php if ($row["TIPO_DE_DOCUMENTO"] === "MANUAL TECNICO / TECHNICAL MANUAL") echo "selected"; ?>>MANUAL TECNICO / TECHNICAL MANUAL</option>
                    <option value="MANUAL DE FORMACION / TRAINING MANUAL" <?php if ($row["TIPO_DE_DOCUMENTO"] === "MANUAL DE FORMACION / TRAINING MANUAL") echo "selected"; ?>>MANUAL DE FORMACION / TRAINING MANUAL</option>
                    <option value="FICHA TECNICA / DATA SHEET" <?php if ($row["TIPO_DE_DOCUMENTO"] === "FICHA TECNICA / DATA SHEET") echo "selected"; ?>>FICHA TECNICA / DATA SHEET</option>
                    <option value="INSTRUCCIÓN TECNICA / TECHNICAL INSTRUCTION" <?php if ($row["TIPO_DE_DOCUMENTO"] === "INSTRUCCIÓN TECNICA / TECHNICAL INSTRUCTION") echo "selected"; ?>>INSTRUCCIÓN TECNICA / TECHNICAL INSTRUCTION</option>
                    <option value="GUIA / GUITEME" <?php if ($row["TIPO_DE_DOCUMENTO"] === "GUIA / GUITEME") echo "selected"; ?>>GUIA / GUITEME</option>
                    <option value="PROCEDIMIENTO / PROCESS" <?php if ($row["TIPO_DE_DOCUMENTO"] === "PROCEDIMIENTO / PROCESS") echo "selected"; ?>>PROCEDIMIENTO / PROCESS</option>
                    <option value="INSTRUCTIVO / INSTRUCTIVE" <?php if ($row["TIPO_DE_DOCUMENTO"] === "INSTRUCTIVO / INSTRUCTIVE") echo "selected"; ?>>INSTRUCTIVO / INSTRUCTIVE</option>
                    <option value="CROQUIS / SKETCH" <?php if ($row["TIPO_DE_DOCUMENTO"] === "CROQUIS / SKETCH") echo "selected"; ?>>CROQUIS / SKETCH</option>
                    <option value="DOCUMENTO COMERCIAL  / COMERCIAL DOCUMENT" <?php if ($row["TIPO_DE_DOCUMENTO"] === "DOCUMENTO COMERCIAL  / COMERCIAL DOCUMENT") echo "selected"; ?>>DOCUMENTO COMERCIAL  / COMERCIAL DOCUMENT</option>
                    <option value="LIBRO / BOOK" <?php if ($row["TIPO_DE_DOCUMENTO"] === "LIBRO / BOOK") echo "selected"; ?>>LIBRO / BOOK</option>
                    <option value="NORMA / NORME" <?php if ($row["TIPO_DE_DOCUMENTO"] === "NORMA / NORME") echo "selected"; ?>>NORMA / NORME</option>
                    <option value="RECOMENDACIÓN / RECOMENDATION" <?php if ($row["TIPO_DE_DOCUMENTO"] === "RECOMENDACIÓN / RECOMENDATION") echo "selected"; ?>>RECOMENDACIÓN / RECOMENDATION</option>
                    <option value="ITC" <?php if ($row["TIPO_DE_DOCUMENTO"] === "ITC") echo "selected"; ?>>ITC</option>
                    <option value="REGLAMENTO / REGLAMENT" <?php if ($row["TIPO_DE_DOCUMENTO"] === "REGLAMENTO / REGLAMENT") echo "selected"; ?>>REGLAMENTO / REGLAMENT</option>
                    <option value="LEY / LAW" <?php if ($row["TIPO_DE_DOCUMENTO"] === "LEY / LAW") echo "selected"; ?>>LEY / LAW</option>
                    <option value="FORMATO / FORMAT" <?php if ($row["TIPO_DE_DOCUMENTO"] === "FORMATO / FORMAT") echo "selected"; ?>>FORMATO / FORMAT</option>
                    <option value="ESPECIFICACION TECNICA / TECHNICAL SPECIFICATION" <?php if ($row["TIPO_DE_DOCUMENTO"] === "ESPECIFICACION TECNICA / TECHNICAL SPECIFICATION") echo "selected"; ?>>ESPECIFICACION TECNICA / TECHNICAL SPECIFICATION</option>
                </select>

                <label for="TECNOLOGIA_ASOCIADA">TECNOLOGIA ASOCIADA / ASSOCIATED TECHNOLOGY:</label>
                <select ITEM="TECNOLOGIA_ASOCIADA" class="box" name="TECNOLOGIA_ASOCIADA">
                    <option value="GOLDWIND" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "GOLDWIND") echo "selected"; ?>>GOLDWIND</option>
                    <option value="AWP" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "AWP") echo "selected"; ?>>AWP</option>
                    <option value="GAMESA" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "GAMESA") echo "selected"; ?>>GAMESA</option>
                    <option value="SIEMENS" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "SIEMENS") echo "selected"; ?>>SIEMENS</option>
                    <option value="SIEMENS GAMESA" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "SIEMENS GAMESA") echo "selected"; ?>>SIEMENS GAMESA</option>
                    <option value="GE" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "GE") echo "selected"; ?>>GE</option>
                    <option value="NORDEX" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "NORDEX") echo "selected"; ?>>NORDEX</option>
                    <option value="VESTAS" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "VESTAS") echo "selected"; ?>>VESTAS</option>
                    <option value="LM" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "LM") echo "selected"; ?>>LM</option>
                    <option value="TPI" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "TPI") echo "selected"; ?>>TPI</option>
                    <option value="LIBERTY" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "LIBERTY") echo "selected"; ?>>LIBERTY</option>
                    <option value="BOP" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "BOP") echo "selected"; ?>>BOP</option>
                    <option value="AVANTI" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "AVANTI") echo "selected"; ?>>AVANTI</option>
                    <option value="FIBERBLADE" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "FIBERBLADE") echo "selected"; ?>>FIBERBLADE</option>
                    <option value="3S" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "3S") echo "selected"; ?>>3S</option>
                    <option value="INGETEAM" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "INGETEAM") echo "selected"; ?>>INGETEAM</option>
                    <option value="OTROS/ OTHERS" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "OTROS/ OTHERS") echo "selected"; ?>>OTROS/ OTHERS</option>
                    <option value="NO APLICA/ NA" <?php if ($row["TECNOLOGIA_ASOCIADA"] === "NO APLICA/ NA") echo "selected"; ?>>NO APLICA/ NA</option>
                </select>

                <label for="TIPO_DE_ARCHIVO">TIPO DE ARCHIVO / TYPE OF FILE:</label>
                <select ITEM="TIPO_DE_ARCHIVO" class="box" name="TIPO_DE_ARCHIVO">
                    <option value="CONSULTA / CONSULT" <?php if ($row["TIPO_DE_ARCHIVO"] === "CONSULTA / CONSULT") echo "selected"; ?>>CONSULTA / CONSULT</option>
                    <option value="EJECUTABLE / EXECUTABLE" <?php if ($row["TIPO_DE_ARCHIVO"] === "EJECUTABLE / EXECUTABLE") echo "selected"; ?>>EJECUTABLE / EXECUTABLE</option>
                    <option value="EDITABLE / EDITABLE" <?php if ($row["TIPO_DE_ARCHIVO"] === "EDITABLE / EDITABLE") echo "selected"; ?>>EDITABLE / EDITABLE</option>
                    <option value="FABRICACION / FABRICATION" <?php if ($row["TIPO_DE_ARCHIVO"] === "FABRICACION / FABRICATION") echo "selected"; ?>>FABRICACION / FABRICATION</option>
                    <option value="DESARROLLO / DEVELOP" <?php if ($row["TIPO_DE_ARCHIVO"] === "DESARROLLO / DEVELOP") echo "selected"; ?>>DESARROLLO / DEVELOP</option>
                </select>

                <label for="AREA_ASOCIADA">AREA ASOCIADA / ASSOCIATED AREA:</label>
                <select ITEM="AREA_ASOCIADA" class="box" name="AREA_ASOCIADA">
                    <option value="OPERACIONES / OPERATIONS" <?php if ($row["AREA_ASOCIADA"] === "OPERACIONES / OPERATIONS") echo "selected"; ?>>OPERACIONES / OPERATIONS</option>
                    <option value="SEGURITEMAD / SAFETY" <?php if ($row["AREA_ASOCIADA"] === "SEGURITEMAD / SAFETY") echo "selected"; ?>>SEGURITEMAD / SAFETY</option>
                    <option value="INGENIERIA / ENGINEERING" <?php if ($row["AREA_ASOCIADA"] === "INGENIERIA / ENGINEERING") echo "selected"; ?>>INGENIERIA / ENGINEERING</option>
                    <option value="COMERCIAL / COMMERCIAL" <?php if ($row["AREA_ASOCIADA"] === "COMERCIAL / COMMERCIAL") echo "selected"; ?>>COMERCIAL / COMMERCIAL</option>
                    <option value="ADMINISTRATIVO / ADMINISTRATIVE" <?php if ($row["AREA_ASOCIADA"] === "ADMINISTRATIVO / ADMINISTRATIVE") echo "selected"; ?>>ADMINISTRATIVO / ADMINISTRATIVE</option>
                </select>

                <label for="PROPIETARIO">PROPIETARIO / OWNER (ACUTAL):</label>
                <select ITEM="PROPIETARIO" class="box" name="PROPIETARIO">
                    <option value="PROPIO / OWN" <?php if ($row["PROPIETARIO"] === "PROPIO / OWN") echo "selected"; ?>>PROPIO / OWN</option>
                    <option value="CLIENTE / CLIENT" <?php if ($row["PROPIETARIO"] === "CLIENTE / CLIENT") echo "selected"; ?>>CLIENTE / CLIENT</option>
                    <option value="SUMINISTRADOR / SUPPLY" <?php if ($row["PROPIETARIO"] === "SUMINISTRADOR / SUPPLY") echo "selected"; ?>>SUMINISTRADOR / SUPPLY</option>
                    <option value="PUBLICO / PUBLIC" <?php if ($row["PROPIETARIO"] === "PUBLICO / PUBLIC") echo "selected"; ?>>PUBLICO / PUBLIC</option>
                    <option value="OFICIAL / OFFICIAL" <?php if ($row["PROPIETARIO"] === "OFICIAL / OFFICIAL") echo "selected"; ?>>OFICIAL / OFFICIAL</option>
                </select>

                <label for="DISPONIBLE">DISPONIBLE / AVAILABLE:</label>
                <select ITEM="DISPONIBLE" class="box" name="DISPONIBLE">
                    <option value="SI" <?php if ($row["DISPONIBLE"] === "SI") echo "selected"; ?>>SI</option>
                    <option value="NO" <?php if ($row["DISPONIBLE"] === "NO") echo "selected"; ?>>NO</option>
                </select>
                </div>

                <div class="llenado_user">
                <label for="CODIGO">CÓDIGO/CODE:</label>
                <input type="text" ITEM="CODIGO" class="box" name="CODIGO" value="<?php echo $row["CODIGO"]; ?>">

                <label for="DESCRIPCION">DESCRIPCIÓN/DESCRIPTION:</label>
                <input type="text" ITEM="DESCRIPCION" class="box" name="DESCRIPCION" value="<?php echo $row["DESCRIPCION"]; ?>">

                <label for="REVISION">No. DE REVISIÓN / No. REVISION:</label>
                <input type="text" ITEM="REVISION" class="box" name="REVISION" value="<?php echo $row["REVISION"]; ?>">

                <label for="AUTOR">AUTOR / AUTHOR:</label>
                <input type="text" ITEM="AUTOR" class="box" name="AUTOR" value="<?php echo $row["AUTOR"]; ?>">

                <label for="IDIOMA">ITEMIOMA / LANGUAGE:</label>
                <input type="text" ITEM="IDIOMA" class="box" name="IDIOMA" value="<?php echo $row["IDIOMA"]; ?>">

                <label for="PUBLICACION">AÑO DE PUBLICACION / YEAR OF PUBLICATION:</label>
                <input type="number" ITEM="PUBLICACION" class="box" name="PUBLICACION" min="1900" max="2099" value="<?php echo $row["PUBLICACION"]; ?>">

                <label for="archivo">ARCHIVO ADJUNTO:</label>
                            <input type="file" name="archivo" class="files-btc" accept=".pdf, .doc, .docx">
                            <br><br>
                            <div class="boton_save" ><button type="submit">AGREGAR</button></div>
                            </div>
                        </form>
        </div>
        </body>
        </html>
        <?php
    } else {
        echo "No se encontró el registro.";
    }
}

// Cerrar la conexión
$conn->close();
?>