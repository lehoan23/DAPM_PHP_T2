<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects'; // Bảng mà model này liên kết
    use SoftDeletes;

    // protected $fillable = [
    //     'name',
    //     'description',
    //     'goal_amount',
    //     'collected_amount',
    //     'status',
    //     'start_day',
    //     'end_day',
    //     'create_by',
    //     'cate_id',
    // ];

    protected $guarded = [];
    public function creator()
    {
        return $this->belongsTo(User::class, 'create_by');
    }
    public function images()
    {
        return $this->hasMany(Image::class, 'id_project');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'project_id', 'id');
    }
}
