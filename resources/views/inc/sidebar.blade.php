<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    <div>
        <a href="index.html" class="sidebar-logo">
            <img src="assets/images/logo.png" alt="site logo" class="light-logo">
            <img src="assets/images/logo-light.png" alt="site logo" class="dark-logo">
            <img src="assets/images/logo-icon.png" alt="site logo" class="logo-icon">
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            {{-- Dashboard --}}
            @can('dashboard.view')
                <li>
                    <a href="{{ route('dashboard.index') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endcan

            <li class="sidebar-menu-group-title">Control Panel</li>

            {{-- Entity --}}
            @can('entity.view')
                <li>
                    <a href="{{ route('entities.index') }}">
                        <iconify-icon icon="lucide:component" class="menu-icon"></iconify-icon>
                        <span>Entity</span>
                    </a>
                </li>
            @endcan
                @can('trip.view')
                <li>
                    <a href="{{ route('trips.index') }}" class="sidebar-link">
                        <iconify-icon icon="mdi:car-outline" class="menu-icon"></iconify-icon>
                        <span>Trips</span>
                    </a>
                </li>
                @endcan

            {{-- Users --}}
            @can('users.view')
                <li>
                    <a href="{{ route('users.index') }}">
                        <iconify-icon icon="si:user-alt-2-duotone" class="menu-icon"></iconify-icon>
                        <span>Users</span>
                    </a>
                </li>
            @endcan

            {{-- Roles --}}
            @can('roles.view')
                <li>
                    <a href="{{ route('roles.index') }}">
                        <iconify-icon icon="solar:shield-keyhole-broken" class="menu-icon"></iconify-icon>
                        <span>Roles</span>
                    </a>
                </li>
            @endcan

            {{-- App Config --}}
            @canany(['applications.view', 'entity-applications.view'])
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="si:flow-cascade-line" class="menu-icon"></iconify-icon>
                        <span>App Config</span>
                    </a>

                    <ul class="sidebar-submenu">
                        @can('applications.view')
                            <li>
                                <a href="{{ route('applications.index') }}">
                                    <iconify-icon icon="solar:bolt-circle-line-duotone" class="menu-icon"></iconify-icon>
                                    <span>Applications</span>
                                </a>
                            </li>
                        @endcan

                        @can('entity-applications.view')
                            <li>
                                <a href="{{ route('entity-applications.index') }}">
                                    <iconify-icon icon="si:flow-branch-line" class="menu-icon"></iconify-icon>
                                    <span>Entity Application</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            {{-- Devices --}}
            @can('device.view')
                <li>
                    <a href="{{ route('devices.index') }}">
                        <iconify-icon icon="bitcoin-icons:node-hardware-outline" class="menu-icon"></iconify-icon>
                        <span>Devices</span>
                    </a>
                </li>
            @endcan

            {{-- Sample Dropdown (UNCHANGED) --}}
            <li class="dropdown d-none">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Sample Dropdown</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="invoice-list.html">
                            <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                            List
                        </a>
                    </li>
                    <li>
                        <a href="invoice-preview.html">
                            <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                            Preview
                        </a>
                    </li>
                    <li>
                        <a href="invoice-add.html">
                            <i class="ri-circle-fill circle-icon text-info-main w-auto"></i>
                            Add new
                        </a>
                    </li>
                    <li>
                        <a href="invoice-edit.html">
                            <i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                            Edit
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Settings (UNCHANGED) --}}
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
                    <span>Settings</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('settings.cameras.index') }}"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Camera Settings</a></li>
                    <li><a href="{{ route('settings.email.index') }}"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Email Settings</a></li>
                    <li><a href="{{route('settings.notification.index')}}"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Notification Alert</a>
                    </li>
                    <li><a href="theme.html"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Theme</a>
                    </li>
                    <li><a href="currencies.html"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Currencies</a></li>
                    <li><a href="language.html"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Languages</a></li>
                    <li><a href="payment-gateway.html"><i
                                class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Payment Gateway</a></li>
                </ul>
            </li>

        </ul>
    </div>
</aside>
