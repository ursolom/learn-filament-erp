<?php

namespace App\Filament\App\Resources\BlogResource\Pages;

use App\Filament\App\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    public static function canAccessRecord(Model $record): bool
    {
        return $record->user_id === Auth::id();
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
