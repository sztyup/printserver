<?php

namespace App\Console\Commands;

use App\Printing\Manager;
use Illuminate\Console\Command;

class UpdatePrinters extends Command
{
    protected $name = 'printers:discover';

    protected $description = 'Discover new printers on the network';

    /**
     * @param Manager $manager
     * @throws \Exception
     */
    public function handle(Manager $manager)
    {
        $discovered = $manager->discover();

        dd($discovered);
    }
}
