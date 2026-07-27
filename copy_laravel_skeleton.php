<?php

$source = 'C:\laragon\www\laravel-website-enhancements-kiohana.com';
$dest = __DIR__;

echo "Copying standard Laravel skeleton files...\n";

// Helper function to recursively copy directories
function copyDir($src, $dst) {
    if (!is_dir($src)) return;
    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }
    $dir = opendir($src);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// 1. Copy bootstrap directory files (except bootstrap/cache)
if (!is_dir("$dest/bootstrap")) {
    mkdir("$dest/bootstrap", 0755, true);
}
if (file_exists("$source/bootstrap/app.php")) {
    copy("$source/bootstrap/app.php", "$dest/bootstrap/app.php");
    echo "Copied bootstrap/app.php\n";
}
if (!is_dir("$dest/bootstrap/cache")) {
    mkdir("$dest/bootstrap/cache", 0755, true);
    file_put_contents("$dest/bootstrap/cache/.gitignore", "*\n!.gitignore\n");
    echo "Created bootstrap/cache/\n";
}

// 2. Copy config directory
copyDir("$source/config", "$dest/config");
echo "Copied config directory\n";

// 3. Create app/Console and clean Console Kernel
if (!is_dir("$dest/app/Console")) {
    mkdir("$dest/app/Console", 0755, true);
}
$consoleKernel = <<<EOT
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule \$schedule): void
    {
        // \$schedule->command('inspire')->hourly();
    }

    protected function commands(): void
    {
        \$this->load(__DIR__.'/Commands');
    }
}
EOT;
file_put_contents("$dest/app/Console/Kernel.php", $consoleKernel);
echo "Created app/Console/Kernel.php\n";

// 4. Create app/Exceptions and clean Exception Handler
if (!is_dir("$dest/app/Exceptions")) {
    mkdir("$dest/app/Exceptions", 0755, true);
}
$exceptionHandler = <<<EOT
<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected \$dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        \$this->reportable(function (Throwable \$e) {
            //
        });
    }
}
EOT;
file_put_contents("$dest/app/Exceptions/Handler.php", $exceptionHandler);
echo "Created app/Exceptions/Handler.php\n";

// 5. Copy Http/Kernel.php and Middleware
if (!is_dir("$dest/app/Http")) {
    mkdir("$dest/app/Http", 0755, true);
}
if (file_exists("$source/app/Http/Kernel.php")) {
    copy("$source/app/Http/Kernel.php", "$dest/app/Http/Kernel.php");
    echo "Copied app/Http/Kernel.php\n";
}
copyDir("$source/app/Http/Middleware", "$dest/app/Http/Middleware");
echo "Copied app/Http/Middleware directory\n";

// 6. Create storage directories
$storageDirs = [
    'storage/app/public',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs'
];
foreach ($storageDirs as $dir) {
    if (!is_dir("$dest/$dir")) {
        mkdir("$dest/$dir", 0755, true);
    }
}
file_put_contents("$dest/storage/logs/laravel.log", "");
echo "Created storage directory structure\n";

echo "\nSkeleton restored successfully! You can now run 'composer install'.\n";
