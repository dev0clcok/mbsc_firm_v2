<?php

namespace App\Http\Services;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Turns an uploaded picture into three WebP files for the public site, up
 * to 1280, 800 and 480 pixels wide, so each screen downloads a fitting size.
 *
 * Files are named "{name}-1280.webp", "{name}-800.webp" and
 * "{name}-480.webp" whatever their real width, so the smaller variants can
 * always be derived from the large URL.
 */
class ImageStore
{
    public const LARGE = 1280;

    /** Smaller variants, widest first. */
    public const VARIANTS = [800, 480];

    private const QUALITY = 74;

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
     * Write every variant into a directory and return the large one's size.
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
        imagewebp($large, "{$directory}/{$name}-".self::LARGE.'.webp', self::QUALITY);

        foreach (self::VARIANTS as $width) {
            imagewebp($this->resize($source, $width), "{$directory}/{$name}-{$width}.webp", self::QUALITY);
        }

        return ['width' => imagesx($large), 'height' => imagesy($large)];
    }

    /**
     * Delete every variant of an image stored by store(). Anything that is
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
            ...array_map(fn (int $width) => self::variant($path, $width), self::VARIANTS),
        ]);
    }

    /**
     * The URL or path of a smaller variant, given the large one.
     */
    public static function variant(string $large, int $width): string
    {
        return str_replace('-'.self::LARGE.'.webp', "-{$width}.webp", $large);
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
