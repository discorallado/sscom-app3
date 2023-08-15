<?php

namespace App\Filament\Resources\Manager\WorkResource\Pages;

use App\Filament\Resources\Manager\WorkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorks extends ListRecords
{
    protected static string $resource = WorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
