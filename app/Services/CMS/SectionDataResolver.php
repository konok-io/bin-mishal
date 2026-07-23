<?php

declare(strict_types=1);

namespace App\Services\CMS;

use App\Models\CMS\PageSection;
use Illuminate\Database\Eloquent\Collection;

class SectionDataResolver
{
    protected array $allowedModels = [];

    public function __construct()
    {
        $this->allowedModels = config('page_sections.data_source_models', []);
    }

    public function resolve(PageSection $section): Collection|array
    {
        $dataSource = $section->data_source ?? [];

        if (empty($dataSource)) {
            return [];
        }

        $model = $dataSource['model'] ?? null;

        if (!$model || !$this->isAllowedModel($model)) {
            return [];
        }

        return $this->queryModel($model, $dataSource);
    }

    public function isAllowedModel(string $model): bool
    {
        return isset($this->allowedModels[$model]);
    }

    public function getModelClass(string $model): ?string
    {
        return $this->allowedModels[$model] ?? null;
    }

    protected function queryModel(string $modelKey, array $config): Collection
    {
        $class = $this->allowedModels[$modelKey] ?? null;

        if (!$class) {
            return collect();
        }

        $query = $class::query();

        if (!empty($config['filter'])) {
            $this->applyFilters($query, $config['filter']);
        }

        if (!empty($config['order'])) {
            $this->applyOrdering($query, $config['order']);
        }

        $limit = $config['limit'] ?? null;
        if ($limit) {
            $query->limit((int) $limit);
        }

        if (!empty($config['columns'])) {
            $query->select($config['columns']);
        }

        return $query->get();
    }

    protected function applyFilters($query, array $filters): void
    {
        foreach ($filters as $field => $value) {
            if (is_array($value)) {
                if (isset($value['column'])) {
                    $operator = $value['operator'] ?? '=';
                    $boolean = $value['boolean'] ?? 'and';
                    $query->where($value['column'], $operator, $value['value'], $boolean);
                }
            } elseif (is_bool($value)) {
                $query->where($field, $value);
            } elseif (is_null($value)) {
                $query->whereNull($field);
            } else {
                $query->where($field, $value);
            }
        }
    }

    protected function applyOrdering($query, string|array $order): void
    {
        if (is_string($order)) {
            $parts = explode(',', $order);
            foreach ($parts as $part) {
                $segments = explode(':', trim($part));
                if (count($segments) === 2) {
                    $field = trim($segments[0]);
                    $direction = strtolower(trim($segments[1])) === 'asc' ? 'asc' : 'desc';
                    $query->orderBy($field, $direction);
                }
            }
        } elseif (is_array($order)) {
            foreach ($order as $field => $direction) {
                $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
                $query->orderBy($field, $direction);
            }
        }
    }

    public function getTranslatedAttribute(mixed $model, string $key): ?string
    {
        $locale = app()->getLocale();

        if (is_array($model->$key)) {
            return $model->$key[$locale] ?? $model->$key['en'] ?? null;
        }

        return $model->$key ?? null;
    }

    public function getFeatured(string $modelKey, int $limit = 8): Collection
    {
        return $this->queryModel($modelKey, [
            'filter' => ['is_featured' => true],
            'limit' => $limit,
            'order' => 'order:asc',
        ]);
    }

    public function getLatest(string $modelKey, int $limit = 8): Collection
    {
        return $this->queryModel($modelKey, [
            'limit' => $limit,
            'order' => 'created_at:desc',
        ]);
    }

    public function getAvailableModels(): array
    {
        $models = [];

        foreach ($this->allowedModels as $key => $class) {
            $models[$key] = [
                'key' => $key,
                'class' => $class,
                'name' => class_basename($class),
            ];
        }

        return $models;
    }
}
