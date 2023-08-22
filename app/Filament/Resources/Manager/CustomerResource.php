<?php

namespace App\Filament\Resources\Manager;

use App\Filament\Resources\Manager\CustomerResource\Pages;
use App\Filament\Resources\Manager\CustomerResource\RelationManagers;
use App\Models\Manager\Customer;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
  protected static ?string $model = Customer::class;

  protected static ?int $navigationSort = 6;

  protected static ?string $slug = 'manager/customers';

  protected static ?string $modelLabel = 'Cliente';

  protected static ?string $pluralModelLabel = 'Clientes';

  protected static ?string $recordTitleAttribute = 'name';

  protected static ?string $navigationGroup = 'Manager';

  protected static ?string $navigationIcon = 'heroicon-o-user-circle';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        // Section::make(2)
        //   ->schema([
        Forms\Components\TextInput::make('rut')
          ->required()
          //   ->regex('^(\d{1,3}(?:\.\d{1,3}){2}-[\dkK])$')
          ->maxLength(12)
          ->columnSpan(1),
        //   ]),
        Forms\Components\TextInput::make('name')
          ->required()
          ->columnSpan(2)
          ->maxLength(191),
        Forms\Components\TextInput::make('giro')
          //   ->required()
          ->columnSpan(1)
          ->maxLength(191),

        Forms\Components\MarkdownEditor::make('contacto')
          //   ->required()
          ->columnSpan('full'),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('rut'),
        Tables\Columns\TextColumn::make('name')
          ->limit(35)
          ->tooltip(function (TextColumn $column, $state): ?string {
            $state = $column->getState();
            if (strlen($state) <= 35) {
              return null;
            }
            return $state;
          }),
        Tables\Columns\TextColumn::make('giro')
          ->limit(25)
          ->tooltip(function (TextColumn $column, $state): ?string {
            $state = $column->getState();
            if (strlen($state) <= 25) {
              return null;
            }
            return $state;
          }),

        Tables\Columns\TextColumn::make('user.name')
          ->label('Creado por')
          ->toggleable(isToggledHiddenByDefault: true)
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('created_at')
          ->label('Creado el')
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true)
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('updated_at')
          ->label('Modificado el')
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true)
          ->searchable()
          ->sortable(),
        Tables\Columns\TextColumn::make('deleted_at')
          ->label('Eliminado el')
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true)
          ->placeholder('Nunca')
          ->searchable()
          ->sortable(),
      ])
      ->defaultSort('created_at', 'asc')
      ->filters([
        Tables\Filters\TrashedFilter::make(),
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
        Tables\Actions\ForceDeleteAction::make(),
        Tables\Actions\RestoreAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\DeleteBulkAction::make(),
        Tables\Actions\ForceDeleteBulkAction::make(),
        Tables\Actions\RestoreBulkAction::make(),
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
      'index' => Pages\ManageCustomers::route('/'),
      //   'index' => Pages\ListCustomers::route('/'),
      //   'create' => Pages\CreateCustomer::route('/create'),
      //   'view' => Pages\ViewCustomer::route('/{record}'),
      //   'edit' => Pages\EditCustomer::route('/{record}/edit'),
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
