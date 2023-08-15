<?php

namespace App\Filament\Resources\Manager\WorkResource\Pages;

use App\Filament\Resources\Manager\WorkResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWork extends ViewRecord
{
    protected static string $resource = WorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
