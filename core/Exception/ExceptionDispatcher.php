<?php

namespace Core\Exception;

use Throwable;
use ReflectionFunction;

class ExceptionDispatcher
{
    protected array $handlers = [];

    protected bool $debug;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    public function register(callable $handler): void
    {
        $reflection = new ReflectionFunction($handler);
        $params = $reflection->getParameters();

        if (!isset($params[0])) {
            throw new \InvalidArgumentException('Handler must accept an exception.');
        }

        $type = $params[0]->getType();

        if (!$type || $type->isBuiltin()) {
            throw new \InvalidArgumentException('Handler must type-hint an exception class.');
        }

        $this->handlers[$type->getName()] = $handler;
    }

    public function handle(Throwable $e): void
    {
        if ($this->debug) {

            $response = $this->dispatch($e);

            if ($response === null) {
                $this->renderDefault($e);
            }
        }

    }

    protected function dispatch(Throwable $e): mixed
    {
        foreach ($this->handlers as $class => $handler) {
            if ($e instanceof $class) {
                return $handler($e);
            }
        }

        return null;
    }

    protected function renderDefault(Throwable $e): void
    {
        http_response_code($e->getCode() >= 400 ? $e->getCode() : 500);

        if ($this->debug) {
            echo "<h1>" . get_class($e) . "</h1>";
            echo "<p>" . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        } else {
            echo "Something went wrong.";
        }
    }

    protected function renderJson($message, int $code): void
    {
        header('Content-Type: application/json', true, $code);

        echo json_encode([
            'message' => $message,
            'status' => $code,
        ]);

        exit;
    }
}