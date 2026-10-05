<?php

namespace FLAIRUK\GoodTillSystem\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'goodtill:install')]
class InstallCommand extends Command
{
    protected $signature = 'goodtill:install';

    protected $description = 'Publish the Goodtill config and add its environment variables to .env';

    /** @var list<string> */
    protected array $variables = ['GOOD_TILL_SUBDOMAIN', 'GOOD_TILL_USERNAME', 'GOOD_TILL_PASSWORD', 'GOOD_TILL_OUTLET_ID'];

    public function handle(Filesystem $files): int
    {
        $this->call('vendor:publish', ['--tag' => 'goodtill-config']);

        foreach ([$this->laravel->environmentFilePath(), base_path('.env.example')] as $path) {
            if (! $files->exists($path)) {
                continue;
            }

            $contents = $files->get($path);
            $missing = array_filter($this->variables, fn (string $key) => ! preg_match("/^{$key}=/m", $contents));

            if ($missing) {
                $files->append($path, PHP_EOL.implode(PHP_EOL, array_map(fn ($key) => "{$key}=", $missing)).PHP_EOL);
                $this->components->info('Added '.implode(', ', $missing).' to '.basename($path).'.');
            }
        }

        $this->components->info('Fill in your Goodtill credentials, then run `php artisan goodtill:status` to check the connection.');

        return self::SUCCESS;
    }
}
