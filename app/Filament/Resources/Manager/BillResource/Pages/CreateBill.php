<?php

namespace App\Filament\Resources\Manager\BillResource\Pages;

use App\Filament\Resources\Manager\BillResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBill extends CreateRecord
{
  protected static string $resource = BillResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $data['user_id'] = auth()->id();

    return $data;
  }
}
