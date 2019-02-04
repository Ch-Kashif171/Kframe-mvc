<?php

namespace Core\Generators;

use migrations\Migration;

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

        if (file_exists(getcwd(). '/application/controllers'.'/'.$controllerName.'.php')) {
            return [
            'status' => false,
            'message' => ucfirst($controllerName).' Controller Build Not Successful, Controller Already Exist'
            ];
        }
        $templatefile = getcwd(). '/core/controller/templates/ControllerTemplate.php';
        if(file_exists($templatefile)){
                if( strpos(file_get_contents($templatefile),'controllername') !== false) {
                $newcontent = str_replace('controllername', ucfirst($controllerName), file_get_contents($templatefile));
                $controllerfile = getcwd(). '/application/controllers'.'/'.ucfirst($controllerName).'.php';
                $newfile = fopen($controllerfile, 'w');
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

        if (file_exists(getcwd(). '/application/models'.'/'.$modelname.'.php')) {
          return [
            'status' => false,
            'message' => ucfirst($modelname).'Model Build Not Successful, Model Already Exist'
          ];
        }
        $templatefile = getcwd(). '/core/model/templates/ModelTemplate.php';
        if(file_exists($templatefile)){
          if( strpos(file_get_contents($templatefile),'modelname') !== false) {
            $newcontent = str_replace('modelname', $modelname.'Model', file_get_contents($templatefile));
            $controllerfile = getcwd(). '/application/models'.'/'.$modelname.'Model.php';
            $newfile = fopen($controllerfile, 'w');
            file_put_contents($controllerfile,$newcontent);
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

        if (!file_exists(getcwd(). '/application/controllers/Auth')) {
            mkdir(getcwd(). '/application/controllers/Auth', 0777, true);
        }

        /*loginController*/
        $controllerName = 'Login';
        if (file_exists(getcwd(). '/application/controllers/Auth/LoginController.php')) {
            return [
                'status' => false,
                'message' => 'LoginController Build Not Successful, Controller Already Exist'
            ];
        }
        $templatefile = getcwd(). '/core/controller/templates/AuthControllerTemplate.php';
        if(file_exists($templatefile)){
            if( strpos(file_get_contents($templatefile),'controllername') !== false) {
                $newcontent = str_replace('controllername', ucfirst($controllerName).'Controller', file_get_contents($templatefile));
                $controllerfile = getcwd(). '/application/controllers/Auth'.'/'.ucfirst($controllerName).'Controller.php';
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
        if (file_exists(getcwd(). '/application/controllers/Auth/RegisterController.php')) {
            return [
                'status' => false,
                'message' => 'RegisterController Build Not Successful, Controller Already Exist'
            ];
        }
        $templatefile = getcwd(). '/core/controller/templates/RegisterControllerTemplate.php';
        if(file_exists($templatefile)){
            if( strpos(file_get_contents($templatefile),'controllername') !== false) {
                $newcontent = str_replace('controllername', ucfirst($controllerName).'Controller', file_get_contents($templatefile));
                $controllerfile = getcwd(). '/application/controllers/Auth'.'/'.ucfirst($controllerName).'Controller.php';
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
    }

    /**
     * @param $migrate
     * @return array
     */
    public  function generateMigration($migrate)
    {
       $class = new Migration();
       $message = $class->up();
        return [
            'status' => true,
            'message' => $message
        ];
    }

}


 ?>
