<?php

namespace App\Filament\Resources\Manager\ProductResource\Pages;

use App\Filament\Resources\Manager\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProducts extends ManageRecords
{
  protected static string $resource = ProductResource::class;

  protected function getActions(): array
  {
    return [
      \Filament\Actions\CreateAction::make()
        ->mutateFormDataUsing(function (array $data): array {
          $data['user_id'] = auth()->id();

          return $data;
        }),
    ];
  }

  protected function getHeaderWidgets(): array
  {
    return ProductResource::getWidgets();
  }
}
