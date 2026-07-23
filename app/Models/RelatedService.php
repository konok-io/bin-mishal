<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatedService extends Model
{
    use HasFactory;

    protected $table = 'related_services';

    protected $fillable = [
        'service_type', // 'umrah', 'visa', 'cargo', 'flight', 'investor', 'appointment', 'page'
        'service_id',
        'related_service_type',
        'related_service_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function service()
    {
        return $this->belongsToModel($this->service_type, $this->service_id);
    }

    public function relatedService()
    {
        return $this->belongsToModel($this->related_service_type, $this->related_service_id);
    }

    protected function belongsToModel(string $type, int $id): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        $modelClass = match ($type) {
            'umrah' => \App\Models\UmrahPackage::class,
            'visa' => \App\Models\VisaType::class,
            'cargo' => \App\Models\Cargo\Cargo::class,
            'flight' => \App\Models\FlightRoute::class,
            'investor' => \App\Models\InvestorService::class,
            'appointment' => null,
            'page' => \App\Models\CMS\Page::class,
            default => null,
        };

        if (!$modelClass) {
            return new \Illuminate\Database\Eloquent\Relations\BelongsTo(
                new \Illuminate\Database\Eloquent\Builder(new \Illuminate\Database\Eloquent\Model()),
                $this
            );
        }

        return $this->belongsTo($modelClass, $this->getTable() === 'related_services' ? 'service_id' : 'related_service_id');
    }

    public function getRelatedServiceModel(): ?Model
    {
        return match ($this->related_service_type) {
            'umrah' => \App\Models\UmrahPackage::find($this->related_service_id),
            'visa' => \App\Models\VisaType::find($this->related_service_id),
            'cargo' => \App\Models\Cargo\Cargo::find($this->related_service_id),
            'flight' => \App\Models\FlightRoute::find($this->related_service_id),
            'investor' => \App\Models\InvestorService::find($this->related_service_id),
            default => null,
        };
    }

    public static function getRelated(string $serviceType, int $serviceId, int $limit = 4): array
    {
        return static::where('service_type', $serviceType)
            ->where('service_id', $serviceId)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->related_service_type,
                    'id' => $item->related_service_id,
                    'model' => $item->getRelatedServiceModel(),
                ];
            })
            ->filter(fn($item) => $item['model'] !== null)
            ->toArray();
    }

    public static function addRelated(
        string $serviceType,
        int $serviceId,
        string $relatedType,
        int $relatedId,
        int $sortOrder = 0
    ): self {
        // Check if already exists
        $exists = static::where('service_type', $serviceType)
            ->where('service_id', $serviceId)
            ->where('related_service_type', $relatedType)
            ->where('related_service_id', $relatedId)
            ->exists();

        if ($exists) {
            return static::where('service_type', $serviceType)
                ->where('service_id', $serviceId)
                ->where('related_service_type', $relatedType)
                ->where('related_service_id', $relatedId)
                ->first();
        }

        return static::create([
            'service_type' => $serviceType,
            'service_id' => $serviceId,
            'related_service_type' => $relatedType,
            'related_service_id' => $relatedId,
            'sort_order' => $sortOrder,
        ]);
    }

    public static function removeRelated(
        string $serviceType,
        int $serviceId,
        string $relatedType,
        int $relatedId
    ): bool {
        return static::where('service_type', $serviceType)
            ->where('service_id', $serviceId)
            ->where('related_service_type', $relatedType)
            ->where('related_service_id', $relatedId)
            ->delete() > 0;
    }
}
