<?php

/**
 * Artisan In-Process Runner
 * 
 * Boots Laravel and executes commands in-process without spawning OS sub-processes.
 * Useful in sandboxed or limited execution environments.
 */

define('LARAVEL_START', microtime(true));

// 1. Locate autoload and bootstrap
$autoload = __DIR__ . '/../vendor/autoload.php';
$bootstrap = __DIR__ . '/../bootstrap/app.php';

if (!file_exists($autoload)) {
    echo "<h3>Error: vendor/autoload.php not found!</h3>";
    echo "<p>Please ensure you copy the <strong>vendor</strong> folder from an existing project or run <code>composer install</code> in the root directory: <code>C:\\Users\\dell\\.gemini\\antigravity-ide\\scratch\\super-dawn-school</code></p>";
    exit;
}

require $autoload;
$app = require_once $bootstrap;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

// 2. Resolve Console Kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 3. Simple Action Router
$action = $_GET['action'] ?? 'status';
$output = '';

try {
    switch ($action) {
        case 'migrate':
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();
            break;
            
        case 'seed':
            Artisan::call('db:seed', ['--force' => true]);
            $output = Artisan::output();
            break;
            
        case 'migrate-seed':
            Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
            $output = Artisan::output();
            break;
            
        case 'key-generate':
            Artisan::call('key:generate');
            $output = Artisan::output();
            break;

        case 'clear-cache':
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            $output = "All caches cleared successfully!\n" . Artisan::output();
            break;

        case 'status':
        default:
            $connectionStatus = "Disconnected";
            $dbName = "Unknown";
            try {
                $dbName = DB::connection()->getDatabaseName();
                $connectionStatus = "Connected to database: " . $dbName;
            } catch (\Exception $e) {
                $connectionStatus = "Failed to connect to DB: " . $e->getMessage();
            }

            $output = "Status:\n";
            $output .= "Laravel Version: " . app()->version() . "\n";
            $output .= "PHP Version: " . phpversion() . "\n";
            $output .= "Database Status: " . $connectionStatus . "\n";
            $output .= "\nTo run actions, append '?action=' to the URL. Supported actions:\n";
            $output .= "- migrate (Runs database migrations)\n";
            $output .= "- seed (Seeds the database)\n";
            $output .= "- migrate-seed (Runs fresh migrations and seeds the database)\n";
            $output .= "- key-generate (Generates application encryption key)\n";
            $output .= "- clear-cache (Clears application and configuration caches)\n";
            break;
    }
} catch (\Exception $e) {
    $output = "Execution failed with Exception:\n" . $e->getMessage() . "\n\nStack Trace:\n" . $e->getTraceAsString();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel Artisan Runner</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #0f172a; color: #f1f5f9; padding: 40px; margin: 0; }
        .container { max-width: 900px; margin: 0 auto; background: #1e293b; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        h1 { margin-top: 0; color: #38bdf8; font-size: 24px; border-bottom: 1px solid #334155; padding-bottom: 15px; }
        .links { margin: 20px 0; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { display: inline-block; padding: 10px 16px; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; font-weight: 500; font-size: 14px; transition: background 0.2s; }
        .btn:hover { background: #1d4ed8; }
        .btn-danger { background: #dc2626; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-secondary { background: #475569; }
        .btn-secondary:hover { background: #334155; }
        pre { background: #090d16; padding: 20px; border-radius: 8px; overflow-x: auto; color: #10b981; font-family: "Courier New", Courier, monospace; border: 1px solid #1e293b; font-size: 14px; line-height: 1.5; white-space: pre-wrap; }
        .footer { margin-top: 30px; font-size: 12px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Super Dawn School Lakhi - Artisan Runner</h1>
        
        <div class="links">
            <a href="?action=status" class="btn btn-secondary">Status Check</a>
            <a href="?action=key-generate" class="btn">Generate Key</a>
            <a href="?action=migrate" class="btn">Run Migrations</a>
            <a href="?action=seed" class="btn">Run Seeders</a>
            <a href="?action=migrate-seed" class="btn btn-danger" onclick="return confirm('WARNING: This will wipe all tables (migrate:fresh) and run seeders. Are you sure?');">Wipe, Migrate & Seed</a>
            <a href="?action=clear-cache" class="btn btn-secondary">Clear Cache</a>
        </div>

        <pre><?php echo htmlspecialchars($output); ?></pre>

        <div class="footer">
            Laravel CLI Web Wrapper. Use for development purposes only.
        </div>
    </div>
</body>
</html>
