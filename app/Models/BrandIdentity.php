<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandIdentity extends Model
{
    use HasFactory;
    use Auditable;
    protected $fillable = [
        'marketing_id',
        'values',
        'mission',
        'vision',
        'tone',
        'visual_style',
        'user_id',
        'business_id'
    ];

    protected $casts = [
        'values' => 'array',
    ];

    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
