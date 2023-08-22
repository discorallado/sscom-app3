<?php

namespace App\Filament\Resources\Manager\WorkResource\Pages;

use App\Filament\Resources\Manager\CotizationResource;
use App\Filament\Resources\Manager\WorkResource;
use App\Models\Manager\Cotization;
use App\Models\Manager\Work;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;


class ReportWork extends Page implements HasInfolists
{
  use InteractsWithInfolists;

  public Work $work;

  public $record;

  protected static string $resource = WorkResource::class;

  protected static string $view = 'filament.resources.manager.work-resource.pages.report-work';

  protected static ?string $navigationIcon = 'heroicon-o-home';

  protected static ?string $title = 'Informe de proyecto';

  protected static ?string $navigationLabel = 'Informe proyecto';


  public function mount($record)
  {
    $this->record = $record;
    $this->work = Work::findOrFail($record);
  }

  protected function getFooterWidgets(): array
  {
    return [
      //   PaymentsStats::class,
      WorkResource\Widgets\RelatedCotizations::make([
        'record' => $this->record,
      ]),
      //   ChartPayments::class,
      //   RelatedBill::class,
      //   RelatedPayments::class,
    ];
  }

  public function getFooterWidgetsColumns(): int
  {
    return 4;
  }

  public function productInfolist(Infolist $infolist): Infolist
  {
    return $infolist
      ->record($this->work)
      ->columns(4)
      ->schema([
        Section::make('Datos')
          ->description('Detalles del proyecto')
          ->icon('heroicon-m-shopping-bag')
          ->columnSpan(3)
          ->columns(2)
          ->compact()
          ->schema([
            TextEntry::make('title')
              ->label('Título:'),
            TextEntry::make('customer.name')
              ->label('Cliente:'),
            TextEntry::make('inicio')
              ->label('Fecha de inicio:'),
            TextEntry::make('termino')
              ->label('Fecha de término:')
              ->placeholder('Nunca.'),
            TextEntry::make('descripcion')
              ->html()
              ->columnSpan(2),
          ]),
        Section::make()
          ->columnSpan(1)
          ->columns(1)
          ->schema([
            TextEntry::make('created_at')
              ->label('Creado:'),
            TextEntry::make('updated_at')
              ->label('Modificado:')
              ->placeholder('nunca.'),
          ]),
      ]);
  }
}
