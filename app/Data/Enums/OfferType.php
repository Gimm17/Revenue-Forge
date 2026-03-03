<?php

namespace App\Data\Enums;

enum OfferType: string
{
    case OneTime = 'one_time';
    case Subscription = 'subscription';
    case CreditPack = 'credit_pack';
    case SaasPlan = 'saas_plan';

    public function label(): string
    {
        return match ($this) {
            self::OneTime => 'One-Time Purchase',
            self::Subscription => 'Subscription',
            self::CreditPack => 'Credit Pack',
            self::SaasPlan => 'SaaS Plan',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::OneTime => 'shopping-bag',
            self::Subscription => 'refresh-cw',
            self::CreditPack => 'coins',
            self::SaasPlan => 'layout',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OneTime => 'blue',
            self::Subscription => 'violet',
            self::CreditPack => 'amber',
            self::SaasPlan => 'cyan',
        };
    }
}
