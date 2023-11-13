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
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Documentación Técnica</title>
  <link rel="stylesheet" href="../estilos/add.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="container">

  <form class="form" action="DocumentacionTecnica.php" method="POST" enctype="multipart/form-data">
    
    <h2>Documentación Tecnica</h2>
    <div class="llenado_permiso">

    <label for="TIPO_DE_DOCUMENTO">TIPO DE DOCUMENTO/DOCUMENT TYPE:</label>
    <select name="TIPO_DE_DOCUMENTO" id="TIPO_DE_DOCUMENTO" class="box" required>
    <option></option>
    <option>PLANO/PLANE</option>
    <option>MANUAL TECNICO / TECHNICAL MANUAL</option>
    <option>MANUAL DE FORMACION / TRAINING MANUAL</option>
    <option>FICHA TECNICA / DATA SHEET</option>
    <option>INSTRUCCIÓN TECNICA / TECHNICAL INSTRUCTION</option>
    <option>GUIA / GUIDE</option>
    <option>PROCEDIMIENTO / PROCESS</option>
    <option>INSTRUCTIVO / INSTRUCTIVE</option>
    <option>CROQUIS / SKETCH</option>
    <option>DOCUMENTO COMERCIAL  / COMERCIAL DOCUMENT</option>
    <option>LIBRO / BOOK</option>
    <option>NORMA / NORME</option>
    <option>RECOMENDACIÓN / RECOMENDATION</option>
    <option>ITC</option>
    <option>REGLAMENTO / REGLAMENT</option>
    <option>LEY / LAW</option>
    <option>FORMATO / FORMAT</option>
    <option>ESPECIFICACION TECNICA / TECHNICAL SPECIFICATION</option>
    </select>

    <label for="TECNOLOGIA_ASOCIADA">TECNOLOGIA ASOCIADA / ASSOCIATED TECHNOLOGY:</label>
    <select name="TECNOLOGIA_ASOCIADA" id="TECNOLOGIA_ASOCIADA" class="box" required>
    <option></option>
    <option>GOLDWIND</option>
    <option>AWP</option>
    <option>GAMESA</option>
    <option>SIEMENS</option>
    <option>SIEMENS GAMESA</option>
    <option>GE</option>
    <option>NORDEX</option>
    <option>VESTAS</option>
    <option>LM</option>
    <option>TPI</option>
    <option>LIBERTY</option>
    <option>BOP</option>
    <option>AVANTI</option>
    <option>FIBERBLADE</option>
    <option>3S</option>
    <option>INGETEAM</option>
    <option>OTROS/ OTHERS</option>
    <option>NO APLICA/ NA</option>
    </select>

    <label for="TIPO_DE_ARCHIVO">TIPO DE ARCHIVO / TYPE OF FILE:</label>
    <select name="TIPO_DE_ARCHIVO" id="TIPO_DE_ARCHIVO" class="box" required>
    <option></option>
    <option>CONSULTA / CONSULT</option>
    <option>EJECUTABLE / EXECUTABLE</option>
    <option>EDITABLE / EDITABLE</option>
    <option>FABRICACION / FABRICATION</option>
    <option>DESARROLLO / DEVELOP</option>
    </select>

    <label for="AREA_ASOCIADA">AREA ASOCIADA / ASSOCIATED AREA:</label>
    <select name="AREA_ASOCIADA" id="AREA_ASOCIADA" class="box" required>
    <option></option>
    <option>OPERACIONES / OPERATIONS</option>
    <option>SEGURIDAD / SAFETY</option>
    <option>INGENIERIA / ENGINEERING</option>
    <option>COMERCIAL / COMMERCIAL</option>
    <option>ADMINISTRATIVO / ADMINISTRATIVE</option>
    </select>

    <label for="PROPIETARIO">PROPIETARIO / OWNER:</label>
    <select name="PROPIETARIO" id="PROPIETARIO" class="box" required>
    <option></option>
    <option>PROPIO / OWN</option>
    <option>CLIENTE / CLIENT</option>
    <option>SUMINISTRADOR / SUPPLY</option>
    <option>PUBLICO / PUBLIC</option>
    <option>OFICIAL / OFFICIAL</option>
    </select>

    <label for="DISPONIBLE">DISPONIBLE / AVAILABLE:</label>
    <select name="DISPONIBLE" id="DISPONIBLE" class="box" required>
      <option></option>
      <option>SI</option>
      <option>NO</option>
      </select>
      </div>

      <div class="llenado_user">

    <label for="CODIGO">CÓDIGO/CODE:</label>
    <input type="text" id="CODIGO" class="box" name="CODIGO" autocomplete="CODIGO" required >

    <label for="DESCRIPCION">DESCRIPCIÓN/DESCRIPTION:</label>
    <input type="text" id="DESCRIPCION" class="box" name="DESCRIPCION" autocomplete="DESCRIPCION" required >

    <label for="REVISION">No. DE REVISIÓN / No. REVISION:</label>
    <input type="text" id="REVISION" class="box" name="REVISION" autocomplete="REVISION" required >

    <label for="AUTOR">AUTOR / AUTHOR:</label>
    <input type="text" id="AUTOR" class="box" name="AUTOR" autocomplete="AUTOR" required >

    <label for="IDIOMA">IDIOMA / LANGUAGE:</label>
    <input type="text" id="IDIOMA" class="box" name="IDIOMA" autocomplete="IDIOMA" required >

    <label for="PUBLICACION">AÑO DE PUBLICACION / YEAR OF PUBLICATION:</label>
    <input type="number" id="PUBLICACION" class="box" name="PUBLICACION" min="1900" max="2099" required>

    <label for="archivo">ARCHIVO ADJUNTO:</label>
    <input type="file" name="archivo" class="files-btc" accept=".pdf, .doc, .docx">

    <br><br>
    <div class="boton_save" ><button type="submit">AGREGAR</button></div>
</form>
</div>
</body>
</html>