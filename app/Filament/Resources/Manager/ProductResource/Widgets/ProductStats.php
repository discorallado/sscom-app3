<?php

namespace App\Filament\Resources\Manager\ProductResource\Widgets;

use App\Models\Manager\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStats extends BaseWidget
{
  protected function getStats(): array
  {
    return [
      Stat::make('Productos sin precio', Product::where('precio_stock', '<', 1)->count())
        ->color('danger'),
      Stat::make('Product Inventory', 123),
      Stat::make('Average price', number_format(Product::avg('precio_stock'), 0, '', '.')),
    ];
  }
}
