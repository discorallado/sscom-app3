<?php

namespace App\Filament\Resources\Manager\BillResource\Widgets;

use App\Models\Manager\Bill;
use App\Models\Manager\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BillStats extends BaseWidget
{
  protected function getStats(): array
  {
    $count_venta_mes = (int)Bill::where('tipo', '=', 'VENTA')->where('fecha', '>=', now()->subMonth())->count();
    $count_compra_mes = (int)Bill::where('tipo', '=', 'COSTO')->where('fecha', '>=', now()->subMonth())->count();

    $venta_mes = (int)Bill::where('tipo', '=', 'VENTA')->where('fecha', '>=', \now()->subMonth())->sum('total_price');
    $compra_mes = (int)Bill::where('tipo', '=', 'COSTO')->where('fecha', '>=', \now()->subMonth())->sum('total_price');

    $pagos_mes = (int)Payment::where('manager_bill_id', '!=', null)->sum('abono');
    $deuda_mes = (int)Bill::where('tipo', '=', 'VENTA')->sum('total_price') - (int)Payment::where('manager_bill_id', '!=', null)->sum('abono');
    return [
      //
      Stat::make(
        'Ventas mes' . ' (' . $count_venta_mes . ' facturas)',
        '$' . number_format($venta_mes, 0, 0, '.')
      ),

      Stat::make(
        'Compras mes' . ' (' . $count_compra_mes . ' facturas)',
        '$' . number_format($compra_mes, 0, 0, '.')
      ),

      Stat::make(
        'Total pagado mes',
        '$' . number_format($pagos_mes, 0, 0, '.')
      ),
      Stat::make(
        'Total adeudado mes',
        '$' . number_format($deuda_mes, 0, 0, '.')
      ),
    ];
  }
}
