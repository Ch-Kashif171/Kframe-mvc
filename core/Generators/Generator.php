<?php
declare(strict_types=1);

namespace Core\Generators;

define('ROOT_PATH', defined('root_path') ? root_path : dirname(__DIR__, 2));

class Generator {

    /**
     * @var Generator
     */
    public static $instance;
    public function __construct() {
        self::$instance = $this;
    }

    /**
     * @return Generator
     */
    public static function getInstance(){
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $controllerName
     * @return array
     */
    public function generateController(string $controllerName): array {
        $controllerFile = ROOT_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . ucfirst($controllerName) . '.php';
        if (file_exists($controllerFile)) {
            return [
                'status' => false,
                'message' => ucfirst($controllerName) . ' Controller Already Exist'
            ];
        }
        $templateFile = ROOT_PATH . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'ControllerTemplate.php';
        if (file_exists($templateFile)) {
            $controllerClass = $controllerName;
            if (str_contains($controllerName, '\\')) {
                $controller_name_arr = explode('\\', $controllerName);
                $controllerClass = end($controller_name_arr);
            } elseif (str_contains($controllerName, '/')) {
                $controller_name_arr = explode('/', $controllerName);
                $controllerClass = end($controller_name_arr);
            } else {
                $controller_name_arr = [$controllerName];
            }
            if (str_contains(file_get_contents($templateFile), 'controllername')) {
                $directory = '';
                if (count($controller_name_arr) > 1) {
                    $directory = $controller_name_arr[0];
                    $controllerDir = ROOT_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . $directory;
                    if (!file_exists($controllerDir)) {
                        mkdir($controllerDir, 0777, true);
                    }
                }

                $newContent = str_replace('controllername', ucfirst($controllerClass), file_get_contents($templateFile));
                $updatedContent = $this->prependNamespace($newContent, $directory);
                file_put_contents($controllerFile, $updatedContent);
                return [
                    'status' => true,
                    'message' => ucfirst($controllerName) . ' Controller Generated Successfully'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Controller Template File Not Found'
                ];
            }
        }
        return [
            'status' => false,
            'message' => 'Controller Template File Not Found'
        ];
    }

    /**
     * @param $modelname
     * @return array
     */
    public  function generateModel($modelname){

        if (file_exists(getcwd(). '/app/models'.'/'.$modelname.'.php')) {
            return [
                'status' => false,
                'message' => ucfirst($modelname).'Model Build Not Successful, Model Already Exist'
            ];
        }
        $templatefile = getcwd(). '/core/Templates/Models/ModelTemplate.php';
        if(file_exists($templatefile)){

            if (strpos($modelname,'\\') !== false){
                $model_name_arr = explode('\\',$modelname);
                $model_class_name = end($model_name_arr);
            } elseif (strpos($modelname,'/') !== false){
                $model_name_arr = explode('/',$modelname);
                $model_class_name = end($model_name_arr);
            } else {
                $model_class_name = $modelname;
            }

            if( strpos(file_get_contents($templatefile),'modelname') !== false) {
                $newcontent = str_replace('modelname', $model_class_name, file_get_contents($templatefile));
                $modelfile = getcwd(). '/app/models'.'/'.$modelname.'.php';
                fopen($modelfile, 'w');
                file_put_contents($modelfile,$newcontent);
                return [
                    'status' => true,
                    'message' => ucfirst($modelname).' Model Generated Successfully'
                ];
            }
            else {
                return [
                    'status' => false,
                    'message' => 'Model Template File Not Found'
                ];
            }
        }
    }

    /**
     * @param $controllerName
     * @return array
     */
    public  function generateAuth($controllerName)
    {
        $response['errors'] = [];

        if (!file_exists(getcwd(). '/app/controllers/Auth')) {
            mkdir(getcwd(). '/app/Controllers/Auth', 0777, true);
        }

        /*loginController*/
        $controllerName = 'Login';
        if (file_exists(getcwd(). '/app/Controllers/Auth/LoginController.php')) {
            $response['errors'][] = 'LoginController Already Exist';
        }
        $templatefile = getcwd(). '/core/Templates/Controllers/AuthControllerTemplate.php';
        if(file_exists($templatefile)){
            if( strpos(file_get_contents($templatefile),'controllername') !== false) {
                $newcontent = str_replace('controllername', ucfirst($controllerName).'Controller', file_get_contents($templatefile));
                $controllerfile = getcwd(). '/app/Controllers/Auth'.'/'.ucfirst($controllerName).'Controller.php';
                $newfile = fopen($controllerfile, 'w');
                file_put_contents($controllerfile,$newcontent);
            }
            else {
                $response['errors'][] = 'Login Controller Template File Not Found';
            }
        }/*end loginController*/


        /*RegisterController*/
        $controllerName = 'Register';
        if (file_exists(getcwd(). '/app/Controllers/Auth/RegisterController.php')) {
            $response['errors'][] = 'RegisterController Already Exist';
        }
        $templatefile = getcwd(). '/core/Templates/Controllers/RegisterControllerTemplate.php';
        if(file_exists($templatefile)){
            if( strpos(file_get_contents($templatefile),'controllername') !== false) {
                $newcontent = str_replace('controllername', ucfirst($controllerName).'Controller', file_get_contents($templatefile));
                $controllerfile = getcwd(). '/app/Controllers/Auth'.'/'.ucfirst($controllerName).'Controller.php';
                $newfile = fopen($controllerfile, 'w');
                file_put_contents($controllerfile,$newcontent);
            }
            else {
                $response['errors'][] = 'Register Controller Template File Not Found';
            }
        }
        /*RegisterController*/

        $this->generateRoutes();
        $this->generateViews();

        if (!empty($response['errors'])) {
            return [
                'status' => false,
                'message' => $response['errors']
            ];
        }
        return [
            'status' => true,
            'message' => 'Auth Scaffolding Created successfully'
        ];
    }

    /**
     * @return array
     */
    private function generateRoutes() {
        $templatefile = getcwd(). '/core/Templates/Routes/RouteTemplate.php';
        if(file_exists($templatefile)){

            $newcontent = file_get_contents($templatefile);
            $routefile = getcwd(). '/routes/route.php';

            if(str_contains(file_get_contents($routefile), $newcontent)) {

                return [
                    'status' => false,
                    'message' => "Auth routes already exists!",
                ];
            }

            $newfile = fopen($routefile, 'a');
            file_put_contents($routefile, $newcontent,FILE_APPEND | LOCK_EX);

            return [
                'status' => true,
                'Message' => 'Auth Routes has been created.',
            ];
        }
        return [
            'status' => false,
            'Message' => 'Failed to create auth routes.',
        ];
    }

    private function generateViews() {
        if (!file_exists(getcwd(). '/views/auth')) {
            mkdir(getcwd(). '/views/auth', 0777, true);
        }

        /*loginController*/
        if (file_exists(getcwd(). '/views/auth/login.php')) {
            return [
                'status' => false,
                'message' => 'Login view already exist'
            ];
        }
        if (file_exists(getcwd(). '/views/auth/register.php')) {
            return [
                'status' => false,
                'message' => 'Register view already exist'
            ];
        }
        $register_template = getcwd(). '/core/Templates/Views/auth/register.php';
        if(file_exists($register_template)){

            $newcontent = file_get_contents($register_template);
            $register_view = getcwd(). '/views/auth/register.php';
            file_put_contents($register_view,$newcontent);
        }  else {
            return [
                'status' => false,
                'message' => 'Register view template file not found'
            ];
        }

        $login_template = getcwd(). '/core/Templates/Views/auth/login.php';
        if(file_exists($login_template)){

            $newcontent = file_get_contents($login_template);
            $login_view = getcwd(). '/views/auth/login.php';
            file_put_contents($login_view,$newcontent);
        }  else {
            return [
                'status' => false,
                'message' => 'Login view template file not found'
            ];
        }

        $header_template = getcwd(). '/core/Templates/Views/partials/header.php';
        if(file_exists($header_template)){

            $newcontent = file_get_contents($header_template);
            $header_view = getcwd(). '/views/partials/header.php';
            file_put_contents($header_view,$newcontent);
        }  else {
            return [
                'status' => false,
                'message' => 'Header view template file not found'
            ];
        }
    }

    /**
     * @param $migrate
     * @return array
     */
    public function generateMigration($migrate)
    {
        // Rollback the last migration
        if ($migrate === 'rollback') {
            $db = new \Core\Database\Doctrine();
            $db->rawQuery("CREATE TABLE IF NOT EXISTS `migrations` (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255) NOT NULL, is_migrate VARCHAR(255) NOT NULL);");
            $result = $db->rawQuery("SELECT * FROM migrations WHERE is_migrate = '1' ORDER BY id DESC LIMIT 1");
            if (!$result) {
                return [
                    'status' => false,
                    'message' => 'No migrations to rollback.'
                ];
            }
            $className = $result[0]['migration'];
            // Find the migration file
            $migrationDir = getcwd() . '/migrations/';
            $files = glob($migrationDir . '*.php');
            $fileToRollback = null;
            foreach ($files as $file) {
                $base = basename($file, '.php');
                $parts = explode('_', $base, 5);
                $fileClass = isset($parts[4]) ? str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $parts[4]))) : null;
                if ($fileClass === $className) {
                    $fileToRollback = $file;
                    break;
                }
            }
            if ($fileToRollback && class_exists($className)) {
                $instance = new $className();
                if (method_exists($instance, 'down')) {
                    $instance->down();
                    $db->rawQuery("DELETE FROM migrations WHERE migration = '" . $className . "' AND is_migrate = '1' LIMIT 1");
                    return [
                        'status' => true,
                        'message' => 'Rolled back: ' . $className
                    ];
                }
            }
            return [
                'status' => false,
                'message' => 'Could not rollback migration: ' . $className
            ];
        }
        // If the argument is 'migrate', run all migration files in the migrations directory
        if ($migrate === 'migrate') {
            $migrationDir = getcwd() . '/migrations/';
            if (!file_exists($migrationDir)) {
                return [
                    'status' => false,
                    'message' => 'No migrations directory found.'
                ];
            }
            $files = glob($migrationDir . '*.php');
            if (!$files) {
                return [
                    'status' => false,
                    'message' => 'No migration files found.'
                ];
            }
            $ran = 0;
            foreach ($files as $file) {
                require_once $file;
                // Extract class name from file (e.g., 2024_05_18_123456_create_users_table.php => CreateUsersTable)
                $base = basename($file, '.php');
                // Remove timestamp prefix if present
                $parts = explode('_', $base, 5);
                $className = isset($parts[4]) ? str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $parts[4]))) : null;
                if ($className && class_exists($className)) {
                    // Check if already migrated (by class name)
                    $db = new \Core\Database\Doctrine();
                    $db->rawQuery("CREATE TABLE IF NOT EXISTS `migrations` (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255) NOT NULL, is_migrate VARCHAR(255) NOT NULL);");
                    $result = $db->rawQuery("SELECT * FROM migrations WHERE migration = '" . $className . "' AND is_migrate = '1'");
                    if (!$result) {
                        $instance = new $className();
                        if (method_exists($instance, 'up')) {
                            $instance->up();
                            $db->rawQuery("INSERT INTO migrations (migration, is_migrate) VALUES ('" . $className . "', '1')");
                            $ran++;
                        }
                    }
                }
            }
            return [
                'status' => true,
                'message' => $ran . ' migration(s) ran.'
            ];
        }

        return [
            'status' => false,
            'message' => 'Nothing to migrate'
        ];
    }

    /**
     * @param $newcontent
     * @param $namespace
     * @return array|string
     */
    private function prependNamespace($newcontent, $namespace): array|string
    {
        $specificString = "<?php";
        if ($namespace !== '') {
            $line = "\n\nnamespace " .$controllerNameSpace = "App\\Controllers\\" . $namespace . ";";
        } else {
            $line = "\n\nnamespace " .$controllerNameSpace = "App\\Controllers;";
        }

        return str_replace($specificString, $specificString . $line, $newcontent);

    }

    /**
     * @param string $name
     * @return array
     */
    public function generateMigrationFile(string $name): array
    {
        $templateFile = getcwd() . '/core/Templates/Migrations/MigrationTemplate.php';
        if (!file_exists($templateFile)) {
            return [
                'status' => false,
                'message' => 'Migration template file not found.'
            ];
        }
        // Normalize class name (StudlyCase)
        $className = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
        $timestamp = date('Y_m_d_His');
        $fileName = $timestamp . '_' . strtolower($name) . '.php';
        $migrationDir = getcwd() . '/migrations/';
        if (!file_exists($migrationDir)) {
            mkdir($migrationDir, 0777, true);
        }
        $filePath = $migrationDir . $fileName;
        if (file_exists($filePath)) {
            return [
                'status' => false,
                'message' => 'Migration file already exists.'
            ];
        }
        // Extract table name from migration name (supports both StudlyCase and snake_case)
        $tableName = 'table_name';
        if (preg_match('/create_(.+)_table/i', strtolower($name), $matches)) {
            $tableName = $matches[1];
        } elseif (preg_match('/Create([A-Za-z0-9]+)Table/', $className, $matches)) {
            // Convert CamelCase to snake_case
            $tableName = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $matches[1]));
        }
        $content = file_get_contents($templateFile);
        $content = str_replace('migrationname', $className, $content);
        $content = str_replace('table_name', $tableName, $content);
        file_put_contents($filePath, $content);
        return [
            'status' => true,
            'message' => 'Migration created: ' . $fileName
        ];
    }

}
