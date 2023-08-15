<?php

namespace App\Filament\Resources\Manager\PaymentResource\Pages;

use App\Filament\Resources\Manager\PaymentResource;
use Closure;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManagePayments extends ManageRecords
{
  protected static string $resource = PaymentResource::class;

  protected function getActions(): array
  {
    return [
      \Filament\Actions\CreateAction::make()
        ->slideOver()
        ->mutateFormDataUsing(function (array $data): array {
          $data['user_id'] = auth()->id();

          return $data;
        }),
    ];
  }
  //   protected function getTableRecordClassesUsing(): ?Closure
  //   {
  //     return fn (Model $record) => match ($record->Bill->tipo) {
  //       'VENTA' => 'bg-success-400',
  //       'COSTO' => [
  //         'text-danger-600',
  //         // 'dark:border-orange-300' => config('tables.dark_mode'),
  //       ],
  //     };
  //   }
}
