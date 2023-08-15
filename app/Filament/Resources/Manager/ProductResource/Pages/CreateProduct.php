<?php

namespace App\Filament\Resources\Manager\ProductResource\Pages;

use App\Filament\Resources\Manager\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
