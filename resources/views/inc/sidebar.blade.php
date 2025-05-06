<div class="app-brand demo" style="z-index: 9999;">
  <a href="{{ route('home') }}" class="app-brand-link">
    <span style="background: #fff; width: 185px !important; height: 82px !important;" class="app-brand-logo demo">
      <img width="200" height="100" src="{{ asset('assets/img/logo.png') }}">
    </span>
    {{-- <span class="app-brand-text demo menu-text fw-bold" style="    font-size: 0.90rem">{{ config('app.name')
      }}</span> --}}
  </a>

  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
    <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
    <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
  </a>
</div>

<div class="menu-inner-shadow"></div>

<ul class="menu-inner py-1" style="z-index: 9999;">

  <li class="menu-item {{ request()->is('/home') ? 'active' : '' }}">
    <a href="{{ route('home') }}" class="menu-link">
      <i class="menu-icon tf-icons ti ti-home"></i>
      <div>{{ __('messages.Home') }}</div>
    </a>
  </li>


  @can('admins')
  <li class="menu-item {{ request()->is('admin/admins*') ? 'active' : '' }}">
    <a href="{{ route('admin.admins.index') }}" class="menu-link">
      <i class="menu-icon fa-solid fa-headset"></i>
      <div>{{ __('messages.Technical support') }}</div>
    </a>
  </li>
  @endcan


  @can('show_doctor')
  <li class="menu-item {{ request()->is('admin/doctors*') ? 'active' : '' }}">
    <a href="{{ route('admin.doctors.index') }}" class="menu-link">
      <i class="menu-icon fa-solid fa-stethoscope"></i>

      <div>{{ __('messages_303.doctors') }}</div>
    </a>
  </li>
  @endcan

  @if (auth()->user()->type->value == 'doctor')
  <li class="menu-item {{ request()->is('admin/doctors*') ? 'active' : '' }}">
    <a href="{{ route('admin.doctors.show', auth()->id()) }}" class="menu-link">
      <i class="menu-icon ti ti-file-invoice"></i>
      <div>{{ __('messages_301.My account') }}</div>
    </a>
  </li>
  @endif

  @can('show_supplier')
  <li class="menu-item {{ request()->is('admin/suppliers*') ? 'active' : '' }}">
    <a href="{{ route('admin.suppliers.index') }}" class="menu-link">
      <i class="menu-icon ti ti-parachute"></i>
      <div>{{ __('messages_301.Suppliers') }}</div>
    </a>
  </li>
  @endcan

  <li
    class="menu-item {{ request()->is('admin/categories*') || request()->is('admin/sub-categories*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons ti ti-category "></i>
      <div>{{ __('messages.Brands') }}</div>
      {{-- <div class="badge bg-primary rounded-pill ms-auto">5</div> --}}
    </a>
    <ul class="menu-sub">
      @can('show_category')
      <li class="menu-item {{ request()->is('admin/categories*') ? 'active ' : '' }}">
        <a href="{{ route('admin.categories.index') }}" class="menu-link">
          <div>{{ __('messages.Brands') }}</div>
        </a>
      </li>
      @endcan

      @can('show_sub_category')
      <li class="menu-item {{ request()->is('admin/sub-categories*') ? 'active ' : '' }}">
        <a href="{{ route('admin.sub_categories.index') }}" class="menu-link">
          <div>{{ __('messages_301.Sub categories') }}</div>
        </a>
      </li>
      @endcan


    </ul>
  </li>

  @can('show_product')
  <li class="menu-item {{ request()->is('admin/products*') ? 'active' : '' }}">
    <a href="{{ route('admin.products.index') }}" class="menu-link">
      <i class="menu-icon fa-brands fa-product-hunt"></i>

      <div>{{ __('messages_303.products') }}</div>
    </a>
  </li>
  @endcan

  @can('show_package')
  <li class="menu-item {{ request()->is('admin/packages*') ? 'active' : '' }}">
    <a href="{{ route('admin.packages.index') }}" class="menu-link">
      <i class="menu-icon fa-solid fa-boxes-packing"></i>

      <div>{{ __('messages_303.packages') }}</div>
    </a>
  </li>
  @endcan


  @can('show_invoice')
  <li class="menu-item {{ request()->is('admin/invoices*') ? 'active' : '' }}">
    <a href="{{ route('admin.invoices.index') }}" class="menu-link">
      <i class="menu-icon  ti ti-article"></i>
      @if (auth()->user()->type->value == 'admin')
      <div>{{ __('messages.Invoices') }}</div>
      @else
      <div>{{ __('messages_301.Recipes') }}</div>
      @endif
    </a>
  </li>
  @endcan

  @can('show_coupon')
  <li class="menu-item {{ request()->is('admin/coupons*') ? 'active' : '' }}">
    <a href="{{ route('admin.coupons.index') }}" class="menu-link">
      <i class="menu-icon  ti ti-discount"></i>
      <div>{{ __('messages_301.Coupons') }}</div>
    </a>
  </li>
  @endcan

  {{-- @can('show_coupon') --}}
  {{-- <li class="menu-item {{ request()->is('admin/formulations*') ? 'active' : '' }}">
    <a href="{{ route('admin.formulations.index') }}" class="menu-link">
      <i class="menu-icon ti ti-medicine-syrup"></i>
      <div>{{ __('messages_301.Formulations') }}</div>
    </a>
  </li> --}}
  {{-- @endcan --}}

  @can('admins')
  <li class="menu-item  {{ request()->is('admin/settings*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons ti ti-settings"></i>
      <div>{{ __('messages.Settings') }}</div>
      {{-- <div class="badge bg-primary rounded-pill ms-auto">5</div> --}}
    </a>
    <ul class="menu-sub">
      {{-- <li class="menu-item  {{ request()->is('admin/settings/languages') ? 'active open' : '' }}">
        <a href="{{ route('admin.settings.languages.index') }}" class="menu-link">
          <div>{{ __('messages.Languages') }}</div>
        </a>
      </li> --}}
      @foreach (array_unique(App\Models\Setting::pluck('page')->toArray()) as $page)
      <li class="menu-item  {{ request()->is(" admin/settings/pages/$page") ? 'active open' : '' }}">
        <a href="{{ route('admin.settings.pages.index', $page) }}" class="menu-link">
          <div>{{ __("messages.$page") }}</div>
        </a>
      </li>
      @endforeach
      <li class="menu-item {{ request()->is(" admin/settings/invoices") ? 'active open' : '' }}">
        <a href="{{ route('admin.settings.invoices') }}" class="menu-link">
          <div>{{ __("messages.Invoices") }}</div>
        </a>
      </li>
    </ul>
  </li>

  @endcan






  {{--


  <!-- start 2 level  -->
  <li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons ti ti-smart-home"></i>
      <div data-i18n="2 Level example">2 Level example</div>
      <div class="badge bg-primary rounded-pill ms-auto">5</div>
    </a>
    <ul class="menu-sub">
      <li class="menu-item">
        <a href="index.html" class="menu-link">
          <div data-i18n="Analytics">Analytics</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="dashboards-crm.html" class="menu-link">
          <div data-i18n="CRM">CRM</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="app-ecommerce-dashboard.html" class="menu-link">
          <div data-i18n="eCommerce">eCommerce</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="app-logistics-dashboard.html" class="menu-link">
          <div data-i18n="Logistics">Logistics</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="app-academy-dashboard.html" class="menu-link">
          <div data-i18n="Academy">Academy</div>
        </a>
      </li>
    </ul>
  </li>





  <!-- e-commerce-app menu start -->
  <li class="menu-item active open">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
      <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
      <div data-i18n="3 Leve">3 Level</div>
    </a>
    <ul class="menu-sub">
      <li class="menu-item">
        <a href="app-ecommerce-dashboard.html" class="menu-link">
          <div data-i18n="Dashboard">Dashboard</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <div data-i18n="Products">Products</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="app-ecommerce-product-list.html" class="menu-link">
              <div data-i18n="Product List">Product List</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="app-ecommerce-product-add.html" class="menu-link">
              <div data-i18n="Add Product">Add Product</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="app-ecommerce-category-list.html" class="menu-link">
              <div data-i18n="Category List">Category List</div>
            </a>
          </li>
        </ul>
      </li>
      <li class="menu-item active open">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <div data-i18n="Order">Order</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item active">
            <a href="app-ecommerce-order-list.html" class="menu-link">
              <div data-i18n="Order List">Order List</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="app-ecommerce-order-details.html" class="menu-link">
              <div data-i18n="Order Details">Order Details</div>
            </a>
          </li>
        </ul>
      </li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <div data-i18n="Customer">Customer</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="app-ecommerce-customer-all.html" class="menu-link">
              <div data-i18n="All Customers">All Customers</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <div data-i18n="Customer Details">Customer Details</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="app-ecommerce-customer-details-overview.html" class="menu-link">
                  <div data-i18n="Overview">Overview</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="app-ecommerce-customer-details-security.html" class="menu-link">
                  <div data-i18n="Security">Security</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="app-ecommerce-customer-details-billing.html" class="menu-link">
                  <div data-i18n="Address & Billing">Address & Billing</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="app-ecommerce-customer-details-notifications.html" class="menu-link">
                  <div data-i18n="Notifications">Notifications</div>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </li>
      <li class="menu-item">
        <a href="app-ecommerce-manage-reviews.html" class="menu-link">
          <div data-i18n="Manage Reviews">Manage Reviews</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="app-ecommerce-referral.html" class="menu-link">
          <div data-i18n="Referrals">Referrals</div>
        </a>
      </li>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <div data-i18n="Settings">Settings</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="app-ecommerce-settings-detail.html" class="menu-link">
              <div data-i18n="Store Details">Store Details</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="app-ecommerce-settings-payments.html" class="menu-link">
              <div data-i18n="Payments">Payments</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="app-ecommerce-settings-checkout.html" class="menu-link">
              <div data-i18n="Checkout">Checkout</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="app-ecommerce-settings-shipping.html" class="menu-link">
              <div data-i18n="Shipping & Delivery">Shipping & Delivery</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="app-ecommerce-settings-locations.html" class="menu-link">
              <div data-i18n="Locations">Locations</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="app-ecommerce-settings-notifications.html" class="menu-link">
              <div data-i18n="Notifications">Notifications</div>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </li>
  <!-- e-commerce-app menu end -->








  <li class="menu-item">
    <a href="app-email.html" class="menu-link">
      <i class="menu-icon tf-icons ti ti-mail"></i>
      <div data-i18n="Email">single</div>
    </a>
  </li>
  --}}


</ul>