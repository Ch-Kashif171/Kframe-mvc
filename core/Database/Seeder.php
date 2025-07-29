<?php

namespace Core\Database;

use Core\Support\Traits\Seed\Messages;

abstract class Seeder
{
    use Messages;
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
        foreach ((array) $seeders as $seeder) {
            if (!class_exists($seeder)) {
                $this->error("Seeder {$seeder} not found.");
                exit();
            };
            if (!method_exists($seeder, 'run')) {
                $this->error("Seeder {$seeder} must have a run() method.");
                exit();
            }

            $this->info("🌱 Starting seeder: {$seeder}");
            $this->startLoader();

            (new $seeder())->run();

            $this->stopLoader();
            $this->info("✅ Finished seeder: {$seeder}");
        }
    }

}