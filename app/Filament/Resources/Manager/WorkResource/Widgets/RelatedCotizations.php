<?php

namespace App\Filament\Resources\Manager\WorkResource\Widgets;

use App\Models\Manager\Cotization;
use App\Models\Manager\Payment;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class RelatedCotizations extends BaseWidget
{
  public int $record;

  protected int | string | array $columnSpan = [
    'md' => 2,
    'xl' => 3,
  ];

  public function table(Table $table): Table
  {
    return $table
      ->query(Cotization::query()->where('manager_work_id', '=', $this->record))
      ->columns([
        Tables\Columns\TextColumn::make('fecha')
          ->date(),
        Tables\Columns\TextColumn::make('id'),
        Tables\Columns\TextColumn::make('codigo'),

        Tables\Columns\TextColumn::make('total_price')
          ->summarize(Sum::make())
          ->money('clp'),
        Tables\Columns\TextColumn::make('payments_sum_abono')
          ->label('Pagos')
          ->sum('payments', 'abono')
          ->placeholder('$0')
          ->sortable()
          ->summarize(Sum::make())
          ->money('clp'),
      ])
      ->actions([
        Tables\Actions\Action::make('abrir'),
      ])
      ->paginated(false);
  }
}
