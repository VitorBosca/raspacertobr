<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        'slotegrator/*',
        'digitopay/*',
        'digitopay/callback',
        'webhooks/*',
        'salsa/*',
        'fivers/*',
        'gold_api',
        'gold_api/*',
        'kagaming/*',
        'vibra/*',
        'cron/*',
        'venixcg',
        'venixcg/*',
        'ever/*',
        'ever',
        'playgaming',
        'playgaming/*',
        'playigaming_api',
        'playigaming_api/*',
        'bspay/*',
        'bspay',
        'nomad/*',
        'nomad',
        'pradapay/*',
        'pradapay',
    ];
}
