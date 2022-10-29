<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sitemap';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        SitemapIndex::create()
            ->add('/sitemap/pages_sitemap.xml')
            ->add('/sitemap/products_sitemap.xml')
            ->add('/sitemap/collections_sitemap.xml')
            ->add('/sitemap/categories_sitemap.xml')
            ->add('/sitemap/tags_sitemap.xml')
            ->writeToFile(public_path('sitemap_index.xml'));

        SitemapGenerator::create(config('app.url'))
            ->writeToFile(public_path('/sitemap/pages_sitemap.xml'));
        SitemapGenerator::create(config('app.url'))
            ->writeToFile(public_path('/sitemap/products_sitemap.xml'));
        SitemapGenerator::create(config('app.url'))
            ->writeToFile(public_path('/sitemap/collections_sitemap.xml'));
        SitemapGenerator::create(config('app.url'))
            ->writeToFile(public_path('/sitemap/categories_sitemap.xml'));
        SitemapGenerator::create(config('app.url'))
            ->writeToFile(public_path('/sitemap/tags_sitemap.xml'));
    }
}
