<?php

namespace App\Filament\Resources\Manager\CustomerResource\Pages;

use App\Filament\Resources\Manager\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
