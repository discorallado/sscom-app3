<?php

namespace App\Filament\Resources\Manager\CategoryResource\Pages;

use App\Filament\Resources\Manager\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
