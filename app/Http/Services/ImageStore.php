<?php

namespace App\Http\Services;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Turns an uploaded picture into two WebP files for the public site:
 * a large one (up to 1280px wide) and a small one (up to 640px wide).
 *
 * Files are named "{name}-1280.webp" and "{name}-640.webp" whatever their
 * real width, so the small variant can always be derived from the large URL.
 */
class ImageStore
{
    public const LARGE = 1280;

    public const SMALL = 640;

    private const QUALITY = 78;

    /**
     * Store an upload on the public disk.
     *
     * @return array{url: string, width: int, height: int}
     */
    public function store(UploadedFile $file, string $directory): array
    {
        $name = Str::random(20);
        $disk = Storage::disk('public');
        $disk->makeDirectory($directory);

        $size = $this->write($file->getContent(), $disk->path($directory), $name);

        return ['url' => "/storage/{$directory}/{$name}-".self::LARGE.'.webp', ...$size];
    }

    /**
     * Write both variants into a directory and return the large one's size.
     *
     * @return array{width: int, height: int}
     */
    public function write(string $binary, string $directory, string $name): array
    {
        $source = @imagecreatefromstring($binary);

        if (! $source instanceof GdImage) {
            throw new RuntimeException('The image could not be read.');
        }

        $source = $this->applyOrientation($source, $binary);

        $large = $this->resize($source, self::LARGE);
        $small = $this->resize($source, self::SMALL);

        $size = ['width' => imagesx($large), 'height' => imagesy($large)];

        imagewebp($large, "{$directory}/{$name}-".self::LARGE.'.webp', self::QUALITY);
        imagewebp($small, "{$directory}/{$name}-".self::SMALL.'.webp', self::QUALITY);

        return $size;
    }

    /**
     * Delete both variants of an image stored by store(). Anything that is
     * not an uploaded file on the public disk (seeded or remote images) is
     * left alone.
     */
    public function delete(?string $url): void
    {
        if (! $url || ! str_starts_with($url, '/storage/')) {
            return;
        }

        $path = Str::after($url, '/storage/');

        Storage::disk('public')->delete([
            $path,
            str_replace('-'.self::LARGE.'.webp', '-'.self::SMALL.'.webp', $path),
        ]);
    }

    private function resize(GdImage $source, int $maxWidth): GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);

        // Never enlarge: a small upload keeps its own size.
        $targetWidth = min($width, $maxWidth);
        $targetHeight = max(1, (int) round($height * $targetWidth / $width));

        $target = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($target, false);
        imagesavealpha($target, true);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        return $target;
    }

    /**
     * Phone cameras store rotation as EXIF metadata, which GD ignores.
     */
    private function applyOrientation(GdImage $image, string $binary): GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data('data://image/jpeg;base64,'.base64_encode($binary));

        $angle = match ($exif['Orientation'] ?? 1) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        return $angle === 0 ? $image : imagerotate($image, $angle, 0);
    }
}
