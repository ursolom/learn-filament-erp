<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\Employee;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make()->badge(Employee::count()),
            'This Week' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('date_hired', '>=', now()->startOfWeek()))
                ->badge(Employee::query()->where('date_hired', '>=', now()->subWeek())->count()),
            'This Month' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('date_hired', '>=', now()->startOfMonth()))
                ->badge(Employee::query()->where('date_hired', '>=', now()->subMonth())->count()),
            'This Year' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('date_hired', '>=', now()->startOfYear()))
                ->badge(Employee::query()->where('date_hired', '>=', now()->subYear())->count()),
        ];
    }
}
