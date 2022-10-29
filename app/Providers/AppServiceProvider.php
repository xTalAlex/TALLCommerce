<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use RalphJSmit\Laravel\SEO\Facades\SEOManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        SEOManager::SEODataTransformer(function (SEOData $SEOData): SEOData {
            // This will change the title on *EVERY* page. Do any logic you want here, e.g. based on the current request.
            $titleSegments = explode( " | ",  $SEOData->title );
            $titleSegments[0] = __($titleSegments[0]);
            $SEOData->title = implode( " | " , $titleSegments );
            
            return $SEOData;
        });
    }
}
