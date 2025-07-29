<?php

namespace Core\Database;

abstract class Seeder
{
    /**
     * Run the seeder.
     */
    abstract public function run(): void;

    /**
     * Call one or multiple seeders.
     *
     * @param string|array $seeders
     */
    public function call(string|array $seeders): void
    {
        $seeders = is_array($seeders) ? $seeders : [$seeders];

        foreach ($seeders as $seeder) {
            if (!class_exists($seeder)) {
                throw new \Exception("Seeder class {$seeder} not found.");
            }

            $instance = new $seeder();

            if (!method_exists($instance, 'run')) {
                throw new \Exception("Seeder class {$seeder} must implement a run() method.");
            }

            echo "Seeding: " . $seeder . PHP_EOL;
            $instance->run();
            echo "Seeded: " . $seeder . PHP_EOL;
        }
    }
}