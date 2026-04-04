<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('app:cleanup-temp-files')]
#[Description('Command description')]
class CleanupTempFiles extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Storage::disk('public')->deleteDirectory('faq-temp');
    }
}
