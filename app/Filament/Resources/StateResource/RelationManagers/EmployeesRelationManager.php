<?php

namespace App\Filament\Resources\StateResource\RelationManagers;

use App\Models\City;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
    {
        return $form
        ->schema([

            Forms\Components\Section::make('Relationship')
                ->schema([
                    Forms\Components\Select::make('country_id')
                        ->relationship(name: 'country', titleAttribute: 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->afterStateUpdated(function (Set $set) {
                            $set('state_id', null);
                            $set('city_id', null);
                        })
                        ->optionsLimit(20)
                        ->live(),
                    Forms\Components\Select::make('state_id')
                        ->options(
                            fn(Get $get) => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck("name", 'id')
                        )
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn(Set $set) => $set('city_id', null))
                        ->searchable()
                        ->preload()
                        ->optionsLimit(20),
                    Forms\Components\Select::make('city_id')
                        ->options(
                            fn(Get $get) => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck("name", 'id')
                        )
                        ->live()
                        ->required()
                        ->searchable()
                        ->optionsLimit(20),
                    Forms\Components\Select::make('department_id')
                        ->relationship(name: 'department', titleAttribute: 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                ])->columns(2),
            Forms\Components\Section::make('User Information')->description('This information is used to identify the employee.')->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('middle_name')
                    ->required()
                    ->maxLength(255),
            ])->columns(3),
            Forms\Components\Section::make('Contact Information')->description('This information is used to contact the employee.')->schema([
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip_code')
                    ->required()
                    ->maxLength(255),
            ])->columns(2),
            Forms\Components\Section::make('Dates')->description('This information is used to identify the employee.')->schema([
                Forms\Components\DatePicker::make('date_of_birth')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->required(),
                Forms\Components\DatePicker::make('date_hired')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->required(),
            ])->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                Tables\Columns\TextColumn::make('first_name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
