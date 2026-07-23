<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use App\Models\Media;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('upload')
                ->label('Upload Files')
                ->icon('heroicon-o-cloud-arrow-up')
                ->form([
                    FileUpload::make('files')
                        ->label('Choose Files')
                        ->multiple()
                        ->directory('uploads')
                        ->maxFiles(20)
                        ->maxSize(10240) // 10MB
                        ->acceptedFileTypes([
                            'image/*',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'video/mp4',
                        ])
                        ->preserveFilenames(),
                    Select::make('folder')
                        ->label('Folder')
                        ->options(Media::getFolders())
                        ->default('general'),
                ])
                ->action(function (array $data) {
                    $this->handleUpload($data);
                })
                ->modalHeading('Upload Media Files')
                ->modalWidth('lg'),
        ];
    }

    protected function handleUpload(array $data): void
    {
        $files = $data['files'];
        $folder = $data['folder'] ?? 'general';

        foreach ($files as $fileData) {
            if (isset($fileData['tmp_name'])) {
                // Handle Livewire temporary file
                $this->processUploadedFile($fileData['tmp_name'], $fileData['original_name'], $folder);
            } elseif (is_array($fileData)) {
                // Direct array data
                $path = $fileData['path'] ?? null;
                $name = $fileData['name'] ?? $fileData['original_name'] ?? 'unknown';
                
                if ($path && file_exists($path)) {
                    $this->processUploadedFile($path, $name, $folder);
                }
            }
        }

        $this->notify('success', 'Files uploaded successfully!');
    }

    protected function processUploadedFile(string $path, string $originalName, string $folder): Media
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $newName = uniqid() . '_' . time() . '.' . $extension;
        $storagePath = "uploads/{$folder}/{$newName}";

        // Copy file to storage
        Storage::put($storagePath, file_get_contents($path));

        // Get file info
        $mimeType = mime_content_type($path);
        $fileSize = filesize($path);
        
        // Get image dimensions if applicable
        $width = null;
        $height = null;
        if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            $imageInfo = getimagesize($path);
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];
            }
        }

        // Determine file type
        $fileType = $this->getFileType($mimeType);

        // Generate thumbnail for images
        if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            $this->generateThumbnail($path, $storagePath);
        }

        return Media::create([
            'user_id' => auth()->id(),
            'folder' => $folder,
            'name' => pathinfo($originalName, PATHINFO_FILENAME),
            'original_name' => $originalName,
            'file_name' => $storagePath,
            'mime_type' => $mimeType,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'width' => $width,
            'height' => $height,
            'alt' => pathinfo($originalName, PATHINFO_FILENAME),
            'is_active' => true,
        ]);
    }

    protected function getFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        if ($mimeType === 'application/pdf' || str_contains($mimeType, 'document') || str_contains($mimeType, 'sheet')) {
            return 'document';
        }
        return 'other';
    }

    protected function generateThumbnail(string $sourcePath, string $storagePath): void
    {
        try {
            $image = \Intervention\Image\Facades\Image::make($sourcePath);
            $image->fit(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            });
            
            $thumbnailPath = 'thumbnails/' . basename($storagePath);
            Storage::put($thumbnailPath, $image->stream()->__toString());
        } catch (\Exception $e) {
            // Thumbnail generation failed, continue without thumbnail
            \Log::warning('Thumbnail generation failed: ' . $e->getMessage());
        }
    }
}
