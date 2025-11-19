<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    protected $guarded = ['id'];
    public function children()
    {
        return $this->hasMany(NewsCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(NewsCategory::class, 'parent_id');
    }
}
