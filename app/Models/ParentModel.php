<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parents';
    protected $primaryKey = 'parent_id';
    public $timestamps = false; // only created_at exists; set to false to avoid Laravel expecting updated_at

    protected $fillable = ['full_name','email','phone','relation_to_student','stud_id'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'stud_id', 'stud_id');
    }
}
