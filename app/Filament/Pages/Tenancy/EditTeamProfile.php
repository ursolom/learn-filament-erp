<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Team profile';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make("profile")->schema([
                    TextInput::make('name'),
                    TextInput::make('slug')
                ])
            ]);
    }
}
