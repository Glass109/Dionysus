<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ImageService
{
    private const SIZES = [
        'thumbnail' => [150, 150],
        'medium' => [400, 400],
        'large' => [800, 800],
    ];

    private const QUALITY = 80;

    public function optimizeAndStore(UploadedFile $file, string $path): array
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $webpFilename = Str::uuid() . '.webp';
        
        $paths = [];
        
        // Procesar imagen original
        $image = Image::make($file);
        
        // Generar versiones en diferentes tamaños
        foreach (self::SIZES as $size => $dimensions) {
            $resized = clone $image;
            $resized->fit($dimensions[0], $dimensions[1], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Guardar versión original
            $resized->save(storage_path('app/public/' . $path . '/' . $size . '_' . $filename), self::QUALITY);
            $paths[$size] = $path . '/' . $size . '_' . $filename;
            
            // Guardar versión WebP
            $resized->save(storage_path('app/public/' . $path . '/' . $size . '_' . $webpFilename), self::QUALITY, 'webp');
            $paths[$size . '_webp'] = $path . '/' . $size . '_' . $webpFilename;
        }
        
        return $paths;
    }

    public function deleteImages(array $paths): void
    {
        foreach ($paths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
} 