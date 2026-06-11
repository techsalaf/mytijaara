<?php $__env->startSection('title',translate('Store List')); ?>

<?php $__env->startPush('css_or_js'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <?php
            $verified_seller_badge = \App\CentralLogics\Helpers::get_business_settings('verified_seller_badge');
            $recommended_store_list = $verified_seller_badge ? \App\CentralLogics\Helpers::get_verified_seller_eligible_stores(countOnly: false , moduleId: config('module.current_module_id')) : [];
            $recommended_stores = count($recommended_store_list) ;
        ?>
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
            <div>
                <h1 class="page-header-title"><i class="tio-filter-list"></i> <?php echo e(translate('messages.stores')); ?> <span class="badge badge-soft-dark ml-2" id="itemCount"><?php echo e($stores->total()); ?></span></h1>
                <div class="page-header-select-wrapper">
                </div>
            </div>
            <?php if($recommended_stores??0 > 0): ?>
            <div class="d-flex align-items-center gap-2 bg-success bg-opacity-10 flex-wrap rounded py-1 px-2">
                    <div class="fs-12 mb-0 d-flex align-items-center gap-2">
                        <img src="<?php echo e(asset('public/assets/admin/img/badge-rounded-circle.svg')); ?>" alt="" class="rounded-0 w-auto h-auto object-contain">
                        <?php echo e(translate('Recommended')); ?> <strong class="title-clr"><?php echo e($recommended_stores); ?></strong> <?php echo e(translate('stores for verification')); ?>

                    </div>

                <button class="btn btn--primary bg-theme2 border-0 py-1 px-3 fs-12 fw-500 mb-0 offcanvas-trigger  " data-target="#offcanvas__customBtn3" data-id="0"  data-url="" type="button">
                    <?php echo e(translate('messages.View')); ?>

                </button>
            </div>
            <?php endif; ?>
        </div>
        <!-- End Page Header -->


        <!-- Resturent Card Wrapper -->
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card card--bg-1">
                    <h4 class="title"><?php echo e($total_store); ?></h4>
                    <span class="subtitle"><?php echo e(translate('messages.total_stores')); ?></span>
                    <img class="resturant-icon" src="<?php echo e(asset('/public/assets/admin/img/total-store.png')); ?>" alt="store">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card card--bg-2">
                    <h4 class="title"><?php echo e($active_stores); ?></h4>
                    <span class="subtitle"><?php echo e(translate('messages.active_stores')); ?></span>
                    <img class="resturant-icon" src="<?php echo e(asset('/public/assets/admin/img/active-store.png')); ?>" alt="store">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card card--bg-3">
                    <h4 class="title"><?php echo e($inactive_stores); ?></h4>
                    <span class="subtitle"><?php echo e(translate('messages.inactive_stores')); ?></span>
                    <img class="resturant-icon" src="<?php echo e(asset('/public/assets/admin/img/close-store.png')); ?>" alt="store">
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="resturant-card card--bg-4">
                    <h4 class="title"><?php echo e($recent_stores); ?></h4>
                    <span class="subtitle"><?php echo e(translate('messages.newly_joined_stores')); ?></span>
                    <img class="resturant-icon" src="<?php echo e(asset('/public/assets/admin/img/add-store.png')); ?>" alt="store">
                </div>
            </div>
        </div>
        <!-- Resturent Card Wrapper -->
        <!-- Transaction Information -->
        <ul class="transaction--information text-uppercase">
            <li class="text--info">
                <i class="tio-document-text-outlined"></i>
                <div>
                    <span><?php echo e(translate('messages.total_transactions')); ?></span> <strong><?php echo e($total_transaction); ?></strong>
                </div>
            </li>

            <?php if(auth('admin')->user()->role_id == 1): ?>
                <li class="seperator"></li>
                <li class="text--success">
                    <i class="tio-checkmark-circle-outlined success--icon"></i>
                    <div>
                        <span><?php echo e(translate('messages.commission_earned')); ?></span> <strong><?php echo e(\App\CentralLogics\Helpers::format_currency($comission_earned)); ?></strong>
                    </div>
                </li>
            <?php endif; ?>

            <li class="seperator"></li>
            <li class="text--danger">
                <i class="tio-atm"></i>
                <div>
                    <span><?php echo e(translate('messages.total_store_withdraws')); ?></span> <strong><?php echo e(\App\CentralLogics\Helpers::format_currency($store_withdraws)); ?></strong>
                </div>
            </li>
        </ul>
        <!-- Transaction Information -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title"><?php echo e(translate('messages.stores_list')); ?></h5>

                <?php if(!isset(auth('admin')->user()->zone_id)): ?>
                <div class="select-item min--280">
                    <select name="zone_id" class="form-control js-select2-custom set-filter" data-url="<?php echo e(url()->full()); ?>" data-filter="zone_id">
                        <option value="" <?php echo e(!request('zone_id')?'selected':''); ?>><?php echo e(translate('messages.All_Zones')); ?></option>
                        <?php $__currentLoopData = \App\Models\Zone::orderBy('name')->get(['id','name']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $z): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option
                                value="<?php echo e($z['id']); ?>" <?php echo e(isset($zone) && $zone->id == $z['id']?'selected':''); ?>>
                                <?php echo e($z['name']); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <?php endif; ?>
                    <form class="search-form">
                                    <!-- Search -->
                        <div class="input-group input--group">
                            <input id="datatableSearch_" type="search" value="<?php echo e(request()?->search ?? null); ?>" name="search" class="form-control"
                                    placeholder="<?php echo e(translate('ex_:_Search_Store_Name')); ?>" aria-label="<?php echo e(translate('messages.search')); ?>" >
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>

                        </div>
                        <!-- End Search -->
                    </form>
                    <?php if(request()->get('search')): ?>
                    <button type="reset" class="btn btn--primary ml-2 location-reload-to-base" data-url="<?php echo e(url()->full()); ?>"><?php echo e(translate('messages.reset')); ?></button>
                    <?php endif; ?>


                    <!-- Unfold -->
                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"
                            data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                            <i class="tio-download-to mr-1"></i> <?php echo e(translate('messages.export')); ?>

                        </a>

                        <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                            <span class="dropdown-header"><?php echo e(translate('messages.download_options')); ?></span>
                            <a id="export-excel" class="dropdown-item" href="<?php echo e(route('admin.store.export', ['type'=>'excel',request()->getQueryString()])); ?>">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="<?php echo e(asset('public/assets/admin')); ?>/svg/components/excel.svg"
                                    alt="Image Description">
                                <?php echo e(translate('messages.excel')); ?>

                            </a>
                            <a id="export-csv" class="dropdown-item" href="<?php echo e(route('admin.store.export', ['type'=>'csv',request()->getQueryString()])); ?>">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="<?php echo e(asset('public/assets/admin')); ?>/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                                <?php echo e(translate('messages.csv')); ?>

                            </a>

                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                            "order": [],
                            "orderCellsTop": true,
                            "paging":false

                        }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="border-0"><?php echo e(translate('sl')); ?></th>
                        <th class="border-0"><?php echo e(translate('messages.store_information')); ?></th>
                        <th class="border-0"><?php echo e(translate('messages.owner_information')); ?></th>
                        <th class="border-0"><?php echo e(translate('messages.zone')); ?></th>
                        <th class="text-uppercase border-0"><?php echo e(translate('messages.featured')); ?></th>
                        <th class="text-uppercase border-0"><?php echo e(translate('messages.status')); ?></th>
                        <th class="text-center border-0"><?php echo e(translate('messages.action')); ?></th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key+$stores->firstItem()); ?></td>
                            <td>
                                <div>
                                    <a href="<?php echo e(route('admin.store.view', $store->id)); ?>" class="table-rest-info" alt="view store">
                                        <img class="img--60 circle onerror-image" data-onerror-image="<?php echo e(asset('public/assets/admin/img/160x160/img1.jpg')); ?>"
                                                src="<?php echo e($store['logo_full_url'] ?? asset('public/assets/admin/img/160x160/img1.jpg')); ?>">
                                        <div class="info max-w-200px">
                                            <div title="<?php echo e($store?->name); ?>" class="text--title ">
                                                <?php echo e(Str::limit($store->name,20,'...')); ?>

                                                <?php if($verified_seller_badge == 1 && $store->storeConfig?->verified_seller): ?>
                                                    <img src="<?php echo e(asset('public/assets/admin/img/checked-badge.svg')); ?>" alt="" class="rounded-0 w-auto h-auto object-contain">
                                                <?php endif; ?>
                                            </div>
                                            <div class="font-light">
                                                <?php echo e(translate('messages.id')); ?>:<?php echo e($store->id); ?>

                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </td>

                            <td>
                                <span title="<?php echo e($store?->vendor?->f_name.' '.$store?->vendor?->l_name); ?>" class="d-block font-size-sm text-body">
                                    <?php echo e(Str::limit($store->vendor->f_name.' '.$store->vendor->l_name,20,'...')); ?>

                                </span>
                                <div>
                                    <a href="tel:<?php echo e($store['phone']); ?>">
                                        <?php echo e($store['phone']); ?>

                                    </a>
                                </div>
                            </td>
                            <td>
                                <?php echo e($store->zone?$store->zone->name:translate('messages.zone_deleted')); ?>

                            </td>
                            <td>
                                <label class="toggle-switch toggle-switch-sm" for="featuredCheckbox<?php echo e($store->id); ?>">
                                    <input type="checkbox" data-url="<?php echo e(route('admin.store.featured',[$store->id,$store->featured?0:1])); ?>" class="toggle-switch-input redirect-url" id="featuredCheckbox<?php echo e($store->id); ?>" <?php echo e($store->featured?'checked':''); ?>>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>

                            <td>
                                <?php if(isset($store->vendor->status)): ?>
                                    <?php if($store->vendor->status): ?>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox<?php echo e($store->id); ?>">
                                        <input type="checkbox" data-url="<?php echo e(route('admin.store.status',[$store->id,$store->status?0:1])); ?>" data-message="<?php echo e(translate('messages.you_want_to_change_this_store_status')); ?>" class="toggle-switch-input status_change_alert" id="stocksCheckbox<?php echo e($store->id); ?>" <?php echo e($store->status?'checked':''); ?>>
                                        <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                    <?php else: ?>
                                    <span class="badge badge-soft-danger"><?php echo e(translate('messages.denied')); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-soft-danger"><?php echo e(translate('messages.pending')); ?></span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--warning btn-outline-warning"
                                            href="<?php echo e(route('admin.store.view', $store->id)); ?>"
                                            title="<?php echo e(translate('messages.view')); ?>"><i
                                                class="tio-visible-outlined"></i>
                                        </a>
                                    <a class="btn action-btn btn--primary btn-outline-primary"
                                    href="<?php echo e(route('admin.store.edit',[$store['id']])); ?>" title="<?php echo e(translate('messages.edit_store')); ?>"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                    data-id="vendor-<?php echo e($store['id']); ?>" data-message="<?php echo e(translate('You want to remove this store')); ?>" title="<?php echo e(translate('messages.delete_store')); ?>"><i class="tio-delete-outlined"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.store.delete',[$store['id']])); ?>" method="post" id="vendor-<?php echo e($store['id']); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

            </div>
                <?php if(count($stores) !== 0): ?>
                <hr>
                <?php endif; ?>
                <div class="page-area">
                    <?php echo $stores->withQueryString()->links(); ?>

                </div>
                <?php if(count($stores) === 0): ?>
                <div class="empty--data">
                    <img src="<?php echo e(asset('/public/assets/admin/svg/illustrations/sorry.svg')); ?>" alt="public">
                    <h5>
                        <?php echo e(translate('no_data_found')); ?>

                    </h5>
                </div>
                <?php endif; ?>
            <!-- End Table -->
        </div>
        <!-- End Card -->
    </div>



    <div id="offcanvas__customBtn3" class="custom-offcanvas d-flex flex-column justify-content-between">
        <div class="d-flex flex-column flex-grow-1">
            <div class="custom-offcanvas-header bg-white d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                <h3 class="mb-0 fs-18 text-title fw-semibold"><?php echo e(translate('Verification Recommendations')); ?></h3>
                <button type="button"
                    class="btn-close w-25px h-25px border rounded-circle d-center bg-white text-dark offcanvas-close fz-15px p-0"
                    aria-label="Close">&times;</button>
            </div>
            <div class="custom-offcanvas-body p-4 d-flex flex-column gap-3">
                <p class="fs-14 lh-base color-5d6167 mb-0">
                    <?php echo e(translate('We have detected that')); ?> <strong><?php echo e(number_format($recommended_stores)); ?></strong>
                    <?php echo e(translate('stores have GOOD performance based on their overall activity. You can give them a Verified badge, which will appear next to the store name to build customer trust.')); ?>

                </p>

                <div class="bg--secondary rounded p-4">
                    <h4 class="mb-3 fs-18 text-title fw-semibold"><?php echo e(translate('They qualified the criteria')); ?></h4>

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-2">
                            <span class="d-center flex-shrink-0 mt-1 w-18px h-18px rounded-circle bg-success">
                                <i class="tio-done text-white fz-10px"></i>
                            </span>
                            <span class="fs-14 color-5d6167">
                                <strong class="text-title"><?php echo e(config('verified_seller.stores.minimum_total_orders', 10)); ?>+</strong> <?php echo e(translate('Orders completed')); ?>

                            </span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <span class="d-center flex-shrink-0 mt-1 w-18px h-18px rounded-circle bg-success">
                                <i class="tio-done text-white fz-10px"></i>
                            </span>
                            <span class="fs-14 color-5d6167">
                                <?php echo e(translate('Order completion rate above')); ?> <strong class="text-title"><?php echo e(config('verified_seller.stores.minimum_success_rate', 40)); ?>%</strong>
                            </span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <span class="d-center flex-shrink-0 mt-1 w-18px h-18px rounded-circle bg-success">
                                <i class="tio-done text-white fz-10px"></i>
                            </span>
                            <span class="fs-14 color-5d6167">
                                <strong class="text-title"><?php echo e(config('verified_seller.stores.minimum_account_age_months', 3)); ?>+</strong> <?php echo e(translate('months since account creation')); ?>

                            </span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <span class="d-center flex-shrink-0 mt-1 w-18px h-18px rounded-circle bg-success">
                                <i class="tio-done text-white fz-10px"></i>
                            </span>
                            <span class="fs-14 color-5d6167">
                                <strong class="text-title"><?php echo e(translate('Positive')); ?></strong> <?php echo e(translate('customer feedback trend')); ?>

                            </span>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <span class="d-center flex-shrink-0 mt-1 w-18px h-18px rounded-circle bg-success">
                                <i class="tio-done text-white fz-10px"></i>
                            </span>
                            <span class="fs-14 color-5d6167">
                                <strong class="text-title"><?php echo e(config('verified_seller.stores.minimum_avg_rating', 2)); ?>+</strong> <?php echo e(translate('Rating')); ?>/5.00
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="align-items-center bg-white bottom-0 d-flex gap-3 justify-content-center mt-auto offcanvas-footer p-3 position-sticky border-top">
            <button type="button" id="open-eligible-store-list" class="btn w-100 btn--reset h--40px"><?php echo e(translate('View List')); ?></button>
            <button type="button" id="verify-all-summary" class="btn w-100 btn--primary h--40px"><?php echo e(translate('Verify All')); ?></button>
        </div>
    </div>
    <div id="offcanvas__eligibleStores" class="custom-offcanvas d-flex flex-column justify-content-between">
        <div class="d-flex flex-column flex-grow-1">
            <div class="custom-offcanvas-header bg-white d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <h3 class="mb-0 fs-18 text-title fw-semibold d-flex align-items-center gap-2">
                        <span id="back-to-verification" class="d-inline-flex align-items-center justify-content-center w-20px h-20px rounded-circle bg-light">
                            <i class="tio-arrow-backward fs-12"></i>
                        </span>
                        <?php echo e(translate('Stores List')); ?>

                    </h3>
                    <span class="badge badge-soft-dark"><?php echo e($recommended_stores); ?></span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <label class="d-flex align-items-center gap-2 mb-0 cursor-pointer">
                        <input type="checkbox" id="select-all-eligible-stores" class="form-check-input mt-0">
                        <span class="fs-14 text-title"><?php echo e(translate('Select All')); ?></span>
                    </label>
                    <button type="button" class="btn-close w-25px h-25px border rounded-circle d-center bg-white text-dark offcanvas-close fz-15px p-0" aria-label="Close">&times;</button>
                </div>
            </div>
            <div class="custom-offcanvas-body p-3 p-md-4 d-flex flex-column gap-3" id="eligible-store-list">
                <?php $__currentLoopData = $recommended_store_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-10 p-3 eligible-store-item">
                        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    <img class="w-50px h-50px rounded-circle border object-fit-cover onerror-image"
                                         data-onerror-image="<?php echo e(asset('public/assets/admin/img/160x160/img1.jpg')); ?>"
                                         src="<?php echo e($store['logo_full_url']); ?>">
                                </div>
                                <div>
                                    <h4 class="mb-1 fs-16 text-title fw-semibold"><?php echo e($store['name']); ?></h4>
                                    <div class="d-flex flex-wrap gap-2 fs-13 color-6c757d">
                                        <span><?php echo e(translate('messages.Rating')); ?> <?php echo e(number_format($store['avg_rating'], 1)); ?>/5</span>
                                        <span><?php echo e(translate('messages.Order')); ?> <?php echo e(number_format($store['total_orders'])); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="eligible-action-wrapper d-flex align-items-center justify-content-end min-w-110px">
                                <a href="<?php echo e(route('admin.store.verified-seller', [$store['id']])); ?>" class="btn btn--primary btn-sm min-w-110px eligible-give-btn">
                                    <i class="tio-done mr-1"></i><?php echo e(translate('Give Badge')); ?>

                                </a>
                                <label class="form-check m-0 d-none eligible-store-check">
                                    <input type="checkbox" class="form-check-input mt-0" checked disabled>
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <div id="eligible-store-footer" class=" d-none">
            <div class="align-items-center bg-white bottom-0 d-flex gap-3 justify-content-center mt-auto offcanvas-footer p-3 position-sticky border-top">
                <div class="d-flex gap-3 w-100 justify-content-center">
                    <button type="button" class="btn w-100 btn--reset offcanvas-close h--40px"><?php echo e(translate('Cancel')); ?></button>
                    <button type="button" id="verify-all-stores" class="btn w-100 btn--primary h--40px"><?php echo e(translate('Verify All')); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="offcanvasOverlay" class="offcanvas-overlay"></div>
    <div class="modal shedule-modal fade" id="verify-all-modal" tabindex="-1" aria-labelledby="verifyAllModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pb-2 max-w-500">
                <div class="modal-header">
                    <button type="button"
                        class="close bg-modal-btn w-30px h-30 rounded-circle position-absolute right-0 top-0 m-2 z-2"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img src="<?php echo e(asset('public/assets/admin/img/badge-big.png')); ?>" alt="icon" class="mb-3">
                        <h3 class="mb-2"><?php echo e(translate('Verify all qualified stores?')); ?></h3>
                        <p class="mb-0"><?php echo e(translate('This will give a verified badge to every store that matches the verification criteria.')); ?></p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0 gap-2">
                    <button type="button" class="btn min-w-120px btn--reset" data-dismiss="modal"><?php echo e(translate('messages.Cancel')); ?></button>
                    <a href="<?php echo e(route('admin.store.verified-seller-all')); ?>" class="btn min-w-120px btn--primary"><?php echo e(translate('messages.Yes')); ?></a>
                </div>
            </div>
        </div>
    </div>



<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script>
        "use strict";
        function resetEligibleStoreSelection() {
            $('#select-all-eligible-stores').prop('checked', false);
            $('#eligible-store-footer').addClass('d-none');
            $('#eligible-store-list .eligible-store-item').removeClass('border border-success bg-success bg-opacity-10');
            $('#eligible-store-list .eligible-give-btn').removeClass('d-none');
            $('#eligible-store-list .eligible-store-check').addClass('d-none');
        }

        $(document).on('click', '#open-eligible-store-list', function (e) {
            e.preventDefault();
            $('#offcanvas__customBtn3').removeClass('open');
            $('#offcanvas__eligibleStores').addClass('open');
            $('#offcanvasOverlay').addClass('show');
            resetEligibleStoreSelection();
        });

        $(document).on('click', '#back-to-verification', function (e) {
            e.preventDefault();
            $('#offcanvas__eligibleStores').removeClass('open');
            $('#offcanvas__customBtn3').addClass('open');
            $('#offcanvasOverlay').addClass('show');
            resetEligibleStoreSelection();
        });

        $(document).on('change', '#select-all-eligible-stores', function () {
            const checked = $(this).is(':checked');
            $('#eligible-store-list .eligible-store-item').toggleClass('border border-success bg-success bg-opacity-10', checked);
            $('#eligible-store-list .eligible-give-btn').toggleClass('d-none', checked);
            $('#eligible-store-list .eligible-store-check').toggleClass('d-none', !checked);
            $('#eligible-store-footer').toggleClass('d-none', !checked);
        });

        $(document).on('click', '#verify-all-stores', function (e) {
            e.preventDefault();
            $('#offcanvas__eligibleStores').removeClass('open');
            $('#offcanvas__customBtn3').removeClass('open');
            $('#offcanvasOverlay').removeClass('show');
            $('#verify-all-modal').modal('show');
        });

        $(document).on('click', '#verify-all-summary', function (e) {
            e.preventDefault();
            $('#offcanvas__customBtn3').removeClass('open');
            $('#offcanvas__eligibleStores').removeClass('open');
            $('#offcanvasOverlay').removeClass('show');
            resetEligibleStoreSelection();
            $('#verify-all-modal').modal('show');
        });

        $(document).on('click', '.offcanvas-close, #offcanvasOverlay', function () {
            resetEligibleStoreSelection();
        });

        $('.status_change_alert').on('click', function (event) {
            let url = $(this).data('url');
            let message = $(this).data('message');
            status_change_alert(url, message, event)
        })

        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '<?php echo e(translate('Are you sure?')); ?>' ,
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '<?php echo e(translate('messages.no')); ?>',
                confirmButtonText: '<?php echo e(translate('messages.yes')); ?>',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href=url;
                }
            })
        }

    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\mytijaara\resources\views/admin-views/vendor/list.blade.php ENDPATH**/ ?>