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

  /*
  *  Verifica que la cot tenga o no pagos antes de ser nidificada
  */
  //   protected function handleRecordUpdate(Model $record, array $data): Model
  //   {
  //     $facturas = Bill::where('manager_cotization_id', '=', $record->id)->count();
  //     $registrado = $record->total_price;
  //     $nuevo = $data['total_price'];
  //     // dd($facturas);
  //     dd(($facturas > 0) && ($registrado != $nuevo));
  //     if (($facturas > 0) && ($registrado != $nuevo)) {
  //       Notification::make()
  //         ->title('Factura asociada')
  //         ->warning()
  //         ->body('La cotizacion modificada tiene una o mas facturas asociadas, desea modificar los montos de éstas?')
  //         ->actions([
  //           Action::make('view')
  //             ->button(),
  //           Action::make('undo')
  //             ->color('secondary'),
  //         ])
  //         ->send();
  //       return $record;
  //     }
  //     // $record->update($data);

  //   }
}
