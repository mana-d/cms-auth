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
        $this->info("OK");

        (new Filesystem)->copy(__DIR__.'/../../stubs/config/cms-menu.php', config_path('cms-menu.php'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/config/cms-module-task.php', config_path('cms-module-task.php'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/app', app_path('/'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/database', database_path('/'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/resources', resource_path('/'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/routes', base_path('routes'));

        (new Filesystem)->copy(__DIR__.'/../../stubs/package.json', base_path('/package.json'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/postcss.config.js', base_path('/postcss.config.js'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/tailwind.config.js', base_path('/tailwind.config.js'));
        (new Filesystem)->copy(__DIR__.'/../../stubs/webpack.mix.js', base_path('/webpack.mix.js'));
    }
}