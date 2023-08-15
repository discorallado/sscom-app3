<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
  use HasFactory;

  use SoftDeletes;
  use InteractsWithMedia;

  /**
   * @var string
   */
  protected $table = 'manager_categories';

  /**
   * @var array<string, string>
   */
  protected $casts = [
    'is_visible' => 'boolean',
  ];

  public function children(): HasMany
  {
    return $this->hasMany(Category::class, 'parent_id');
  }

  public function parent(): BelongsTo
  {
    return $this->belongsTo(Category::class, 'parent_id');
  }

  public function products(): BelongsToMany
  {
    return $this->belongsToMany(Product::class, 'manager_category_product', 'manager_category_id', 'manager_product_id');
  }
}
