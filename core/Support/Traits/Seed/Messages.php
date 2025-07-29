<?php

namespace Core\Support\Traits\Seed;

trait Messages
{
    /**
     * @var bool
     */
    protected bool $loaderActive = false;

    /**
     * @param string $message
     * @return void
     */
    protected function info(string $message): void
    {
        $this->output($message, '1;34'); // Bold Blue
    }

    /**
     * @param string $message
     * @return void
     */
    protected function success(string $message): void
    {
        $this->output($message, '1;32'); // Bold Green
    }

    /**
     * @param string $message
     * @return void
     */
    protected function error(string $message): void
    {
        $this->output("❌ {$message}", '1;31'); // Bold Red
    }

    /**
     * @param string $message
     * @return void
     */
    protected function line(string $message): void
    {
        echo $message . PHP_EOL;
    }

    /**
     * @param string $message
     * @param string $colorCode
     * @return void
     */
    protected function output(string $message, string $colorCode): void
    {
        // Parse pseudo-tags like <info>...</info> or <success>...</success>
        $message = preg_replace_callback('/<(\w+)>(.*?)<\/\1>/', function ($matches) {
            return match ($matches[1]) {
                'info'    => "\033[1;34m{$matches[2]}\033[0m", // Blue
                'success' => "\033[1;32m{$matches[2]}\033[0m", // Green
                'error'   => "\033[1;31m{$matches[2]}\033[0m", // Red
                default   => $matches[2],
            };
        }, $message);

        echo "\033[{$colorCode}m{$message}\033[0m" . PHP_EOL;
    }

    /**
     * @return void
     */
    protected function startLoader(): void
    {
        $this->loaderActive = true;
        // Run loader in a separate thread using a non-blocking async call (simulated)
        // Since PHP doesn't support threading natively, we'll simulate it using a separate loop
        $frames = ['|', '/', '-', '\\'];

        // Start a background loader loop
        // We'll use output buffering to avoid display glitches
        ob_implicit_flush(true);
        echo "⏳ Seeding in progress ";

        // Start the spinner in a background loop using a tick-like behavior
        register_tick_function(function () use ($frames) {
            $i = 0;
            while ($this->loaderActive) {
                echo "\r⏳ Seeding in progress " . $frames[$i++ % count($frames)];
                usleep(100000); // 0.1s
            }
        });
    }

    /**
     * @return void
     */
    protected function stopLoader(): void
    {
        $this->loaderActive = false;
        echo "\r✅ Seeding complete!          " . PHP_EOL;
        unregister_tick_function(function () {});
    }


}