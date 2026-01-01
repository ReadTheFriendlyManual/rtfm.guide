<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ShareLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_id',
        'token',
        'mode',
        'visit_count',
    ];

    protected function casts(): array
    {
        return [
            'visit_count' => 'integer',
        ];
    }

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    public static function generateToken(): string
    {
        do {
            $token = Str::random(16);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    public function incrementVisitCount(): void
    {
        $this->increment('visit_count');
    }
}
