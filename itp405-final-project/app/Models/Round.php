<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_outdoor',
        'target_distance',
        'bow_type',
        'session_type',
        'location',
        'weather_json',
        'arrows_per_end',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scoreEntries()
    {
        return $this->hasMany(ScoreEntry::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favoritedByUsers()
{
    return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
}

}
