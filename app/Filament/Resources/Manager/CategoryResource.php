<?php

namespace App\Filament\Resources\Manager;

use App\Filament\Resources\Manager\CategoryResource\Pages;
use App\Filament\Resources\Manager\CategoryResource\RelationManagers;
use App\Models\Manager\Category;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
  protected static ?string $model = Category::class;

  protected static ?string $slug = 'manager/categories';

  protected static ?string $modelLabel = 'Categoria';

  protected static ?string $pluralModelLabel = 'Categorias';

  protected static ?string $recordTitleAttribute = 'name';

  protected static ?string $navigationGroup = 'Manager';

  protected static ?string $navigationIcon = 'heroicon-o-tag';


  protected static ?int $navigationSort = 7;

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Section::make()
          ->schema([
            Grid::make()
              ->schema([
                TextInput::make('name')
                  ->required()
                  ->maxValue(50)
                  ->lazy()
                  ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),


                TextInput::make('slug')
                  ->disabled()
                  ->required()
                  ->unique(Category::class, 'slug', ignoreRecord: true),
              ]),

            Select::make('parent_id')
              ->label('Parent')
              ->relationship('parent', 'name', fn (Builder $query) => $query->where('parent_id', null))
              //   ->searchable()
              ->options(Category::query()->pluck('name', 'id'))
              ->placeholder('Select parent category'),


            Select::make('color')
              ->options([
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'warning' => 'Warning',
                'danger' => 'Danger',
                'success' => 'Success',
              ]),

            Toggle::make('is_visible')
              ->label('Visible to customers.')
              ->default(true),

            RichEditor::make('description')
              ->label('Description'),
          ])
          ->columnSpan(['lg' => fn (?Category $record) => $record === null ? 3 : 2]),
        Section::make()
          ->schema([
            Placeholder::make('created_at')
              ->label('Created at')
              ->content(fn (Category $record): ?string => $record->created_at?->diffForHumans()),

            Placeholder::make('updated_at')
              ->label('Last modified at')
              ->content(fn (Category $record): ?string => $record->updated_at?->diffForHumans()),
          ])
          ->columnSpan(['lg' => 1])
          ->hidden(fn (?Category $record) => $record === null),
      ])
      ->columns(3);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')
          ->label('Name')
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('parent.name')
          ->label('Parent')
          ->badge()
          ->color(fn (Category $record): ?string => Category::find($record->parent_id)?->color)
          ->searchable()
          ->sortable(),
        Tables\Columns\ToggleColumn::make('is_visible')
          ->label('Visibility')
          ->sortable(),

        Tables\Columns\TextColumn::make('created_at')
          ->label('Creado el')
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true)
          ->sortable(),
        Tables\Columns\TextColumn::make('updated_at')
          ->label('Modificado el')
          ->placeholder('Nunca')
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true)
          ->sortable(),
        Tables\Columns\TextColumn::make('deleted_at')
          ->label('Eliminado el')
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true)
          ->placeholder('Nunca')
          ->sortable(),
      ])
      ->filters([
        //
        Tables\Filters\TrashedFilter::make(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
        Tables\Actions\ForceDeleteAction::make(),
        Tables\Actions\RestoreAction::make(),
      ])
      ->bulkActions([

        Tables\Actions\DeleteBulkAction::make()
          ->action(function () {
            Notification::make()
              ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
              ->warning()
              ->send();
          }),
        Tables\Actions\ForceDeleteBulkAction::make(),
        Tables\Actions\RestoreBulkAction::make(),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
      RelationManagers\ProductsRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListCategories::route('/'),
      'create' => Pages\CreateCategory::route('/create'),
      'view' => Pages\ViewCategory::route('/{record}'),
      'edit' => Pages\EditCategory::route('/{record}/edit'),
    ];
  }

  public static function getEloquentQuery(): Builder
  {
    return parent::getEloquentQuery()
      ->withoutGlobalScopes([
        SoftDeletingScope::class,
      ]);
  }
}
