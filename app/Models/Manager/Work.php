<?php

namespace App\Models\Manager;

use App\Models\User as ModelsUser;
use App\Models\Manager\Customer;
use App\Models\Manager\Cotization;
use App\Models\Manager\Bill;
use App\Models\Manager\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Work extends Model implements HasMedia
{
  use HasFactory;
  use InteractsWithMedia;
  use SoftDeletes;

  /**
   * @var string
   */
  protected $table = 'manager_works';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'title',
    'descripcion',
    'inicio',
    'termino',
    'user_id',
    'manager_customer_id',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(ModelsUser::class, 'user_id');
  }

  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class, 'manager_customer_id');
  }

  public function payments(): HasMany
  {
    return $this->hasMany(Payment::class, 'manager_work_id');
  }

  public function bills(): HasMany
  {
    return $this->hasMany(Bill::class, 'manager_work_id');
  }

  public function cotization(): HasMany
  {
    return $this->hasMany(Cotization::class, 'manager_work_id');
  }
}
