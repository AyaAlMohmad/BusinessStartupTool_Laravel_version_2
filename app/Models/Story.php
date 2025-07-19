<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'educational',
        'content',
        'image',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'educational'=>'json',
        'title' => 'json',
        'content' => 'json',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
