<?php

// Establecer zona horaria de España
date_default_timezone_set('Europe/Madrid');

// Configuración: dónde almacenar los logs ('database' o 'file')
define('LOG_STORAGE', 'database'); // Cambia a 'database' si deseas usar la base de datos

require_once 'LogController.php';

// Parse request
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents('php://input'), true) ?: $_GET;

// Route to controller
$controller = new LogController(LOG_STORAGE);
$controller->handleRequest($method, $action, $data);
