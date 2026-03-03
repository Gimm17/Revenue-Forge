<?php

namespace App\Data\Enums;

enum OrderStatus: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Pending => 'Pending',
            self::Paid => 'Paid',
            self::Failed => 'Failed',
            self::Cancelled => 'Cancelled',
            self::Expired => 'Expired',
            self::Refunded => 'Refunded',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Pending => 'yellow',
            self::Paid => 'green',
            self::Failed => 'red',
            self::Cancelled => 'gray',
            self::Expired => 'orange',
            self::Refunded => 'purple',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Paid, self::Failed, self::Cancelled, self::Expired, self::Refunded]);
    }
}
