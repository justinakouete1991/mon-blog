<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'photo',
        'published_at',
        'likes',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    // Un post appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un post peut avoir plusieurs commentaires
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }
}