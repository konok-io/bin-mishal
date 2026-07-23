<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\RelatedService;
use Illuminate\View\Component;

class RelatedServices extends Component
{
    public string $serviceType;
    public int $serviceId;
    public int $limit;
    public array $relatedItems = [];

    public function __construct(
        string $serviceType,
        int $serviceId,
        int $limit = 4
    ) {
        $this->serviceType = $serviceType;
        $this->serviceId = $serviceId;
        $this->limit = $limit;
        $this->loadRelated();
    }

    protected function loadRelated(): void
    {
        $this->relatedItems = RelatedService::getRelated(
            $this->serviceType,
            $this->serviceId,
            $this->limit
        );
    }

    public function getServiceInfo(array $item): array
    {
        $model = $item['model'] ?? null;
        if (!$model) {
            return [];
        }

        $type = $item['type'];
        $title = match ($type) {
            'umrah' => $model->name ?? $model->title ?? 'Umrah Package',
            'visa' => $model->name ?? $model->title ?? 'Visa Service',
            'cargo' => $model->name ?? 'Cargo Service',
            'flight' => $model->origin ?? $model->name ?? 'Flight',
            'investor' => $model->name ?? $model->title ?? 'Investment Service',
            default => 'Service',
        };

        $image = match ($type) {
            'umrah' => $model->image ?? $model->featured_image ?? null,
            'visa' => $model->image ?? null,
            'cargo' => $model->image ?? null,
            'flight' => null,
            'investor' => $model->icon ?? null,
            default => null,
        };

        $route = match ($type) {
            'umrah' => route('services.umrah.show', ['slug' => $model->slug ?? $model->id]),
            'visa' => route('services.visa.show', ['slug' => $model->slug ?? $model->id]),
            'cargo' => route('cargo'),
            'flight' => route('services.airticket'),
            'investor' => route('investor'),
            default => '#',
        };

        return [
            'title' => $title,
            'image' => $image,
            'route' => $route,
            'type' => ucfirst($type),
        ];
    }

    public function render()
    {
        return view('components.related-services');
    }
}
