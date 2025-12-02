<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Services\AddonService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class AddonActivationController extends Controller
{
    public function __construct(
        private readonly AddonService $addonService,
    )
    {
    }

    public function index()
    {
        return view('admin-views.addon-activation.index');
    }

    public function activation(Request $request): Redirector|RedirectResponse|Application
    {
        $data = $this->addonService->addonActivationProcess(request: $request);
        if ($data['status']) {
            Helpers::businessUpdateOrInsert(['key' => $request['key']], [
                'value' => json_encode([
                    'activation_status' => $request['status'] ?? 0,
                    'username' => $request['username'],
                    'purchase_key' => $request['purchase_key'],
                ])
            ]);
            Toastr::success(translate('activated_successfully'));
        } else {
            Toastr::error($data['message']);
        }
        return back();
    }

    // CUSTOM EDIT
    public function toggle($addon_name, $status): Redirector|RedirectResponse|Application
    {
        // Get current addon configuration
        $config = $this->addonService->getAddonsConfig();
        
        if (!isset($config[$addon_name])) {
            Toastr::error(translate('addon_not_found'));
            return back();
        }
        
        // Update the active status directly in system-addons.php
        $config[$addon_name]['active'] = $status;
        $this->addonService->updateActivationConfig(app: $addon_name, response: $config[$addon_name]);
        
        // Also update the business settings for the UI
        $business_key = $this->getBusinessKeyFromAddonName($addon_name);
        if ($business_key) {
            $current_setting = Helpers::getBusinessSettingsValue($business_key);
            if ($current_setting) {
                $setting_data = json_decode($current_setting, true);
                $setting_data['activation_status'] = $status;
                Helpers::businessUpdateOrInsert(['key' => $business_key], [
                    'value' => json_encode($setting_data)
                ]);
            }
        }
        
        $message = $status == 1 ? translate('addon_activated_successfully') : translate('addon_deactivated_successfully');
        Toastr::success($message);
        
        return back();
    }
    
    private function getBusinessKeyFromAddonName($addon_name): ?string
    {
        $mapping = [
            'vendor_app' => 'addon_activation_vendor_app',
            'deliveryman_app' => 'addon_activation_delivery_man_app',
            'react_web' => 'addon_activation_react',
            'user_app' => 'addon_activation_user_app', // if exists
        ];
        
        return $mapping[$addon_name] ?? null;
    }
}
