<?php

namespace FLAIRUK\GoodTillSystem\Console;

use FLAIRUK\GoodTillSystem\Exceptions\GoodTillException;
use FLAIRUK\GoodTillSystem\GoodTill;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'goodtill:status')]
class StatusCommand extends Command
{
    protected $signature = 'goodtill:status
                            {--fresh : Discard the cached token and log in again}';

    protected $description = 'Check the Goodtill credentials and list the outlets they can access';

    public function handle(GoodTill $goodTill): int
    {
        if ($this->option('fresh')) {
            $goodTill->tokens()->forget();
        }

        try {
            $outlets = $goodTill->outlets()->all();
        } catch (GoodTillException $e) {
            $this->components->error($e->getMessage());

            return self::FAILURE;
        } catch (ConnectionException $e) {
            $this->components->error('Could not reach Goodtill: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->components->info('Connected to Goodtill.');

        $this->table(['Outlet ID', 'Name'], array_map(
            fn (array $outlet) => [$outlet['id'] ?? '', $outlet['outlet_name'] ?? $outlet['name'] ?? ''],
            $outlets,
        ));

        return self::SUCCESS;
    }
}
