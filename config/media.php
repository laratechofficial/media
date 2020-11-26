<?php

return [

    /*
     * The disk where files should be uploaded.
     */
    'disk' => 'public',

    /*
     * The queue used to perform image conversions.
     * Leave empty to use the default queue driver.
     */
    'queue' => null,

    /*
     * The fully qualified class name of the media model.
     */
    'model' => Laratech\Media\Models\Media::class,

    /*
    * The conversion size of images
    */
    'conversion_sizes' => [
        'thumbnail' => [100, 100],
    ],

    /*
    * Background for canvas
     */
    'canvas_bg' => 'ffffff',

];
