<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use App\Services\ScanEffectService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Imagick;

class ScanController extends Controller
{
    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/heic', 'image/heif'];
    private const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB

    public function index()
    {
        return view('scanner.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,heic,heif|max:10240',
        ]);

        $file = $request->file('image');
        
        // Generate unique session ID
        $sessionId = Str::uuid()->toString();
        
        // Store original image
        $originalPath = $file->store('scans/originals', 'public');
        
        // Create scan record
        $scan = Scan::create([
            'session_id' => $sessionId,
            'original_path' => $originalPath,
            'applied_effect' => 'original',
            'is_cropped' => false,
        ]);

        return response()->json([
            'success' => true,
            'scan_id' => $scan->id,
            'session_id' => $sessionId,
            'image_url' => Storage::url($originalPath),
        ]);
    }

    public function edit($sessionId)
    {
        $scan = Scan::where('session_id', $sessionId)->firstOrFail();
        
        return view('scanner.edit', [
            'scan' => $scan,
        ]);
    }

    public function crop(Request $request, $sessionId)
    {
        $request->validate([
            'corners' => 'required|array',
            'corners.*.x' => 'required|numeric',
            'corners.*.y' => 'required|numeric',
        ]);

        $scan = Scan::where('session_id', $sessionId)->firstOrFail();
        $corners = $request->input('corners');
        
        try {
            // Apply perspective crop using Imagick
            $originalFullPath = Storage::disk('public')->path($scan->original_path);
            $croppedImagick = ScanEffectService::perspectiveCrop($originalFullPath, $corners);
            
            // Generate processed path
            $processedFilename = 'cropped_' . Str::uuid() . '.png';
            $processedPath = 'scans/processed/' . $processedFilename;
            $processedFullPath = Storage::disk('public')->path($processedPath);
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory('scans/processed');
            
            // Save cropped image
            $croppedImagick->writeImage($processedFullPath);
            $croppedImagick->clear();
            $croppedImagick->destroy();
            
            // Update scan record
            $scan->update([
                'processed_path' => $processedPath,
                'corners_json' => $corners,
                'is_cropped' => true,
            ]);

            return response()->json([
                'success' => true,
                'image_url' => Storage::url($processedPath),
                'corners' => $corners,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to crop image: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function applyEffect(Request $request, $sessionId)
    {
        $request->validate([
            'effect' => 'required|in:original,no-shadow,lighten,magic-color,magic-pro,bw,eco,grayscale',
        ]);

        $scan = Scan::where('session_id', $sessionId)->firstOrFail();
        $effect = $request->input('effect');
        
        try {
            // Get the base image (cropped if available, otherwise original)
            $basePath = $scan->processed_path 
                ? Storage::disk('public')->path($scan->processed_path) 
                : Storage::disk('public')->path($scan->original_path);
            
            // Apply effect
            $effectService = new ScanEffectService($basePath);
            $processedImagick = $effectService->applyEffect($effect);
            
            // Generate processed path
            $processedFilename = 'effect_' . $effect . '_' . Str::uuid() . '.png';
            $processedPath = 'scans/processed/' . $processedFilename;
            $processedFullPath = Storage::disk('public')->path($processedPath);
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory('scans/processed');
            
            // Save processed image
            $processedImagick->writeImage($processedFullPath);
            $processedImagick->clear();
            $processedImagick->destroy();
            
            // Update scan record
            $scan->update([
                'processed_path' => $processedPath,
                'applied_effect' => $effect,
            ]);

            return response()->json([
                'success' => true,
                'image_url' => Storage::url($processedPath),
                'effect' => $effect,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to apply effect: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function generateThumbnail(Request $request, $sessionId)
    {
        $request->validate([
            'effect' => 'required|in:original,no-shadow,lighten,magic-color,magic-pro,bw,eco,grayscale',
        ]);

        $scan = Scan::where('session_id', $sessionId)->firstOrFail();
        $effect = $request->input('effect');
        
        try {
            // Get the base image (cropped if available, otherwise original)
            $basePath = $scan->processed_path 
                ? Storage::disk('public')->path($scan->processed_path) 
                : Storage::disk('public')->path($scan->original_path);
            
            // Apply effect
            $effectService = new ScanEffectService($basePath);
            $processedImagick = $effectService->applyEffect($effect);
            
            // Create thumbnail
            $thumbnailFilename = 'thumb_' . $effect . '_' . Str::uuid() . '.png';
            $thumbnailPath = 'scans/thumbnails/' . $thumbnailFilename;
            $thumbnailFullPath = Storage::disk('public')->path($thumbnailPath);
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory('scans/thumbnails');
            
            // Resize to thumbnail
            $processedImagick->thumbnailImage(120, 160, true);
            $processedImagick->writeImage($thumbnailFullPath);
            $processedImagick->clear();
            $processedImagick->destroy();
            
            return response()->json([
                'success' => true,
                'thumbnail_url' => Storage::url($thumbnailPath),
                'effect' => $effect,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate thumbnail: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function save(Request $request, $sessionId)
    {
        $scan = Scan::where('session_id', $sessionId)->firstOrFail();
        
        $scan->update([
            'applied_effect' => $request->input('effect', 'original'),
        ]);

        return response()->json([
            'success' => true,
            'scan_id' => $scan->id,
            'message' => 'Scan saved successfully',
        ]);
    }

    public function download(Request $request, $sessionId)
    {
        $scan = Scan::where('session_id', $sessionId)->firstOrFail();
        
        $format = $request->input('format', 'png');
        
        try {
            // Get the processed image path
            $imagePath = $scan->processed_path 
                ? Storage::disk('public')->path($scan->processed_path) 
                : Storage::disk('public')->path($scan->original_path);
            
            $imagick = new Imagick($imagePath);
            
            // Set format based on requested type
            switch ($format) {
                case 'jpg':
                case 'jpeg':
                    $imagick->setImageFormat('jpeg');
                    $imagick->setImageCompressionQuality(90);
                    $filename = 'scan_' . $scan->session_id . '.jpg';
                    $contentType = 'image/jpeg';
                    break;
                case 'pdf':
                    $imagick->setImageFormat('pdf');
                    $filename = 'scan_' . $scan->session_id . '.pdf';
                    $contentType = 'application/pdf';
                    break;
                default:
                    $imagick->setImageFormat('png');
                    $filename = 'scan_' . $scan->session_id . '.png';
                    $contentType = 'image/png';
            }
            
            $imagick->clear();
            $imagick->destroy();
            
            return response()->download($imagePath, $filename, [
                'Content-Type' => $contentType,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to download: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function mergePdf(Request $request)
    {
        $request->validate([
            'session_ids' => 'required|array',
            'session_ids.*' => 'required|uuid',
        ]);

        $sessionIds = $request->input('session_ids');
        
        try {
            $pdf = new Imagick();
            
            foreach ($sessionIds as $sessionId) {
                $scan = Scan::where('session_id', $sessionId)->first();
                if (!$scan) continue;
                
                $imagePath = $scan->processed_path 
                    ? Storage::disk('public')->path($scan->processed_path) 
                    : Storage::disk('public')->path($scan->original_path);
                
                $page = new Imagick($imagePath);
                $page->setImageFormat('pdf');
                $pdf->addImage($page);
                $page->clear();
                $page->destroy();
            }
            
            // Generate PDF
            $pdfFilename = 'merged_' . Str::uuid() . '.pdf';
            $pdfPath = 'scans/pdf/' . $pdfFilename;
            $pdfFullPath = Storage::disk('public')->path($pdfPath);
            
            Storage::disk('public')->makeDirectory('scans/pdf');
            
            $pdf->writeImages($pdfFullPath, true);
            $pdf->clear();
            $pdf->destroy();
            
            return response()->json([
                'success' => true,
                'pdf_url' => Storage::url($pdfPath),
                'filename' => $pdfFilename,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to merge PDF: ' . $e->getMessage(),
            ], 500);
        }
    }
}
