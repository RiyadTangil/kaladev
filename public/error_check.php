<?php
$logFile = '../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    $errors = [];
    
    // Extract error messages and stack traces
    preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*?(?:error|exception).*?(?:\n.*?){0,50}?(?=\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]|$)/is', $logContent, $errors);
    
    if (!empty($errors[0])) {
        echo "<h1>Last 3 Errors</h1>";
        echo "<pre>";
        for ($i = max(0, count($errors[0]) - 3); $i < count($errors[0]); $i++) {
            echo htmlspecialchars($errors[0][$i]) . "\n\n";
        }
        echo "</pre>";
    } else {
        echo "No errors found in the log file.";
    }
} else {
    echo "Log file not found.";
}
?> 