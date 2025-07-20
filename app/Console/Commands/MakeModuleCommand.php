<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create a new module with MVC, routes, database, public assets, and layout structure';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $lowerName = Str::lower($name);
        $modulePath = base_path("app/Modules/{$name}");

        $dirs = [
            "{$modulePath}/Controllers",
            "{$modulePath}/Models",
            "{$modulePath}/Views",
            "{$modulePath}/Views/layouts",
            "{$modulePath}/Database/Migrations",
            "{$modulePath}/Database/Seeders",
            "{$modulePath}/Database/Factories",
            "{$modulePath}/Public/CSS",
            "{$modulePath}/Public/JS",
            "{$modulePath}/Public/Images",
            "{$modulePath}/Public/Docs",
        ];

        $fs = new Filesystem;

        foreach ($dirs as $dir) {
            $fs->ensureDirectoryExists($dir);
        }

        // Create Controller
        $controller = <<<EOT
<?php

namespace App\\Modules\\{$name}\\Controllers;

use App\\Http\\Controllers\\Controller;

class {$name}Controller extends Controller
{
    public function index()
    {
        return view("{$name}::index");
    }
}
EOT;
        $fs->put("{$modulePath}/Controllers/{$name}Controller.php", $controller);

        // Create Blade View (Beautiful welcome page with Tailwind & SVG)
        $view = <<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to {$name} Module</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-purple-100 to-blue-100 min-h-screen flex items-center justify-center">

    <div class="max-w-3xl w-full bg-white rounded-2xl shadow-2xl p-10 text-center relative overflow-hidden">
        <!-- Decorative SVG -->
        <svg class="absolute top-0 left-0 w-48 h-48 -z-10 opacity-20" fill="none" stroke="url(#gradient)" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="gradient" gradientTransform="rotate(45)">
                    <stop offset="0%" stop-color="#7C3AED" />
                    <stop offset="100%" stop-color="#3B82F6" />
                </linearGradient>
            </defs>
            <path stroke-width="4" d="M50 50 Q100 0 150 50 T250 50"></path>
        </svg>

        <h1 class="text-4xl font-extrabold text-purple-700 mb-4">Welcome to the <span class="text-blue-600">{$name} Module</span></h1>
        <p class="text-gray-600 text-lg mb-6">
            You have successfully created a dynamic Laravel module. This page is coming from:
            <code class="bg-gray-100 px-2 py-1 rounded">Modules/{$name}/Views/index.blade.php</code>
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
            <div class="bg-purple-100 p-6 rounded-xl shadow hover:scale-105 transition-transform duration-300">
                <h2 class="text-lg font-semibold text-purple-700">Modular Structure</h2>
                <p class="text-sm text-purple-900 mt-2">Your module is isolated with its own views, controllers, and migrations.</p>
            </div>
            <div class="bg-blue-100 p-6 rounded-xl shadow hover:scale-105 transition-transform duration-300">
                <h2 class="text-lg font-semibold text-blue-700">Auto Routing</h2>
                <p class="text-sm text-blue-900 mt-2">Routes are registered automatically. You can now build feature-wise!</p>
            </div>
        </div>

        <div class="mt-10 text-sm text-gray-500">
            &copy; <?php echo date('Y'); ?> - Laravel Modular System. Built with 💻 and ☕
        </div>
    </div>

</body>
</html>
EOT;

        $fs->put("{$modulePath}/Views/index.blade.php", $view);

        // Create Layout View
        $layout = <<<EOT
<!DOCTYPE html>
<html>
<head>
    <title>{$name} Module</title>
    <link rel="stylesheet" href="{{ asset('app/Modules/{$name}/Public/CSS/style.css') }}">
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
EOT;
        $fs->put("{$modulePath}/Views/layouts/app.blade.php", $layout);

        // Create Route File
        $routeFile = <<<EOT
<?php

use Illuminate\\Support\\Facades\\Route;
use App\\Modules\\{$name}\\Controllers\\{$name}Controller;

Route::prefix('{$lowerName}')->group(function () {
    Route::get('/', [{$name}Controller::class, 'index']);
});
EOT;
        $fs->put("{$modulePath}/routes.php", $routeFile);

        // Register routes if not already registered
        $routeServicePath = base_path('routes/modules.php');
        if (!$fs->exists($routeServicePath)) {
            $fs->put($routeServicePath, "<?php\n\n// Auto-loaded module routes\n");
        }

        $includeLine = "require base_path('app/Modules/{$name}/routes.php');";
        $content = $fs->get($routeServicePath);
        if (!str_contains($content, $includeLine)) {
            $fs->append($routeServicePath, "\n" . $includeLine);
        }

        $this->info("✅ Module '{$name}' created successfully.");
        $this->info("📦 Route auto-registered in routes/modules.php");
        $this->info("💡 Access it at: http://localhost:8000/{$lowerName}");
    }
}
