<?php

namespace App\Support;

use App\Models\User;

class LoanReportAccess
{
    /** Role names must match Spatie records (case-sensitive). */
    public const ROLES = ['Petugas', 'admin', 'super_admin'];

    public static function canAccess(?User $user = null): bool
    {
        $user ??= auth()->user();

        return $user?->hasAnyRole(self::ROLES) ?? false;
    }
}
