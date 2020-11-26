<?php

namespace Laratech\Media\Commands;

use Illuminate\Console\Command;
use Laratech\Media\Jobs\PerformConversions;

class RegenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:regenerate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate the resize image.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $conversions = array_keys(config('media.conversion_sizes'));

        if ($conversions) {
            $model = config('media.model');

            $media = $model::all();

            $media->each(function ($media) use ($conversions) {
                PerformConversions::dispatch(
                    $media, $conversions
                );
            });
        }
    }
}
