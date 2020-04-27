<?php

namespace Lar\LteAdmin\Commands;

use Composer\Json\JsonFormatter;
use Illuminate\Console\Command;
use Lar\LteAdmin\Models\LteSeeder;

/**
 * Class LteUpdateAssets
 *
 * @package Lar\LteAdmin\Commands
 */
class LteInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lte:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install admin LTE';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!\Schema::hasTable('lte_users')) {

            $this->call('migrate', array_filter([
                '--path' => __DIR__ . '/../../migrations',
                '--realpath' => true,
                '--force' => true,
            ]));

            $this->call('db:seed', [
                '--class' => LteSeeder::class
            ]);
        }

        if (!\Schema::hasTable('users')) {

            $this->call('migrate', array_filter([
                '--force' => true
            ]));
        }

        if (!is_dir($dir = lte_app_path())) {

            mkdir($dir, 0777, true);

            $this->info("Directory {$dir} created!");
        }

        if (!is_dir($dir = lte_app_path('Controllers'))) {

            mkdir($dir, 0777, true);

            $this->info("Directory {$dir} created!");
        }

        if (!is_dir($dir = lte_app_path('Extensions'))) {

            mkdir($dir, 0777, true);

            $this->info("Directory {$dir} created!");
        }

        if (!is_dir($dir = public_path('uploads'))) {

            mkdir($dir, 0777, true);

            $this->info("Directory {$dir} created!");
        }

        if (!is_dir($dir = public_path('uploads/images'))) {

            mkdir($dir, 0777, true);

            $this->info("Directory {$dir} created!");
        }

        if (!is_dir($dir = public_path('uploads/files'))) {

            mkdir($dir, 0777, true);

            $this->info("Directory {$dir} created!");
        }

        if (!is_dir($dir = resource_path("views/admin"))) {

            mkdir($dir, 0777, true);

            $this->info("Directory {$dir} created!");
        }

        if (!is_dir($dir = resource_path("views/admin/resource"))) {

            $this->call('vendor:publish', [
                '--tag' => 'lte-view'
            ]);
        }

        $nav = lte_app_path('navigator.php');

        if (!is_file($nav)) {

            file_put_contents(
                $nav,
                "<?php\n\nuse Lar\Roads\Roads;\nuse Lar\LteAdmin\Navigate;\nuse Lar\LteAdmin\Core\NavGroup;\n\nNavigate::do(function (Navigate \$navigate, Roads \$roads) {\n\t\n});"
            );

            $this->info("File {$nav} created!");
        }

        $bootstrap = lte_app_path('bootstrap.php');

        if (!is_file($bootstrap)) {

            file_put_contents(
                $bootstrap,
                "<?php\n\nuse \Lar\Layout\Respond;\nuse Lar\Layout\Tags\TABLE;\n\n"
            );

            $this->info("File {$bootstrap} created!");
        }

        $controller = lte_app_path('Controllers/Controller.php');

        if (!is_file($controller)) {

            file_put_contents(
                $controller,
                "<?php\n\nnamespace App\LteAdmin\Controllers;\n\nuse Lar\LteAdmin\Controllers\Controller as LteController;\n\n/**\n * Controller Class\n *\n * @package App\LteAdmin\Controllers\n */\nclass Controller extends LteController\n{\n\t\n}"
            );

            $this->info("File {$controller} created!");
        }

        if (!is_file(public_path('ljs/js/ljs.js'))) {

            $this->call('vendor:publish', [
                '--tag' => 'ljs-assets'
            ]);
        }

        if (!is_file(public_path('lte-admin/js/app.js'))) {

            $this->call('vendor:publish', [
                '--tag' => 'lte-assets'
            ]);
        }

        if (!is_file(config_path('layout.php'))) {

            $this->call('vendor:publish', [
                '--tag' => 'lar-layout-config'
            ]);
        }

        if (!is_file(config_path('lte.php'))) {

            $this->call('vendor:publish', [
                '--tag' => 'lte-config'
            ]);
        }

        $base_composer = json_decode(file_get_contents(base_path('composer.json')), 1);

        if (!isset($base_composer['scripts']['post-autoload-dump']) || array_search('@php artisan lar:dump', $base_composer['scripts']['post-autoload-dump']) === false) {

            $base_composer['scripts']['post-autoload-dump'][] = 'chmod -R 0777 public/uploads';
            $base_composer['scripts']['post-autoload-dump'][] = '@php artisan lar:dump';

            file_put_contents(base_path('composer.json'), JsonFormatter::format(json_encode($base_composer), false, true));

            $this->info("File composer.json updated!");
        }


        $this->info("Lar Admin LTE Installed");
    }
}