<?php

function generateCSV($data) {
    $filename = tempnam(sys_get_temp_dir(), 'logs_') . '.csv';
    $file = fopen($filename, 'w');

    // Add headers
    fputcsv($file, array_keys($data[0]));

    // Add rows
    foreach ($data as $row) {
        fputcsv($file, $row);
    }

    fclose($file);

    // Encode in Base64
    $csvBase64 = base64_encode(file_get_contents($filename));

    // Clean up temporary file
    unlink($filename);

    return $csvBase64;
}
