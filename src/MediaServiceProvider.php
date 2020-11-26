<?php

namespace Laratech\Media;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\Image;
use Laratech\Media\Commands\RegenerateCommand;
use Laratech\Media\Facades\Conversion;

class MediaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/media.php', 'media'
        );

        $this->app->singleton(ConversionRegistry::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Migrations
        if (! class_exists('CreateMediaTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_media_table.stub' => database_path(
                    'migrations/'.date('Y_m_d_His', time()).'_create_media_table.php'
                ),
            ], 'migrations');
        }

        // Config
        $this->publishes([
            __DIR__.'/../config/media.php' => config_path('media.php'),
        ], 'config');

        //Command
        if ($this->app->runningInConsole()) {
            $this->commands([
                RegenerateCommand::class,
            ]);
        }

        //Image resize 
        if ($sizes = config('media.conversion_sizes')) {
            foreach ($sizes as $key => $value) {
                Conversion::register($key, function (Image $image) use ($value){
                    if ($image->width() < $value[0] || $image->height() < $value[1]) {
                        return $image->resizeCanvas($value[0], $value[1], 'center', false, config('media.canvas_bg'));
                    } else {
                        return $image->fit($value[0], $value[1], fn ($constraint) => $constraint->upsize());
                    }
                });     
            }
        }
    }
}
