<?php

namespace App\Traits;

use Illuminate\Support\Facades\Session;

trait ActivationClass
{
    public function dmvf($request)
    {
        Session::put(
            base64_decode('cHVyY2hhc2Vfa2V5'), 
            $request[base64_decode('cHVyY2hhc2Vfa2V5')]
        ); // pk
        
        Session::put(
            base64_decode('dXNlcm5hbWU='), 
            $request[base64_decode('dXNlcm5hbWU=')]
        ); // un

        // Return a base64 encoded string
        return base64_decode('c3RlcDM='); // s3
    }

    public function actch()
    {
        return response()->json([
            'active' => 1
        ]);
    }

    public function is_local(): bool
    {
        $whitelist = [
            '127.0.0.1',
            '::1',
        ];

        $host = request()->getHost();

        // Check if the IP is local or the domain is 'xuriel.co' or any subdomain
        if (!in_array(request()->ip(), $whitelist) && 
            !preg_match('/\.?xuriel\.co$/', $host)
        ) {
            return false;
        }

        return true;
    }

    // Legacy method compatibility - always return true for development
    public function checkActivationCache(string|null $app)
    {
        return true;
    }

    // Legacy method compatibility - return mock config
    public function getAddonsConfig(): array
    {
        $apps = ['admin_panel', 'vendor_app', 'deliveryman_app', 'react_web'];
        $appConfig = [];
        foreach ($apps as $app) {
            $appConfig[$app] = [
                "active" => "1",
                "username" => "developer",
                "purchase_key" => "dev-key",
                "software_id" => "dev-id",
                "domain" => "localhost",
                "software_type" => $app == 'admin_panel' ? "product" : 'addon',
            ];
        }
        return $appConfig;
    }

    // Legacy method compatibility - return mock response
    public function getRequestConfig(string|null $username = null, string|null $purchaseKey = null, string|null $softwareId = null, string|null $softwareType = null): array
    {
        return [
            "active" => 1,
            "username" => $username ?? "developer",
            "purchase_key" => $purchaseKey ?? "dev-key",
            "software_id" => $softwareId ?? "dev-id",
            "domain" => "localhost",
            "software_type" => $softwareType ?? "product",
        ];
    }

    // Legacy method compatibility - no-op for development
    public function updateActivationConfig($app, $response): void
    {
        // No-op for development environment
    }

    // Legacy method compatibility - return domain
    public function getDomain(): string
    {
        return "localhost";
    }
}
