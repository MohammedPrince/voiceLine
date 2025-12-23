<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceCall extends Model
{
    protected $table = 'voice_calls';
    protected $primaryKey = 'call_id';
    public $timestamps = true; // table has created_at & updated_at

    protected $fillable = [
   
'ticket_number',
'customer_type',
'stud_id',
'staff_ID',
'category',
'issue',
'Solution_Note',
'Found_Status',
'Final_Status',
'priority',
'parent_id',
'parent_name',
'parent_phone',
'handled_by_user_id',
'created_at',
'updated_at',
           // <-- add here
    ];
 public function user()
    {
        return $this->belongsTo(User::class, 'handled_by_user_id');
    }
    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'voice_call_id', 'call_id');
    }
     public function category()
    {
        return $this->belongsTo(Category::class, 'category');
    }
}
 
