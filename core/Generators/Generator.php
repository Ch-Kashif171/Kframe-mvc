<?php
declare(strict_types=1);

namespace Core\Generators;

use Core\Support\Constants;
use Core\Support\DB;

define('ROOT_PATH', defined('root_path') ? root_path : dirname(__DIR__, 2));

class Generator
{
    public static ?self $instance = null;

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }

    public function generateController(string $controllerName): array
    {
        $pathParts = preg_split('/[\\\\\/]/', $controllerName);
        $className = ucfirst(array_pop($pathParts));
        $directory = implode(DIRECTORY_SEPARATOR, $pathParts);
        $baseDir = ROOT_PATH . '/app/Http/Controllers' . ($directory ? '/' . $directory : '');
        $controllerFile = "$baseDir/$className.php";

        if (file_exists($controllerFile)) {
            return ['status' => false, 'message' => "$className Controller Already Exists"];
        }

        $templatePath = ROOT_PATH . '/core/Templates/Controllers/ControllerTemplate.php';
        if (!file_exists($templatePath)) {
            return ['status' => false, 'message' => 'Controller Template File Not Found'];
        }

        if (!file_exists($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        $templateContent = file_get_contents($templatePath);
        $controllerContent = str_replace('controllername', $className, $templateContent);
        $controllerContent = $this->prependNamespace($controllerContent, $directory);
        file_put_contents($controllerFile, $controllerContent);

        return ['status' => true, 'message' => "$className Controller Generated Successfully"];
    }

    public function generateModel(string $modelName): array
    {
        $modelFile = ROOT_PATH . '/app/Models/' . $modelName . '.php';
        if (file_exists($modelFile)) {
            return ['status' => false, 'message' => "$modelName Model Already Exists"];
        }

        $templatePath = ROOT_PATH . '/core/Templates/Models/ModelTemplate.php';
        if (!file_exists($templatePath)) {
            return ['status' => false, 'message' => 'Model Template File Not Found'];
        }

        $templateContent = file_get_contents($templatePath);
        $className = ucfirst(basename(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $modelName)));
        $modelContent = str_replace('modelname', $className, $templateContent);

        file_put_contents($modelFile, $modelContent);
        return ['status' => true, 'message' => "$className Model Generated Successfully"];
    }

    public function generateAuth(string $controllerName): array
    {
        $responses = [
            $this->generateAuthControllers(),
            $this->generateRoutes(),
            $this->generateViews()
        ];

        $errors = array_merge(...array_column($responses, 'errors'));

        return empty($errors)
            ? ['status' => true, 'message' => 'Auth scaffolding created successfully']
            : ['status' => false, 'message' => implode("\n", $errors)];
    }

    private function generateRoutes(): array
    {
        $errors = [];
        $templatePath = ROOT_PATH . '/core/Templates/Routes/RouteTemplate.php';
        $routeFile = ROOT_PATH . '/routes/web.php';

        if (file_exists($templatePath)) {
            $routeContent = file_get_contents($templatePath);
            if (!str_contains(file_get_contents($routeFile), $routeContent)) {
                file_put_contents($routeFile, $routeContent, FILE_APPEND | LOCK_EX);
            } else {
                $errors[] = 'Auth routes already exist!';
            }
        }

        return ['errors' => $errors];
    }

    private function generateViews(): array
    {
        $errors = [];
        $views = [
            'auth/login' => 'auth/login',
            'auth/register' => 'auth/register',
            'partials/header' => 'partials/header',
            'home' => 'home'
        ];

        foreach ($views as $view => $template) {
            $viewPath = ROOT_PATH . "/views/$view.php";
            $templatePath = ROOT_PATH . "/core/Templates/Views/$template.php";

            if (file_exists($viewPath) && $view !== 'partials/header') {
                $errors[] = ucfirst(basename($view)) . ' view already exists';
                continue;
            }

            if (file_exists($templatePath)) {
                if (!is_dir(dirname($viewPath))) {
                    mkdir(dirname($viewPath), 0777, true);
                }
                file_put_contents($viewPath, file_get_contents($templatePath));
            } else {
                $errors[] = ucfirst($template) . ' template not found';
            }
        }

        return ['errors' => $errors];
    }

    private function generateAuthControllers(): array
    {
        $errors = [];

        // Generate Auth Controllers
        $errors = array_merge($errors, $this->generateControllerSet([
            'Login'    => 'AuthControllerTemplate',
            'Register' => 'RegisterControllerTemplate',
        ], '/Auth'));

        // Generate Home Controller
        $homeError = $this->generateHomeController();
        if ($homeError) {
            $errors[] = $homeError;
        }

        return ['errors' => $errors];
    }

    private function generateControllerSet(array $controllers, string $subDir): array
    {
        $errors = [];

        foreach ($controllers as $name => $templateFile) {
            $controllerClass = str_ends_with($name, 'Controller') ? $name : $name . 'Controller';
            $controllerPath = ROOT_PATH . "/app/Http/Controllers$subDir/$controllerClass.php";
            $templatePath = ROOT_PATH . "/core/Templates/Controllers/$templateFile.php";

            if (file_exists($controllerPath)) {
                $errors[] = "$controllerClass already exists";
                continue;
            }

            if (!file_exists($templatePath)) {
                $errors[] = "$controllerClass template not found";
                continue;
            }

            $content = str_replace('controllername', $controllerClass, file_get_contents($templatePath));
            if (!is_dir(dirname($controllerPath))) {
                mkdir(dirname($controllerPath), 0777, true);
            }
            file_put_contents($controllerPath, $content);
        }

        return $errors;
    }

    private function generateHomeController(): ?string
    {
        $homeControllerClass = 'HomeController';
        $templatePath = ROOT_PATH . '/core/Templates/Controllers/HomeControllerTemplate.php';
        $controllerPath = ROOT_PATH . '/app/Http/Controllers/HomeController.php';

        if (!file_exists($templatePath)) {
            return 'Home controller template file not found';
        }

        $templateContent = file_get_contents($templatePath);

        $controllerContent = str_contains($templateContent, 'controllername')
            ? str_replace('controllername', $homeControllerClass, $templateContent)
            : $templateContent;

        if (!is_dir(dirname($controllerPath))) {
            mkdir(dirname($controllerPath), 0777, true);
        }

        file_put_contents($controllerPath, $controllerContent);

        return null; // No error
    }

    public function generateMigration(string $action): array
    {
        $migrationDir = ROOT_PATH . DIRECTORY_SEPARATOR . Constants::MIGRATION_DIR . DIRECTORY_SEPARATOR ;
        DB::rawQuery("CREATE TABLE IF NOT EXISTS `migrations` (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255) NOT NULL, is_migrate VARCHAR(255) NOT NULL);");

        if ($action === 'rollback') {
            $result = DB::rawQuery("SELECT * FROM migrations WHERE is_migrate = '1' ORDER BY id DESC LIMIT 1");
            if (empty($result)) {
                return ['status' => false, 'message' => 'No migrations to rollback.'];
            }

            $className = $result[0]['migration'];
            foreach (glob("$migrationDir*.php") as $file) {
                require_once $file;
                if (class_exists($className)) {
                    $instance = new $className();
                    if (method_exists($instance, 'down')) {
                        $instance->down();
                        DB::rawQuery("DELETE FROM migrations WHERE migration = '$className' LIMIT 1");
                        return ['status' => true, 'message' => "Rolled back: $className"];
                    }
                }
            }

            return ['status' => false, 'message' => "Could not rollback migration: $className"];
        }

        if ($action === 'migrate') {
            $ran = 0;
            foreach (glob("$migrationDir*.php") as $file) {
                require_once $file;
                $className = $this->extractClassName($file);
                if ($className && class_exists($className)) {
                    $exists = DB::rawQuery("SELECT * FROM migrations WHERE migration = '$className' AND is_migrate = '1'");
                    if (!$exists) {
                        $instance = new $className();
                        if (method_exists($instance, 'up')) {
                            $instance->up();
                            DB::rawQuery("INSERT INTO migrations (migration, is_migrate) VALUES ('$className', '1')");
                            $ran++;
                        }
                    }
                }
            }
            return ['status' => true, 'message' => "$ran migration(s) ran."];
        }

        return ['status' => false, 'message' => 'Nothing to migrate'];
    }

    private function extractClassName(string $filePath): ?string
    {
        $base = basename($filePath, '.php');
        $parts = explode('_', $base, 5);
        return isset($parts[4]) ? str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $parts[4]))) : null;
    }

    public function generateMigrationFile(string $name): array
    {
        $templatePath = ROOT_PATH . '/core/Templates/Migrations/MigrationTemplate.php';
        if (!file_exists($templatePath)) {
            return ['status' => false, 'message' => 'Migration template file not found.'];
        }

        $className = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_" . strtolower($name) . '.php';
        $filePath = ROOT_PATH . DIRECTORY_SEPARATOR  .Constants::MIGRATION_DIR. DIRECTORY_SEPARATOR  . $fileName;

        if (file_exists($filePath)) {
            return ['status' => false, 'message' => 'Migration file already exists.'];
        }

        $tableName = $this->extractTableName($name, $className);
        $content = str_replace(['migrationname', 'table_name'], [$className, $tableName], file_get_contents($templatePath));
        file_put_contents($filePath, $content);

        return ['status' => true, 'message' => "Migration created: $fileName"];
    }

    private function extractTableName(string $name, string $className): string
    {
        if (preg_match('/create_(.+)_table/i', strtolower($name), $matches)) {
            return $matches[1];
        } elseif (preg_match('/Create([A-Za-z0-9]+)Table/', $className, $matches)) {
            return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $matches[1]));
        }
        return 'table_name';
    }

    private function prependNamespace(string $content, string $namespace): string
    {
        $namespaceLine = $namespace
            ? "\n\nnamespace App\\Http\\Controllers\\" . str_replace('/', '\\', $namespace) . ';'
            : "\n\nnamespace App\\Http\\Controllers;";
        return str_replace('<?php', '<?php' . $namespaceLine, $content);
    }
}
