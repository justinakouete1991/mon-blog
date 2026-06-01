<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'body',
    ];

    // Un commentaire appartient à un post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Un commentaire appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un commentaire peut avoir des réponses
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    // Un commentaire peut appartenir à un commentaire parent
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}