<?php

namespace App\Filament\Resources\Manager\CustomerResource\Pages;

use App\Filament\Resources\Manager\CustomerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCustomers extends ManageRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->mutateFormDataUsing(function (array $data): array {
                $data['user_id'] = auth()->id();

                return $data;
            }),
        ];
    }
}
