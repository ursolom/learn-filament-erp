<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BlogResource\Pages;
use App\Filament\App\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $recordTitleAttribute = 'title';
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->title;
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'success';
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function ($query) {
                $query->where('privet', false)
                    ->orWhere(function ($query) {
                        $query->where('privet', true)
                            ->where('user_id', Auth::id());
                    });
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Info')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),
                        ToggleButtons::make('privet')
                            ->label('Status')
                            ->options([
                                true => 'Privet',
                                false => 'Public'
                            ])->colors([
                                false => 'danger',
                                true => 'success',
                            ])->icons([
                                false => 'heroicon-o-lock-closed',
                                true => 'heroicon-o-lock-open',
                            ])
                            ->inline()
                            ->default(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Content')
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label('Content')
                            ->required()
                            ->maxLength(65535),
                    ])
                    ->columnSpanFull(),

                Section::make('Image')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Blog Image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->imageEditorViewportWidth('1920')
                            ->imageEditorViewportHeight('1080')
                            ->imageEditorMode(2)
                            ->directory('blogs')
                            ->imagePreviewHeight('80')
                            ->uploadingMessage('Uploading attachment...')
                            ->loadingIndicatorPosition('center')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->maxSize(1024),

                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('content')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->limit(50),
                IconColumn::make('privet')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->toggleable(isToggledHiddenByDefault: false),
                ImageColumn::make('image')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->square()
                    ->ring(5)
                    ->circular()
                    ->stacked(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('privet')
                    ->options([
                        true => 'Privet',
                        false => 'Public',
                    ])->native(false),
            ])->filtersFormWidth(MaxWidth::FourExtraLarge)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn($record) => $record->user_id === Auth::id() || $record->privet === false),

                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $record->user_id === Auth::id()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn($record) => $record->user_id === Auth::id()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Delete My Blogs Only')
                        ->action(function ($records) {
                            $userId = Auth::id();

                            $records->each(function ($record) use ($userId) {
                                if ($record->user_id === $userId) {
                                    $record->delete();
                                }
                            });
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            // 'view' => Pages\ViewBlog::route('/{record}'),
            // 'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
