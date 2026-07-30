<?php

namespace App\Services;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Exception;

class ScanEffectService
{
    private $imagick;
    private $imagePath;

    public function __construct(string $imagePath)
    {
        $this->imagePath = $imagePath;
        $this->imagick = new Imagick($imagePath);
    }

    public function applyEffect(string $effect): Imagick
    {
        return match ($effect) {
            'original' => $this->applyOriginal(),
            'no-shadow' => $this->applyNoShadow(),
            'lighten' => $this->applyLighten(),
            'magic-color' => $this->applyMagicColor(),
            'magic-pro' => $this->applyMagicPro(),
            'bw' => $this->applyBW(),
            'eco' => $this->applyEco(),
            'grayscale' => $this->applyGrayscale(),
            default => $this->applyOriginal(),
        };
    }

    public function applyOriginal(): Imagick
    {
        $imagick = clone $this->imagick;
        return $imagick;
    }

    public function applyNoShadow(): Imagick
    {
        $imagick = clone $this->imagick;
        
        // Normalize and enhance to remove shadows
        $imagick->transformImageColorspace(Imagick::COLORSPACE_SRGB);
        $imagick->modulateImage(100, 120, 100); // Reduce saturation slightly
        $imagick->levelImage(0.1, 1.0, 255); // Normalize brightness
        
        // White balance adjustment
        $imagick->autoLevelImage();
        
        return $imagick;
    }

    public function applyLighten(): Imagick
    {
        $imagick = clone $this->imagick;
        
        // Increase brightness
        $imagick->modulateImage(100, 100, 130); // Increase lightness by 30%
        $imagick->sigmoidalContrastImage(true, 3, 50); // Slight contrast enhancement
        
        return $imagick;
    }

    public function applyMagicColor(): Imagick
    {
        $imagick = clone $this->imagick;
        
        // Increase contrast
        $imagick->sigmoidalContrastImage(true, 2, 50);
        
        // Increase saturation
        $imagick->modulateImage(100, 130, 100); // 30% more saturation
        
        // Increase sharpness
        $imagick->sharpenImage(2, 1);
        
        // Auto level for better exposure
        $imagick->autoLevelImage();
        
        return $imagick;
    }

    public function applyMagicPro(): Imagick
    {
        $imagick = clone $this->imagick;
        
        // Stronger contrast
        $imagick->sigmoidalContrastImage(true, 3, 45);
        
        // Higher saturation
        $imagick->modulateImage(100, 150, 100);
        
        // Stronger sharpness
        $imagick->sharpenImage(2, 0.8);
        
        // Auto level and contrast
        $imagick->autoLevelImage();
        $imagick->contrastImage(true);
        
        // White balance correction
        $imagick->transformImageColorspace(Imagick::COLORSPACE_SRGB);
        
        // Enhance details
        $imagick->enhanceImage();
        
        return $imagick;
    }

    public function applyBW(): Imagick
    {
        $imagick = clone $this->imagick;
        
        // Convert to grayscale first
        $imagick->transformImageColorspace(Imagick::COLORSPACE_GRAY);
        
        // High contrast threshold for pure B&W
        $imagick->sigmoidalContrastImage(true, 4, 50);
        
        // Posterize to create pure black and white
        $imagick->posterizeImage(2, false);
        
        return $imagick;
    }

    public function applyEco(): Imagick
    {
        $imagick = clone $this->imagick;
        
        // Convert to grayscale
        $imagick->transformImageColorspace(Imagick::COLORSPACE_GRAY);
        
        // Halftone effect simulation with blur
        $imagick->blurImage(0, 1);
        
        // Reduce contrast for lighter tones
        $imagick->sigmoidalContrastImage(false, 1, 100);
        
        // Posterize for halftone look
        $imagick->posterizeImage(4, true);
        
        return $imagick;
    }

    public function applyGrayscale(): Imagick
    {
        $imagick = clone $this->imagick;
        
        // Simple grayscale conversion
        $imagick->transformImageColorspace(Imagick::COLORSPACE_GRAY);
        
        return $imagick;
    }

    public static function perspectiveCrop(string $imagePath, array $corners): Imagick
    {
        if (!extension_loaded('imagick')) {
            throw new Exception('Imagick extension is required for perspective cropping');
        }

        $imagick = new Imagick($imagePath);
        
        // Order corners: top-left, top-right, bottom-right, bottom-left
        // $corners expected format: [{x, y}, {x, y}, {x, y}, {x, y}]
        
        // Calculate the dimensions of the output image
        $widthTop = hypot(
            $corners[1]['x'] - $corners[0]['x'],
            $corners[1]['y'] - $corners[0]['y']
        );
        $widthBottom = hypot(
            $corners[2]['x'] - $corners[3]['x'],
            $corners[2]['y'] - $corners[3]['y']
        );
        $heightLeft = hypot(
            $corners[3]['x'] - $corners[0]['x'],
            $corners[3]['y'] - $corners[0]['y']
        );
        $heightRight = hypot(
            $corners[2]['x'] - $corners[1]['x'],
            $corners[2]['y'] - $corners[1]['y']
        );
        
        $outputWidth = (int) max($widthTop, $widthBottom);
        $outputHeight = (int) max($heightLeft, $heightRight);
        
        // Set up control points for perspective distortion
        // Source points (the 4 corners from input)
        $srcPoints = [
            $corners[0]['x'], $corners[0]['y'],  // top-left
            $corners[1]['x'], $corners[1]['y'],  // top-right
            $corners[2]['x'], $corners[2]['y'],  // bottom-right
            $corners[3]['x'], $corners[3]['y'],  // bottom-left
        ];
        
        // Destination points (rectangle)
        $dstPoints = [
            0, 0,                          // top-left
            $outputWidth - 1, 0,            // top-right
            $outputWidth - 1, $outputHeight - 1,  // bottom-right
            0, $outputHeight - 1,           // bottom-left
        ];
        
        // Apply perspective distortion using SIMILARITY distortion
        $imagick->distortImage(Imagick::DISTORTION_PERSPECTIVE, array_merge($srcPoints, $dstPoints), true);
        
        return $imagick;
    }

    public function getImagick(): Imagick
    {
        return $this->imagick;
    }

    public function __destruct()
    {
        if ($this->imagick) {
            $this->imagick->clear();
            $this->imagick->destroy();
        }
    }
}
