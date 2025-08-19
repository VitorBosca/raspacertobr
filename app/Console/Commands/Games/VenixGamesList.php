<?php

namespace App\Console\Commands\Games;

use App\Traits\Commands\Games\FiversGamesCommandTrait;
use App\Traits\Providers\VenixCGTrait;
use App\Traits\Providers\VeniXTrait;
use Illuminate\Console\Command;

class VenixGamesList extends Command
{
    use VenixCGTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'venix:games';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        self::getProviderVenixCG();
        self::getVenixCGGames();
    }
}
