<?php
/**
 * Einfacher .env Datei Loader
 * Lädt Umgebungsvariablen aus einer .env Datei
 */
function loadEnv($envFile) {
    if (!file_exists($envFile)) {
        throw new Exception("Umgebungsdatei nicht gefunden: $envFile");
    }
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Kommentare überspringen
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Zeile in Key=Value aufteilen
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Anführungszeichen entfernen falls vorhanden
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            // In $_ENV und putenv() setzen
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

/**
 * Umgebungsvariable abrufen mit Fallback
 */
function getEnvironmentVar($key, $default = null) {
    $value = $_ENV[$key] ?? getenv($key) ?? $default;
    if ($value === null) {
        throw new Exception("Umgebungsvariable '$key' nicht gefunden");
    }
    return $value;
}
?>
