<?php

namespace App\Filament\Resources\Manager\BillResource\RelationManagers;

use App\Models\Manager\Bill;
use App\Models\Manager\Cotization;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class PaymentsRelationManager extends RelationManager
{
  protected static string $relationship = 'payments';

  protected static ?string $recordTitleAttribute = 'doc';

  protected static ?string $modelLabel = 'Pago';

  protected static ?string $pluralModelLabel = 'Pagos';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Grid::make(3)->schema([

          Forms\Components\DateTimePicker::make('fecha')
            ->default(now())
            ->seconds(\false)
            ->required(),

          Forms\Components\Select::make('manager_cotization_id')
            ->label('Cotizacion:')
            ->disabled()
            ->options(Cotization::all()->pluck('codigo', 'id'))
            ->default(function (RelationManager $livewire): int {
              return $livewire->ownerRecord->manager_cotization_id;
            }),

          Forms\Components\Select::make('manager_bill_id')
            ->label('Factura:')
            ->disabled()
            ->options(Bill::all()->pluck('doc', 'id'))
            ->default(function (RelationManager $livewire): int {
              return (int)$livewire->ownerRecord->id;
            }),
        ]),

        Forms\Components\Grid::make(3)->schema([
          Forms\Components\TextInput::make('total_price')
            ->label('Total:')
            ->hint('Saldo del último pago')
            ->disabled()
            ->required()
            ->reactive()
            ->default(function (RelationManager $livewire, callable $set): string {
              $consulta = DB::table('manager_payments')
                ->where('manager_bill_id', '=', $livewire->ownerRecord->id)
                ->orderBy('fecha', 'DESC')
                ->get('saldo');
              if (count($consulta) > 0) {
                return (string)$consulta[0]->saldo;
              } else {
                return (string)$livewire->ownerRecord->total_price;
              }
            })
            // ->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: '$', thousandsSeparator: '.', decimalPlaces: 0))
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
              $set('saldo', (string)floor((int)$get('total_price') - (int)$get('abono')));
            }),

          Forms\Components\TextInput::make('abono')
            ->label('Abono:')
            ->reactive()
            ->default('0')
            ->required()
            ->lte('total_price')
            ->afterStateUpdated(function ($state, callable $set, callable $get) {
              $set('saldo', (string)floor((int)$get('total_price') - (int)$get('abono')));
            }),
          // ->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: '$', thousandsSeparator: '.', decimalPlaces: 0)),

          Forms\Components\TextInput::make('saldo')
            ->label('Saldo:')
            ->disabled()
            ->default('0')
            ->reactive()
            ->required()
            ->default(function (callable $get): string {
              return (string)$get('total_price');
            }),
          // ->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: '$', thousandsSeparator: '.', decimalPlaces: 0)),

        ]),

        Forms\Components\RichEditor::make('descripcion')
          ->columnSpan('full')
          ->maxLength(65535),

        Forms\Components\Hidden::make('manager_work_id')
          ->default(function (RelationManager $livewire) {
            return $livewire->ownerRecord->manager_work_id;
          }),



        SpatieMediaLibraryFileUpload::make('file')
          ->label('Adjunto')
          ->preserveFilenames()
          ->openable()
          ->downloadable()
          ->columnSpan('full'),
      ]);
  }

  public function table(Table $table): Table
  {

    return $table
      ->columns([
        Tables\Columns\TextColumn::make('fecha'),
        Tables\Columns\TextColumn::make('total_price'),
        Tables\Columns\TextColumn::make('abono'),
        Tables\Columns\TextColumn::make('saldo'),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        Tables\Filters\TrashedFilter::make()
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make(),
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
        Tables\Actions\ForceDeleteAction::make(),
        Tables\Actions\RestoreAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\DeleteBulkAction::make(),
        Tables\Actions\RestoreBulkAction::make(),
        Tables\Actions\ForceDeleteBulkAction::make(),
      ])
      ->emptyStateActions([
        Tables\Actions\CreateAction::make(),
      ])
      ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
        SoftDeletingScope::class,
      ]));
  }
}
