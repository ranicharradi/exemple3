<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'video_url',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('access_granted')
            ->withTimestamps();
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function hasAccessFor(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->enrollments()
            ->where('user_id', $user->id)
            ->where('access_granted', true)
            ->exists();
    }

    public function hasOpenPaymentFor(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->payments()
            ->where('user_id', $user->id)
            ->whereIn('status', [Payment::STATUS_PENDING, Payment::STATUS_APPROVED])
            ->exists();
    }

    public function isInCartFor(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->cartItems()
            ->where('user_id', $user->id)
            ->exists();
    }
}
