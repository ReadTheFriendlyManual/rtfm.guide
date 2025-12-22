<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedGuide extends Model
{
    /** @use HasFactory<\Database\Factories\SavedGuideFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'guide_id',
        'user_id',
    ];

    /**
     * @return BelongsTo<Guide, SavedGuide>
     */
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    /**
     * @return BelongsTo<User, SavedGuide>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
