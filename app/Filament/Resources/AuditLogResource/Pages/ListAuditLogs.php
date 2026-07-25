<?php

declare(strict_types=1);

namespace App\Filament\Resources\AuditLogResource\Pages;

use App\Filament\Resources\Pages\BasePage;
use Filament\Resources\Pages\ListRecords;

class ListAuditLogs extends BasePage
{
    protected static string $resource = \App\Filament\Resources\AuditLogResource::class;

    {
        return [
            'index' => ListRecords::route('/'),
        ];
    }
}
