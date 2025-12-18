<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reaction extends Model
{
    /** @use HasFactory<\Database\Factories\ReactionFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'guide_id',
        'user_id',
        'type',
    ];

    /**
     * @return list<string>
     */
    public static function allowedTypes(): array
    {
        return ['helpful', 'saved_me', 'outdated', 'needs_update'];
    }

    /**
     * @return BelongsTo<Guide, Reaction>
     */
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    /**
     * @return BelongsTo<User, Reaction>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
