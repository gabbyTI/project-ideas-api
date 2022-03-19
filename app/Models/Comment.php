<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "body"
    ];

    /**
     * Polymorphic Relationship
     * Means u can create one relation in this class and u can reuse this relationship accross all model claasses
     * 
     * Allows us to reuse the same comment model class in different models
     * 
     */
    public function commentable(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
