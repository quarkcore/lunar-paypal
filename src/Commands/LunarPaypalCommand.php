<?php

namespace Lancodev\LunarPaypal\Commands;

use Illuminate\Console\Command;

class LunarPaypalCommand extends Command
{
    public $signature = 'lunar-paypal';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
