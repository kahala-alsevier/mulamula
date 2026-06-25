<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $guarded = [];

    protected static function boot() {
        parent::boot();
        static::creating(fn ($model) => $model->slug = Str::slug($model->name));
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
