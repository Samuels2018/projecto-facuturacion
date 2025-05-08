<?php

function processNumericFilter($value) {
    $value = trim($value);
    
    // Rango (ejemplo: 10-45, 10.50-45.75)
    if (preg_match('/^([0-9]*\.?[0-9]+)\s*-\s*([0-9]*\.?[0-9]+)$/', $value, $matches)) {
        return [
            'type' => 'range',
            'min' => (float)$matches[1],
            'max' => (float)$matches[2],
            'raw' => $value
        ];
    }
    // Mayor que (>10, >10.50)
    else if (preg_match('/^>\s*([0-9]*\.?[0-9]+)$/', $value, $matches)) {
        return [
            'type' => 'gt',
            'value' => (float)$matches[1],
            'raw' => $value
        ];
    }
    // Menor que (<10, <10.50)
    else if (preg_match('/^<\s*([0-9]*\.?[0-9]+)$/', $value, $matches)) {
        return [
            'type' => 'lt',
            'value' => (float)$matches[1],
            'raw' => $value
        ];
    }
    // Valor exacto (10, 10.50)
    else if (is_numeric($value)) {
        return [
            'type' => 'eq',
            'value' => (float)$value,
            'raw' => $value
        ];
    }
    // Búsqueda por texto
    return [
        'type' => 'like',
        'value' => $value,
        'raw' => $value
    ];
}
