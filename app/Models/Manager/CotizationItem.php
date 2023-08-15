<?php

namespace App\Models\Manager;

use App\Models\Manager\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CotizationItem extends Model
{
  use HasFactory;

  /**
   * @var string
   */
  protected $table = 'manager_cotization_items';

  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class, 'manager_product_id');
  }
}
