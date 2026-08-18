
<?php
    use App\CentralLogics\Helpers;

    $req = request()->path();
    $is = function($pat) use ($req) { return \Illuminate\Support\Str::is($pat, $req); };
    $admin_user = auth('admin')->user();

    $can_settings = Helpers::module_permission_check('settings');
    $can_zone     = Helpers::module_permission_check('zone');
    $can_module   = Helpers::module_permission_check('module');
    $can_sub      = Helpers::module_permission_check('subscription');
    $can_pro      = Helpers::module_permission_check('pro_customer_subscription');
    $can_customer = Helpers::module_permission_check('customer_management');
    $rental_on    = addon_published_status('Rental');
    $ride_on      = addon_published_status('RideShare');
    $tax_on       = addon_published_status('TaxModule');

    $active_section = 'biz';
    if ($is('admin/business-settings/module*'))                   $active_section = 'mods';
    elseif ($is('admin/business-settings/subscription*') || $is('admin/pro-customer*'))         $active_section = 'subs';
    elseif ($is('taxvat/*'))                                      $active_section = 'fin';
    elseif ($is('admin/business-settings/pages/*') || $is('admin/business-settings/seo-settings*')) $active_section = 'pages';
    elseif ($is('admin/business-settings/file-manager*'))         $active_section = 'media';
    elseif ($is('admin/business-settings/login-settings*') || $is('admin/business-settings/login-url-setup*'))       $active_section = 'auth';
    elseif ($is('admin/business-settings/email-setup*') || $is('admin/business-settings/rental-email-setup*') || $is('admin/business-settings/notification-setup*') || $is('admin/business-settings/fcm*')) $active_section = 'comm';
    elseif ($is('admin/business-settings/third-party*') || $is('admin/business-settings/offline-payment*') || $is('admin/business-settings/marketing*') || $is('admin/business-settings/open-ai*') || $is('admin/payment/configuration*') || $is('admin/sms/configuration*')) $active_section = 'int';
    elseif ($is('admin/business-settings/safety-precaution*') || $is('admin/business-settings/ride-fare*') || $is('admin/business-settings/ride-share*')) $active_section = 'safety';
    elseif ($is('admin/business-settings/db-index*'))             $active_section = 'maint';
    elseif ($is('admin/business-settings/language*') || $is('admin/business-settings/app-settings*') || $is('admin/business-settings/websocket*') || $is('admin/business-settings/addon-activation*') || $is('admin/business-settings/system-addon*')) $active_section = 'sys';
?>

<aside id="v2-shell" class="v2-shell" data-workspace="settings" data-active-section="<?php echo e($active_section); ?>">
    <div id="v2-rail" class="v2-rail" role="navigation" aria-label="Sections">
        <div class="v2-rail-scope d-none">SETTINGS</div>
        <div class="v2-rail-btns">
            <?php if($can_settings || $can_zone): ?>
            <button class="v2-rail-btn <?php echo e($active_section==='biz' ? 'is-active' : ''); ?>" data-section="biz" data-label="<?php echo e(translate('Business Setup')); ?>" aria-label="<?php echo e(translate('Business Setup')); ?>">
                <i data-lucide="briefcase"></i><span class="v2-pin-dot"></span>
            </button>
            <?php endif; ?>
            <?php if($can_module): ?>
            <button class="v2-rail-btn <?php echo e($active_section==='mods' ? 'is-active' : ''); ?>" data-section="mods" data-label="<?php echo e(translate('Business Modules')); ?>" aria-label="<?php echo e(translate('Business Modules')); ?>">
                <i data-lucide="boxes"></i><span class="v2-pin-dot"></span>
            </button>
            <?php endif; ?>
            <?php if($can_sub || $can_pro || $can_customer): ?>
            <button class="v2-rail-btn <?php echo e($active_section==='subs' ? 'is-active' : ''); ?>" data-section="subs" data-label="<?php echo e(translate('Subscription Management')); ?>" aria-label="<?php echo e(translate('Subscription Management')); ?>">
                <i data-lucide="credit-card"></i><span class="v2-pin-dot"></span>
            </button>
            <?php endif; ?>
            <?php if($can_settings && $tax_on): ?>
            <button class="v2-rail-btn <?php echo e($active_section==='fin' ? 'is-active' : ''); ?>" data-section="fin" data-label="<?php echo e(translate('Finance & Tax')); ?>" aria-label="<?php echo e(translate('Finance & Tax')); ?>">
                <i data-lucide="receipt"></i><span class="v2-pin-dot"></span>
            </button>
            <?php endif; ?>
            <?php if($can_settings): ?>
            <button class="v2-rail-btn <?php echo e($active_section==='pages' ? 'is-active' : ''); ?>" data-section="pages" data-label="<?php echo e(translate('Website, Pages & Content')); ?>" aria-label="<?php echo e(translate('Website, Pages & Content')); ?>">
                <i data-lucide="file-text"></i><span class="v2-pin-dot"></span>
            </button>
            <button class="v2-rail-btn <?php echo e($active_section==='sys' ? 'is-active' : ''); ?>" data-section="sys" data-label="<?php echo e(translate('System Configuration')); ?>" aria-label="<?php echo e(translate('System Configuration')); ?>">
                <i data-lucide="cog"></i><span class="v2-pin-dot"></span>
            </button>
            <button class="v2-rail-btn <?php echo e($active_section==='auth' ? 'is-active' : ''); ?>" data-section="auth" data-label="<?php echo e(translate('Authentication & Access')); ?>" aria-label="<?php echo e(translate('Authentication & Access')); ?>">
                <i data-lucide="lock"></i><span class="v2-pin-dot"></span>
            </button>
            <button class="v2-rail-btn <?php echo e($active_section==='comm' ? 'is-active' : ''); ?>" data-section="comm" data-label="<?php echo e(translate('Communication Setup')); ?>" aria-label="<?php echo e(translate('Communication Setup')); ?>">
                <i data-lucide="mail"></i><span class="v2-pin-dot"></span>
            </button>
            <button class="v2-rail-btn <?php echo e($active_section==='int' ? 'is-active' : ''); ?>" data-section="int" data-label="<?php echo e(translate('Integrations & Third-Party')); ?>" aria-label="<?php echo e(translate('Integrations & Third-Party')); ?>">
                <i data-lucide="plug"></i><span class="v2-pin-dot"></span>
            </button>
            <?php if($ride_on): ?>
            <button class="v2-rail-btn <?php echo e($active_section==='safety' ? 'is-active' : ''); ?>" data-section="safety" data-label="<?php echo e(translate('Ride Share Settings')); ?>" aria-label="<?php echo e(translate('Ride Share Settings')); ?>">
                <i data-lucide="car-front"></i><span class="v2-pin-dot"></span>
            </button>
            <?php endif; ?>
            <button class="v2-rail-btn <?php echo e($active_section==='media' ? 'is-active' : ''); ?>" data-section="media" data-label="<?php echo e(translate('Media & File Management')); ?>" aria-label="<?php echo e(translate('Media & File Management')); ?>">
                <i data-lucide="image"></i><span class="v2-pin-dot"></span>
            </button>
            <button class="v2-rail-btn <?php echo e($active_section==='maint' ? 'is-active' : ''); ?>" data-section="maint" data-label="<?php echo e(translate('Maintenance & Database')); ?>" aria-label="<?php echo e(translate('Maintenance & Database')); ?>">
                <i data-lucide="database"></i><span class="v2-pin-dot"></span>
            </button>
            <?php endif; ?>
        </div>
        <div class="v2-rail-bottom">
            <button class="v2-rail-btn v2-rail-profile" id="v2-rail-profile" aria-haspopup="menu" aria-expanded="false" aria-label="<?php echo e($admin_user->f_name ?? 'Admin'); ?>">
                <span class="v2-avatar"><?php echo e(strtoupper(substr($admin_user->f_name ?? 'A', 0, 1) . substr($admin_user->l_name ?? '', 0, 1))); ?></span>
            </button>
        </div>
    </div>

    <aside id="v2-panel" class="v2-panel" aria-label="<?php echo e(translate('Section navigation')); ?>">
        <?php if($can_settings || $can_zone): ?>
        <div class="v2-panel-content" data-panel="biz" <?php if($active_section!=='biz'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Business Setup')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('Core business configuration and zones')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::biz'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="v2-group">
                    <div class="v2-group-items">
                        <?php if($can_settings): ?>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/business-setup*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.business-setup')); ?>" data-id="biz-info">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('Business Settings')); ?></span>
                            <button type="button" class="v2-pin" data-pin="biz-info" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php endif; ?>
                        <?php if($can_zone): ?>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/zone*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.zone.home')); ?>" data-id="biz-zone">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('Zone Setup')); ?></span>
                            <button type="button" class="v2-pin" data-pin="biz-zone" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($can_module): ?>
        <div class="v2-panel-content" data-panel="mods" <?php if($active_section!=='mods'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Business Modules')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('Module creation and management')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::mods'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="v2-group">
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/module/store*') || $is('admin/business-settings/module/create*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.module.create')); ?>" data-id="mod-add">
                            <span class="v2-dot v2-dot--green"></span><span class="v2-label"><?php echo e(translate('Add New Module')); ?></span>
                            <button type="button" class="v2-pin" data-pin="mod-add" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e(($is('admin/business-settings/module') || $is('admin/business-settings/module/edit/*')) ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.module.index')); ?>" data-id="mod-list">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('Manage Modules')); ?></span>
                            <button type="button" class="v2-pin" data-pin="mod-list" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($can_sub || $can_pro || $can_customer): ?>
        <div class="v2-panel-content" data-panel="subs" <?php if($active_section!=='subs'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Subscription Management')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('Subscription packages, subscribers, and settings')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::subs'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php if($can_sub): ?>
                <div class="v2-group">
                    <button type="button" class="v2-group-header" data-group-toggle="sub-vendor"><span><?php echo e(translate('Vendor Subscription')); ?></span><i data-lucide="chevron-down" class="v2-chev"></i></button>
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/subscription/subscriptionackage*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.subscriptionackage.index')); ?>" data-id="sub-pkg">
                            <span class="v2-dot v2-dot--green"></span><span class="v2-label"><?php echo e(translate('Subscription Packages')); ?></span>
                            <button type="button" class="v2-pin" data-pin="sub-pkg" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/subscription/subscriber-list*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.subscriptionackage.subscriberList')); ?>" data-id="sub-list">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('Subscribers')); ?></span>
                            <button type="button" class="v2-pin" data-pin="sub-list" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/subscription/settings*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.subscriptionackage.settings')); ?>" data-id="sub-set">
                            <span class="v2-dot v2-dot--gray"></span><span class="v2-label"><?php echo e(translate('Subscription Settings')); ?></span>
                            <button type="button" class="v2-pin" data-pin="sub-set" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(Helpers::get_business_settings('pro_member_status') == 1 && ($can_pro || $can_customer)): ?>
                <div class="v2-group">
                    <button type="button" class="v2-group-header" data-group-toggle="sub-pro"><span><?php echo e(translate('messages.Pro_Customer_Management')); ?></span><i data-lucide="chevron-down" class="v2-chev"></i></button>
                    <div class="v2-group-items">
                        <?php if($can_customer): ?>
                        <a class="v2-nav-item <?php echo e($is('admin/pro-customer/list*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.pro-customer.list')); ?>" data-id="pro-list">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('messages.Pro_Customer_List')); ?></span>
                            <button type="button" class="v2-pin" data-pin="pro-list" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php endif; ?>
                        <?php if($can_pro): ?>
                        <a class="v2-nav-item <?php echo e($is('admin/pro-customer/benefits-setup*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.pro-customer.benefits-setup')); ?>" data-id="pro-ben">
                            <span class="v2-dot v2-dot--green"></span><span class="v2-label"><?php echo e(translate('messages.Pro_Customer_Benefits_Setup')); ?></span>
                            <button type="button" class="v2-pin" data-pin="pro-ben" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/pro-customer/price-setup*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.pro-customer.price-setup')); ?>" data-id="pro-price">
                            <span class="v2-dot v2-dot--amber"></span><span class="v2-label"><?php echo e(translate('messages.Price_Setup')); ?></span>
                            <button type="button" class="v2-pin" data-pin="pro-price" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/pro-customer/additional-setup*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.pro-customer.additional-setup')); ?>" data-id="pro-add">
                            <span class="v2-dot v2-dot--violet"></span><span class="v2-label"><?php echo e(translate('messages.Additional_Setup')); ?></span>
                            <button type="button" class="v2-pin" data-pin="pro-add" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/pro-customer/transactions*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.pro-customer.transactions')); ?>" data-id="pro-tx">
                            <span class="v2-dot v2-dot--gray"></span><span class="v2-label"><?php echo e(translate('messages.Transactions')); ?></span>
                            <button type="button" class="v2-pin" data-pin="pro-tx" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($can_settings && $tax_on): ?>
        <div class="v2-panel-content" data-panel="fin" <?php if($active_section!=='fin'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Finance & Tax')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('Charges, penalties, and financial configurations')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::fin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php if($tax_on): ?>
                <div class="v2-group">
                    <button type="button" class="v2-group-header" data-group-toggle="fin-tax"><span><?php echo e(translate('Tax Configuration')); ?></span><i data-lucide="chevron-down" class="v2-chev"></i></button>
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e(\Illuminate\Support\Str::is(['taxvat/get-taxvat-data*', 'taxvat/add-taxvat-data*', 'taxvat/update-taxvat-data*', 'taxvat/export-taxvat*'], $req) ? 'is-active' : ''); ?>" href="<?php echo e(route('taxvat.index')); ?>" data-id="tax-create">
                            <span class="v2-dot v2-dot--amber"></span><span class="v2-label"><?php echo e(translate('Create_Taxes')); ?></span>
                            <button type="button" class="v2-pin" data-pin="tax-create" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('taxvat/system-taxvat*') ? 'is-active' : ''); ?>" href="<?php echo e(route('taxvat.systemTaxvat', ['type' => 'vendor'])); ?>" data-id="tax-setup">
                            <span class="v2-dot v2-dot--amber"></span><span class="v2-label"><?php echo e(translate('Setup_Taxes')); ?></span>
                            <button type="button" class="v2-pin" data-pin="tax-setup" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($can_settings): ?>
        <div class="v2-panel-content" data-panel="pages" <?php if($active_section!=='pages'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Website, Pages & Content')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('Public-facing pages, policies, and branding')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::pages'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="v2-group">
                    <button type="button" class="v2-group-header" data-group-toggle="pg-soc"><span><?php echo e(translate('Social & Branding')); ?></span><i data-lucide="chevron-down" class="v2-chev"></i></button>
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/pages/social-media*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.social-media.index')); ?>" data-id="pg-soc-link">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('Social Media Links')); ?></span>
                            <button type="button" class="v2-pin" data-pin="pg-soc-link" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>

                <div class="v2-group">
                    <button type="button" class="v2-group-header" data-group-toggle="pg-land"><span><?php echo e(translate('Landing pages')); ?></span><i data-lucide="chevron-down" class="v2-chev"></i></button>
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/pages/admin-landing-page-settings*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.admin-landing-page-settings', 'setup')); ?>" data-id="pg-adm">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('Admin Landing Page')); ?></span>
                            <button type="button" class="v2-pin" data-pin="pg-adm" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/pages/react-landing-page-settings*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.react-landing-page-settings', 'header')); ?>" data-id="pg-rea">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('React Landing Page')); ?></span>
                            <button type="button" class="v2-pin" data-pin="pg-rea" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php if(addon_published_status('RideShare') == 1): ?>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/pages/react-ride-share-page-settings*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.react-ride-share-page-settings', 'hero')); ?>" data-id="pg-rea-ride">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('messages.react_ride_share_page')); ?></span>
                            <button type="button" class="v2-pin" data-pin="pg-rea-ride" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php endif; ?>
                        
                    </div>
                </div>

                <div class="v2-group">
                    <button type="button" class="v2-group-header" data-group-toggle="pg-leg"><span><?php echo e(translate('Business pages')); ?></span><i data-lucide="chevron-down" class="v2-chev"></i></button>
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/pages/business-page/terms-and-conditions*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.terms-and-conditions')); ?>" data-id="bp-tc">
                            <span class="v2-dot v2-dot--violet"></span><span class="v2-label"><?php echo e(translate('Terms & Conditions')); ?></span>
                            <button type="button" class="v2-pin" data-pin="bp-tc" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/pages/business-page/privacy-policy*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.privacy-policy')); ?>" data-id="bp-pp">
                            <span class="v2-dot v2-dot--violet"></span><span class="v2-label"><?php echo e(translate('Privacy Policy')); ?></span>
                            <button type="button" class="v2-pin" data-pin="bp-pp" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/pages/business-page/about-us*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.about-us')); ?>" data-id="bp-ab">
                            <span class="v2-dot v2-dot--violet"></span><span class="v2-label"><?php echo e(translate('About Us')); ?></span>
                            <button type="button" class="v2-pin" data-pin="bp-ab" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/pages/business-page/refund*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.refund')); ?>" data-id="bp-rf">
                            <span class="v2-dot v2-dot--violet"></span><span class="v2-label"><?php echo e(translate('Refund Policy')); ?></span>
                            <button type="button" class="v2-pin" data-pin="bp-rf" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/pages/business-page/cancelation*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.cancelation')); ?>" data-id="bp-cn">
                            <span class="v2-dot v2-dot--violet"></span><span class="v2-label"><?php echo e(translate('Cancellation Policy')); ?></span>
                            <button type="button" class="v2-pin" data-pin="bp-cn" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/pages/business-page/shipping-policy*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.shipping-policy')); ?>" data-id="bp-sh">
                            <span class="v2-dot v2-dot--violet"></span><span class="v2-label"><?php echo e(translate('Shipping Policy')); ?></span>
                            <button type="button" class="v2-pin" data-pin="bp-sh" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>

                <div class="v2-group">
                    <button type="button" class="v2-group-header" data-group-toggle="pg-seo"><span><?php echo e(translate('SEO & Metadata')); ?></span><i data-lucide="chevron-down" class="v2-chev"></i></button>
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/seo-settings*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.seo-settings.pageMetaData')); ?>" data-id="pg-meta">
                            <span class="v2-dot v2-dot--amber"></span><span class="v2-label"><?php echo e(translate('Page Meta Data (SEO)')); ?></span>
                            <button type="button" class="v2-pin" data-pin="pg-meta" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="v2-panel-content" data-panel="sys" <?php if($active_section!=='sys'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('System Configuration')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('Platform-wide technical settings')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::sys'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="v2-group">
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/language*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.language.index')); ?>" data-id="sys-lang">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('Language Management')); ?></span>
                            <button type="button" class="v2-pin" data-pin="sys-lang" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/app-settings*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.app-settings')); ?>" data-id="sys-app">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('App Settings')); ?></span>
                            <button type="button" class="v2-pin" data-pin="sys-app" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/websocket*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.websocket')); ?>" data-id="sys-ws">
                            <span class="v2-dot v2-dot--violet"></span><span class="v2-label"><?php echo e(translate('WebSocket Configuration')); ?></span>
                            <button type="button" class="v2-pin" data-pin="sys-ws" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/addon-activation*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.addon-activation.index')); ?>" data-id="sys-add">
                            <span class="v2-dot v2-dot--amber"></span><span class="v2-label"><?php echo e(translate('Addon Activation')); ?></span>
                            <button type="button" class="v2-pin" data-pin="sys-add" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/system-addon*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.system-addon.index')); ?>" data-id="sys-sa">
                            <span class="v2-dot v2-dot--gray"></span><span class="v2-label"><?php echo e(translate('System Addons')); ?></span>
                            <button type="button" class="v2-pin" data-pin="sys-sa" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="v2-panel-content" data-panel="auth" <?php if($active_section!=='auth'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Authentication & Access')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('Login systems and access settings')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::auth'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="v2-group">
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e(($is('admin/business-settings/login-settings*') || $is('admin/business-settings/login-url-setup*')) ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.login-settings.index')); ?>" data-id="auth-login">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('Login & Authentication Setup')); ?></span>
                            <button type="button" class="v2-pin" data-pin="auth-login" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="v2-panel-content" data-panel="comm" <?php if($active_section!=='comm'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Communication Setup')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('Email, notifications, and push')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::comm'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <div class="v2-group">
                    <button type="button" class="v2-group-header" data-group-toggle="comm-email"><span><?php echo e(translate('Email configuration')); ?></span><i data-lucide="chevron-down" class="v2-chev"></i></button>
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/email-setup*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.email-setup', ['admin', 'forgot-password'])); ?>" data-id="em-all">
                            <span class="v2-dot v2-dot--green"></span><span class="v2-label"><?php echo e(translate('All Modules Email Setup')); ?></span>
                            <button type="button" class="v2-pin" data-pin="em-all" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php if($rental_on): ?>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/rental-email-setup*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.rental-email-setup', ['admin', 'provider-registration'])); ?>" data-id="em-ren">
                            <span class="v2-dot v2-dot--green"></span><span class="v2-label"><?php echo e(translate('Rental Module Email Setup')); ?></span>
                            <button type="button" class="v2-pin" data-pin="em-ren" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="v2-group">
                    <button type="button" class="v2-group-header" data-group-toggle="comm-notif"><span><?php echo e(translate('Notifications')); ?></span><i data-lucide="chevron-down" class="v2-chev"></i></button>
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e(($is('admin/business-settings/notification-setup*') && !str_contains(request()->fullUrl(), 'module=rental')) ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.notification_setup')); ?>" data-id="sn-all">
                            <span class="v2-dot v2-dot--green"></span><span class="v2-label"><?php echo e(translate('All Modules Notifications')); ?></span>
                            <button type="button" class="v2-pin" data-pin="sn-all" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php if($rental_on): ?>
                        <a class="v2-nav-item <?php echo e(($is('admin/business-settings/notification-setup*') && str_contains(request()->fullUrl(), 'module=rental')) ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.notification_setup', ['module' => 'rental'])); ?>" data-id="sn-rental">
                            <span class="v2-dot v2-dot--green"></span><span class="v2-label"><?php echo e(translate('Rental Module Notifications')); ?></span>
                            <button type="button" class="v2-pin" data-pin="sn-rental" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php endif; ?>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/fcm*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.fcm-index')); ?>" data-id="fcm">
                            <span class="v2-dot v2-dot--rose"></span><span class="v2-label"><?php echo e(translate('Firebase Notifications')); ?></span>
                            <button type="button" class="v2-pin" data-pin="fcm" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="v2-panel-content" data-panel="int" <?php if($active_section!=='int'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Integrations & Third-Party')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('External tools, payment, AI, and analytics')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::int'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="v2-group">
                    <div class="v2-group-items">
                        <?php
                            $int_third_party_active = $is('admin/business-settings/third-party/sms-module*')
                                || $is('admin/business-settings/third-party/mail-config*')
                                || $is('admin/business-settings/third-party/test-mail*')
                                || $is('admin/business-settings/third-party/config-setup*')
                                || $is('admin/business-settings/third-party/social-login*')
                                || $is('admin/business-settings/third-party/recaptcha*')
                                || $is('admin/business-settings/third-party/firebase-otp*')
                                || $is('admin/business-settings/third-party/storage-connection*')
                                || $is('admin/sms/configuration*');
                        ?>
                        <a class="v2-nav-item <?php echo e($int_third_party_active ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.third-party.sms-module')); ?>" data-id="int-sms">
                            <span class="v2-dot v2-dot--blue"></span><span class="v2-label"><?php echo e(translate('3rd Party & External Services')); ?></span>
                            <button type="button" class="v2-pin" data-pin="int-sms" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e(($is('admin/business-settings/third-party/payment-method*') || $is('admin/business-settings/offline-payment*') || $is('admin/payment/configuration*')) ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.third-party.payment-method')); ?>" data-id="int-pay">
                            <span class="v2-dot v2-dot--green"></span><span class="v2-label"><?php echo e(translate('Payment Methods')); ?></span>
                            <button type="button" class="v2-pin" data-pin="int-pay" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/marketing*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.marketing.analytic')); ?>" data-id="int-an">
                            <span class="v2-dot v2-dot--amber"></span><span class="v2-label"><?php echo e(translate('Analytics & Tracking Scripts')); ?></span>
                            <button type="button" class="v2-pin" data-pin="int-an" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php if(Route::has('admin.business-settings.openAI')): ?>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/open-ai*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.openAI')); ?>" data-id="int-ai">
                            <span class="v2-dot v2-dot--violet"></span><span class="v2-label"><?php echo e(translate('AI Configuration')); ?></span>
                            <button type="button" class="v2-pin" data-pin="int-ai" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if($ride_on): ?>
        <div class="v2-panel-content" data-panel="safety" <?php if($active_section!=='safety'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Ride Share Settings')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('Fare, penalties, and safety configurations')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::safety'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="v2-group">
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e(($is('admin/business-settings/ride-fare*') || $is('admin/business-settings/ride-share*')) ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.ride-fare.penalty')); ?>" data-id="fin-fare">
                            <span class="v2-dot v2-dot--rose"></span><span class="v2-label"><?php echo e(translate('Ride Fare Penalty & Charges')); ?></span>
                            <button type="button" class="v2-pin" data-pin="fin-fare" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php if(Route::has('admin.business-settings.safety-precaution.index') && defined('SAFETY_ALERT')): ?>
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/safety-precaution*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.safety-precaution.index', SAFETY_ALERT)); ?>" data-id="safety-alerts">
                            <span class="v2-dot v2-dot--rose"></span><span class="v2-label"><?php echo e(translate('Safety Alerts & Precautions')); ?></span>
                            <button type="button" class="v2-pin" data-pin="safety-alerts" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="v2-panel-content" data-panel="media" <?php if($active_section!=='media'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Media & File Management')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('Files, assets, and gallery')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::media'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="v2-group">
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/file-manager*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.file-manager.index')); ?>" data-id="media-gal">
                            <span class="v2-dot v2-dot--amber"></span><span class="v2-label"><?php echo e(translate('Gallery / File Manager')); ?></span>
                            <button type="button" class="v2-pin" data-pin="media-gal" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="v2-panel-content" data-panel="maint" <?php if($active_section!=='maint'): ?> hidden <?php endif; ?>>
            <div class="v2-panel-header">
                <div class="v2-panel-title"><span class="name"><?php echo e(translate('Maintenance & Database')); ?></span></div>
                <div class="v2-panel-subtitle"><?php echo e(translate('System cleanup and maintenance')); ?></div>
            </div>
            <div class="v2-panel-body">
                <?php echo $__env->make('layouts.admin.partials._v2_pinned_card', ['key' => 'settings::maint'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <div class="v2-group">
                    <div class="v2-group-items">
                        <a class="v2-nav-item <?php echo e($is('admin/business-settings/db-index*') ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.business-settings.db-index')); ?>" data-id="maint-clean">
                            <span class="v2-dot v2-dot--rose"></span><span class="v2-label"><?php echo e(translate('Clean Database')); ?></span>
                            <button type="button" class="v2-pin" data-pin="maint-clean" title="<?php echo e(translate('Pin')); ?>"><?php echo $__env->make('layouts.admin.partials._v2_pin_icon', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </aside>
</aside>

<?php echo $__env->make('layouts.admin.partials._v2_profile_pop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('layouts.admin.partials._v2_sidebar_script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\mytijaara\resources\views/layouts/admin/partials/_sidebar_v2_settings.blade.php ENDPATH**/ ?>