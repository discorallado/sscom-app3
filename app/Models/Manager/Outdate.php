<?php

namespace App\Models\Manager;

use App\Models\User as ModelsUser;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Outdate extends Model implements HasMedia
{
  use HasFactory;
  use SoftDeletes;
  use InteractsWithMedia;

  /**
   * @var string
   */
  protected $table = 'manager_outdates';

  protected $casts = [
    'file' => 'array',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'id',
    'tipo',
    'tipo_doc',
    'file',
    'num_doc',
    'date',
    'excento',
    'neto',
    'observaciones',
    'user_id',
    'manager_customer_id',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(ModelsUser::class, 'user_id');
  }

  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class, 'customer_id');
  }
}
