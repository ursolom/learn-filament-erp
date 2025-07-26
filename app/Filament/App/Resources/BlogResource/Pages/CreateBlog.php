<?php

namespace App\Filament\App\Resources\BlogResource\Pages;

use App\Filament\App\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;
}
