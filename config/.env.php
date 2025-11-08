<?php
/**
 * Environment Variables Loader
 * This file loads the environment variables from .env file
 */

function loadEnv() {
    $envFile = __DIR__ . '/.env';
    
    if (!file_exists($envFile)) {
        die('.env file not found');
    }

    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        if (!empty($key)) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// Load environment variables
loadEnv();