<?php

namespace App\Http\ViewComposers\Tenant;

use App\Models\Tenant\AccountPayment;
use App\Models\Tenant\Configuration;
use DateInterval;
use DateTime;

class CompactSidebarViewComposer
{
    public function compose($view)
    {
        $configuration = Configuration::first();

        $fechaActual = new DateTime();
        $fechaActual->add(new DateInterval('P6D'));
        $pagosDB = AccountPayment::where("state", "0")
                                   ->where("date_of_payment", "<=", $fechaActual)
                                   ->orderBy("date_of_payment", "desc")->first();
        // $set = (new \App\Http\Controllers\Tenant\ConfigurationController)->getSystemPhone();

        $view->show_ws = $configuration->enable_whatsapp;
        $view->phone_whatsapp = $configuration->phone_whatsapp;
        $view->vc_compact_sidebar = $configuration;
        $view->pagosDB = $pagosDB;
        $view->fechaActual = $fechaActual;
        //variables para validar si se debe mostrar notificacion del cambio de contraseña
        $view->vc_check_last_password_update = (object)[
            'enabled_remember_change_password' => $configuration->enabled_remember_change_password,
            'quantity_month_remember_change_password' => $configuration->quantity_month_remember_change_password,
        ];
    }
}
