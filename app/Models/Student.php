<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

 
class Student extends Model
{
    protected $table = 'students';
    protected $connection = 'mysql_sis2';
    protected $primaryKey = 'stud_id';
  public $timestamps = false;
protected $fillable = [
    'stud_id',
    'stud_name',
    'stud_surname',
    'familyname',
    'curr_sem',
    'faculty_code',
    'major_code'
];



    // dump has no created_at/updated_at on students

    // Example relationship: one student can have many parents
    public function parents()
    {
        return $this->hasMany(ParentModel::class, 'stud_id', 'stud_id');
    }



////

    public function tickets() {
        return $this->hasMany(Ticket::class, 'opened_by_whois', 'stud_id');
    }

/*
        public function tickets()
    {
        return $this->hasMany(Ticket::class, 'stud_id', 'stud_id');
    }
        */
}
