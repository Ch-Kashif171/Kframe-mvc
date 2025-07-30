<?php

namespace Core\Commands;

use App\Providers\RouteServiceProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Core\Support\Routing\Router;
use Core\Support\Routing\RegisterAllRoutes;

class RouteListCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('route:list')
            ->setDescription('List all registered routes')
            ->addOption('method', 'm', InputOption::VALUE_OPTIONAL, 'Filter routes by HTTP method')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Filter routes by name')
            ->addOption('path', null, InputOption::VALUE_OPTIONAL, 'Filter routes by path')
            ->setHelp('This command lists all registered routes in a table format.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Load all routes first
        $this->loadRoutes();

        $methodFilter = $input->getOption('method');
        $nameFilter = $input->getOption('name');
        $pathFilter = $input->getOption('path');

        $routes = $this->getAllRoutes();
        $filteredRoutes = $this->filterRoutes($routes, $methodFilter, $nameFilter, $pathFilter);

        if (empty($filteredRoutes)) {
            $output->writeln('<comment>No routes found.</comment>');
            return Command::SUCCESS;
        }

        $this->displayRoutes($output, $filteredRoutes);

        $output->writeln('');
        $output->writeln(sprintf('<info>Total routes: %d</info>', count($filteredRoutes)));

        return Command::SUCCESS;
    }

    /**
     * Load all routes by requiring the route files
     */
    private function loadRoutes(): void
    {
        // Set required server variables for CLI execution
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            $_SERVER['REQUEST_METHOD'] = 'GET';
        }
        if (!isset($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = '/';
        }


        // Bind router to container for facade resolution
        app('router', new Router());

        // Load route files directly instead of using RegisterAllRoutes
        $routeFiles = RouteServiceProvider::register();

        foreach ($routeFiles as $file) {
            if (file_exists(root_path . '/' . $file)) {
                require_once root_path . '/' . $file;
            }
        }
    }

    /**
     * Get all registered routes from Router
     */
    private function getAllRoutes(): array
    {
        $routes = [];
        
        // Get static routes
        $staticRoutes = Router::$routes;
        $routeHandlers = $this->getRouteHandlers();
        $dynamicRoutes = $this->getDynamicRoutes();

        // Process static routes
        foreach ($staticRoutes as $method => $methodRoutes) {
            foreach ($methodRoutes as $route) {
                $routeKey = $method . ':' . $route;
                $handler = $routeHandlers[$routeKey] ?? null;
                
                $routes[] = [
                    'method' => $method,
                    'uri' => $route,
                    'name' => $this->getRouteName($routeKey),
                    'action' => $this->formatAction($handler),
                    'middleware' => $this->formatMiddleware($handler),
                    'type' => 'static'
                ];
            }
        }

        // Process dynamic routes
        foreach ($dynamicRoutes as $method => $methodRoutes) {
            foreach ($methodRoutes as $route) {
                $routes[] = [
                    'method' => $method,
                    'uri' => $route['route'],
                    'name' => $this->getRouteName($method . ':' . $route['route']),
                    'action' => $this->formatAction($route),
                    'middleware' => $this->formatMiddleware($route),
                    'type' => 'dynamic'
                ];
            }
        }

        return $routes;
    }

    /**
     * Get route handlers using reflection
     */
    private function getRouteHandlers(): array
    {
        $reflection = new \ReflectionClass(Router::class);
        $property = $reflection->getProperty('routeHandlers');
        $property->setAccessible(true);
        return $property->getValue() ?? [];
    }

    /**
     * Get dynamic routes using reflection
     */
    private function getDynamicRoutes(): array
    {
        $reflection = new \ReflectionClass(Router::class);
        $property = $reflection->getProperty('dynamicRoutes');
        $property->setAccessible(true);
        return $property->getValue() ?? [];
    }

    /**
     * Filter routes based on provided criteria
     */
    private function filterRoutes(array $routes, ?string $method, ?string $name, ?string $path): array
    {
        return array_filter($routes, function ($route) use ($method, $name, $path) {
            if ($method && strtoupper($route['method']) !== strtoupper($method)) {
                return false;
            }
            
            if ($name && stripos($route['name'], $name) === false) {
                return false;
            }
            
            if ($path && stripos($route['uri'], $path) === false) {
                return false;
            }
            
            return true;
        });
    }

    /**
     * Display routes in a table format
     */
    private function displayRoutes(OutputInterface $output, array $routes): void
    {
        $table = new Table($output);
        $table->setHeaders(['Method', 'URI', 'Name', 'Action', 'Middleware']);

        foreach ($routes as $route) {
            $table->addRow([
                $this->colorizeMethod($route['method']),
                $route['uri'],
                $route['name'] ?: '-',
                $route['action'],
                $route['middleware'] ?: '-'
            ]);
        }

        $table->render();
    }

    /**
     * Format action for display
     */
    private function formatAction($handler): string
    {
        if (!$handler) {
            return '-';
        }

        if (isset($handler['controller']['closure'])) {
            return '<comment>Closure</comment>';
        }

        if (isset($handler['controller']) && is_array($handler['controller'])) {
            $controller = $handler['controller'][0] ?? '';
            $method = $handler['controller'][1] ?? '';
            
            if ($controller && $method) {
                $shortName = $this->getClassBasename($controller);
                return "<info>{$shortName}@{$method}</info>";
            }
        }

        return '-';
    }

    /**
     * Format middleware for display
     */
    private function formatMiddleware($handler): string
    {
        if (!$handler) {
            return '-';
        }

        $middleware = $handler['middleware'] ?? null;
        
        if (!$middleware) {
            return '-';
        }

        if (is_array($middleware)) {
            return implode(', ', $middleware);
        }

        return (string) $middleware;
    }

    /**
     * Get route name (if available)
     */
    private function getRouteName(string $routeKey): string
    {
        // This could be extended to support named routes
        return '';
    }

    /**
     * Colorize HTTP method
     */
    private function colorizeMethod(string $method): string
    {
        $colors = [
            'GET' => 'green',
            'POST' => 'yellow',
            'PUT' => 'blue',
            'PATCH' => 'cyan',
            'DELETE' => 'red'
        ];

        $color = $colors[strtoupper($method)] ?? 'white';
        return "<fg={$color}>{$method}</>";
    }

    /**
     * Get class basename (without namespace)
     */
    private function getClassBasename(string $class): string
    {
        $parts = explode('\\', $class);
        return end($parts);
    }
} 