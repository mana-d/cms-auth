<?php

namespace Mana\Cms\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class InstallPermisionOnlyCommand extends Command
{
	protected $signature = 'manacms:install-permission-only';
	
	protected $description = 'Install the Mana CMS controllers';
	
	public function handle()
    {
        $this->info("OK");

        (new Filesystem)->copy(__DIR__.'/../../stubs/config/cms-menu.php', config_path('cms-menu.php'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/config/cms-module-task.php', config_path('cms-module-task.php'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/app/models', app_path('/models'));
		(new Filesystem)->copyDirectory(__DIR__.'/../../stubs/app/libraries', app_path('/libraries'));
		(new Filesystem)->copyDirectory(__DIR__.'/../../stubs/app/http/middleware', app_path('/http/middleware'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/database/migrations', database_path('/migrations'));
    }
}