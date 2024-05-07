<?php

namespace App\Models;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [ 'conversation_id', 'user_id', 'body', 'type'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => __('User')
        ]);
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class , 'recipients')
            ->withPivot([
                'read_at', 'delete_at'
            ]);
    }
}
