<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


use App\Models\User as ModelsUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Payment extends Model implements HasMedia
{
  use HasFactory;
  use SoftDeletes;
  use InteractsWithMedia;

  /**
   * @var string
   */
  protected $table = 'manager_payments';

  protected $casts = [
    'file' => 'array',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'fecha',
    'tipo',
    'manager_cotization_id',
    'doc',
    'file',
    'manager_work_id',
    'manager_bill_id',
    'descripcion',
    'total_price',
    'abono',
    'saldo',
    'user_id',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(ModelsUser::class, 'user_id');
  }

  public function cotization(): BelongsTo
  {
    return $this->belongsTo(Cotization::class, 'manager_cotization_id');
  }

  public function work(): BelongsTo
  {
    return $this->belongsTo(Work::class, 'manager_work_id');
  }

  public function bill(): BelongsTo
  {
    return $this->belongsTo(Bill::class, 'manager_bill_id');
  }
}
