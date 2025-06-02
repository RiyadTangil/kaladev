<?php

// This script fixes the mail configuration in the .env file

$envFile = __DIR__ . '/.env';
$envContent = file_get_contents($envFile);

// Fix the MAIL_HOST and MAIL_PORT values
$envContent = preg_replace('/MAIL_HOST=587/', 'MAIL_HOST=smtp.gmail.com', $envContent);
$envContent = preg_replace('/MAIL_PORT=smtp.gmail.com/', 'MAIL_PORT=587', $envContent);

// Fix the MAIL_ENCRYPTION value
$envContent = preg_replace('/MAIL_ENCRYPTION=false/', 'MAIL_ENCRYPTION=tls', $envContent);

// Write the updated content back to the .env file
file_put_contents($envFile, $envContent);

echo "Mail configuration fixed successfully!\n"; 