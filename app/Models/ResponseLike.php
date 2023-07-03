<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseLike extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','comment_response_id'];
}