<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'title', 'content'])]
class Post extends Model
{
    use SoftDeletes, HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
