<?php

namespace App\Filament\Resources\Manager\BillResource\Pages;

use App\Filament\Resources\Manager\BillResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBill extends ViewRecord
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
