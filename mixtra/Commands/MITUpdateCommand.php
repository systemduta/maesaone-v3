<?php
namespace Mixtra\Commands;

use App;
use Cache;
use MITBooster;
use DB;
use Illuminate\Console\Command;
use Request;
use Symfony\Component\Process\Process;

class MITUpdateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mixtra:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'MIXTRA Update Command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->header();
        $this->checkRequirements();

        $this->info('Updating: ');

        $this->info('Publishing Mixtra Framework needs file...');
        $this->call('vendor:publish', ['--tag' => 'mit_assets', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'mit_config', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'mit_localization', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'mit_vendor', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'mit_sidemenu', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'mit_migration', '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'mit_controllers', '--force' => true]);

        $this->info('Dumping the autoloaded files and reloading all new files...');
        $process = new Process(['ls', '-lsa']);
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo 'OUT > '.$buffer;
            }
        });
        
        $this->info('Migrating database...');
        $this->call('migrate');
        $this->call('db:seed', ['--class' => 'MITSeeder']);
        
        $this->info('Clearing Cache...');
        $this->call('config:clear');
        if (app()->version() < 1.4) {
            $this->call('optimize');
        }

        $this->info('Updating Mixtra Framework Is Completed ! Thank You :)');

        $this->footer();
    }

    private function header()
    {
        $this->info("
#     __  __     _  _____  	
#    /  \/  \   | ||_   _|  	
#   / /\  /\ \  | |  | |
#  / /  \/  \ \ | |  | |	
# /_/        \_\|_|  |_|
#                                                                                                                       
			");
        $this->info('---------- :===: Thanks for choosing Mixtra Framework :==: ---------');
        $this->info('====================================================================');
    }

    private function checkRequirements()
    {
        $this->info('System Requirements Checking:');
        $system_failed = 0;
        $laravel = app();

        if ($laravel::VERSION >= 8.0) {
            $this->info('Laravel Version (>= 8.0.*): [Good]');
        } else {
            $this->info('Laravel Version (>= 8.0.*): [Bad]');
            $system_failed++;
        }

        if (version_compare(phpversion(), '7.3.0', '>=')) {
            $this->info('PHP Version (>= 7.3.*): [Good]');
        } else {
            $this->info('PHP Version (>= 7.3.*): [Bad] Yours: '.phpversion());
            $system_failed++;
        }

        if (extension_loaded('mbstring')) {
            $this->info('Mbstring extension: [Good]');
        } else {
            $this->info('Mbstring extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('openssl')) {
            $this->info('OpenSSL extension: [Good]');
        } else {
            $this->info('OpenSSL extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('pdo')) {
            $this->info('PDO extension: [Good]');
        } else {
            $this->info('PDO extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('tokenizer')) {
            $this->info('Tokenizer extension: [Good]');
        } else {
            $this->info('Tokenizer extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('xml')) {
            $this->info('XML extension: [Good]');
        } else {
            $this->info('XML extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('gd')) {
            $this->info('GD extension: [Good]');
        } else {
            $this->info('GD extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('fileinfo')) {
            $this->info('PHP Fileinfo extension: [Good]');
        } else {
            $this->info('PHP Fileinfo extension: [Bad]');
            $system_failed++;
        }

        if (is_writable(base_path('public'))) {
            $this->info('public dir is writable: [Good]');
        } else {
            $this->info('public dir is writable: [Bad]');
            $system_failed++;
        }

        if ($system_failed != 0) {
            $this->info('Sorry unfortunately your system is not meet with our requirements !');
            $this->footer(false);
        }
        $this->info('--');
    }

    private function footer($success = true)
    {
        $this->info('----------------------------------------------------');
        $this->info('Homepage : http://www.mixtra.co.id');
        $this->info('====================================================');
        if ($success == true) {
            $this->info('-------------------------- :===: Completed !! :===: --------------------------');
        } else {
            $this->info('-------------------------- :===: Failed !! :===: --------------------------');
        }
        exit;
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }

        return 'composer';
    }
}
