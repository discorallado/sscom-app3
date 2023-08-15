<?php

namespace App\Filament\Resources\Manager\BillResource\Pages;

use App\Filament\Resources\Manager\BillResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBills extends ListRecords
{
  protected static string $resource = BillResource::class;

  protected function getHeaderActions(): array
  {
    return [
      Actions\CreateAction::make(),
    ];
  }

  protected function getHeaderWidgets(): array
  {
    return BillResource::getWidgets();
  }
}
