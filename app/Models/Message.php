<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'user_id', 'body', 'attachment_url', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
