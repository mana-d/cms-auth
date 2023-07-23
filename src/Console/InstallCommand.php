<?php

namespace Mana\Cms\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
	protected $signature = 'manacms:install';
	
	protected $description = 'Install the Mana CMS controllers';
	
	public function handle()
    {
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/app/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));
		
		(new Filesystem)->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/app/Http/Requests/Auth', app_path('Http/Requests/Auth'));

        $this->call('vendor:publish', ['--tag' => 'manacms-config']);
        $this->call('vendor:publish', ['--tag' => 'manacms-migrations']);
    }
}