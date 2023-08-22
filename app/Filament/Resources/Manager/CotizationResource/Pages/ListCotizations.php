<?php

namespace App\Filament\Resources\Manager\CotizationResource\Pages;

use App\Filament\Resources\Manager\CotizationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCotizations extends ListRecords
{
  protected static string $resource = CotizationResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }

  protected function getHeaderWidgets(): array
  {
    return CotizationResource::getWidgets();
  }
}
