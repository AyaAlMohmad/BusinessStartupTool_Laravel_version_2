<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaunchChecklist extends Model
{
    use Auditable;
    protected $fillable = [
        'launch_preparation_id',
        'category',
        'task',
        'due_date',
        'status',
        'assignee',
        'notes',
        'user_id',
        'business_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function launchPreparation()
    {
        return $this->belongsTo(LaunchPreparation::class);
    }
}
