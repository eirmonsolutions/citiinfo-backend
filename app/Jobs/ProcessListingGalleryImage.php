<?php

namespace App\Jobs;

use App\Models\BusinessListing;
use App\Models\BusinessGallery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessListingGalleryImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $listingId;
    protected $tempPath;
    protected $index;

    /**
     * Create a new job instance.
     */
    public function __construct($listingId, $tempPath, $index)
    {
        $this->listingId = $listingId;
        $this->tempPath = $tempPath;
        $this->index = $index;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $manager = new ImageManager(new Driver());
        
        if (!Storage::disk('local')->exists($this->tempPath)) {
            return;
        }

        $fullTempPath = Storage::disk('local')->path($this->tempPath);
        $image = $manager->read($fullTempPath);
        $image->scaleDown(width: 1200);

        $filename = uniqid('gallery_') . '.jpg';
        $path = 'business/gallery/' . $filename;

        $quality = 85;
        do {
            $encoded = $image->toJpeg($quality);
            $quality -= 5;
        } while (strlen((string)$encoded) > (500 * 1024) && $quality > 30);

        Storage::disk('public')->put($path, (string)$encoded);

        BusinessGallery::create([
            'business_id' => $this->listingId,
            'image_path'  => $path,
            'is_cover'    => $this->index === 0,
            'sort_order'  => $this->index,
            'uploaded_at' => now(),
        ]);

        // Clean up temp file
        Storage::disk('local')->delete($this->tempPath);
    }
}
