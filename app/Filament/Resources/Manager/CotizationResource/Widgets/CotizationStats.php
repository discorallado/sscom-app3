<?php

namespace App\Filament\Resources\Manager\CotizationResource\Widgets;

use App\Models\Manager\Cotization;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CotizationStats extends BaseWidget
{
  protected function getStats(): array
  {
    $cotizationData = Trend::model(Cotization::class)
      ->between(
        start: now()->subMonth(),
        end: now(),
      )
      ->dateColumn('fecha')
      ->perDay()
      ->count();

    return [
      Stat::make('Cotizaciones el último mes', Cotization::where('fecha', '>=', now()->subMonth())->count())
        ->color('success')
        ->chart(
          $cotizationData
            ->map(fn (TrendValue $value) => $value->aggregate)
            ->toArray()
        ),

      Stat::make('Cotizaciones activas', Cotization::where('vencimiento', '>=', now())->count() . ' de ' . Cotization::all()->count()),

      Stat::make('Valor promedio', '$ ' . number_format(Cotization::avg('total_price'), 0, 0, '.')),
      Stat::make('Valor maximo', '$ ' . number_format(Cotization::max('total_price'), 0, 0, '.')),
    ];
  }
}
