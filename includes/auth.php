<?php
if (!isset($_SESSION['usuario'])) {
    header('Location: login.html');
    exit();
}
?>