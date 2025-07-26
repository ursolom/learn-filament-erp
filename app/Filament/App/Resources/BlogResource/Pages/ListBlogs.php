<?php

namespace App\Filament\App\Resources\BlogResource\Pages;

use App\Filament\App\Resources\BlogResource;
use App\Models\Blog;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBlogs extends ListRecords
{
    // use HasResizableColumn;
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'Privet' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('privet', true))
                ->badge(Blog::query()->where('privet', true)->count()),
            'Public' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('privet', false))
                ->badge(Blog::query()->where('privet', false)->count()),
        ];
    }
}
