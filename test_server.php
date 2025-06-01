<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple test server
echo "Starting test server on port 7000..." . PHP_EOL;

// Check if sockets extension is loaded
if (!extension_loaded('sockets')) {
    echo "Sockets extension is not loaded!" . PHP_EOL;
    exit(1);
}

// Create a socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "Error creating socket: " . socket_strerror(socket_last_error()) . PHP_EOL;
    exit(1);
}

// Set options
if (!socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1)) {
    echo "Error setting socket option: " . socket_strerror(socket_last_error($socket)) . PHP_EOL;
    exit(1);
}

try {
    // Bind the socket to an address/port
    if (!socket_bind($socket, '0.0.0.0', 7000)) {
        echo "Error binding socket: " . socket_strerror(socket_last_error($socket)) . PHP_EOL;
        exit(1);
    }

    // Start listening
    if (!socket_listen($socket, 5)) {
        echo "Error listening on socket: " . socket_strerror(socket_last_error($socket)) . PHP_EOL;
        exit(1);
    }

    echo "Server successfully running on port 7000!" . PHP_EOL;
    echo "Press Ctrl+C to exit" . PHP_EOL;
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . PHP_EOL;
} finally {
    // Clean up
    if (isset($socket) && $socket) {
        socket_close($socket);
    }
}
?> 