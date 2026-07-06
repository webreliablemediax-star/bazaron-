<ul class="tt-side-nav">

    <!-- dashboard -->
    <li class="side-nav-item nav-item">
        <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
            <span class="tt-nav-link-icon"><i data-feather="pie-chart"></i></span>
            <span class="tt-nav-link-text">{{ localize('Dashboard') }}</span>
        </a>
    </li>

    <!-- products -->
    @php
        $productsActiveRoutes = [
            'admin.brands.index',
            'admin.brands.edit',
            'admin.units.index',
            'admin.units.edit',
            'admin.variations.index',
            'admin.variations.edit',
            'admin.variationValues.index',
            'admin.variationValues.edit',
            'admin.taxes.index',
            'admin.taxes.edit',
            'admin.categories.index',
            'admin.categories.create',
            'admin.categories.edit',
            'admin.products.index',
            'admin.products.create',
            'admin.products.edit',
        ];
    @endphp
    @php
        $pendingSellers = \App\Models\User::where('user_type', 'vendor')->where('vendor_status', 'pending')->count();
    @endphp

    @canany(['products', 'categories', 'variations', 'brands', 'units', 'taxes'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($productsActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#sidebarProducts"
                aria-expanded="{{ areActiveRoutes($productsActiveRoutes, 'true') }}" aria-controls="sidebarProducts"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="shopping-bag"></i></span>
                <span class="tt-nav-link-text">{{ localize('Inventory') }}</span>
            </a>

            <div class="collapse {{ areActiveRoutes($productsActiveRoutes, 'show') }}" id="sidebarProducts">
                <ul class="side-nav-second-level">

                    @can('products')
                        <li
                            class="{{ areActiveRoutes(['admin.products.index', 'admin.products.create', 'admin.products.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.products.index') }}"
                                class="{{ areActiveRoutes(['admin.products.index', 'admin.products.create', 'admin.products.edit']) }}">{{ localize('All Products') }}</a>
                        </li>
                    @endcan

                    @can('dilevery_charges')
                        <li class="{{ areActiveRoutes(['admin.dilevery-charges.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.dilevery-charges.edit') }}"
                                class="{{ areActiveRoutes(['admin.dilevery-charges.edit']) }}">Dilevery Charges</a>
                        </li>
                    @endcan

                    @can('categories')
                        <li
                            class="{{ areActiveRoutes(['admin.categories.index', 'admin.categories.create', 'admin.categories.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.categories.index') }}"
                                class="{{ areActiveRoutes(['admin.categories.index', 'admin.categories.create', 'admin.categories.edit']) }}">{{ localize('All Categories') }}</a>
                        </li>
                    @endcan

                    @can('variations')
                        <li
                            class="{{ areActiveRoutes(
                                ['admin.variations.index', 'admin.variations.edit', 'admin.variationValues.index', 'admin.variationValues.edit'],
                                'tt-menu-item-active',
                            ) }}">
                            <a href="{{ route('admin.variations.index') }}"
                                class="{{ areActiveRoutes([
                                    'admin.variations.index',
                                    'admin.variations.edit',
                                    'admin.variationValues.index',
                                    'admin.variationValues.edit',
                                ]) }}">{{ localize('All Variations') }}</a>
                        </li>
                    @endcan

                    @if (auth()->user()->user_type == 'vendor')
                        <li class="{{ areActiveRoutes(['vendor.brands.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('vendor.brands.index') }}"
                                class="{{ areActiveRoutes(['vendor.brands.index']) }}">
                                {{ localize('My Brands') }}
                            </a>
                        </li>
                    @elseif(auth()->user()->user_type == 'admin')
                        @can('brands')
                            <li
                                class="{{ areActiveRoutes(['admin.brands.index', 'admin.brands.edit'], 'tt-menu-item-active') }}">
                                <a href="{{ route('admin.brands.index') }}"
                                    class="{{ areActiveRoutes(['admin.brands.index', 'admin.brands.edit']) }}">
                                    {{ localize('All Brands') }}
                                </a>
                            </li>
                        @endcan
                    @endif





                    @can('units')
                        <li class="{{ areActiveRoutes(['admin.units.index', 'admin.units.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.units.index') }}"
                                class="{{ areActiveRoutes(['admin.units.index']) }}">{{ localize('All Units') }}</a>
                        </li>
                    @endcan

                    @can('taxes')
                        <li class="{{ areActiveRoutes(['admin.taxes.index', 'admin.taxes.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.taxes.index') }}"
                                class="{{ areActiveRoutes(['admin.taxes.index']) }}">{{ localize('All Taxes') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan

    <!-- orders -->
    @can('orders')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.orders.index', 'admin.orders.show'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.orders.index') }}"
                class="side-nav-link {{ areActiveRoutes(['admin.orders.index', 'admin.orders.show']) }}">
                <span class="tt-nav-link-icon"><i data-feather="shopping-cart"></i></span>
                <span class="tt-nav-link-text">
                    <span>{{ localize('Orders') }}</span>

                    @php
                        $newOrdersCount = \App\Models\Order::isPlaced()->count();
                    @endphp

                    @if ($newOrdersCount > 0)
                        <small class="badge bg-danger">{{ localize('New') }}</small>
                    @endif
                </span>
            </a>
        </li>
    @endcan




    <!-- stock -->
    @php
        $stockActiveRoutes = [
            'admin.stocks.create',
            'admin.locations.index',
            'admin.locations.create',
            'admin.locations.edit',
        ];
    @endphp
    @canany(['add_stock', 'show_locations'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($stockActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#manageStock"
                aria-expanded="{{ areActiveRoutes($stockActiveRoutes, 'true') }}" aria-controls="manageStock"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="database"></i></span>
                <span class="tt-nav-link-text">{{ localize('Stocks') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($stockActiveRoutes, 'show') }}" id="manageStock">
                <ul class="side-nav-second-level">

                    @can('add_stock')
                        <li class="{{ areActiveRoutes(['admin.stocks.create'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.stocks.create') }}"
                                class="{{ areActiveRoutes(['admin.stocks.create']) }}">{{ localize('Add Stock') }}</a>
                        </li>
                    @endcan

                    @can('show_locations')
                        <li
                            class="{{ areActiveRoutes(['admin.locations.index', 'admin.locations.create', 'admin.locations.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.locations.index') }}">{{ localize('All Locations') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan
    <!-- Users -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('Users') }}</span>
    </li>

    <!-- customers -->
    @can('customers')
        <li class="side-nav-item nav-item">
            <a href="{{ route('admin.customers.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="users"></i></span>
                <span class="tt-nav-link-text">{{ localize('Customers') }}</span>
            </a>
        </li>
    @endcan
    {{-- 
    @can('vendor_approval')
        <li class="side-nav-item nav-item">
            <a href="{{ route('admin.vendors.index') }}" class="side-nav-link">
                <i class="fa fa-store me-2"></i>
                <span class="tt-nav-link-text d-flex justify-content-between align-items-center">
                    <span>Sellers Approval</span>

                    @if ($pendingSellers > 0)
                        <span class="badge bg-danger rounded-pill">
                            {{ $pendingSellers }}
                        </span>
                    @endif
                </span>
            </a>
        </li>
    @endcan
    --}}

    @if (auth()->user()->user_type == 'admin')
        <li class="side-nav-item nav-item">
            <a href="{{ route('admin.vendors.list') }}" class="side-nav-link">
                <i class="fa fa-users me-2"></i>
                <span class="tt-nav-link-text">Sellers List</span>
            </a>
        </li>

        <li class="side-nav-item nav-item">
            <a class="side-nav-link" href="{{ route('admin.vendor.profile.requests') }}">
                <i class="fa fa-user-edit me-2"></i>
                <span class="tt-nav-link-text">
                    Seller Profile Requests
                </span>
            </a>
        </li>


        <li class="side-nav-item nav-item">
            <a class="side-nav-link" href="{{ route('admin.purchase.quantity.requests') }}">

                <i class="fa fa-sort-numeric-up me-2"></i>

                <span class="tt-nav-link-text">
                    Purchase Quantity Requests
                </span>
            </a>
        </li>
        <li class="side-nav-item nav-item">
    <a class="side-nav-link"
       href="{{ route('admin.variation.requests') }}">

        <i class="fa fa-code-branch me-2"></i>

        <span class="tt-nav-link-text">
            Variation Requests
        </span>
    </a>
</li>
    @endif
    @can('pincodes')
        <li class="side-nav-item nav-item">
            <a class="side-nav-link" href="{{ route('admin.pincodes.index') }}">
                <i class="fa fa-store me-2"></i>
                <span class="tt-nav-link-text">{{ localize('Pincode Management') }}</span>
            </a>
        </li>
    @endcan

    @php
        $vendorProfile = \App\Models\VendorProfile::where('user_id', auth()->id())->first();
    @endphp
    @if (auth()->user()->user_type == 'vendor')
        <li class="{{ areActiveRoutes(['vendor.settlements.index']) }}" class="side-nav-item nav-item">
            <a href="{{ route('vendor.settlements.index') }}" class="side-nav-link">
                <i class="las la-wallet"></i>
                <span class="tt-nav-link-text">Settlement page</span>
            </a>
        </li>
        @if ($vendorProfile && $vendorProfile->has_own_logistics == 1)
            <li class="side-nav-item nav-item">
                <a class="side-nav-link" href="{{ route('vendor.pincodes.index') }}">
                    <i class="fa fa-map-marker me-2"></i>
                    <span class="tt-nav-link-text">Manage Pincodes</span>
                </a>
            </li>
        @endif
        <li class="side-nav-item nav-item">
            <a class="side-nav-link" href="{{ route('vendor.manage.address') }}">
                <i class="fa fa-home me-2"></i>
                <span class="tt-nav-link-text">Manage Address</span>
            </a>
        </li>
        <li class="side-nav-item nav-item">
            <a class="side-nav-link" href="{{ route('vendor.invoice.config') }}">
                <i class="fa fa-file-invoice me-2"></i>
                <span class="tt-nav-link-text">Invoice Configuration</span>
            </a>
        </li>

    @endif
 @if (auth()->user()->user_type == 'vendor')
   <li class="side-nav-item nav-item">
    <a class="side-nav-link" href="{{ route('vendor.request.approvals') }}">
        <i class="fa fa-check-circle me-2"></i>
        <span class="tt-nav-link-text">Request Approval</span>
    </a>
</li>
@endif



    <!-- staffs -->
    @can('staffs')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.staffs.index', 'admin.staffs.create', 'admin.staffs.edit'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.staffs.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="user-check"></i></span>
                <span class="tt-nav-link-text">{{ localize('Employee Staffs') }}</span>
            </a>
        </li>
    @endcan





    <!-- Contents -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('Contents') }}</span>
    </li>

    <!-- pages -->
    @php
        $pagesActiveRoutes = ['admin.pages.index', 'admin.pages.create', 'admin.pages.edit'];
    @endphp
    @can('pages')
        <li class="side-nav-item nav-item {{ areActiveRoutes($pagesActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.pages.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="copy"></i></span>
                <span class="tt-nav-link-text">{{ localize('Pages') }}</span>
            </a>
        </li>
    @endcan


    <!-- seller page -->

    @if (auth()->user()->user_type == 'admin')
        <li class="side-nav-item nav-item {{ areActiveRoutes(['admin.seller-page.index'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.seller-page.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"><i data-feather="user-plus"></i></span>
                <span class="tt-nav-link-text">Seller Page</span>
            </a>
        </li>
    @endif
    
     @if (auth()->user()->user_type == 'admin')
        <li class="side-nav-item nav-item {{ areActiveRoutes(['instractions.index'], 'tt-menu-item-active') }}">
            <a href="{{ route('instraction.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"><i data-feather="file-text"></i></span>
                <span class="tt-nav-link-text">Seller Onboarding Instructions</span>
            </a>
        </li>
    @endif

    <!-- Blog Systems -->
    @php
        $blogActiveRoutes = [
            'admin.blogs.index',
            'admin.blogs.create',
            'admin.blogs.edit',
            'admin.blogCategories.index',
            'admin.blogCategories.edit',
        ];
    @endphp
    @canany(['blogs', 'blog_categories'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($blogActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#blogSystem"
                aria-expanded="{{ areActiveRoutes($blogActiveRoutes, 'true') }}" aria-controls="blogSystem"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="file-text"></i></span>
                <span class="tt-nav-link-text">{{ localize('Blogs') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($blogActiveRoutes, 'show') }}" id="blogSystem">
                <ul class="side-nav-second-level">
                    @can('blogs')
                        <li
                            class="{{ areActiveRoutes(['admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.blogs.index') }}"
                                class="{{ areActiveRoutes(['admin.blogs.index', 'admin.blogs.create', 'admin.blogs.edit']) }}">{{ localize('All Blogs') }}</a>
                        </li>
                    @endcan

                    @can('blog_categories')
                        <li
                            class="{{ areActiveRoutes(['admin.blogCategories.index', 'admin.blogCategories.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.blogCategories.index') }}">{{ localize('Categories') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan

    <!-- media manager -->
    @can('media_manager')
        <li class="side-nav-item">
            <a href="{{ route('admin.mediaManager.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="folder"></i></span>
                <span class="tt-nav-link-text">{{ localize('Media Manager') }}</span>
            </a>
        </li>
    @endcan
    @if (auth()->user()->user_type == 'admin')
        <li class="side-nav-item">
            <a href="{{ route('admin.video.manager') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="video"></i></span>
                <span class="tt-nav-link-text">{{ localize('Video Manager') }}</span>
            </a>
        </li>
    @endif
    <!-- Promotions -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('Promotions') }}</span>
    </li>
    <!-- newsletter -->
    @php
        $newsletterActiveRoutes = ['admin.newsletters.index', 'admin.subscribers.index'];
    @endphp
    @canany(['newsletters', 'subscribers'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($newsletterActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#newsletter"
                aria-expanded="{{ areActiveRoutes($newsletterActiveRoutes, 'true') }}" aria-controls="newsletter"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="map"></i></span>
                <span class="tt-nav-link-text">{{ localize('Newsletters') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($newsletterActiveRoutes, 'show') }}" id="newsletter">
                <ul class="side-nav-second-level">

                    @can('newsletters')
                        <li class="{{ areActiveRoutes(['admin.newsletters.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.newsletters.index') }}"
                                class="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Bulk Emails') }}</a>
                        </li>
                    @endcan

                    @can('subscribers')
                        <li class="{{ areActiveRoutes(['admin.subscribers.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.subscribers.index') }}"
                                lass="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Subscribers') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan

    <!-- Coupons -->
    @can('coupons')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.coupons.index', 'admin.coupons.create', 'admin.coupons.edit'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.coupons.index') }}"
                class="side-nav-link {{ areActiveRoutes(['admin.coupons.index', 'admin.coupons.create', 'admin.coupons.edit']) }}">
                <span class="tt-nav-link-icon"> <i data-feather="scissors"></i></span>
                <span class="tt-nav-link-text">{{ localize('Coupons') }}</span>
            </a>
        </li>
    @endcan

    <!-- campaigns -->
    {{-- @can('campaigns')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.campaigns.index', 'admin.campaigns.create', 'admin.campaigns.edit'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.campaigns.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"> <i data-feather="zap"></i></span>
                <span class="tt-nav-link-text">{{ localize('Campaigns') }}</span>
            </a>
        </li>
    @endcan --}}

    <!-- Fulfillment -->
    {{-- <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('Fulfillment') }}</span>
    </li> --}}
    <!-- Logistics -->
    @can('logistics')
        <li
            class="side-nav-item nav-item {{ areActiveRoutes(['admin.logistics.index', 'admin.logistics.create', 'admin.logistics.edit'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.logistics.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"><i data-feather="cpu"></i></span>
                <span class="tt-nav-link-text">{{ localize('Logistics') }}</span>
            </a>
        </li>
    @endcan

    <!-- shipping zones -->
    {{-- @php
        $logisticZoneActiveRoutes = ['admin.logisticZones.index', 'admin.logisticZones.create', 'admin.logisticZones.edit', 'admin.countries.index', 'admin.states.index', 'admin.states.create', 'admin.states.edit', 'admin.cities.index', 'admin.cities.create', 'admin.cities.edit'];
    @endphp
    @can('shipping_zones')
        <li class="side-nav-item nav-item {{ areActiveRoutes($logisticZoneActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.logisticZones.index') }}" class="side-nav-link">
                <i class="uil-ship"></i>
                <span class="tt-nav-link-icon"><i data-feather="truck"></i></span>
                <span class="tt-nav-link-text">{{ localize('Shipping Zones') }}</span>
            </a>
        </li>
    @endcan --}}

    <!-- Reports -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('Reports') }}</span>
    </li>

    <!-- reports -->
    @php
        $reportActiveRoutes = [
            'admin.reports.orders',
            'admin.reports.sales',
            'admin.reports.categorySales',
            'admin.reports.salesAmount',
            'admin.reports.deliveryStatus',
        ];
    @endphp

    @canany(['order_reports', 'product_sale_reports', 'category_sale_reports', 'sales_amount_reports',
        'delivery_status_reports'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($reportActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#reports"
                aria-expanded="{{ areActiveRoutes($reportActiveRoutes, 'true') }}" aria-controls="reports"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="bar-chart"></i></span>
                <span class="tt-nav-link-text">{{ localize('Reports') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($reportActiveRoutes, 'show') }}" id="reports">
                <ul class="side-nav-second-level">

                    @can('order_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.orders'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.orders') }}"
                                class="{{ areActiveRoutes(['admin.reports.orders']) }}">{{ localize('Orders Report') }}</a>
                        </li>
                    @endcan

                    @can('product_sale_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.sales'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.sales') }}"
                                class="{{ areActiveRoutes(['admin.reports.sales']) }}">{{ localize('Product Sales') }}</a>
                        </li>
                    @endcan

                    @can('category_sale_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.categorySales'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.categorySales') }}"
                                class="{{ areActiveRoutes(['admin.reports.categorySales']) }}">{{ localize('Category Wise Sales') }}</a>
                        </li>
                    @endcan

                    @can('sales_amount_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.salesAmount'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.salesAmount') }}"
                                class="{{ areActiveRoutes(['admin.reports.salesAmount']) }}">{{ localize('Sales Amount Report') }}</a>
                        </li>
                    @endcan

                    @can('delivery_status_reports')
                        <li class="{{ areActiveRoutes(['admin.reports.deliveryStatus'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.reports.deliveryStatus') }}"
                                class="{{ areActiveRoutes(['admin.reports.deliveryStatus']) }}">{{ localize('Delivery Status Report') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan


    <!-- Support -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('Support') }}</span>
    </li>

    @can('contact_us_messages')
        <li class="side-nav-item nav-item {{ areActiveRoutes(['admin.queries.index'], 'tt-menu-item-active') }}">
            <a href="{{ route('admin.queries.index') }}"
                class="side-nav-link {{ areActiveRoutes(['admin.queries.index']) }}">
                <span class="tt-nav-link-icon"><i data-feather="hash"></i></span>
                <span class="tt-nav-link-text">
                    <span>{{ localize('Queries') }}</span>

                    @php
                        $newMsgCount = \App\Models\ContactUsMessage::where('is_seen', 0)->count();
                    @endphp

                    @if ($newMsgCount > 0)
                        <small class="badge bg-danger">{{ localize('New') }}</small>
                    @endif
                </span>
            </a>
        </li>
    @endcan

    <!-- Settings -->
    <li class="side-nav-title side-nav-item nav-item mt-3">
        <span class="tt-nav-title-text">{{ localize('Settings') }}</span>
    </li>
     @if(auth()->user()->user_type == 'vendor')

<li class="side-nav-item nav-item">
    <a class="side-nav-link" href="{{ route('vendor.profile.settings') }}">
        <i class="fa fa-cog me-2"></i>
        <span class="tt-nav-link-text">Profile Settings</span>
    </a>
</li>

@endif
   <li class="side-nav-item nav-item">
        <a class="side-nav-link" href="{{ route('vendor.shipment.settings') }}">
            <i class="fa fa-truck me-2"></i>
            <span class="tt-nav-link-text">Shipment Settings</span>
        </a>
    </li>
     @if (auth()->user()->user_type == 'admin')
    <li class="side-nav-item nav-item">
    <a class="side-nav-link"
       href="{{ route('admin.delivery.settings') }}">

        <i class="fa fa-shipping-fast me-2"></i>

        <span class="tt-nav-link-text">
            Delivery Settings
        </span>
    </a>
</li>
@endif

    {{-- shipping --}}
    @if (auth()->user()->user_type == 'admin')
        <li class="side-nav-title side-nav-item nav-item mt-3">
            <span class="tt-nav-title-text">
                <i class="las la-truck"></i> {{ localize('Shipping') }}</span>
        </li>

        <!-- shipping methods -->
        <li
            class="side-nav-item nav-item
    {{ areActiveRoutes(['admin.shipping.weight'], 'tt-menu-item-active') }}">

            <a href="{{ route('admin.shipping.weight') }}"
                class="side-nav-link
       {{ areActiveRoutes(['admin.shipping.weight']) }}">

                <span class="tt-nav-link-icon">
                    <i data-feather="package"></i>
                </span>

                <span class="tt-nav-link-text">
                    Weight
                </span>

            </a>
        </li>

        <li
            class="side-nav-item nav-item
                {{ areActiveRoutes(['admin.shipping.zone'], 'tt-menu-item-active') }}">

            <a href="{{ route('admin.shipping.zone') }}"
                class="side-nav-link
           {{ areActiveRoutes(['admin.shipping.zone']) }}">

                <span class="tt-nav-link-icon">
                    <i data-feather="map"></i>
                </span>

                <span class="tt-nav-link-text">
                    Zone
                </span>

            </a>
        </li>

        <li
            class="side-nav-item nav-item
    {{ areActiveRoutes(['admin.shipping.charge'], 'tt-menu-item-active') }}">

            <a href="{{ route('admin.shipping.charge') }}"
                class="side-nav-link
       {{ areActiveRoutes(['admin.shipping.charge']) }}">

                <span class="tt-nav-link-icon">
                    <i data-feather="truck"></i>
                </span>

                <span class="tt-nav-link-text">
                    Shipping Charges
                </span>

            </a>
        </li>


        <li class="side-nav-item nav-item
    {{ areActiveRoutes(['admin.gst.index'], 'tt-menu-item-active') }}">

            <a href="{{ route('admin.gst.index') }}"
                class="side-nav-link
        {{ areActiveRoutes(['admin.gst.index']) }}">

                <span class="tt-nav-link-icon">
                    <i data-feather="percent"></i>
                </span>

                <span class="tt-nav-link-text">
                    GST
                </span>

            </a>

        </li>


        <li
            class="side-nav-item nav-item
    {{ areActiveRoutes(['admin.payment.gateway.index'], 'tt-menu-item-active') }}">

            <a href="{{ route('admin.payment.gateway.index') }}"
                class="side-nav-link
        {{ areActiveRoutes(['admin.payment.gateway.index']) }}">

                <span class="tt-nav-link-icon">
                    <i data-feather="credit-card"></i>
                </span>

                <span class="tt-nav-link-text">
                    Payment Gateway
                </span>

            </a>

        </li>

        <li class="side-nav-item nav-item
    {{ areActiveRoutes(['admin.tsd.index'], 'tt-menu-item-active') }}">

            <a href="{{ route('admin.tsd.index') }}"
                class="side-nav-link
        {{ areActiveRoutes(['admin.tsd.index']) }}">

                <span class="tt-nav-link-icon">
                    <i data-feather="percent"></i>
                </span>

                <span class="tt-nav-link-text">
                    TDS
                </span>

            </a>

        </li>
    @endif
    <!-- affiliateSystem -->
    {{-- @php
        $affiliateSystemActiveRoutes = ['admin.newsletters.aasd'];
    @endphp
    @canany(['newsletters', 'subscribers'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($affiliateSystemActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#affiliateSystem"
                aria-expanded="{{ areActiveRoutes($affiliateSystemActiveRoutes, 'true') }}"
                aria-controls="affiliateSystem" class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="percent"></i></span>
                <span class="tt-nav-link-text">{{ localize('Affiliate System') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($affiliateSystemActiveRoutes, 'show') }}" id="affiliateSystem">
                <ul class="side-nav-second-level">
                    <li class="{{ areActiveRoutes(['admin.affiliate.configurations'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.affiliate.configurations') }}"
                            class="{{ areActiveRoutes(['admin.affiliate.configurations']) }}">{{ localize('Configurations') }}</a>
                    </li>

                    <li class="{{ areActiveRoutes(['admin.subscribers.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.subscribers.index') }}"
                            lass="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Withdraw Request') }}</a>
                    </li>

                    <li class="{{ areActiveRoutes(['admin.subscribers.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.subscribers.index') }}"
                            lass="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Earning Histories') }}</a>
                    </li>

                    <li class="{{ areActiveRoutes(['admin.subscribers.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.subscribers.index') }}"
                            lass="{{ areActiveRoutes(['admin.newsletters.index']) }}">{{ localize('Payment Histories') }}</a>
                    </li>
                </ul>
            </div>
        </li>
    @endcan --}}

    <!-- Appearance -->
    @php
        $appearanceActiveRoutes = [
            'admin.appearance.header',
            'admin.appearance.homepage.hero',
            'admin.appearance.homepage.editHero',
            'admin.appearance.homepage.topCategories',
            'admin.appearance.homepage.topTrendingProducts',
            'admin.appearance.homepage.featuredProducts',
            'admin.appearance.homepage.bannerOne',
            'admin.appearance.homepage.editBannerOne',
            'admin.appearance.homepage.bestDeals',
            'admin.appearance.homepage.bannerTwo',
            'admin.appearance.homepage.clientFeedback',
            'admin.appearance.homepage.editClientFeedback',
            'admin.appearance.homepage.bestSelling',
            'admin.appearance.homepage.customProductsSection',
            'admin.appearance.products.index',
            'admin.appearance.products.details',
            'admin.appearance.products.details.editWidget',
            'admin.appearance.about-us.popularBrands',
            'admin.appearance.about-us.features',
            'admin.appearance.about-us.editFeatures',
            'admin.appearance.about-us.whyChooseUs',
            'admin.appearance.about-us.editWhyChooseUs',
        ];

        $homepageActiveRoutes = [
            'admin.appearance.homepage.hero',
            'admin.appearance.homepage.editHero',
            'admin.appearance.homepage.topCategories',
            'admin.appearance.homepage.topTrendingProducts',
            'admin.appearance.homepage.featuredProducts',
            'admin.appearance.homepage.bannerOne',
            'admin.appearance.homepage.editBannerOne',
            'admin.appearance.homepage.bestDeals',
            'admin.appearance.homepage.bannerTwo',
            'admin.appearance.homepage.clientFeedback',
            'admin.appearance.homepage.editClientFeedback',
            'admin.appearance.homepage.bestSelling',
            'admin.appearance.homepage.customProductsSection',
        ];

    @endphp

    @canany(['homepage', 'product_page', 'product_details_page', 'about_us_page', 'header', 'footer'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($appearanceActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#Appearance"
                aria-expanded="{{ areActiveRoutes($appearanceActiveRoutes, 'true') }}" aria-controls="Appearance"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="layout"></i></span>
                <span class="tt-nav-link-text">{{ localize('Appearance') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($appearanceActiveRoutes, 'show') }}" id="Appearance">
                <ul class="side-nav-second-level">

                    @can('homepage')
                        <li class="{{ areActiveRoutes($homepageActiveRoutes, 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.homepage.hero') }}"
                                class="{{ areActiveRoutes($homepageActiveRoutes) }}">{{ localize('Homepage') }}</a>
                        </li>
                    @endcan
                    @can('mega_menu_columns')
                        <li class="side-nav-item nav-item">
                            <a class="side-nav-link" href="{{ route('admin.mega_menu_columns.index') }}">
                                <i class="fa fa-th-large"></i>
                                <span class="tt-nav-link-text">{{ localize('- Mega Menu Columns') }}</span>
                            </a>
                        </li>
                    @endcan


                    @can('product_page')
                        <li class="{{ areActiveRoutes(['admin.appearance.products.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.products.index') }}"
                                class="{{ areActiveRoutes(['admin.appearance.products.index']) }}">{{ localize('Products Page') }}</a>
                        </li>
                    @endcan

                    @can('product_details_page')
                        <li
                            class="{{ areActiveRoutes(['admin.appearance.products.details', 'admin.appearance.products.details.editWidget'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.products.details') }}"
                                class="{{ areActiveRoutes(['admin.appearance.products.details']) }}">{{ localize('Product Details') }}</a>
                        </li>
                    @endcan

                    @can('about_us_page')
                        @php
                            $aboutUsActiveRoutes = [
                                'admin.appearance.about-us.index',
                                'admin.appearance.about-us.popularBrands',
                                'admin.appearance.about-us.features',
                                'admin.appearance.about-us.editFeatures',
                                'admin.appearance.about-us.whyChooseUs',
                                'admin.appearance.about-us.editWhyChooseUs',
                            ];
                        @endphp

                        <li class="{{ areActiveRoutes($aboutUsActiveRoutes, 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.about-us.index') }}"
                                class="{{ areActiveRoutes($aboutUsActiveRoutes) }}">{{ localize('About Us') }}</a>
                        </li>
                    @endcan

                    @can('header')
                        <li class="{{ areActiveRoutes(['admin.appearance.header'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.header') }}"
                                class="{{ areActiveRoutes(['admin.appearance.header']) }}">{{ localize('Header') }}</a>
                        </li>
                    @endcan

                    @can('footer')
                        <li class="{{ areActiveRoutes(['admin.appearance.footer'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.appearance.footer') }}"
                                class="{{ areActiveRoutes(['admin.appearance.footer']) }}">{{ localize('Footer') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcanany


    <!-- Roles & Permission -->
    @php
        $rolesActiveRoutes = ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit'];
    @endphp
    @can('roles_and_permissions')
        <li class="side-nav-item nav-item {{ areActiveRoutes($rolesActiveRoutes, 'tt-menu-item-active') }}">
            <a href="{{ route('admin.roles.index') }}" class="side-nav-link">
                <span class="tt-nav-link-icon"><i data-feather="unlock"></i></span>
                <span class="tt-nav-link-text">{{ localize('Roles & Permissions') }}</span>
            </a>
        </li>
    @endcan


    <!-- system settings -->
    @php
        $settingsActiveRoutes = [
            'admin.generalSettings',
            'admin.orderSettings',
            'admin.timeslot.edit',
            'admin.languages.index',
            'admin.languages.edit',
            'admin.currencies.index',
            'admin.currencies.edit',
            'admin.languages.localizations',
            'admin.smtpSettings.index',
        ];
    @endphp

    @canany(['smtp_settings', 'general_settings', 'currency_settings', 'language_settings'])
        <li class="side-nav-item nav-item {{ areActiveRoutes($settingsActiveRoutes, 'tt-menu-item-active') }}">
            <a data-bs-toggle="collapse" href="#systemSetting"
                aria-expanded="{{ areActiveRoutes($settingsActiveRoutes, 'true') }}" aria-controls="systemSetting"
                class="side-nav-link tt-menu-toggle">
                <span class="tt-nav-link-icon"><i data-feather="settings"></i></span>
                <span class="tt-nav-link-text">{{ localize('System Settings') }}</span>
            </a>
            <div class="collapse {{ areActiveRoutes($settingsActiveRoutes, 'show') }}" id="systemSetting">
                <ul class="side-nav-second-level">

                    @can('auth_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.authSettings'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.authSettings') }}"
                                class="{{ areActiveRoutes(['admin.settings.authSettings']) }}">{{ localize('Auth Settings') }}</a>
                        </li>
                    @endcan

                    @can('otp_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.otpSettings'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.otpSettings') }}"
                                class="{{ areActiveRoutes(['admin.settings.otpSettings']) }}">{{ localize('OTP Settings') }}</a>
                        </li>
                    @endcan

                    @can('order_settings')
                        <li
                            class="{{ areActiveRoutes(['admin.orderSettings', 'admin.timeslot.edit'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.orderSettings') }}"
                                class="{{ areActiveRoutes(['admin.generalSettings']) }}">{{ localize('Order Settings') }}</a>
                        </li>
                    @endcan

                    <li class="d-none {{ areActiveRoutes(['admin.smtpSettings.index'], 'tt-menu-item-active') }}">
                        <a href="{{ route('admin.smtpSettings.index') }}"
                            class="{{ areActiveRoutes(['admin.smtpSettings.index']) }}">{{ localize('Admin Store') }}</a>
                    </li>

                    @can('smtp_settings')
                        <li class="{{ areActiveRoutes(['admin.smtpSettings.index'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.smtpSettings.index') }}"
                                class="{{ areActiveRoutes(['admin.smtpSettings.index']) }}">{{ localize('SMTP Settings') }}</a>
                        </li>
                    @endcan

                    @can('general_settings')
                        <li class="{{ areActiveRoutes(['admin.generalSettings'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.generalSettings') }}"
                                class="{{ areActiveRoutes(['admin.generalSettings']) }}">{{ localize('General Settings') }}</a>
                        </li>
                    @endcan

                    @can('payment_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.paymentMethods'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.paymentMethods') }}"
                                class="{{ areActiveRoutes(['admin.settings.paymentMethods']) }}">{{ localize('Payment Methods') }}</a>
                        </li>
                    @endcan

                    @can('social_login_settings')
                        <li class="{{ areActiveRoutes(['admin.settings.socialLogin'], 'tt-menu-item-active') }}">
                            <a href="{{ route('admin.settings.socialLogin') }}"
                                class="{{ areActiveRoutes(['admin.settings.socialLogin']) }}">{{ localize('Social Media Login') }}</a>
                        </li>
                    @endcan

                    @can('language_settings')
                        <li
                            class="{{ areActiveRoutes(
                                ['admin.languages.index', 'admin.languages.edit', 'admin.languages.localizations'],
                                'tt-menu-item-active',
                            ) }}">
                            <a href="{{ route('admin.languages.index') }}"
                                class="{{ areActiveRoutes(['admin.languages.index', 'admin.languages.edit', 'admin.languages.localizations']) }}">{{ localize('Multilingual Settings') }}</a>
                        </li>
                    @endcan

                    @can('currency_settings')
                        <li
                            class="{{ areActiveRoutes(
                                ['admin.currencies.index', 'admin.currencies.edit', 'admin.currencies.localizations'],
                                'tt-menu-item-active',
                            ) }}">
                            <a href="{{ route('admin.currencies.index') }}"
                                class="{{ areActiveRoutes(['admin.currencies.index', 'admin.currencies.edit', 'admin.currencies.localizations']) }}">{{ localize('Multi Currency Settings') }}</a>
                        </li>
                    @endcan
                </ul>
            </div>
        </li>
    @endcan
</ul>
