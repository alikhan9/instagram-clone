<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComments extends Model
{
    use HasFactory;

    protected $fillable = ['post_id','user_id','content'];

    protected $with = ['user'];

    public function user() {
        return $this->belongsTo(User::class)->select('name');
    } 

}