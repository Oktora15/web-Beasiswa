<?php

namespace App;

enum ApplicationStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Review = 'review';
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf',
            self::Submitted => 'Terkirim',
            self::Review => 'Sedang ditinjau',
            self::Accepted => 'Diterima',
            self::Rejected => 'Ditolak',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Draft => 'bg-slate-100 text-slate-700',
            self::Submitted => 'bg-blue-100 text-blue-800',
            self::Review => 'bg-amber-100 text-amber-800',
            self::Accepted => 'bg-emerald-100 text-emerald-800',
            self::Rejected => 'bg-rose-100 text-rose-800',
        };
    }

    /**
     * @return array<self>
     */
    public function transitions(): array
    {
        return match ($this) {
            self::Draft => [self::Draft, self::Submitted],
            self::Submitted => [self::Review],
            self::Review => [self::Accepted, self::Rejected],
            default => [],
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->transitions(), true);
    }

    /**
     * @return array<self>
     */
    public function adminTransitions(): array
    {
        return array_values(array_filter(
            $this->transitions(),
            fn (self $status): bool => in_array($status, [self::Review, self::Accepted, self::Rejected], true),
        ));
    }
}
