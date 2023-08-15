<?php

namespace App\Filament\Resources\Manager\CategoryResource\RelationManagers;

use App\Filament\Resources\Manager\ProductResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
  protected static string $relationship = 'products';

  protected static ?string $recordTitleAttribute = 'name';

  public function form(Form $form): Form
  {
    return ProductResource::form($form);
  }

  public function table(Table $table): Table
  {
    return ProductResource::table($table)
      ->emptyStateActions([
        Tables\Actions\CreateAction::make(),
      ])
      ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
        SoftDeletingScope::class,
      ]));
  }
}
