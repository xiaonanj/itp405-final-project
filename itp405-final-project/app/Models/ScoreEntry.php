<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScoreEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'end_number',
        'arrow1_score',
        'arrow2_score',
        'arrow3_score',
        'arrow4_score',
        'arrow5_score',
        'arrow6_score',
    ];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    
}
