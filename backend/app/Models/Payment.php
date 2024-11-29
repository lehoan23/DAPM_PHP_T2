<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments'; // Bảng mà model này liên kết


    // Các cột có thể được gán giá trị (Mass Assignment)
    protected $fillable = [
        'amount',
        'payment_date',
        'payment_method',
        'status',
        'user_id',
        'project_id',
    ];

    // Các mối quan hệ (nếu có)
    
    // Mối quan hệ với bảng User (một Payment thuộc một User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mối quan hệ với bảng Project (một Payment thuộc một Project)
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // protected $guarded = [];
}