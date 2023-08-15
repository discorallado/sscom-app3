<?php

namespace App\Models\Manager;

use App\Models\User as ModelsUser;
use App\Models\Manager\Cotization as ModelsCotization;
use App\Models\Manager\Customer as ModelsCustomer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model implements HasMedia
{
  use HasFactory;
  use SoftDeletes;
  use InteractsWithMedia;

  /**
   * @var string
   */
  protected $table = 'manager_bills';

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
    'doc',
    'total_rpice',
    'file',
    'descripcion',
    'customer',
    'user_id',
    'manager_work_id',
    'manager_cotization_id',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(ModelsUser::class, 'user_id');
  }

  public function work(): BelongsTo
  {
    return $this->belongsTo(Work::class, 'manager_work_id');
  }

  public function cotization(): BelongsTo
  {
    return $this->belongsTo(ModelsCotization::class, 'manager_cotization_id');
  }

  public function payments(): HasOne
  {
    return $this->hasOne(Payment::class, 'manager_bill_id');
  }
}
