<?php

namespace App\Models\Manager;

use App\Models\User as ModelsUser;
use App\Models\Manager\Cotization as ModelsCotization;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Customer extends Model implements HasMedia
{
  use HasFactory;
  use InteractsWithMedia;
  use SoftDeletes;


  /**
   * @var string
   */
  protected $table = 'manager_customers';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'rut',
    'name',
    'user_id',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(ModelsUser::class, 'user_id');
  }

  public function work(): HasMany
  {
    return $this->hasMany(Work::class, 'manager_customer_id');
  }
}
