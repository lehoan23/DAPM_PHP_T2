<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendingProject extends Model
{
    use HasFactory;
    protected $table = 'pending_projects'; // Bảng mà model này liên kết
    protected $guarded = [];
    use SoftDeletes;
    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by');
    }
}