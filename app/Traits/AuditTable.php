<?php

namespace App\Traits;

use App\Observers\AuditObserver;

trait AuditTable
{
    public static function bootAuditTable()
    {
        static::observe(AuditObserver::class);
    }
}
