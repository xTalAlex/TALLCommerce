<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AlgoliaRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'algolia:refresh {model=App\Models\Product}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh algolia imports';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('scout:flush', [ 'model' => $this->argument('model') ]);
        $this->call('scout:import', [ 'model' => $this->argument('model') ]);
        return 0;
    }
}
