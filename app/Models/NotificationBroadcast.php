<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationBroadcast extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notifications_broadcast';

    protected $fillable = [
        'judul',
        'pesan',
        'target_role',
        'status',
        'created_by',
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}