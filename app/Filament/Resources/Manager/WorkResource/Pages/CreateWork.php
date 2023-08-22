<?php

namespace App\Filament\Resources\Manager\WorkResource\Pages;

use App\Filament\Resources\Manager\WorkResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWork extends CreateRecord
{
  protected static string $resource = WorkResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $data['user_id'] = auth()->id();

    return $data;
  }
}
