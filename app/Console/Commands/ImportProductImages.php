<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:product-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products images from folder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $images = collect(Storage::disk(config('media-library.disk_name'))->allFiles('data/import/immagini'));
        $ctr = 0;
        foreach($images as $image)
        {
            $sku = Str::of($image)->replace('.jpg','')->explode('/')->last();
            $product = Product::where('sku', $sku)->first();
            if($product && !$product->hasImage()){
                $product->addMediaFromDisk($image, config('media-library.disk_name'))->toMediaCollection('gallery');
                $ctr++;
            }
        }

        dump("Imported " . $ctr . " images");

        return Command::SUCCESS;
    }
}
