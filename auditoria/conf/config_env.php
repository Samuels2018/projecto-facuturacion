<?php

function loadEnv($file) {
    if (!is_readable($file)) {
        throw new \InvalidArgumentException('No se puede leer el archivo .env');
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Ignora las líneas comentadas
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value)); // Exporta la variable de entorno
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Carga las variables de entorno desde el archivo .env
loadEnv(__DIR__ . '/../../.env');