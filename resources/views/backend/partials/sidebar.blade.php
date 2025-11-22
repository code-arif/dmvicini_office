<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar" style="overflow: scroll">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('dashboard') }}">
                <img src="{{ asset($settings->logo ?? 'default/logo.png') }}" class="header-brand-img desktop-logo"
                    alt="logo">
                <img src="{{ asset($settings->logo ?? 'default/logo.png') }}" class="header-brand-img toggle-logo"
                    alt="logo">
                <img src="{{ asset($settings->logo ?? 'default/logo.png') }}" class="header-brand-img light-logo"
                    alt="logo">
                <img src="{{ asset($settings->logo ?? 'default/logo.png') }}" class="header-brand-img light-logo1"
                    alt="logo">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>
            <ul class="side-menu mt-2">
                <li>
                    <h3>Menu</h3>
                </li>
                {{-- dashboard --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('dashboard') ? 'has-link' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-gauge"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                {{-- users --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('investor.list') ? 'has-link' : '' }}"
                        href="{{ route('investor.list') }}">
                        <i class="fa-solid fa-users"></i>
                        <span class="side-menu__label">User Management</span>
                    </a>
                </li>

                {{-- Deals --}}
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <i class="fa-brands fa-delicious"></i>
                        <span class="side-menu__label">Deal Management</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>


                    <ul class="slide-menu">
                        <li><a href="{{ route('investment.list') }}" class="slide-item">Deals </a>
                        </li>
                    </ul>
                </li>

                {{-- manage investment --}}
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <i class="fa-solid fa-tags"></i>
                        <span class="side-menu__label">Manage Tags</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a href="{{ route('show.asset.class.list') }}" class="slide-item">Asset Classes</a>
                        </li>
                        <li><a href="{{ route('show.investment.type.list') }}" class="slide-item">Investment Types</a>
                        </li>
                        <li><a href="{{ route('show.investment.strategy.list') }}" class="slide-item">Strategies</a>
                        </li>
                        <li><a href="{{ route('show.tax.strategy.list') }}" class="slide-item">Tax Strategy</a>
                        </li>
                    </ul>
                </li>

                {{-- blog/education --}}
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <i class="fa-solid fa-blog"></i>
                        <span class="side-menu__label">Education</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>


                    <ul class="slide-menu">
                        <li><a href="{{ route('show.category.list') }}" class="slide-item">Categories</a>
                        </li>
                        <li><a href="{{ route('show.education.list') }}" class="slide-item">Education</a>
                        </li>
                    </ul>
                </li>

                {{-- FAQ/Help Center --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('faq') ? 'has-link' : '' }}"
                        href="{{ route('admin.faq.index') }}">
                        <i class="fa-solid fa-clipboard-question"></i>
                        <span class="side-menu__label">FAQ/Help Center</span>
                    </a>
                </li>

                {{-- import- Import Deal/ excel/csv upload --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('faq') ? 'has-link' : '' }}"
                        href="{{ route('investments.import.form') }}">
                        <i class="fa-solid fa-file-excel"></i>
                        <span class="side-menu__label">Import Deal</span>
                    </a>
                </li>

                {{-- subscriber --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('subscribers.index') ? 'has-link' : '' }}"
                        href="{{ route('subscribers.index') }}">
                        <i class="fa-solid fa-user-plus"></i>
                        <span class="side-menu__label">Platform Subscribers</span>
                    </a>
                </li>

                <h3>CMS</h3>

                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('cms.help.center.hero') ? 'has-link' : '' }}"
                        href="{{ route('cms.help.center.hero') }}">
                        <i class="fa-solid fa-handshake-angle"></i>
                        <span class="side-menu__label">Help Center Page</span>
                    </a>
                </li>

                {{-- Footer Management --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('cms.footer.index') ? 'has-link' : '' }}"
                        href="{{ route('cms.footer.index') }}">
                        <i class="fa-solid fa-shoe-prints"></i>
                        <span class="side-menu__label">Footer Management</span>
                    </a>
                </li>

                {{-- Settings --}}
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <i class="fa-solid fa-cog"></i>
                        <span class="side-menu__label">Settings</span><i class="angle fa fa-angle-right"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('setting.general.index') }}" class="slide-item">General Settings</a>
                        </li>
                        <li><a href="{{ route('setting.profile.index') }}" class="slide-item">Profile Settings</a>
                        </li>

                    </ul>
                </li>
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </div>
</div>
<!--/APP-SIDEBAR-->


{{-- sidebar style --}}
<style>
    /* Base menu item styling */
    .side-menu__item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: #333;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .side-menu__item:hover {
        background-color: #f3f6f9;
        color: #172870;
    }

    /* Icon alignment fix */
    .side-menu__item i,
    .side-menu__item svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        display: inline-block;
        text-align: center;
        margin-right: 10px;
        /* consistent spacing */
        color: inherit;
    }

    /* Label */
    .side-menu__label {
        flex: 1;
        display: inline-block;
        margin-bottom: 5px;
    }

    /* Submenu items */
    .slide-menu .slide-item {
        display: block;
        color: #555;
        font-size: 14px;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .slide-menu .slide-item:hover {
        color: #172870;
    }

    /* Optional: heading styling */
    .side-menu h3 {
        font-size: 13px;
        text-transform: uppercase;
        margin: 20px 15px 10px;
        color: #777;
        letter-spacing: 0.5px;
    }
</style>
