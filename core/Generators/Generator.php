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

        if (file_exists(getcwd(). '//controllers'.'/'.$controllerName.'.php')) {
            return [
            'status' => false,
            'message' => ucfirst($controllerName).' Controller Build Not Successful, Controller Already Exist'
            ];
        }
        $templatefile = getcwd(). '/core/controller/templates/ControllerTemplate.php';
        if(file_exists($templatefile)){

            if (strpos($controllerName,'\\') !== false){
                $controller_name_arr = explode('\\',$controllerName);
                $controllerClass = end($controller_name_arr);
            } elseif (strpos($controllerName,'/') !== false){
                $controller_name_arr = explode('/',$controllerName);
                $controllerClass = end($controller_name_arr);
            } else {
                $controllerClass = $controllerName;
            }

                if( strpos(file_get_contents($templatefile),'controllername') !== false) {
                $newcontent = str_replace('controllername', ucfirst($controllerClass), file_get_contents($templatefile));
                $controllerfile = getcwd(). '/app/controllers'.'/'.ucfirst($controllerName).'.php';
                fopen($controllerfile, 'w');
                file_put_contents($controllerfile,$newcontent);
                return [
                'status' => true,
                'message' => ucfirst($controllerName).' Controller Generated Successfully'
                ];
            }
            else {
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

        if (!file_exists(getcwd(). '/app/controllers/Auth')) {
            mkdir(getcwd(). '/app/controllers/Auth', 0777, true);
        }

        /*loginController*/
        $controllerName = 'Login';
        if (file_exists(getcwd(). '/app/controllers/Auth/LoginController.php')) {
            return [
                'status' => false,
                'message' => 'LoginController Build Not Successful, Controller Already Exist'
            ];
        }
        $templatefile = getcwd(). '/core/controller/templates/AuthControllerTemplate.php';
        if(file_exists($templatefile)){
            if( strpos(file_get_contents($templatefile),'controllername') !== false) {
                $newcontent = str_replace('controllername', ucfirst($controllerName).'Controller', file_get_contents($templatefile));
                $controllerfile = getcwd(). '/app/controllers/Auth'.'/'.ucfirst($controllerName).'Controller.php';
                $newfile = fopen($controllerfile, 'w');
                file_put_contents($controllerfile,$newcontent);
            }
            else {
                return [
                    'status' => false,
                    'message' => 'Controller Template File Not Found'
                ];
            }
        }/*end loginController*/


        /*RegisterController*/
        $controllerName = 'Register';
        if (file_exists(getcwd(). '/app/controllers/Auth/RegisterController.php')) {
            return [
                'status' => false,
                'message' => 'RegisterController Build Not Successful, Controller Already Exist'
            ];
        }
        $templatefile = getcwd(). '/core/controller/templates/RegisterControllerTemplate.php';
        if(file_exists($templatefile)){
            if( strpos(file_get_contents($templatefile),'controllername') !== false) {
                $newcontent = str_replace('controllername', ucfirst($controllerName).'Controller', file_get_contents($templatefile));
                $controllerfile = getcwd(). '/app/controllers/Auth'.'/'.ucfirst($controllerName).'Controller.php';
                $newfile = fopen($controllerfile, 'w');
                file_put_contents($controllerfile,$newcontent);
            }
            else {
                return [
                    'status' => false,
                    'message' => 'Controller Template File Not Found'
                ];
            }
        }
        /*RegisterController*/

        $this->generateRoutes();
        $this->generateViews();
        return [
        'status' => false,
        'message' => 'Auth Scaffolding Created successfully'
        ];
    }

    /**
     * @return bool
     */
    private function generateRoutes(){
        $templatefile = getcwd(). '/core/routes/RouteTemplate.php';
        if(file_exists($templatefile)){

            $newcontent = file_get_contents($templatefile);

              $routefile = getcwd(). '/route/route.php';
              $newfile = fopen($routefile, 'a');
              file_put_contents($routefile,$newcontent,FILE_APPEND | LOCK_EX);

              return true;
        }
        return false;
    }

    private function generateViews(){
        if (!file_exists(getcwd(). '/app/views/auth')) {
            mkdir(getcwd(). '/app/views/auth', 0777, true);
        }

        /*loginController*/
        if (file_exists(getcwd(). '/app/views/auth/login.php')) {
            return [
                'status' => false,
                'message' => 'Login view already exist'
            ];
        }
        if (file_exists(getcwd(). '/app/views/auth/register.php')) {
            return [
                'status' => false,
                'message' => 'Register view already exist'
            ];
        }
        $register_template = getcwd(). '/core/views/auth/register.php';
        if(file_exists($register_template)){

            $newcontent = file_get_contents($register_template);
            $register_view = getcwd(). '/app/views/auth/register.php';
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
            $login_view = getcwd(). '/app/views/auth/login.php';
            file_put_contents($login_view,$newcontent);
        }  else {
            return [
                'status' => false,
                'message' => 'Login view template file not found'
            ];
        }

        $header_template = getcwd(). '/core/views/header.php';
        if(file_exists($header_template)){
            $login_template = getcwd(). '/app/views/partials/header.php';
            if(file_exists($login_template)){
                unlink($login_template);
            }
            $newcontent = file_get_contents($header_template);
            $header_view = getcwd(). '/app/views/partials/header.php';
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

}


 ?>
