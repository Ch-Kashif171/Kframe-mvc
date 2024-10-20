<?php

namespace Core\Generators;

require_once __DIR__ . '/../'."../migrations/Migration.php";

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
    public  function generateController($controllerName){

        if (file_exists(getcwd(). '//Controllers'.'/'.$controllerName.'.php')) {
            return [
            'status' => false,
            'message' => ucfirst($controllerName).' Controller Already Exist'
            ];
        }
        $templatefile = getcwd(). '/core/Controllers/templates/ControllerTemplate.php';
        if(file_exists($templatefile)){

            if (str_contains($controllerName, '\\')){
                $controller_name_arr = explode('\\',$controllerName);
                $controllerClass = end($controller_name_arr);
            } elseif (str_contains($controllerName, '/')){
                $controller_name_arr = explode('/',$controllerName);
                $controllerClass = end($controller_name_arr);
            } else {
                $controllerClass = $controllerName;
            }

            if(str_contains(file_get_contents($templatefile), 'controllername')) {
                $controllerfile = getcwd(). '/app/Controllers'.'/'.ucfirst($controllerName).'.php';

                if (file_exists($controllerfile)) {
                    return [
                        'status' => false,
                        'message' => $controllerfile . ' controller already exists'
                    ];
                }

                $newcontent = str_replace('controllername', ucfirst($controllerClass), file_get_contents($templatefile));

                $directory = $controller_name_arr[0];

                $updatedContent = $this->prependNamespace($newcontent, $directory);

                if (!file_exists(getcwd(). '/app/controllers/' . $directory)) {
                    mkdir(getcwd(). '/app/Controllers/' . $directory, 0777, true);
                }

                fopen($controllerfile, 'w');
                file_put_contents($controllerfile, $updatedContent);

                return [
                    'status' => true,
                    'message' => ucfirst($controllerName).' Controller Generated Successfully'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Controller Template File Not Found'
                ];
            }
        }
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
        $templatefile = getcwd(). '/core/model/templates/ModelTemplate.php';
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
        $templatefile = getcwd(). '/core/Controllers/templates/AuthControllerTemplate.php';
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
        $templatefile = getcwd(). '/core/Controllers/templates/RegisterControllerTemplate.php';
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
        $templatefile = getcwd(). '/core/routes/RouteTemplate.php';
        if(file_exists($templatefile)){

            $newcontent = file_get_contents($templatefile);
            $routefile = getcwd(). '/route/route.php';

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
        $register_template = getcwd(). '/core/views/auth/register.php';
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

        $login_template = getcwd(). '/core/views/auth/login.php';
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

        $header_template = getcwd(). '/core/views/header.php';
        if(file_exists($header_template)){
            $login_template = getcwd(). '/views/partials/header.php';
            if(file_exists($login_template)){
                unlink($login_template);
            }
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
        $line = "\n\nnamespace " .$controllerNameSpace = "App\\Controllers\\" . $namespace . ";";

        return str_replace($specificString, $specificString . $line, $newcontent);

    }

}
