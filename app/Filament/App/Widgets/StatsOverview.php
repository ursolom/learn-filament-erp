<?php

namespace App\Filament\App\Widgets;

use App\Models\Blog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $heading = 'Analytics Count';
    protected ?string $description = 'An overview of some analytics count.';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', Team::find(Filament::getTenant())->first()->members->count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Departments', Department::query()->whereBelongsTo(Filament::getTenant())->count())
                ->color('danger'),
            Stat::make('Employees', Employee::query()->whereBelongsTo(Filament::getTenant())->count())
                ->color('success'),
            Stat::make('Blogs', Blog::query()->whereBelongsTo(Filament::getTenant())->count())
                ->color('danger'),
        ];
    }
}
