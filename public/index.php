<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Register shutdown handler for FatalError BEFORE bootstrapping Laravel
// This ensures we can catch FatalErrors that occur during class loading or parsing
register_shutdown_function(function () {
    $error = error_get_last();
    
    // Check if it's a fatal error (E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR)
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR])) {
        try {
            // Try to log using Laravel's logger if available (after bootstrap)
            if (function_exists('app') && app()->bound('log')) {
                $log = app('log');
                $log->error('FatalError detected in shutdown handler', [
                    'type' => $error['type'],
                    'message' => $error['message'],
                    'file' => $error['file'],
                    'line' => $error['line'],
                    'url' => request()?->fullUrl() ?? ($_SERVER['REQUEST_URI'] ?? 'CLI'),
                    'method' => request()?->method() ?? ($_SERVER['REQUEST_METHOD'] ?? 'CLI'),
                    'ip' => request()?->ip() ?? ($_SERVER['REMOTE_ADDR'] ?? null),
                ]);
            } else {
                // Fallback: use error_log if Laravel is not bootstrapped yet
                $logPath = defined('LARAVEL_START') && function_exists('storage_path') 
                    ? storage_path('logs/laravel.log')
                    : __DIR__ . '/../storage/logs/laravel.log';
                
                $errorMessage = sprintf(
                    "[%s] FatalError [%d]: %s in %s:%d | URL: %s | Method: %s\n",
                    date('Y-m-d H:i:s'),
                    $error['type'],
                    $error['message'],
                    $error['file'],
                    $error['line'],
                    $_SERVER['REQUEST_URI'] ?? 'CLI',
                    $_SERVER['REQUEST_METHOD'] ?? 'CLI'
                );
                
                // Ensure directory exists
                $logDir = dirname($logPath);
                if (!is_dir($logDir)) {
                    @mkdir($logDir, 0755, true);
                }
                
                error_log($errorMessage, 3, $logPath);
            }
        } catch (\Throwable $e) {
            // Last resort: use PHP's error_log to stderr
            error_log(sprintf(
                "FatalError [%d]: %s in %s:%d | Failed to log via Laravel: %s",
                $error['type'],
                $error['message'],
                $error['file'],
                $error['line'],
                $e->getMessage()
            ));
        }
    }
});

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
