
<?php
    use App\CentralLogics\Helpers;

    $current_module_id   = Config::get('module.current_module_id');
    $current_module_type = Config::get('module.current_module_type');
    $current_module      = \App\Models\Module::with('storage')->find($current_module_id);
    $admin_user          = auth('admin')->user();
    $local               = session()->has('local') ? session('local') : null;
    $lang                = Helpers::get_business_settings('system_language');

    // Modules visible to this admin (filtered by zone, like the legacy header)
    $modules = \App\Models\Module::when($admin_user?->zone_id, function ($q) use ($admin_user) {
        $q->whereHas('zones', function ($qq) use ($admin_user) { $qq->where('zone_id', $admin_user->zone_id); });
    })->Active()->get();

    // Workspace tab definitions — active state derived from current path
    $req_path = request()->path();
    $is_settings_path = \Illuminate\Support\Str::is('admin/business-settings*', $req_path)
        || \Illuminate\Support\Str::is('admin/payment/configuration*', $req_path)
        || \Illuminate\Support\Str::is('admin/sms/configuration*', $req_path)
        || \Illuminate\Support\Str::is('taxvat/*', $req_path)
        || \Illuminate\Support\Str::is('admin/pro-customer*', $req_path);
    $is_users_path     = \Illuminate\Support\Str::is('admin/users*', $req_path);
    $is_dispatch_path  = \Illuminate\Support\Str::is('admin/dispatch*', $req_path);

    // Inside /admin/transactions/, split Finance vs Reports.
    // Tax reports stay under Finance > Tax & Compliance per prototype.
    // Patterns use kebab-case URIs (route NAMES are camelCase but URIs aren't),
    // and are broad to catch all variants — get-tax-export/list/details, vendor-tax-*, *-wise-taxes, etc.
    $is_tax_path = \Illuminate\Support\Str::is('admin/transactions/report/*tax*', $req_path)
        || \Illuminate\Support\Str::is('admin/transactions/rental/report/*tax*', $req_path)
        || \Illuminate\Support\Str::is('admin/transactions/ride-share/report/*tax*', $req_path);
    $is_reports_path = !$is_tax_path && (
        \Illuminate\Support\Str::is('admin/transactions/report/*', $req_path)
        || \Illuminate\Support\Str::is('admin/transactions/rental/report/*', $req_path)
        || \Illuminate\Support\Str::is('admin/transactions/ride-share/*', $req_path)
    );
    // Order/trip/ride detail pages live under admin/transactions/ but belong
    // to their module's workspace (sidebar + top tab), not Finance. The
    // CurrentModule middleware swaps current_module_type to match; mirror
    // that here so the top tab agrees with the sidebar.
    $is_module_detail_under_transactions = \Illuminate\Support\Str::is('admin/transactions/parcel/order/details/*', $req_path)
        || \Illuminate\Support\Str::is('admin/transactions/rental/trip/details/*', $req_path)
        || \Illuminate\Support\Str::is('admin/transactions/rental/trip/generate-invoice/*', $req_path)
        || \Illuminate\Support\Str::is('admin/transactions/order/details/*', $req_path);
    $is_finance_path = \Illuminate\Support\Str::is('admin/transactions*', $req_path) && !$is_reports_path && !$is_module_detail_under_transactions;
    $is_module_path  = !$is_users_path && !$is_finance_path && !$is_reports_path && !$is_dispatch_path && !$is_settings_path;

    // Notification counts (mirror legacy header)
    $unread_messages = \App\Models\Conversation::whereUserType('admin')->whereHas('last_message', function ($q) {
        $q->whereColumn('conversations.sender_id', 'messages.sender_id');
    })->where('unread_message_count', '>', 0)->count();

    $safety_count = 0; $latest_safety = null;
    if (addon_published_status('RideShare')) {
        $safety_q = \Modules\RideShare\Entities\TripManagement\RideSafetyAlert::where('status', 'pending');
        $safety_count = $safety_q->count();
        $latest_safety = $safety_q->latest()->first();
    }

    // Common-workspace landing routes
    $url_users    = route('admin.users.dashboard');
    $url_finance  = route('admin.transactions.store.withdraw_list');
    $url_reports  = route('admin.transactions.report.day-wise-report');
    $url_dispatch = route('admin.dispatch.dashboard');
    $url_settings = Helpers::settings_workspace_landing_url();
    $url_module   = route('admin.dashboard') . '?module_id=' . $current_module_id;
?>


<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>


<script>
(function () {
    try {
        var saved = localStorage.getItem('v2_mode_v1');
        var isPos = /^\/?admin\/pos(\/|$)/.test(window.location.pathname);
        var mode = isPos ? 'compact' : (saved === 'compact' ? 'compact' : 'pinned');
        document.body.classList.remove('v2-mode-pinned', 'v2-mode-compact');
        document.body.classList.add('v2-mode-' + mode);
    } catch (e) {}
})();
</script>


<?php echo $__env->make('layouts.admin.partials._v2_tour', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<header class="v2-topbar" role="banner">
    <a class="v2-brand" href="<?php echo e(route('admin.dashboard')); ?>">
        <?php ($store_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first()); ?>
        <img class="v2-brand-mark" src="<?php echo e(Helpers::get_full_url('business', $store_logo?->value ?? '', $store_logo?->storage[0]?->value ?? 'public', 'favicon')); ?>" alt="Logo" onerror="this.src='<?php echo e(asset('public/assets/admin/img/160x160/img2.jpg')); ?>'">
    </a>

    <button type="button" class="v2-mode-btn v2-mobile-toggle" id="v2-mobile-toggle" aria-label="<?php echo e(translate('Toggle navigation drawer')); ?>">
        <i data-lucide="menu"></i>
    </button>
    <?php if($layout_features['compact_mode_toggle'] ?? true): ?>
    <button type="button" class="v2-mode-btn v2-desktop-only" id="v2-mode-btn" title="<?php echo e(translate('Toggle view mode')); ?>" aria-label="<?php echo e(translate('Toggle view mode')); ?>">
        <i data-lucide="<?php echo e(session()->get('site_direction') === 'rtl' ? 'panel-right-open' : 'panel-left-open'); ?>" id="v2-mode-icon"></i>
    </button>
    <?php endif; ?>

    <nav class="v2-topnav" id="v2-topnav" aria-label="<?php echo e(translate('Workspaces')); ?>">
        <?php if(Helpers::admin_can_access_workspace('module')): ?>
        <a class="v2-ws-tab v2-ws-tab--module <?php echo e($is_module_path ? 'is-active' : ''); ?>" href="<?php echo e($url_module); ?>">
            <i data-lucide="package" class="v2-ws-ico"></i>
            <span><?php echo e(translate('Module')); ?></span>
            <?php if($is_module_path && $current_module): ?>
                <span class="v2-module-pill">
                    <i data-lucide="layout-grid"></i><?php echo e($current_module->module_name); ?>

                </span>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        <?php if(Helpers::admin_can_access_workspace('users')): ?>
        <a class="v2-ws-tab <?php echo e($is_users_path ? 'is-active' : ''); ?>" href="<?php echo e($url_users); ?>">
            <i data-lucide="users" class="v2-ws-ico"></i>
            <span><?php echo e(translate('Users')); ?></span>
        </a>
        <?php endif; ?>
        <?php if(Helpers::admin_can_access_workspace('finance')): ?>
        <a class="v2-ws-tab <?php echo e($is_finance_path ? 'is-active' : ''); ?>" href="<?php echo e($url_finance); ?>">
            <i data-lucide="wallet" class="v2-ws-ico"></i>
            <span><?php echo e(translate('Finance')); ?></span>
        </a>
        <?php endif; ?>
        <?php if(Helpers::admin_can_access_workspace('reports')): ?>
        <a class="v2-ws-tab <?php echo e($is_reports_path ? 'is-active' : ''); ?>" href="<?php echo e($url_reports); ?>">
            <i data-lucide="bar-chart-3" class="v2-ws-ico"></i>
            <span><?php echo e(translate('Reports')); ?></span>
        </a>
        <?php endif; ?>
        <?php if(Helpers::module_permission_check('order')): ?>
        <a class="v2-ws-tab <?php echo e($is_dispatch_path ? 'is-active' : ''); ?>" href="<?php echo e($url_dispatch); ?>">
            <i data-lucide="route" class="v2-ws-ico"></i>
            <span><?php echo e(translate('Dispatch')); ?></span>
        </a>
        <?php endif; ?>
        <?php if(Helpers::admin_can_access_workspace('settings')): ?>
        <a class="v2-ws-tab <?php echo e($is_settings_path ? 'is-active' : ''); ?>" href="<?php echo e($url_settings); ?>">
            <i data-lucide="settings-2" class="v2-ws-ico"></i>
            <span><?php echo e(translate('messages.Settings')); ?></span>
        </a>
        <?php endif; ?>
    </nav>

    <div class="v2-topbar-spacer"></div>

    
    <button type="button" class="v2-topbar-search" data-toggle="modal" data-target="#staticBackdrop" aria-label="<?php echo e(translate('Search_or')); ?>">
        <i data-lucide="search"></i>
        <span class="v2-search-placeholder"><?php echo e(translate('Search_by_keyword')); ?></span>
        <kbd>Ctrl+K</kbd>
    </button>

    <div class="v2-topbar-actions">
        <button type="button" class="v2-icon-btn v2-mobile-only-search" data-toggle="modal" data-target="#staticBackdrop" aria-label="<?php echo e(translate('Search_or')); ?>">
            <i data-lucide="search"></i>
        </button>

        <?php if($lang): ?>
            <div class="v2-lang-wrap">
                <button type="button" class="v2-icon-btn" id="v2-lang-btn" aria-haspopup="menu" aria-expanded="false" aria-label="<?php echo e(translate('Language')); ?>">
                    <i data-lucide="globe"></i>
                </button>
                <div class="v2-lang-pop" id="v2-lang-pop" role="menu">
                    <div class="v2-lang-pop-head"><?php echo e(translate('Language')); ?></div>
                    <?php $__currentLoopData = $lang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(($data['status'] ?? 0) == 1): ?>
                            <a class="v2-lang-item <?php echo e(($data['code'] === $local) || (!$local && ($data['default'] ?? false) === true) ? 'is-active' : ''); ?>" href="<?php echo e(route('admin.lang', [$data['code']])); ?>">
                                <span class="v2-lang-code"><?php echo e(strtoupper($data['code'])); ?></span>
                                <span class="v2-lang-name"><?php echo e(ucfirst($data['name'] ?? $data['code'])); ?></span>
                                <i data-lucide="check" class="v2-lang-check"></i>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if(\App\CentralLogics\Helpers::module_permission_check('customer_management')): ?>
        <a href="<?php echo e(route('admin.message.list')); ?>" class="v2-icon-btn" aria-label="<?php echo e(translate('messages.message')); ?>">
            <i data-lucide="message-circle"></i>
            <?php if($unread_messages > 0): ?><span class="v2-badge"><?php echo e($unread_messages); ?></span><?php endif; ?>
        </a>
        <?php endif; ?>

        <?php if(addon_published_status('RideShare') && \App\CentralLogics\Helpers::module_permission_check('fleet_view')): ?>
            <a id="v2-safety-link" href="<?php echo e(route('admin.ride-share.safety-alerts', ['module_id' => \App\Models\Module::where('module_type','ride-share')->first()->id ?? 0])); ?>" class="v2-icon-btn <?php if($latest_safety): ?> safety-alert-header-icon <?php endif; ?>" <?php if($latest_safety): ?> data-user-id="<?php echo e($latest_safety->sent_by); ?>" <?php endif; ?> aria-label="<?php echo e(translate('Safety alerts')); ?>">
                <i data-lucide="shield-alert"></i>
                <?php if($safety_count > 0): ?><span class="v2-badge" id="v2-safety-badge"><?php echo e($safety_count); ?></span><?php endif; ?>
            </a>
        <?php endif; ?>

        <?php if($layout_features['fullscreen'] ?? true): ?>
        <button type="button" class="v2-icon-btn v2-desktop-only" id="v2-fs-btn" aria-label="<?php echo e(translate('Toggle fullscreen')); ?>" title="<?php echo e(translate('Toggle fullscreen (F11)')); ?>">
            <i data-lucide="maximize-2" id="v2-fs-icon"></i>
        </button>
        <?php endif; ?>
    </div>

    
    <button type="button" class="v2-module-switcher" id="v2-mod-switch" aria-haspopup="menu" aria-expanded="false">
        <span class="v2-mod-icon">
            <?php if($current_module && $current_module->icon_full_url): ?>
                <img src="<?php echo e($current_module->icon_full_url); ?>" alt="" onerror="this.src='<?php echo e(asset('public/assets/admin/img/new-img/module-icon.svg')); ?>'">
            <?php else: ?>
                <i data-lucide="shopping-cart"></i>
            <?php endif; ?>
        </span>
        <span class="v2-mod-name"><?php echo e($current_module->module_name ?? translate('modules')); ?></span>
        <i data-lucide="chevron-down" class="v2-chev"></i>
    </button>
    <div class="v2-modpop" id="v2-modpop" role="menu">
        <h4><?php echo e(translate('Modules Section')); ?></h4>
        <p><?php echo e(translate('Select Module & Monitor your business module wise')); ?></p>
        <?php if($modules->count() > 0): ?>
            <div class="v2-mod-grid">
                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(($module->module_type === 'rental' && addon_published_status('Rental')) || $module->module_type !== 'rental'): ?>
                        <a href="javascript:"
                           class="v2-mod-tile set-module <?php echo e($current_module_id == $module->id ? 'is-active' : ''); ?>"
                           data-module-id="<?php echo e($module->id); ?>"
                           data-url="<?php echo e($module->module_type === 'rental' && addon_published_status('Rental') ? route('admin.rental.dashboard') : route('admin.dashboard')); ?>"
                           data-filter="module_id">
                            <?php if(getEnvMode() == 'demo' && in_array($module->module_type, ['rental', 'ride-share'])): ?>
                                <span class="v2-mod-addon-tag"><?php echo e(translate('Addon')); ?></span>
                            <?php endif; ?>
                            <div class="v2-mod-tile-ico">
                                <img src="<?php echo e($module->icon_full_url); ?>" alt="" onerror="this.src='<?php echo e(asset('public/assets/admin/img/new-img/module/e-shop.svg')); ?>'">
                            </div>
                            <div><?php echo e($module->module_name); ?></div>
                        </a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(Helpers::module_permission_check('module')): ?>
                    <a href="<?php echo e(route('admin.business-settings.module.create')); ?>" class="v2-mod-tile" title="<?php echo e(translate('add_new_module')); ?>">
                        <div class="v2-mod-tile-ico"><i data-lucide="plus"></i></div>
                        <div><?php echo e(translate('Add')); ?></div>
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="v2-mod-empty">
                <p><?php echo e(translate('Please, Enable or Create Module First')); ?></p>
                <a href="<?php echo e(route('admin.business-settings.module.index')); ?>" class="v2-mod-empty-btn"><?php echo e(translate('messages.Module Setup')); ?></a>
            </div>
        <?php endif; ?>
    </div>
</header>

<div class="v2-mobile-backdrop" id="v2-mobile-backdrop" aria-hidden="true"></div>

<?php $__env->startPush('script_2'); ?>
<script>
(function () {
    'use strict';

    function closePops() {
        document.querySelectorAll('.v2-modpop, .v2-lang-pop').forEach(function (p) { p.classList.remove('is-open'); });
        var ms = document.getElementById('v2-mod-switch');
        var lb = document.getElementById('v2-lang-btn');
        if (ms) ms.setAttribute('aria-expanded', 'false');
        if (lb) lb.setAttribute('aria-expanded', 'false');
    }

    function v2CloseDrawerIfOpen() {
        if (document.body.classList.contains('v2-drawer-open')) {
            document.body.classList.remove('v2-drawer-open');
        }
    }

    var modBtn = document.getElementById('v2-mod-switch');
    var modPop = document.getElementById('v2-modpop');
    if (modBtn && modPop) {
        modBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            v2CloseDrawerIfOpen();
            var isOpen = !modPop.classList.contains('is-open');
            closePops();
            if (isOpen) modPop.classList.add('is-open');
            modBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    var langBtn = document.getElementById('v2-lang-btn');
    var langPop = document.getElementById('v2-lang-pop');
    if (langBtn && langPop) {
        langBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            v2CloseDrawerIfOpen();
            var isOpen = !langPop.classList.contains('is-open');
            closePops();
            if (isOpen) langPop.classList.add('is-open');
            langBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    document.addEventListener('click', function (e) {
        if (e.target.closest('#v2-modpop, #v2-mod-switch, #v2-lang-pop, #v2-lang-btn')) return;
        closePops();
    });

    // View-mode toggle (pinned ↔ compact)
    var MODE_KEY = 'v2_mode_v1';
    var MODE_ORDER = ['pinned', 'compact'];
    function v2IsRtl() { return (document.documentElement.getAttribute('dir') || '').toLowerCase() === 'rtl'; }
    function v2ModeIcon(mode) {
        var rtl = v2IsRtl();
        if (mode === 'compact') return rtl ? 'panel-right-close' : 'panel-left-close';
        return rtl ? 'panel-right-open' : 'panel-left-open';
    }
    var MODE_LABEL = {
        pinned: '<?php echo e(translate('Pinned (rail + panel)')); ?>',
        compact: '<?php echo e(translate('Compact (hover to peek panel)')); ?>'
    };
    var modeBtn = document.getElementById('v2-mode-btn');
    var modeIconEl = document.getElementById('v2-mode-icon');

    function applyMode(mode, persist) {
        if (MODE_ORDER.indexOf(mode) === -1) mode = 'pinned';
        document.body.classList.remove('v2-mode-pinned', 'v2-mode-compact');
        document.body.classList.add('v2-mode-' + mode);
        if (modeBtn) modeBtn.setAttribute('title', MODE_LABEL[mode]);
        // Swap the lucide icon to match the new mode. Lucide replaces our
        // <i data-lucide=> with an <svg> on first render, so re-inject a
        // fresh placeholder and let lucide.createIcons() re-render.
        var ic = document.getElementById('v2-mode-icon');
        if (ic) {
            ic.outerHTML = '<i data-lucide="' + v2ModeIcon(mode) + '" id="v2-mode-icon"></i>';
            modeIconEl = document.getElementById('v2-mode-icon');
            if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
        }
        if (persist !== false) {
            try { localStorage.setItem(MODE_KEY, mode); } catch (e) {}
        }
    }

    // POS auto-compact: force compact mode while on /admin/pos/* without
    // persisting, so leaving POS restores the user's previous preference.
    var isPosPage = /^\/?admin\/pos(\/|$)/.test(window.location.pathname);
    if (isPosPage) {
        applyMode('compact', false);
    } else {
        var savedMode;
        try { savedMode = localStorage.getItem(MODE_KEY); } catch (e) { savedMode = null; }
        // Apply the saved mode WITHOUT re-writing to localStorage. The only
        // writes happen on an explicit user click. This avoids any chance of
        // the page-load apply clobbering an in-flight value, and keeps the
        // localStorage write count minimal.
        applyMode(savedMode || 'pinned', false);
    }

    // Use event delegation on document so clicks land even if the button's
    // children are re-rendered by lucide (it replaces <i data-lucide=> with
    // <svg> in place, which can detach references picked up at script load).
    document.addEventListener('click', function (e) {
        if (!e.target.closest || !e.target.closest('#v2-mode-btn')) return;
        var current = document.body.classList.contains('v2-mode-compact') ? 'compact' : 'pinned';
        var next = MODE_ORDER[(MODE_ORDER.indexOf(current) + 1) % MODE_ORDER.length];
        applyMode(next, !isPosPage);
    });

    // Fullscreen toggle (mirrors the legacy #fsBtn behavior)
    function v2IsFullscreen() {
        return !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement);
    }
    function v2ToggleFullscreen() {
        if (!v2IsFullscreen()) {
            var el = document.documentElement;
            var req = el.requestFullscreen || el.webkitRequestFullscreen || el.mozRequestFullScreen || el.msRequestFullscreen;
            if (req) req.call(el);
        } else {
            var exit = document.exitFullscreen || document.webkitExitFullscreen || document.mozCancelFullScreen || document.msExitFullscreen;
            if (exit) exit.call(document);
        }
    }
    function v2SyncFullscreenIcon() {
        var ic = document.getElementById('v2-fs-icon');
        var btn = document.getElementById('v2-fs-btn');
        if (!ic || !btn) return;
        var fs = v2IsFullscreen();
        ic.outerHTML = '<i data-lucide="' + (fs ? 'minimize-2' : 'maximize-2') + '" id="v2-fs-icon"></i>';
        btn.setAttribute('title', fs ? '<?php echo e(translate('Exit fullscreen')); ?>' : '<?php echo e(translate('Toggle fullscreen (F11)')); ?>');
        if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
    }
    var fsBtn = document.getElementById('v2-fs-btn');
    if (fsBtn) fsBtn.addEventListener('click', v2ToggleFullscreen);
    ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'msfullscreenchange'].forEach(function (ev) {
        document.addEventListener(ev, v2SyncFullscreenIcon);
    });

    // Mobile drawer (rail + panel slide in)
    var mobileToggle = document.getElementById('v2-mobile-toggle');
    var backdrop = document.getElementById('v2-mobile-backdrop');
    function setDrawer(open) {
        document.body.classList.toggle('v2-drawer-open', !!open);
    }
    function isDrawerOpen() {
        return document.body.classList.contains('v2-drawer-open');
    }
    if (mobileToggle) mobileToggle.addEventListener('click', function () {
        setDrawer(!isDrawerOpen());
    });
    if (backdrop) backdrop.addEventListener('click', function () { setDrawer(false); });

    // Auto-close the mobile drawer when the user interacts with any of the
    // topbar action items (search, mobile search, module switcher, language,
    // messages, safety alerts). Otherwise the drawer keeps covering the popup
    // that the user just opened.
    var DRAWER_CLOSE_TRIGGERS = [
        '.v2-topbar-search',
        '.v2-mobile-only-search',
        '#v2-mod-switch',
        '#v2-lang-btn',
        '.v2-topbar-actions a.v2-icon-btn'
    ].join(',');
    document.addEventListener('click', function (e) {
        if (!isDrawerOpen()) return;
        if (e.target.closest(DRAWER_CLOSE_TRIGGERS)) {
            setDrawer(false);
        }
    });

    // Also close the drawer when the user picks an actual sidebar nav item —
    // they're navigating away, the drawer has done its job. Rail buttons are
    // intentionally NOT included here: they only switch which panel is shown
    // inside the drawer, not navigate, so closing on rail tap would force the
    // user to reopen the drawer to pick an item.
    document.addEventListener('click', function (e) {
        if (!isDrawerOpen()) return;
        var navLink = e.target.closest('#v2-panel a[href]');
        if (navLink) setDrawer(false);
    });

    // Close the drawer whenever a Bootstrap modal or offcanvas opens
    // (global search, withdraw-detail offcanvas, etc.) so the drawer
    // doesn't visually overlap the dialog.
    if (window.jQuery) {
        window.jQuery(document).on('show.bs.modal show.bs.offcanvas', function () {
            if (isDrawerOpen()) setDrawer(false);
        });
    } else {
        document.addEventListener('show.bs.modal', function () { if (isDrawerOpen()) setDrawer(false); }, true);
        document.addEventListener('show.bs.offcanvas', function () { if (isDrawerOpen()) setDrawer(false); }, true);
    }

    // Event-delegation fallback for the module switcher tiles. The legacy
    // .set-module handler in admin.js is a direct `$('.set-module').on('click')`
    // binding registered on initial page load — it works for tiles that exist
    // at that point, but to keep the module switcher resilient on mobile (and
    // to also close the drawer when a tile is tapped) we delegate from the
    // popover container.
    var modpopForDelegate = document.getElementById('v2-modpop');
    if (modpopForDelegate) {
        modpopForDelegate.addEventListener('click', function (e) {
            var tile = e.target.closest('a.set-module[data-url][data-module-id]');
            if (!tile) return;
            e.preventDefault();
            setDrawer(false);
            try {
                var url = new URL(tile.getAttribute('data-url'), window.location.href);
                var filterBy = tile.getAttribute('data-filter') || 'module_id';
                url.searchParams.set(filterBy, tile.getAttribute('data-module-id'));
                window.location.href = url.toString();
            } catch (err) {}
        });
    }

    // Ctrl/Cmd+K opens existing search modal (the existing handler in app.blade.php
    // also wires this, but it targets #modalOpener.click() — we leave that intact)

    if (window.lucide && typeof window.lucide.createIcons === 'function') {
        window.lucide.createIcons();
    }
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\mytijaara\resources\views/layouts/admin/partials/_header_v2.blade.php ENDPATH**/ ?>