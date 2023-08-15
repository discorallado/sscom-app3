<?php

namespace App\Filament\Resources\Manager\CotizationResource\Pages;

use App\Filament\Resources\Manager\CotizationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCotization extends EditRecord
{
    protected static string $resource = CotizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
