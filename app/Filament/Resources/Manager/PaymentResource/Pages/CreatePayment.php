<?php

namespace App\Filament\Resources\Manager\PaymentResource\Pages;

use App\Filament\Resources\Manager\PaymentResource;
use App\Models\Manager\Bill;
use App\Models\Manager\Payment;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePayment extends CreateRecord
{
  protected static string $resource = PaymentResource::class;

  protected function mutateFormDataBeforeCreate(array $data): array
  {
    $data['user_id'] = auth()->id();

    return $data;
  }
  protected function handleRecordCreation(array $data): Model
  {
    $data['user_id'] = auth()->id();
    $create_bill = $data['create_bill'];
    unset($data['create_bill']);
    if ($create_bill) {
      $data['tipo'] = 'VENTA';
      if (Bill::create($data)) {
        unset($data['tipo']);
        Notification::make()
          ->title('Factura creada')
          ->success()
          ->send();
        return Payment::create($data);
      }
    }
    return Payment::create($data);
  }
}
