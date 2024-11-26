<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
class Image extends Model
{
    protected $table = 'images'; // Bảng mà model này liên kết

    protected $guarded = [];
    public function project()
{
    return $this->belongsTo(Project::class, 'id_project');
}
}