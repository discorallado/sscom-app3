<?php

namespace App\Filament\Resources\Manager\PaymentResource\Pages;

use App\Filament\Resources\Manager\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
