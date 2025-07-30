<?php

namespace Core\Commands\Executor;

use Core\Commands\CreateControllerCommand;
use Core\Commands\CreateMigrationCommand;
use Core\Commands\CreateModelCommand;
use Core\Commands\DatabaseSeedCommand;
use Core\Commands\MakeAuth;
use Core\Commands\MakeSeederCommand;
use Core\Commands\MigrationCommand;
use Core\Commands\RollbackMigrationCommand;
use Core\Commands\RouteListCommand;
use Core\Dotenv\Dotenv;
use Symfony\Component\Console\Application;

class Commander
{
    protected Application $app;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->loadEnv();

        $this->app = $application;
    }

    /**
     * @return Application
     */
    public function register(): Application
    {
        $this->app->add(new CreateControllerCommand());
        $this->app->add(new CreateModelCommand());
        $this->app->add(new MakeAuth());
        $this->app->add(new MigrationCommand());
        $this->app->add(new CreateMigrationCommand());
        $this->app->add(new RollbackMigrationCommand());
        $this->app->add(new MakeSeederCommand());
        $this->app->add(new DatabaseSeedCommand());
        $this->app->add(new RouteListCommand());

        return $this->app;
    }

    /**
     * @return void
     */
    private function loadEnv()
    {
        $dotenv = new Dotenv(root_path);
        $dotenv->load();
    }
}