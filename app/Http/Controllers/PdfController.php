<?php

namespace App\Http\Controllers;

use App\Models\Manager\Cotization;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
  //
  public function __invoke($model, Cotization $cotization)
  {
    switch ($model) {
      case 'cotization':
        # code...
        //   dd($model);
        return Pdf::loadView('pdf', ['record' => $cotization])
          ->download($cotization->codigo . '.pdf');
        break;

      default:
        # code...
        break;
    }
    //

  }
}
