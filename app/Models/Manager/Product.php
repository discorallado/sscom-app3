<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Product extends Model implements HasMedia
{
  use HasFactory;
  use SoftDeletes;
  use InteractsWithMedia;
  use HasTags;

  /**
   * @var string
   */
  protected $table = 'manager_products';

  protected $casts = [
    'categoria' => 'array',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'nombre',
    'precio_stock',
    'unidad',
    'categoria',
  ];

  public function categories(): BelongsToMany
  {
    return $this->belongsToMany(Category::class, 'manager_category_product', 'manager_product_id', 'manager_category_id')->withTimestamps();
  }


  public function cotizations(): BelongsToMany
  {
    return $this->belongsToMany(Cotizations::class);
  }
}
