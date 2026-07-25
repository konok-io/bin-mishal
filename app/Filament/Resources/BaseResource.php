<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use Filament\Resources\Resource;

class BaseResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;
    
    // Override to fix type compatibility
    public static function getNavigationGroup(): string|null
    {
        return static::$navigationGroup ?? null;
    }
}
