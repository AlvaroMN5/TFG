<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Debes iniciar sesión");
}

$producto_id = $_POST['producto_id'];
// Validar y agregar a $_SESSION['carrito'][$producto_id]