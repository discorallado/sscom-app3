<?php

namespace App\Filament\Resources\Manager\CotizationResource\RelationManagers;

use App\Filament\Resources\Manager\BillResource;
use App\Models\Manager\Bill;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillsRelationManager extends RelationManager
{
  protected static string $relationship = 'bills';

  protected static ?string $modelLabel = 'Factura';

  protected static ?string $pluralModelLabel = 'Facturas';

  protected static ?string $recordTitleAttribute = 'fecha';

  //   public function form(Form $form): Form
  //   {
  //     return $form
  //       ->schema([
  //         Forms\Components\TextInput::make('fecha')
  //           ->required()
  //           ->maxLength(255),
  //       ]);
  //   }

  public function table(Table $table): Table
  {
    return $table

      ->columns([
        Tables\Columns\TextColumn::make('fecha')
          ->searchable()
          ->sortable()
          ->date(),
        Tables\Columns\TextColumn::make('doc')
          ->badge()
          ->searchable()
          ->color('secondary')
          ->sortable(),

        Tables\Columns\TextColumn::make('tipo')
          ->badge()
          ->searchable()
          ->sortable()
          ->colors([
            'success' => 'VENTA',
            'warning' => 'COSTO',
          ]),
        Tables\Columns\TextColumn::make('total_price')
          ->searchable()
          ->sortable()
          ->label('Valor')
          ->money('clp'),
      ])
      ->filters([
        Tables\Filters\TrashedFilter::make()
      ])
      ->headerActions([
        Tables\Actions\Action::make('Nueva factura')
          ->url(fn (): string => BillResource::getUrl('create')),
        // Tables\Actions\CreateAction::make(),
      ])
      ->actions([
        Tables\Actions\Action::make('open')
          ->url(fn (Bill $record): string => BillResource::getUrl('view', ['record' => $record])),
        // Tables\Actions\ViewAction::make(),
        // Tables\Actions\EditAction::make(),
        // Tables\Actions\DeleteAction::make(),
        // Tables\Actions\ForceDeleteAction::make(),
        // Tables\Actions\RestoreAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
          Tables\Actions\RestoreBulkAction::make(),
          Tables\Actions\ForceDeleteBulkAction::make(),
        ]),
      ])
      ->emptyStateActions([
        Tables\Actions\CreateAction::make(),
      ])
      ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
        SoftDeletingScope::class,
      ]));
  }
}
