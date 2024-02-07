<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Validation\Rule;

class Question extends Model
{
    use HasFactory;

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($question) {
            $options = [
                $question->option_1,
                $question->option_2,
                $question->option_3,
                $question->option_4,
            ];

            if (!in_array($question->correct_option, $options)) {
                return false;
            }
        });
    }

    public static function validateOptions($request)
    {
        $options = [
            $request->option_1,
            $request->option_2,
            $request->option_3,
            $request->option_4,
        ];

        $validatedData = $request->validate([
            'correct_option' => ['required', Rule::in($options)],
        ]);
    }

    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class);
    }
}
