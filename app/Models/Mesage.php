<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesage extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'receiver_id',
        'seen',
        'message',
        'team_id'
        
    ];
}
