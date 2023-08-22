<?php

namespace App\Filament\Resources\Manager\WorkResource\RelationManagers;

use App\Models\Manager\Bill;
use App\Models\Manager\Cotization;
use App\Models\Manager\Customer;
use App\Models\Manager\Work;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class BillsRelationManager extends RelationManager
{
  protected static string $relationship = 'bills';

  protected static ?string $modelLabel = 'Factura';

  protected static ?string $pluralModelLabel = 'Facturas';

  protected static ?string $recordTitleAttribute = 'fecha';

  public function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Fieldset::make('Detalles')
          ->columns(3)
          ->schema([

            Forms\Components\DateTimePicker::make('fecha')
              ->label('Fecha emisión:')
              ->seconds(false)
              ->required()
              ->default(\now()),

            Forms\Components\TextInput::make('doc')
              ->label('Numero de documento')
              ->hint('ej: FAC4321')
              ->default('FAC')
              ->autofocus()
              ->required()
              ->prefixIcon('heroicon-o-hashtag')
              ->maxLength(191),

            ButtonGroup::make('tipo')
              ->columnSpan(3)
              ->columns(2)
              ->label('Tipo de factura')
              ->reactive()
              ->options([
                'VENTA' => 'Factura de VENTA',
                'COSTO' => 'Factura de COMPRA',
              ])
            //   ->descriptions([
            //     'VENTA' => 'La factura se guarda como venta.',
            //     'COSTO' => 'La factura se guarda como compra.',
            //   ])
          ]),
        Forms\Components\Fieldset::make('Venta')
          ->columns(3)
          ->visible(fn (\Filament\Forms\Get $get) => $get('tipo') == 'VENTA')
          ->schema([

            Forms\Components\Select::make('manager_work_id')
              ->label('Trabajo')
              ->options(Work::all()->pluck('title', 'id')->toArray())
              ->default(function (RelationManager $livewire): int {
                return (int)$livewire->ownerRecord->id;
              })
              ->reactive()
              ->required()
              ->disabled()
              ->afterStateUpdated(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                $set('manager_cotization_id', null);
                $set('customer',  Work::find($get('manager_work_id'))?->manager_customer_id);
              })
              ->columnSpan(2),

            Forms\Components\Select::make('manager_cotization_id')
              ->label('Cotizacion')
              ->reactive()
              ->options(function (\Filament\Forms\Get $get) {
                $work = Work::find($get('manager_work_id'));
                if (!$work) {
                  return Cotization::all()->pluck('codigo', 'id');
                } else {
                  return $work->cotization->pluck('codigo', 'id')->toArray();
                }
              })
              ->afterStateUpdated(function (\Filament\Forms\Get $get, \Filament\Forms\Set $set) {
                $total_price =  (string)Cotization::find($get('manager_cotization_id'))->total_price;
                $set('total_price', $total_price);
              })
              ->columnSpan(1),

            Forms\Components\Select::make('customer')
              ->label('Cliente:')
              ->disabled()
              ->options(Customer::all()->pluck('name', 'id'))
              ->default(function (RelationManager $livewire): int {
                return (int)$livewire->ownerRecord->Customer->id;
              })
              ->searchable()
              ->columnSpan(3),

          ]),

        Forms\Components\Fieldset::make('Gasto')
          ->visible(fn (\Filament\Forms\Get $get) => $get('tipo') == 'COSTO')
          ->columns(3)
          ->schema([
            Forms\Components\Select::make('customer')
              ->label('Proveedor')
              ->options(Customer::all()->pluck('name', 'name'))
              ->default(null)
              ->searchable()
              ->columnSpan(3)
              ->createOptionForm([
                Forms\Components\TextInput::make('name')
                  ->required(),
                Forms\Components\TextInput::make('rut'),
              ])
              ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                return $action
                  ->modalHeading('Create customer')
                  ->modalButton('Create customer')
                  ->modalWidth('sm');
              })
              ->createOptionUsing(function (array $data) {
                if ($customer = Customer::create($data)) {
                  return $customer->id;
                }
              }),
            Forms\Components\Select::make('manager_work_id')
              ->label('Trabajo')
              ->options(Work::all()->pluck('title', 'id')->toArray())
              //   ->searchable()
              ->disabled()
              ->reactive()
              ->afterStateUpdated(function (callable $set, callable $get) {
                $set('manager_cotization_id', null);
                $set('customer',  Work::find($get('manager_work_id'))?->manager_customer_id);
              })
              ->columnSpan(2),
            Forms\Components\Select::make('manager_cotization_id')
              ->label('Cotizacion')
              ->reactive()
              ->options(function (callable $get) {
                $work = Work::find($get('manager_work_id'));
                if (!$work) {
                  return Cotization::all()->pluck('codigo', 'id');
                } else {
                  return $work->cotization->pluck('codigo', 'id')->toArray();
                }
              })
              ->columnSpan(1),
          ]),

        Forms\Components\Fieldset::make('Monto')
          ->schema([

            Forms\Components\Placeholder::make('neto')
              ->label('Precio Neto $:')
              ->reactive()
              ->columnSpan(1)
              ->content(function (callable $get, callable $set, $state) {
                return '$ ' . \number_format((int)$get('total_price') / 1.19, 0, '', '.');
              }),

            Forms\Components\Placeholder::make('iva')
              ->label('IVA $:')
              ->reactive()
              ->columnSpan(1)
              ->content(function (callable $get, callable $set, $state) {
                return '$ ' . \number_format((int)$get('total_price') * 0.1596638655, 0, '', '.');
              }),

            Forms\Components\TextInput::make('total_price')
              ->label('Precio total $:')
              ->reactive()
              ->columnSpan(1)
              ->default('0')
            //   ->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: '$', thousandsSeparator: '.', decimalPlaces: 0)),

          ])->columns(3),



        Forms\Components\Fieldset::make('Descripcion')
          ->schema([

            RichEditor::make('descripcion'),

            SpatieMediaLibraryFileUpload::make('file')
              ->label('Archivo djunto')
              ->preserveFilenames()
              ->enableOpen()
              ->enableDownload()
              ->columnSpan('full'),
          ])
          ->columns(1),
      ])
      ->columns(3);
  }

  public function table(Table $table): Table
  {
    return $table
      ->defaultSort('created_at', 'desc')
      ->columns([
        Tables\Columns\TextColumn::make('fecha')
          ->sortable()
          ->date(),

        Tables\Columns\BadgeColumn::make('tipo')
          ->sortable()
          ->colors([
            'primary' => 'VENTA',
            'success' => 'PAGO',
          ]),
        Tables\Columns\TextColumn::make('customer.name')
          ->sortable(),
        Tables\Columns\TextColumn::make('cotization.codigo')
          ->placeholder('S/C')
          ->sortable()
          ->size('sm'),
        Tables\Columns\TextColumn::make('total_price')
          ->sortable()
          ->money('clp'),
        Tables\Columns\TextColumn::make('payments_sum_abono')
          ->label('Pagos')
          ->sum('payments', 'abono')
          ->placeholder('$0')
          ->sortable()
          ->money('clp'),
      ])
      ->filters([
        Tables\Filters\TrashedFilter::make()
      ])
      ->headerActions([
        Tables\Actions\CreateAction::make(),
      ])
      ->actions([
        // Tables\Actions\ActionGroup::make([
        Tables\Actions\ViewAction::make()
          ->hidden(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\Action::make('details')
          ->label('Detalles')
          ->icon('heroicon-o-document-text')
          ->color('secondary')
          ->url(fn (Bill $record): string => route('filament.admin.resources.manager.bills.view', $record)),
        Tables\Actions\DeleteAction::make(),
        Tables\Actions\ForceDeleteAction::make(),
        Tables\Actions\RestoreAction::make(),
        // ])
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
