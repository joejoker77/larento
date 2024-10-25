<!doctype html>
<html lang="ru">
<head>
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/build/manifest.json">
    {!! Meta::toHtml() !!}
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <meta name="yandex-verification" content="d3f4ab31cc7e1109" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    @vite('resources/scss/app.scss')
</head>
<body>
<div class="container p-0 site-wrapper @if(Route::current()->getName() === 'home') main @endif">
    <header>
        <div class="header-top d-flex flex-row justify-content-between">
            <div class="left d-flex flex-row justify-content-between justify-content-lg-start align-items-center">
                <div class="header-top_company-address">{{ $settings['address'] ?? env('APP_ADDRESS') }}</div>
                <div class="separator"></div>
                <div class="header-top_clock-working">{{ $settings['work_time'] ?? env('APP_WORK_TIME') }}</div>
            </div>
            <div class="right d-none d-lg-block">
                <nav>
                    <x-menu handler="topMenu" menuClass="nav"/>
                </nav>
            </div>
        </div>
        <div class="header-bottom d-flex flex-row justify-content-between container p-0">
            <div class="logo">
                @if(Route::current()->getName() !== 'home')
                    <a href="{{ route('home') }}" class="logo-link">
                @endif
                    <svg width="157" height="36" viewBox="0 0 157 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs/>
                        <path d="M69.9296 9.43909H73.7935V27.5579H69.9296V23.9561C68.0495 26.6941 65.7179 28.0631 62.9347 28.0631C60.5217 28.0631 58.4565 27.126 56.7392 25.2519C55.0368 23.3778 54.1855 21.101 54.1855 18.4216C54.1855 15.8887 55.0442 13.6778 56.7615 11.789C58.4935 9.88565 60.5217 8.93396 62.8459 8.93396C64.2819 8.93396 65.6439 9.32196 66.9318 10.098C68.2198 10.874 69.2191 11.8842 69.9296 13.1287V9.43909ZM70.4404 18.5534C70.4404 16.8989 69.8408 15.486 68.6417 14.3147C67.4426 13.1287 66.0066 12.5358 64.3337 12.5358C62.7053 12.5358 61.2841 13.1214 60.0702 14.2927C58.8562 15.4641 58.2493 16.8404 58.2493 18.4216C58.2493 20.1347 58.8414 21.5915 60.0257 22.7921C61.2101 23.9927 62.6461 24.593 64.3337 24.593C66.0066 24.593 67.4426 24 68.6417 22.8141C69.8408 21.6135 70.4404 20.1933 70.4404 18.5534Z" fill="#7D7C7A"/>
                        <path d="M81.6012 9.43909V13.1727C82.4598 11.6792 83.2962 10.6031 84.1104 9.94422C84.9395 9.27071 86.0128 8.93396 87.3303 8.93396C87.7004 8.93396 88.2334 9.02181 88.9292 9.1975L87.7522 13.0189C87.012 12.8725 86.5235 12.7993 86.2866 12.7993C84.9839 12.7993 83.8884 13.2532 83.0001 14.161C82.1267 15.0541 81.69 16.1742 81.69 17.5212V35.4292H77.8261V9.43909H81.6012Z" fill="#7D7C7A"/>
                        <path d="M108.484 19.2342H92.8508C93.1912 21.0937 93.9611 22.4554 95.1602 23.3192C96.3741 24.1684 97.6917 24.593 99.1129 24.593C101.304 24.593 103.14 23.6926 104.62 21.8917L107.44 24C105.323 26.7087 102.488 28.0631 98.9352 28.0631C96.2113 28.0631 93.9314 27.1406 92.0957 25.2958C90.26 23.451 89.3422 21.1596 89.3422 18.4216C89.3422 15.8594 90.2674 13.6412 92.118 11.7671C93.9833 9.87833 96.2557 8.93396 98.9352 8.93396C101.57 8.93396 103.828 9.89297 105.708 11.811C107.588 13.7144 108.513 16.1888 108.484 19.2342ZM93.2505 16.1815H104.442C103.199 13.6924 101.363 12.4479 98.9352 12.4479C96.2853 12.4479 94.3904 13.6924 93.2505 16.1815Z" fill="#7D7C7A"/>
                        <path d="M109.941 27.5579V9.43909H113.716V12.4479C114.826 11.2327 115.833 10.3469 116.736 9.79048C117.639 9.21947 118.645 8.93396 119.756 8.93396C121.414 8.93396 122.827 9.47569 123.997 10.5592C125.181 11.6426 125.773 12.9457 125.773 14.4684V27.5579H121.91V15.5885C121.91 14.6075 121.584 13.7949 120.933 13.1507C120.296 12.5065 119.482 12.1844 118.49 12.1844C117.202 12.1844 116.092 12.7261 115.159 13.8096C114.226 14.8784 113.76 16.1742 113.76 17.6969V27.5579H109.941Z" fill="#7D7C7A"/>
                        <path d="M129.562 4.16815H133.426V9.43909H137.245V12.9311H133.426V27.5579H129.562V12.9311H126.52V9.39516H129.562V4.16815Z" fill="#7D7C7A"/>
                        <path d="M137.369 18.4216C137.369 15.9472 138.354 13.751 140.323 11.833C142.307 9.9003 144.579 8.93396 147.14 8.93396C149.775 8.93396 152.077 9.88565 154.046 11.789C156.015 13.6778 157 15.8887 157 18.4216C157 20.9693 156.03 23.2167 154.091 25.164C152.166 27.0967 149.938 28.0631 147.407 28.0631C144.697 28.0631 142.344 27.104 140.345 25.186C138.361 23.268 137.369 21.0132 137.369 18.4216ZM153.269 18.4216C153.269 16.8843 152.677 15.5519 151.493 14.4245C150.308 13.2825 148.902 12.7115 147.273 12.7115C145.601 12.7115 144.157 13.2898 142.943 14.4465C141.744 15.5885 141.145 16.9575 141.145 18.5534C141.145 20.0761 141.751 21.4085 142.965 22.5505C144.194 23.6926 145.63 24.2636 147.273 24.2636C148.872 24.2636 150.271 23.6779 151.47 22.5066C152.669 21.3353 153.269 19.9736 153.269 18.4216Z" fill="#7D7C7A"/>
                        <path d="M50.0694 9.03094V31.6621H73.6372V35.4292H45.8496V9.03094H50.0694Z" fill="#7D7C7A"/>
                        <path d="M85.4473 31.9557H156.306V35.4292H85.4473V31.9557Z" fill="#7D7C7A"/>
                        <rect x="9.031" y="18.062" width="9.031" height="8.747" fill="#7D7C7A" style=""/>
                        <rect width="9.72566" height="9.03097" fill="#7D7C7A"/>
                        <rect x="18.0615" y="9.03107" width="18.7566" height="9.03097" fill="#7D7C7A"/>
                        <rect x="9.031" y="26.398" width="19.267" height="9.031" fill="#786B61" style=""/>
                        <rect x="27.093" y="17.849" width="9.726" height="17.58" fill="#786B61" style=""/>
                    </svg>
                @if(Route::current()->getName() !== 'home')
                    </a>
                @endif
            </div>
            <div class="d-none d-lg-block">
                <x-menu handler="mainMenu" menuClass="navbar navbar-expand-lg" template="components.mega-menu" menuId="mainMenu"/>
            </div>
            <div class="d-block d-lg-none">
                <x-menu :settings="$settings" handler="mainMobile" menuClass="navbar navbar-expand-lg" template="components.mega-menu" menuId="mainMenuMobile"/>
            </div>
            @isset($settings)
                <button class="btn d-lg-none collapse-phone" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForMobile" aria-controls="collapseForMobile" aria-expanded="false">
                    <svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.033 10.4518L11.5965 10.8857C11.5965 10.8857 10.559 11.9173 7.72713 9.10148C4.89528 6.28571 5.93275 5.25413 5.93275 5.25413L6.2076 4.98082C6.88476 4.30755 6.94859 3.22662 6.35777 2.4375L5.14937 0.823282C4.41818 -0.153413 3.00529 -0.282434 2.16722 0.550875L0.663021 2.04654C0.247469 2.45973 -0.0310038 2.99535 0.00276786 3.58954C0.0891616 5.10966 0.776928 8.38033 4.61472 12.1963C8.68451 16.243 12.5032 16.4038 14.0648 16.2582C14.5587 16.2122 14.9882 15.9607 15.3344 15.6164L16.6958 14.2629C17.6147 13.3491 17.3556 11.7826 16.1798 11.1435L14.3489 10.1482C13.5769 9.72852 12.6364 9.85176 12.033 10.4518Z" fill="#7D7C7A"/>
                    </svg>
                </button>
                <div class="company-phones collapse d-lg-inline-flex" id="collapseForMobile">
                    @foreach(format_phones($settings['phones']) as $phone)
                        <a href="{{$phone['link']}}" class="link-phone d-block d-lg-inline">{{ $phone['phone'] }}</a>
                    @endforeach
                </div>
            @endisset
        </div>
    </header>
    <div id="content">
        @include('layouts.partials.flash')
        @section('breadcrumbs', Diglactic\Breadcrumbs\Breadcrumbs::render())
        @yield('breadcrumbs')
        @yield('content')
        <section id="map">
            <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A6ef77792a75f8077da6ed34f4f797a6758128ea9f86389e25c1821abc186f4b1&amp;width=100%25&amp;height=410&amp;lang=ru_RU&amp;scroll=false"></script>
        </section>
    </div>
    <footer class="d-flex flex-column">
        <div class="footer-top d-flex flex-column flex-lg-row">
            <div class="logo">
                @if(Route::current()->getName() !== 'home')
                    <a href="{{ route('home') }}" class="logo-link">
                        @endif
                        <svg width="157" height="36" viewBox="0 0 157 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M69.9296 9.43891H73.7935V27.5577H69.9296V23.9559C68.0495 26.6939 65.7179 28.0629 62.9347 28.0629C60.5217 28.0629 58.4565 27.1258 56.7392 25.2517C55.0368 23.3776 54.1855 21.1009 54.1855 18.4215C54.1855 15.8885 55.0442 13.6776 56.7615 11.7889C58.4935 9.88547 60.5217 8.93377 62.8459 8.93377C64.2819 8.93377 65.6439 9.32177 66.9318 10.0978C68.2198 10.8738 69.2191 11.884 69.9296 13.1286V9.43891ZM70.4404 18.5532C70.4404 16.8987 69.8408 15.4858 68.6417 14.3145C67.4426 13.1286 66.0066 12.5356 64.3337 12.5356C62.7053 12.5356 61.2841 13.1212 60.0702 14.2926C58.8562 15.4639 58.2493 16.8402 58.2493 18.4215C58.2493 20.1345 58.8414 21.5913 60.0257 22.7919C61.2101 23.9925 62.6461 24.5928 64.3337 24.5928C66.0066 24.5928 67.4426 23.9999 68.6417 22.8139C69.8408 21.6133 70.4404 20.1931 70.4404 18.5532Z" fill="white"/>
                            <path d="M81.6012 9.43891V13.1725C82.4598 11.6791 83.2962 10.6029 84.1104 9.94404C84.9395 9.27053 86.0128 8.93377 87.3303 8.93377C87.7004 8.93377 88.2334 9.02162 88.9292 9.19732L87.7522 13.0187C87.012 12.8723 86.5235 12.7991 86.2866 12.7991C84.9839 12.7991 83.8884 13.253 83.0001 14.1608C82.1267 15.0539 81.69 16.174 81.69 17.521V35.429H77.8261V9.43891H81.6012Z" fill="white"/>
                            <path d="M108.484 19.2341H92.8508C93.1912 21.0935 93.9611 22.4552 95.1602 23.319C96.3741 24.1682 97.6917 24.5928 99.1129 24.5928C101.304 24.5928 103.14 23.6924 104.62 21.8915L107.44 23.9999C105.323 26.7085 102.488 28.0629 98.9352 28.0629C96.2113 28.0629 93.9314 27.1405 92.0957 25.2956C90.26 23.4508 89.3422 21.1594 89.3422 18.4215C89.3422 15.8592 90.2674 13.641 92.118 11.7669C93.9833 9.87815 96.2557 8.93377 98.9352 8.93377C101.57 8.93377 103.828 9.89279 105.708 11.8108C107.588 13.7142 108.513 16.1886 108.484 19.2341ZM93.2505 16.1813H104.442C103.199 13.6923 101.363 12.4477 98.9352 12.4477C96.2853 12.4477 94.3904 13.6923 93.2505 16.1813Z" fill="white"/>
                            <path d="M109.941 27.5577V9.43891H113.716V12.4477C114.826 11.2325 115.833 10.3467 116.736 9.7903C117.639 9.21928 118.645 8.93377 119.756 8.93377C121.414 8.93377 122.827 9.47551 123.997 10.559C125.181 11.6424 125.773 12.9455 125.773 14.4683V27.5577H121.91V15.5883C121.91 14.6074 121.584 13.7947 120.933 13.1505C120.296 12.5063 119.482 12.1842 118.49 12.1842C117.202 12.1842 116.092 12.7259 115.159 13.8094C114.226 14.8782 113.76 16.174 113.76 17.6967V27.5577H109.941Z" fill="white"/>
                            <path d="M129.562 4.16797H133.426V9.43891H137.245V12.9309H133.426V27.5577H129.562V12.9309H126.52V9.39498H129.562V4.16797Z" fill="white"/>
                            <path d="M137.369 18.4215C137.369 15.947 138.354 13.7508 140.323 11.8328C142.307 9.90011 144.579 8.93377 147.14 8.93377C149.775 8.93377 152.077 9.88547 154.046 11.7889C156.015 13.6776 157 15.8885 157 18.4215C157 20.9691 156.03 23.2165 154.091 25.1639C152.166 27.0965 149.938 28.0629 147.407 28.0629C144.697 28.0629 142.344 27.1039 140.345 25.1858C138.361 23.2678 137.369 21.013 137.369 18.4215ZM153.269 18.4215C153.269 16.8841 152.677 15.5517 151.493 14.4243C150.308 13.2823 148.902 12.7113 147.273 12.7113C145.601 12.7113 144.157 13.2896 142.943 14.4463C141.744 15.5883 141.145 16.9573 141.145 18.5532C141.145 20.0759 141.751 21.4083 142.965 22.5504C144.194 23.6924 145.63 24.2634 147.273 24.2634C148.872 24.2634 150.271 23.6778 151.47 22.5064C152.669 21.3351 153.269 19.9735 153.269 18.4215Z" fill="white"/>
                            <path d="M50.0694 9.03076V31.6619H73.6372V35.429H45.8496V9.03076H50.0694Z" fill="white"/>
                            <path d="M85.4473 31.9556H156.306V35.429H85.4473V31.9556Z" fill="white"/>
                            <rect x="9.03125" y="18.062" width="9.03097" height="8.33628" fill="white"/>
                            <rect width="9.72566" height="9.03097" fill="white"/>
                            <rect x="18.0615" y="9.03125" width="18.7566" height="9.03097" fill="white"/>
                            <rect x="9.03125" y="26.3984" width="18.0619" height="9.03097" fill="#BEBEBE"/>
                            <rect x="27.0928" y="18.062" width="9.72566" height="17.3672" fill="#BEBEBE"/>
                        </svg>
                        @if(Route::current()->getName() !== 'home')
                    </a>
                @endif
                <p>{{ $settings['slogan'] ?? env('APP_SLOGAN') }}</p>
            </div>
            <div class="footer-top_navigations">
                <nav class="navbar navbar-expand-lg">
                    <div class="navbar-footer">
                        <x-menu menuClass="navbar-nav me-auto mb-2 mb-lg-0" handler="footerMenu" dropDown="false" subMenuClasses="sub-menu position-relative" />
                    </div>
                </nav>
            </div>
            <div class="footer-top_contacts mx-auto ms-lg-auto me-lg-0">
                <div class="footer-top_company-phones">
                    @isset($settings)
                        @foreach(format_phones($settings['phones']) as $phone)
                            <a href="{{$phone['link']}}" class="link-phone">{{ $phone['phone'] }}</a>
                        @endforeach
                    @endisset
                </div>
                <div class="footer-top_clock-working">{{ $settings['work_time'] ?? env('APP_WORK_TIME') }}</div>
                <div class="footer-top_email"><a href="mailto:info@larento.ru">{{ $settings['emails'][0] ?? env('MAIL_FROM_ADDRESS') }}</a></div>
            </div>
        </div>
        <div class="footer-bottom d-flex flex-column flex-lg-row justify-content-between">
            <div class="footer-bottom_company-address">{{ $settings['address'] ?? env('APP_ADDRESS') }}</div>
            <div class="footer-bottom_privacy-links">
                <a href="#">политика конфиденциальности</a>
            </div>
            <div class="footer-bottom_copyright">
                &copy; 2024 All rights reserved
            </div>
        </div>
    </footer>
</div>
<div class="modal fade" id="mainModal" tabindex="-1" aria-labelledby="mainModalTitle" style="display: none;"
     aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="h1 modal-title" id="mainModalTitle"></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-brown" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<div id="scrollUp" class="position-fixed d-none d-lg-flex">
    <span class="material-symbols-outlined">stat_minus_3</span>
</div>
@vite('resources/js/app.js')
</body>
</html>
