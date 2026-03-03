<?php

namespace App\Data\Enums;

enum WorkspacePlanStatus: string
{
    case Trialing = 'trialing';
    case Active = 'active';
    case PastDue = 'past_due';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Trialing => 'Trialing',
            self::Active => 'Active',
            self::PastDue => 'Past Due',
            self::Cancelled => 'Cancelled',
            self::Expired => 'Expired',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Trialing => 'blue',
            self::Active => 'green',
            self::PastDue => 'orange',
            self::Cancelled => 'gray',
            self::Expired => 'red',
        };
    }
}
