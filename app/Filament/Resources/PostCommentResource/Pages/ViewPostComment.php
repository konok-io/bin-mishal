<?php

declare(strict_types=1);

namespace App\Filament\Resources\PostCommentResource\Pages;

use App\Filament\Resources\PostCommentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPostComment extends ViewRecord
{
    protected static ?string $resource = PostCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
