<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Services\StatisticService;

class Statistic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistic:get {option}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * While executing the console command, the statistic service was injected.
     * We can use the injected service to get the statistic data easily anywhere.
     *
     * @return int
     */
    public function handle(StatisticService $service)
    {
        // get the option of command.
        $option = $this->argument('option');
        
        $value = $service->get($option);

        if ($option == 'count') {
            $this->info("total items count - " . $value);

        } else if ($option == 'average') {
            $this->info("average price of an item - " . $value);

        } else if ($option == 'website') {
            $this->info('the website with the highest total price of its items - ' . $value);

        } else if ($option == 'total') {
            $this->info('total price of items added this month - ' . $value);
        } else {
            $this->warn('You can use count|website|average|total as option');
        }

        return 0;
    }
}
