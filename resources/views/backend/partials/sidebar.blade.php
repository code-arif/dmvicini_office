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
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon"
                            enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                            <path
                                d="M19.9794922,7.9521484l-6-5.2666016c-1.1339111-0.9902344-2.8250732-0.9902344-3.9589844,0l-6,5.2666016C3.3717041,8.5219116,2.9998169,9.3435669,3,10.2069702V19c0.0018311,1.6561279,1.3438721,2.9981689,3,3h2.5h7c0.0001831,0,0.0003662,0,0.0006104,0H18c1.6561279-0.0018311,2.9981689-1.3438721,3-3v-8.7930298C21.0001831,9.3435669,20.6282959,8.5219116,19.9794922,7.9521484z M15,21H9v-6c0.0014038-1.1040039,0.8959961-1.9985962,2-2h2c1.1040039,0.0014038,1.9985962,0.8959961,2,2V21z M20,19c-0.0014038,1.1040039-0.8959961,1.9985962-2,2h-2v-6c-0.0018311-1.6561279-1.3438721-2.9981689-3-3h-2c-1.6561279,0.0018311-2.9981689,1.3438721-3,3v6H6c-1.1040039-0.0014038-1.9985962-0.8959961-2-2v-8.7930298C3.9997559,9.6313477,4.2478027,9.0836182,4.6806641,8.7041016l6-5.2666016C11.0455933,3.1174927,11.5146484,2.9414673,12,2.9423828c0.4853516-0.0009155,0.9544067,0.1751099,1.3193359,0.4951172l6,5.2665405C19.7521973,9.0835571,20.0002441,9.6313477,20,10.2069702V19z" />
                        </svg>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                {{-- users --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('investor.list') ? 'has-link' : '' }}"
                        href="{{ route('investor.list') }}">
                        <i class="fa-solid fa-users side-menu__icon"
                            style="font-size:24px; width:24px; height:24px;"></i>
                        <span class="side-menu__label">Investor Manage</span>
                    </a>
                </li>

                {{-- blog/education --}}
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <i class="fa-solid fa-blog side-menu__icon"
                            style="font-size:24px; width:24px; height:24px;"></i>
                        <span class="side-menu__label">Education manage</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>


                    <ul class="slide-menu">
                        <li><a href="{{ route('show.category.list') }}" class="slide-item">Categories</a>
                        </li>
                        <li><a href="{{ route('show.education.list') }}" class="slide-item">Education</a>
                        </li>
                    </ul>
                </li>

                {{-- faq --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('faq') ? 'has-link' : '' }}"
                        href="{{ route('admin.faq.index') }}">
                        <i class="fa-solid fa-clipboard-question side-menu__icon"></i>
                        <span class="side-menu__label">FAQ</span>
                    </a>
                </li>

                {{-- manage investment --}}
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <i class="fa-solid fa-seedling"
                            style="font-size:24px; width:24px; height:24px; margin-right: 5px;"></i>
                        <span class="side-menu__label">Manage Investment</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>


                    <ul class="slide-menu">
                        <li><a href="{{ route('show.asset.class.list') }}" class="slide-item">Assets Classes</a>
                        </li>
                        <li><a href="{{ route('show.investment.type.list') }}" class="slide-item">Investment Types</a>
                        </li>
                        <li><a href="{{ route('show.investment.strategy.list') }}" class="slide-item">Strategies</a>
                        </li>
                        <li><a href="{{ route('get.investments') }}" class="slide-item">Investments</a>
                        </li>
                    </ul>
                </li>

                {{-- excel/csv upload --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('faq') ? 'has-link' : '' }}"
                        href="{{ route('investments.import.form') }}">
                        <i class="fa-solid fa-file-excel side-menu__icon"></i>
                        <span class="side-menu__label">Import CSV/Excel</span>
                    </a>
                </li>

                <h3>CMS</h3>

                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('faq') ? 'has-link' : '' }}"
                        href="{{ route('cms.help.center.hero') }}">
                        <i class="fa-solid fa-clipboard-question side-menu__icon"></i>
                        <span class="side-menu__label">Help Center Page</span>
                    </a>
                </li>

                {{-- Setttings --}}
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 512 512">
                            <path
                                d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z" />
                        </svg>
                        <span class="side-menu__label">Settings</span><i class="angle fa fa-angle-right"></i>
                    </a>

                    <ul class="slide-menu">
                        <li><a href="{{ route('setting.general.index') }}" class="slide-item">General Settings</a></li>
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
