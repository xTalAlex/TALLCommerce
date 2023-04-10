<?php

namespace App\Traits;

trait WithProductsFilterQuery
{
    public function scopeProductsFilter($query, array $filters)
    {
        $query->whereHas('products', function ($query) use ($filters) {

                if(class_basename($this) != 'Category'){
                    $query->when(
                        $filters['category'] ?? false,
                        fn ($query) =>
                        $query->whereHas(
                            'categories',
                            fn ($query) =>
                            $query->where('categories.id',  (int) $filters['category'])
                                ->orWhere('categories.slug', insensitiveLike(), $filters['category'])
                        )
                    );
                }

                if(class_basename($this) != 'Brand'){
                    $query->when(
                        $filters['brand'] ?? false,
                        fn ($query) =>
                        $query->whereHas(
                            'brand',
                            fn ($query) =>
                            $query->whereIn('brands.id',  collect($filters['brand'])->map( fn($i) => (int)$i )->toArray() )
                                ->orWhereIn('brands.slug', $filters['brand'])
                        )
                    );
                }

                if(class_basename($this) != 'Collection'){
                    $query->when(
                        $filters['collection'] ?? false,
                        fn ($query) =>
                        $query->whereHas(
                            'collections',
                            fn ($query) =>
                            $query->whereIn('collections.id', collect($filters['collection'])->map( fn($i) => (int)$i )->toArray() )
                                ->orWhereIn('collections.slug', $filters['collection'])
                        )
                    );
                }

                $query->when(
                    $filters['query'] ?? false,
                    fn ($query) =>
                    $query->where(fn($query) => 
                        $query->where('name', insensitiveLike(), '%' . $filters['query'] . '%')
                        ->orWhere('short_description', insensitiveLike(), '%' . $filters['query'] . '%')
                        ->orWhere('description', insensitiveLike(), '%' . $filters['query'] . '%')
                        ->orWhereHas('tags', fn($query) => $query->where('name', insensitiveLike(), '%' . $filters['query'] . '%' ))
                        ->orWhereHas('categories', fn($query) => $query->where('name', insensitiveLike(), '%' . $filters['query'] . '%' ))
                        ->orWhereHas('collections', fn($query) => $query->where('name', insensitiveLike(), '%' . $filters['query'] . '%' ))
                        ->orWhereHas('brand', fn($query) => $query->where('name', insensitiveLike(), '%' . $filters['query'] . '%' ))
                    )
                );

                return $query;
            }
        );

    }
}