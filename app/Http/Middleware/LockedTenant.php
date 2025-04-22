<?php

namespace App\Http\Middleware;

use App\Models\Tenant\AccountPayment;
use Closure;
use App\Models\Tenant\Configuration;
use DateInterval;
use DateTime;

class LockedTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $configuration = Configuration::first();
        if (null === $configuration) {
            $configuration = new Configuration();
        }

        if ($configuration->isLockedTenant()) {
            abort(403);
        }

        $fechaActual = new DateTime();
        $fechaActual2 = new DateTime();
        $fechaActual->add(new DateInterval('P6D'));
        $pagosDB = AccountPayment::where("state", "0")
            ->where("date_of_payment", "<=", $fechaActual)
            ->orderBy("date_of_payment", "asc")->first();
        if ($pagosDB) {
            $fActual = new DateTime();
            if ($pagosDB->date_of_payment < $fechaActual2) {
                $diasVencidos = $fActual->diff($pagosDB->date_of_payment)->days;
                $diasVencer = 4 - $diasVencidos;
                if ($diasVencer < 0) {
                    abort(403);
                }
            }
        }
        return $next($request);
    }
}
