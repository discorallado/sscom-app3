<?php

namespace App\Filament\Resources\Manager;

use App\Filament\Resources\Manager\WorkResource\Pages;
use App\Filament\Resources\Manager\WorkResource\RelationManagers;
use App\Models\Manager\Customer;
use App\Models\Manager\Work;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkResource extends Resource
{
  protected static ?string $model = Work::class;

  protected static ?int $navigationSort = 2;

  protected static ?string $slug = 'manager/works';

  protected static ?string $recordTitleAttribute = 'title';

  protected static ?string $modelLabel = 'Proyecto';

  protected static ?string $pluralModelLabel = 'Proyectos';

  protected static ?string $navigationGroup = 'Manager';

  protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

  public static function form(Form $form): Form
  {
    return $form
      ->columns(3)
      ->schema([

        Forms\Components\Section::make('Detalles')
          ->columns(2)
          ->description('Detalles del trabajo.')
          ->icon('heroicon-o-identification')
          ->schema([

            Forms\Components\TextInput::make('title')
              ->label('Titulo:')
              ->required()
              ->reactive()
              ->maxLength(255)
              ->columnSpan('full'),


            Forms\Components\Select::make('manager_customer_id')
              ->label('Cliente')
              ->options(Customer::all()->pluck('name', 'id'))
              ->searchable()
              ->required()
              ->createOptionForm([
                Forms\Components\TextInput::make('rut'),
                //   ->required(),
                Forms\Components\TextInput::make('name')
                  ->required(),
              ])
              ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                return $action
                  ->modalHeading('Crear cliente')
                  ->modalButton('Crear')
                  ->modalWidth('md');
              })
              ->createOptionUsing(function (array $data) {
                if ($customer = Customer::create($data)) {
                  return $customer->id;
                }
              })
              ->columnSpan('full'),



          ]),

        Forms\Components\Section::make('Detalles')
          ->columns(1)
          ->description('Detalles del trabajo.')
          ->icon('heroicon-o-identification')
          ->schema([
            Forms\Components\DateTimePicker::make('inicio')
              ->withoutSeconds()
              ->required()
              ->default(today()),

            Forms\Components\DateTimePicker::make('termino')
              ->withoutSeconds()
              ->minDate(fn (Forms\Get $get) => Carbon::parse($get('inicio')))
              ->hiddenOn('create'),
          ]),

        Forms\Components\Section::make('Descripcion')
          ->columns(3)
          ->description('descripcion del trabajo.')
          ->icon('heroicon-o-document-text')
          ->schema([

            Forms\Components\RichEditor::make('descripcion')
              ->disableToolbarButtons([
                'attachFiles',
                'codeBlock',
              ])
              ->columnSpan('full'),

            SpatieMediaLibraryFileUpload::make('file')
              ->label('Archivo djunto')
              ->multiple()
              ->preserveFilenames()
              ->enableOpen()
              ->enableDownload()
              ->columnSpan('full'),
          ]),

      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('title')
          ->label('Titulo')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('inicio')
          ->date()
          ->label('Fecha inicio')
          ->searchable()
          ->sortable(),

        Tables\Columns\TextColumn::make('termino')
          ->date()
          ->label('Fecha término')
          ->placeholder('[ACTIVO]')
          ->searchable()
          ->extraAttributes(function (?Model $record) {
            $termino = $record->termino;
            return $termino
              ? ['class' => '']
              : ['class' => 'text-success-600 font-semi-bold'];
          })
          ->sortable(),

        Tables\Columns\TextColumn::make('customer.name')
          ->label('Cliente')
          ->searchable()
          ->sortable(),

      ])
      ->filters([
        Tables\Filters\TrashedFilter::make(),

        Tables\Filters\Filter::make('created_at')
          ->form([
            Forms\Components\DatePicker::make('created_from')
              ->label('Creado desde: ')
              ->placeholder(fn ($state): string => 'Ene 1, ' . now()->subYear()->format('Y')),
            Forms\Components\DatePicker::make('created_until')
              ->label('Creado hasta: ')
              ->placeholder(fn ($state): string => now()->format('M d, Y')),
          ])
          ->query(function (Builder $query, array $data): Builder {
            return $query
              ->when(
                $data['created_from'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
              )
              ->when(
                $data['created_until'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
              );
          })
          ->indicateUsing(function (array $data): array {
            $indicators = [];
            if ($data['created_from'] ?? null) {
              $indicators['created_from'] = 'Trabajos desde: ' . Carbon::parse($data['created_from'])->toFormattedDateString();
            }
            if ($data['created_until'] ?? null) {
              $indicators['created_until'] = 'Trabajos hasta: ' . Carbon::parse($data['created_until'])->toFormattedDateString();
            }

            return $indicators;
          }),
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
      //   RelationManagers\BillsRelationManager::class,
    ];
  }

  public static function getNavigationBadge(): ?string
  {
    $model = static::getModel();
    return $model::where('termino', '=', null)->count();
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListWorks::route('/'),
      'create' => Pages\CreateWork::route('/create'),
      'view' => Pages\ViewWork::route('/{record}'),
      'edit' => Pages\EditWork::route('/{record}/edit'),
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
