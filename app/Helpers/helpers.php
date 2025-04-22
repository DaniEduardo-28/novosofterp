<?php

if (!function_exists('get_business_design')) {
    function get_business_design()
    {
        $business = 'tecnovo';
        switch (app(Hyn\Tenancy\Contracts\CurrentHostname::class)->fqdn) {
            case 'statine.pseperu.com':
            case 'biotest.pseperu.com':
            case 'oxerva.pseperu.com':
        	case 'onext.pseperu.com':
            case 'carranzarebaza.pseperu.com':
        	case 'munozlab.pseperu.com':
        	case 'minimarketcriss.pseperu.com':
        	case 'ntapyp.pseperu.com':
        	case 'rois.pseperu.com':
        	case 'happylu.pseperu.com':
        	case 'quicklab.pseperu.com':
        	case 'ludavid.pseperu.com':
                $business = 'oxerva';
                break;
            default:
                $business = 'tecnovo';
                break;
        }
        return $business;
    }
}

if (!function_exists('get_version')) {
    function get_version()
    {
        return "1.0.0.0.3";
    }
}
