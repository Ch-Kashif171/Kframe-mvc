<?php
declare(strict_types=1);

namespace Core\Generators;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . 'Migration.php';

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
    public  function generateMigration($migrate)
    {
        $class = new \Migration();
        $message = $class->up();
        return [
            'status' => true,
            'message' => $message
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

}
