<?php

 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
     protected $connection = 'mysql_hdesk';
    protected $table = 'hesk_tickets';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['trackid', 'name','email', 'category', 'priority', // `subject`, `message`, `message_html`, `dt`, `lastchange`, `firstreply`, `closedat`, `articles`, `ip`, `language`, `status`, `openedby`, `firstreplyby`, `closedby`, `replies`, `staffreplies`, `owner`, `assignedby`, `time_worked`, `lastreplier`, `replierid`, `archive`, `locked`, `attachments`, `merged`, `history`, `custom1`,,
    ];

    public function student() {
        return $this->belongsTo(Student::class, 'custom1', 'stud_id');
    }

     
}
