<?php

namespace App\Filament\Resources\Manager;

use App\Filament\Resources\Manager\BillResource\Pages;
use App\Filament\Resources\Manager\BillResource\RelationManagers;
use App\Models\Manager\Bill;
use App\Models\Manager\Cotization;
use App\Models\Manager\Customer;
use App\Models\Manager\Payment;
use App\Models\Manager\Work;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Wallo\FilamentSelectify\Components\ButtonGroup;

class BillResource extends Resource
{
  protected static ?string $model = Bill::class;

  protected static ?int $navigationSort = 4;

  protected static ?string $slug = 'manager/bills';

  protected static ?string $modelLabel = 'Factura';

  protected static ?string $pluralModelLabel = 'Facturas';

  protected static ?string $recordTitleAttribute = 'fecha';

  protected static ?string $navigationGroup = 'Manager';

  protected static ?string $navigationIcon = 'heroicon-o-inbox';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Group::make()
          ->columns(4)
          ->columnSpan('full')
          ->schema([

            Section::make('Detalles')
              ->columns(5)
              ->columnSpan(3)
              ->icon('heroicon-o-identification')
              ->schema([

                DateTimePicker::make('fecha')
                  ->label('Fecha emisión:')
                  ->seconds(false)
                  ->required()
                  ->timezone('America/Santiago')
                  ->default(\now())
                  ->columnSpan(2),

                TextInput::make('doc')
                  ->label('Numero de documento')
                  ->hint('ej: FAC4321')
                  ->mask('FAC999999999999')
                  ->placeholder('FAC')
                  ->autofocus()
                  ->unique(ignorable: fn ($record) => $record)
                  ->required()
                  ->prefixIcon('heroicon-o-hashtag')
                  ->maxLength(191)
                  ->columnSpan(3),

                Select::make('manager_work_id')
                  ->label('Trabajo')
                  ->reactive()
                  ->options(Work::all()->pluck('title', 'id')->toArray())
                  ->afterStateUpdated(function (\Filament\Forms\Set $set, \Filament\Forms\Get $get) {
                    $set('manager_cotization_id', null);
                    $set('total_price', '0');
                  })
                  ->required()
                  ->columnSpan(3),

                Select::make('manager_cotization_id')
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
                  ->afterStateUpdated(function (Get $get, \Filament\Forms\Set $set, string $state) {
                    if ((string)$get('tipo') == 'VENTA') {
                      $total_price =  (string)Cotization::find((int)$get('manager_cotization_id'))->total_price;
                      $set('total_price', $total_price);
                    }
                  })
                  ->columnSpan(2),
              ]),

            Section::make()
              ->columns(1)
              ->columnSpan(1)
              ->hidden(fn (?Bill $record) => $record === null)
              ->schema([
                Placeholder::make('created_at')
                  ->label('Creado')
                  ->content(fn (Bill $record): ?string => $record->created_at?->diffForHumans() . ' (' . $record->created_at->format('H:i d-m-Y') . ')'),
                Placeholder::make('updated_at')
                  ->label('Última modificación')
                  ->content(fn (Bill $record): ?string => $record->updated_at?->diffForHumans() . ' (' . $record->updated_at->format('H:i d-m-Y') . ')'),
              ]),
          ]),

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
          //     'VENTA' => 'Para facturar una cotizacion.',
          //     'COSTO' => 'La factura se guardará como compra.',
          //   ])
          ->disabled(fn (\Filament\Forms\Get $get) => $get('manager_work_id') === null)
          ->afterStateUpdated(function (\Filament\Forms\Get $get, \Filament\Forms\Set $set, string $state) {
            if ((string)$state == 'VENTA') {
              if ($get('manager_work_id')) {
                $set('customer',  Work::find((int)$get('manager_work_id'))?->manager_customer_id);
              }
              if ($get('manager_cotization_id')) {
                $cotizacion =  Cotization::find((int)$get('manager_cotization_id'));
                $total_price =  (string)$cotizacion->total_price;
                $set('total_price', $total_price);

                $items = $cotizacion->items;
                // dd($items);
                $resultado = $cotizacion->codigo . ':------------------------------------- <br />';
                foreach ($items as $item) {
                  //   $resultado .= $item->cantidad . '<br />';
                  $total = (int)$item->precio_anotado * (int)$item->cantidad;
                  $resultado .= $item->cantidad . ' x ' . $item->Product->nombre . ' : $' . \number_format($item->precio_anotado, 0, 0, '.') . '<br />';
                }
                $resultado .= '------------------------------------------------------<br />';
                $resultado .= 'Neto: $' . number_format((int)$cotizacion->total_price - (int)$cotizacion->iva_price, 0, 0, '.');
                $resultado .= '<br />IVA: $' . number_format((int)$cotizacion->iva_price, 0, 0, '.');
                $resultado .= '<br />Total: $' . number_format((int)$cotizacion->total_price, 0, 0, '.');
                $set('descripcion', $resultado);
              }
            } elseif ((string)$state == 'COSTO') {
              $set('customer', null);
              $set('descripcion', null);
              $set('total_price', '0');
            }
          }),

        Section::make('Receptor')
          ->description('Quien recibe la factura')
          ->icon('heroicon-o-user-circle')
          ->schema([
            Select::make('customer')
              ->label(false)
              ->reactive()
              ->searchable()
              ->options(Customer::all()->pluck('name', 'id'))
              ->columnSpan(3),

          ]),
        Section::make('Monto')
          ->icon('heroicon-o-banknotes')
          ->description('Factura de compra')
          ->schema([

            Placeholder::make('neto')
              ->label('Precio Neto $:')
              ->reactive()
              ->columnSpan(1)
              ->content(function (\Filament\Forms\Get $get, \Filament\Forms\Set $set, $state) {
                return '$ ' . \number_format((int)$get('total_price') / 1.19, 0, '', '.');
              }),

            Placeholder::make('iva')
              ->label('IVA $:')
              ->reactive()
              ->columnSpan(1)
              ->content(function (\Filament\Forms\Get $get, \Filament\Forms\Set $set, $state) {
                return '$ ' . \number_format((int)$get('total_price') * 0.1596638655, 0, '', '.');
              }),

            TextInput::make('total_price')
              ->label('Precio total $:')
              ->reactive()
              ->columnSpan(1)
              ->default('0')
              ->mask(RawJs::make(
                <<<'JS'
                      $money($input, ',', '.', 0)
              JS
              )),
          ])->columns(3),

        Section::make('Descripcion')
          ->description('Observaciones y detalles')
          ->icon('heroicon-o-identification')
          ->schema([

            RichEditor::make('descripcion'),

            SpatieMediaLibraryFileUpload::make('file')
              ->label('Archivo djunto')
              ->preserveFilenames()
              ->openable()
              ->downloadable()
              ->columnSpan('full'),
          ])
          ->columns(1),

      ])
      ->columns(3);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('fecha')
          ->searchable()
          ->sortable()
          ->date(),
        Tables\Columns\TextColumn::make('doc')
          ->searchable()
          ->badge()
          ->color('secondary')
          ->sortable(),

        Tables\Columns\TextColumn::make('tipo')
          ->searchable()
          ->sortable()
          ->badge()
          ->colors([
            'success' => 'VENTA',
            'warning' => 'COSTO',
          ]),

        Tables\Columns\TextColumn::make('work.title')
          ->label('Proyecto')
          ->searchable()
          ->size('sm')
          ->sortable(),

        Tables\Columns\TextColumn::make('cotization.codigo')
          ->searchable()
          ->placeholder('S/C')
          ->sortable()
          ->size('sm'),

        Tables\Columns\TextColumn::make('total_price')
          ->searchable()
          ->sortable()
          ->label('Valor')
          ->money('clp'),

        Tables\Columns\TextColumn::make('payments_sum_abono')
          ->label('Pagos')
          ->sum('payments', 'abono')
          ->placeholder('$0')
          ->sortable()
          ->searchable()
          ->money('clp')
          ->iconPosition('after')
          ->icon(function (Model $record) {
            if (((string)$record->tipo == 'VENTA') && ((int)$record->total_price == (int)Payment::where('manager_bill_id', '=', $record->id)->sum('abono'))) {
              return 'heroicon-o-check-badge';
            }
            return null;
          })
          ->color(function (Model $record) {
            if (((string)$record->tipo == 'VENTA') && ((int)$record->total_price == (int)Payment::where('manager_bill_id', '=', $record->id)->sum('abono'))) {
              return 'success';
            }
            return null;
          }),

        Tables\Columns\TextColumn::make('user.name')
          ->label('Creado por')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true)
          ->sortable(),
        Tables\Columns\TextColumn::make('created_at')
          ->label('Creado el')
          ->searchable()
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true)
          ->sortable(),
        Tables\Columns\TextColumn::make('updated_at')
          ->label('Modificado el')
          ->searchable()
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true)
          ->sortable(),
        Tables\Columns\TextColumn::make('deleted_at')
          ->label('Eliminado el')
          ->searchable()
          ->dateTime()
          ->toggleable(isToggledHiddenByDefault: true)
          ->placeholder('Nunca')
          ->sortable(),

      ])
      ->defaultSort('created_at', 'desc')
      //   ->defaultSort( 'tipo', 'desc')
      ->filters([
        Tables\Filters\TrashedFilter::make(),
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([

          Tables\Actions\ViewAction::make(),
          Tables\Actions\EditAction::make(),
          Tables\Actions\DeleteAction::make(),
          Tables\Actions\ForceDeleteAction::make(),
          Tables\Actions\RestoreAction::make(),
        ])
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
      RelationManagers\PaymentsRelationManager::class,
    ];
  }

  public static function getWidgets(): array
  {
    return [
      BillResource\Widgets\BillStats::class,
    ];
  }

  protected function getHeaderWidgetsColumns(): int | array
  {
    return 4;
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListBills::route('/'),
      'create' => Pages\CreateBill::route('/create'),
      'view' => Pages\ViewBill::route('/{record}'),
      'edit' => Pages\EditBill::route('/{record}/edit'),
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
