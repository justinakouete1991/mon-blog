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

    // Les réponses directes à ce commentaire
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with(['user', 'replies'])->latest();
    }

    // Le commentaire parent
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}