<?php

namespace App\Models\Manager;

use App\Models\User as ModelsUser;
use App\Models\Manager\Work;
use App\Models\Manager\Customer;
use App\Models\Manager\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Illuminate\Database\Eloquent\SoftDeletes;

class Cotization extends Model implements HasMedia
{
  use HasFactory;
  use SoftDeletes;
  use InteractsWithMedia;

  /**
   * @var string
   */
  protected $table = 'manager_cotizations';

  protected $casts = [
    'file' => 'array',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'codigo',
    'fecha',
    'validez',
    'descripcion',
    'file',
    'user_id',
    'manager_work',
    'total_price',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(ModelsUser::class, 'user_id');
  }

  public function work(): BelongsTo
  {
    return $this->belongsTo(Work::class, 'manager_work_id',);
  }

  public function bill(): HasOne
  {
    return $this->hasOne(Bill::class, 'manager_cotization_id');
  }

  public function payments(): HasMany
  {
    return $this->hasMany(Payment::class, 'manager_cotization_id');
  }

  public function items(): HasMany
  {
    return $this->hasMany(CotizationItem::class, 'manager_cotization_id');
  }
}
