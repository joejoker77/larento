@extends('layouts.index')
@section('breadcrumbs', '')
@section('content')
    <x-main-banner />
    <x-main-page-products />
    <section id="mainText">
        <div class="d-flex flex-row">
            <div class="main-text">
                @isset($settings)
                    <h1 class="decor-head">{{ $settings['main_head'] }}</h1>
                    {!! $settings['main_text'] !!}
                    <a href="#" class="btn-read-more bnt">подробнее</a>
                @endisset
            </div>
            <div class="main-image">
                <img src="{{asset('storage/images/home/main-image.jpg')}}" alt="главная картинка сайта">
            </div>
        </div>
    </section>
    <x-portfolio-module />
{{--    <section id="reviews" class="reviews">--}}
{{--        <div class="d-flex flex-row justify-content-between">--}}
{{--            <div class="reviews_info d-flex flex-column">--}}
{{--                <h2 class="decor-head-three">--}}
{{--                    <span>larento</span>--}}
{{--                    отзывы--}}
{{--                </h2>--}}
{{--                <div class="reviews_text">--}}
{{--                    <p>Здесь использовать текст рыбу, с количеством знаков без пробелов до 600-700. В данном блоке нужно использовать какое либо изображение, подчеркивающее общую стилистику сайта.</p>--}}
{{--                </div>--}}
{{--                <div class="reviews_buttons d-flex flex-row">--}}
{{--                    <div class="swiper-button reviews_swiper-button-prev">‹</div>--}}
{{--                    <div class="swiper-button reviews_swiper-button-next">›</div>--}}
{{--                    <div class="reviews_all-button">--}}
{{--                        <a href="#" class="btn btn-brown">Все отзывы на яндексе</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="reviews_swiper">--}}
{{--                <div class="swiper-wrapper">--}}
{{--                    <!-- Slides -->--}}
{{--                    <div class="swiper-slide reviews_swiper-slide">--}}
{{--                        <div class="reviews_content d-flex flex-column">--}}
{{--                            <div class="reviews_user-info d-flex flex-row">--}}
{{--                                <div class="reviews_user-avatar">--}}
{{--                                    <img src="{{asset('storage/images/home/avatar-svgrepo-com 1.png')}}" alt="аватар пользователя">--}}
{{--                                </div>--}}
{{--                                <div class="reviews_user-rating-name d-flex flex-column">--}}
{{--                                    <div class="reviews_user-name">валерий</div>--}}
{{--                                    <div class="rating-holder">--}}
{{--                                        <div class="c-rating c-rating--regular" data-rating-value="4.25">--}}
{{--                                            <span>1</span>--}}
{{--                                            <span>2</span>--}}
{{--                                            <span>3</span>--}}
{{--                                            <span>4</span>--}}
{{--                                            <span>5</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="reviews_comment">--}}
{{--                                <p>Спасибо большое менеджеру Ирине за оперативное и недорогое решение по кухне. Идеально вписались в бюджет и дизайн, сроки порадовали - собрали и изготовили очень быстро. Рекомендую!</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="swiper-slide reviews_swiper-slide">--}}
{{--                        <div class="reviews_content d-flex flex-column">--}}
{{--                            <div class="reviews_user-info d-flex flex-row">--}}
{{--                                <div class="reviews_user-avatar">--}}
{{--                                    <img src="{{asset('storage/images/home/avatar-svgrepo-com 1.png')}}" alt="аватар пользователя">--}}
{{--                                </div>--}}
{{--                                <div class="reviews_user-rating-name d-flex flex-column">--}}
{{--                                    <div class="reviews_user-name">валерий</div>--}}
{{--                                    <div class="rating-holder">--}}
{{--                                        <div class="c-rating c-rating--regular" data-rating-value="4.25">--}}
{{--                                            <span>1</span>--}}
{{--                                            <span>2</span>--}}
{{--                                            <span>3</span>--}}
{{--                                            <span>4</span>--}}
{{--                                            <span>5</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="reviews_comment">--}}
{{--                                <p>Спасибо большое менеджеру Ирине за оперативное и недорогое решение по кухне. Идеально вписались в бюджет и дизайн, сроки порадовали - собрали и изготовили очень быстро. Рекомендую!</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="swiper-slide reviews_swiper-slide">--}}
{{--                        <div class="reviews_content d-flex flex-column">--}}
{{--                            <div class="reviews_user-info d-flex flex-row">--}}
{{--                                <div class="reviews_user-avatar">--}}
{{--                                    <img src="{{asset('storage/images/home/avatar-svgrepo-com 1.png')}}" alt="аватар пользователя">--}}
{{--                                </div>--}}
{{--                                <div class="reviews_user-rating-name d-flex flex-column">--}}
{{--                                    <div class="reviews_user-name">валерий</div>--}}
{{--                                    <div class="rating-holder">--}}
{{--                                        <div class="c-rating c-rating--regular" data-rating-value="4.25">--}}
{{--                                            <span>1</span>--}}
{{--                                            <span>2</span>--}}
{{--                                            <span>3</span>--}}
{{--                                            <span>4</span>--}}
{{--                                            <span>5</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="reviews_comment">--}}
{{--                                <p>Спасибо большое менеджеру Ирине за оперативное и недорогое решение по кухне. Идеально вписались в бюджет и дизайн, сроки порадовали - собрали и изготовили очень быстро. Рекомендую!</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="swiper-slide reviews_swiper-slide">--}}
{{--                        <div class="reviews_content d-flex flex-column">--}}
{{--                            <div class="reviews_user-info d-flex flex-row">--}}
{{--                                <div class="reviews_user-avatar">--}}
{{--                                    <img src="{{asset('storage/images/home/avatar-svgrepo-com 1.png')}}" alt="аватар пользователя">--}}
{{--                                </div>--}}
{{--                                <div class="reviews_user-rating-name d-flex flex-column">--}}
{{--                                    <div class="reviews_user-name">валерий</div>--}}
{{--                                    <div class="rating-holder">--}}
{{--                                        <div class="c-rating c-rating--regular" data-rating-value="4.25">--}}
{{--                                            <span>1</span>--}}
{{--                                            <span>2</span>--}}
{{--                                            <span>3</span>--}}
{{--                                            <span>4</span>--}}
{{--                                            <span>5</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="reviews_comment">--}}
{{--                                <p>Спасибо большое менеджеру Ирине за оперативное и недорогое решение по кухне. Идеально вписались в бюджет и дизайн, сроки порадовали - собрали и изготовили очень быстро. Рекомендую!</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="swiper-slide reviews_swiper-slide">--}}
{{--                        <div class="reviews_content d-flex flex-column">--}}
{{--                            <div class="reviews_user-info d-flex flex-row">--}}
{{--                                <div class="reviews_user-avatar">--}}
{{--                                    <img src="{{asset('storage/images/home/avatar-svgrepo-com 1.png')}}" alt="аватар пользователя">--}}
{{--                                </div>--}}
{{--                                <div class="reviews_user-rating-name d-flex flex-column">--}}
{{--                                    <div class="reviews_user-name">валерий</div>--}}
{{--                                    <div class="rating-holder">--}}
{{--                                        <div class="c-rating c-rating--regular" data-rating-value="4.25">--}}
{{--                                            <span>1</span>--}}
{{--                                            <span>2</span>--}}
{{--                                            <span>3</span>--}}
{{--                                            <span>4</span>--}}
{{--                                            <span>5</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="reviews_comment">--}}
{{--                                <p>Спасибо большое менеджеру Ирине за оперативное и недорогое решение по кухне. Идеально вписались в бюджет и дизайн, сроки порадовали - собрали и изготовили очень быстро. Рекомендую!</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
    <section id="schemeWork" class="scheme-work">
        <h3>схема работы</h3>
        <div class="scheme-work_content d-flex flex-row justify-content-between">
            <div class="scheme-work_item d-flex flex-column">
                <div class="scheme-work_item-icon">
                    <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M44.1502 34.4164L42.9355 35.6239C42.9355 35.6239 40.0486 38.4946 32.1686 30.6591C24.2886 22.8239 27.1755 19.9534 27.1755 19.9534L27.9403 19.1929C29.8246 17.3195 30.0022 14.3117 28.3582 12.1159L24.9957 7.62412C22.9611 4.90636 19.0295 4.54735 16.6975 6.86612L12.5119 11.028C11.3556 12.1777 10.5807 13.6681 10.6747 15.3215C10.9151 19.5514 12.8289 28.6524 23.5079 39.2708C34.8326 50.5311 45.4585 50.9786 49.8038 50.5735C51.1782 50.4455 52.3734 49.7455 53.3366 48.7876L57.1249 45.0212C59.6819 42.4786 58.9609 38.1196 55.6891 36.3412L50.5945 33.5716C48.4462 32.4039 45.8291 32.7468 44.1502 34.4164Z" fill="#786B61"/>
                    </svg>
                </div>
                <div class="scheme-work_text">
                    <h4>Обращение в компанию</h4>
                    <p>Отличный планшет, недорогой, мощный, игрушки летают, заряд держит долго, зарядка в комплекте быстрая</p>
                </div>
            </div>
            <div class="scheme-work_item d-flex flex-column">
                <div class="scheme-work_item-icon">
                    <svg width="58" height="58" viewBox="0 0 58 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M39.875 1.8125H16.3125V5.4375H39.875V1.8125Z" fill="#786B61"/>
                        <path d="M44.5839 23.5589C40.4514 20.1151 38.0625 15.0148 38.0625 9.63525V7.25H18.125V9.63525C18.125 15.0148 15.7361 20.1151 11.6036 23.5589L7.25 27.1875L27.1875 56.1875V36.1594C25.1194 35.7389 23.5625 33.9119 23.5625 31.7188C23.5625 29.2157 25.5907 27.1875 28.0938 27.1875C30.5968 27.1875 32.625 29.2157 32.625 31.7188C32.625 33.9101 31.0681 35.7389 29 36.1594V56.1875L48.9375 27.1875L44.5839 23.5589Z" fill="#786B61"/>
                        <path d="M39.875 1.8125H16.3125V5.4375H39.875V1.8125Z" fill="#786B61"/>
                        <path d="M44.5839 23.5589C40.4514 20.1151 38.0625 15.0148 38.0625 9.63525V7.25H18.125V9.63525C18.125 15.0148 15.7361 20.1151 11.6036 23.5589L7.25 27.1875L27.1875 56.1875V36.1594C25.1194 35.7389 23.5625 33.9119 23.5625 31.7188C23.5625 29.2157 25.5907 27.1875 28.0938 27.1875C30.5968 27.1875 32.625 29.2157 32.625 31.7188C32.625 33.9101 31.0681 35.7389 29 36.1594V56.1875L48.9375 27.1875L44.5839 23.5589Z" fill="#786B61"/>
                    </svg>
                </div>
                <div class="scheme-work_text">
                    <h4>Разработка индивидуального дизайн проекта</h4>
                    <p>Наши дизайнеры учитывают множество неочивидных факторов при работе над проектом, что гарантирует на сто процентов все поелания наших клиентов.</p>
                </div>
            </div>
            <div class="scheme-work_item d-flex flex-column">
                <div class="scheme-work_item-icon">
                    <svg width="47" height="47" viewBox="0 0 47 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.9862 11.3323V0.630127L2.67773 12.9394H13.379C14.5358 12.9394 14.9862 12.489 14.9862 11.3323Z" fill="#786B61"/>
                        <path d="M44.5538 16.5449L41.7133 13.7043C41.2185 13.2095 40.4173 13.2103 39.9225 13.7043L38.0811 15.5458L42.7132 20.1773L44.5539 18.3349C45.0479 17.8402 45.0487 17.039 44.5538 16.5449Z" fill="#786B61"/>
                        <path d="M17.6805 41.9774C17.2878 42.0771 16.8722 41.9632 16.5856 41.6766C16.299 41.39 16.1843 40.9735 16.284 40.5808L18.9481 30.094L33.4947 15.5458L34.6609 14.3804L37.2394 11.801L37.6297 11.4115C37.7659 11.2753 37.9202 11.1661 38.0699 11.0498V2.08223C38.0699 0.934217 37.135 0 35.9861 0H17.3662V12.7645C17.3662 14.173 16.2206 15.3194 14.813 15.3194H2.07617V44.9162C2.07617 46.065 3.01112 47 4.15996 47H35.9862C37.135 47 38.07 46.065 38.07 44.9162V29.4053L28.1666 39.3103L17.6805 41.9774Z" fill="#786B61"/>
                        <path d="M21.8718 31.7567L20.2939 37.9668L26.5033 36.3873L41.8702 21.0189L37.2396 16.3875L21.8718 31.7567ZM24.7758 32.8088L23.7237 31.7566L37.1129 18.3673L38.1659 19.4203L24.7758 32.8088Z" fill="#786B61"/>
                    </svg>
                </div>
                <div class="scheme-work_text">
                    <h4>Заключение договора</h4>
                    <p>После согласия дизайна проекта, с клиентом заключается договор на поставку комплектующих и оказания услуг.</p>
                </div>
            </div>
            <div class="scheme-work_item d-flex flex-column">
                <div class="scheme-work_item-icon">
                    <svg width="63" height="63" viewBox="0 0 63 63" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_65_119)">
                            <path d="M11.55 5.77393C5.18175 5.77393 0 10.9557 0 17.3238C0 23.6921 5.18175 28.8739 11.55 28.8739C17.9183 28.8739 23.1001 23.6921 23.1001 17.3238C23.1 10.9557 17.9182 5.77393 11.55 5.77393ZM16.4923 14.9163L9.14238 22.2663C8.93763 22.471 8.66877 22.5739 8.40004 22.5739C8.13131 22.5739 7.86245 22.471 7.6577 22.2663L5.55766 20.1662C5.14705 19.7557 5.14705 19.092 5.55766 18.6815C5.96827 18.2711 6.63186 18.2709 7.04234 18.6815L8.40004 20.0392L15.0077 13.4316C15.4183 13.021 16.0819 13.021 16.4923 13.4316C16.9028 13.8422 16.9029 14.5057 16.4923 14.9163Z" fill="#786B61"/>
                            <path d="M49.3498 44.626C45.8753 44.626 43.0498 47.4515 43.0498 50.926C43.0498 54.4005 45.8753 57.226 49.3498 57.226C52.8243 57.226 55.6498 54.4005 55.6498 50.926C55.6498 47.4515 52.8242 44.626 49.3498 44.626ZM49.3498 55.1261C47.0334 55.1261 45.1498 53.2423 45.1498 50.9261C45.1498 48.6097 47.0336 46.7261 49.3498 46.7261C51.6662 46.7261 53.5498 48.6099 53.5498 50.9261C53.5498 53.2423 51.666 55.1261 49.3498 55.1261Z" fill="#786B61"/>
                            <path d="M13.6496 44.626C10.1751 44.626 7.34961 47.4515 7.34961 50.926C7.34961 54.4005 10.1751 57.226 13.6496 57.226C17.1241 57.226 19.9496 54.4005 19.9496 50.926C19.9496 47.4515 17.1241 44.626 13.6496 44.626ZM13.6496 55.1261C11.3333 55.1261 9.44965 53.2423 9.44965 50.9261C9.44965 48.6097 11.3334 46.7261 13.6496 46.7261C15.9658 46.7261 17.8496 48.6099 17.8496 50.9261C17.8497 53.2423 15.966 55.1261 13.6496 55.1261Z" fill="#786B61"/>
                            <path d="M60.9 39.3761H62.475C62.7647 39.3761 63 39.1409 63 38.851V37.8011C63 37.5113 62.7649 37.276 62.475 37.276H48.3C47.7204 37.276 47.25 36.8056 47.25 36.2261V26.251C47.25 25.9612 47.0149 25.726 46.725 25.726H44.1C43.5205 25.726 43.05 26.1964 43.05 26.7759V42.5259H40.95C40.95 34.5974 40.95 20.4759 40.95 20.4759C40.95 18.1596 39.0663 16.276 36.75 16.276H25.8731C25.5643 16.2477 25.2063 16.4587 25.2063 16.7684C25.2063 16.8388 25.2063 16.8965 25.2063 16.9428C25.1873 24.0586 19.908 30.2589 12.8215 30.9035C8.57846 31.2899 4.68612 29.7191 1.94143 26.9943C1.60958 26.6657 1.04996 26.8925 1.04996 27.3598C1.04996 29.8483 1.04996 35.0868 1.04996 36.7542C1.04996 37.044 1.2851 37.276 1.575 37.276H3.15C3.72955 37.276 4.19996 37.7464 4.19996 38.326C4.19996 38.9055 3.72955 39.3759 3.15 39.3759H1.575C1.28522 39.3759 1.04996 39.6111 1.04996 39.901V41.476C0.470408 41.476 0 41.9464 0 42.5259V47.8736C0 48.1035 0.101883 48.3166 0.266643 48.4773C0.623602 48.827 0.444076 48.8259 4.725 48.8218C5.12293 48.8218 5.58916 48.5235 5.72045 48.1477C7.58215 42.8305 14.4816 40.2674 19.9207 45.0482C20.8016 45.822 21.23 46.935 21.5524 48.0627C21.6826 48.5162 22.0953 48.824 22.5615 48.824H39.9002C40.4797 48.824 40.9501 48.3536 40.9501 47.774C40.9501 47.7729 40.9501 47.7708 40.9501 47.7687C40.9501 47.7036 40.9501 46.4898 40.9501 44.624H43.4103C43.6581 44.624 43.8954 44.5316 44.0887 44.3762C46.658 42.3077 50.1441 41.9055 53.5713 43.5099C55.4455 44.3878 56.5784 46.178 57.2736 48.1279C57.4228 48.5458 57.8186 48.8251 58.2627 48.8251H61.9503C62.5298 48.8251 63.0003 48.3547 63.0003 47.7751V42.0002C63.0003 41.7104 62.7651 41.4751 62.4752 41.4751H60.9002C60.3207 41.4751 59.8503 41.0047 59.8503 40.4252C59.8503 39.8456 60.3204 39.3761 60.9 39.3761Z" fill="#786B61"/>
                            <path d="M62.9282 34.3969L58.1307 26.2667C57.9343 25.9339 57.5605 25.7271 57.1563 25.7271H49.9071C49.5995 25.7271 49.3496 25.9622 49.3496 26.2521V34.6521C49.3496 34.9419 49.5995 35.1772 49.9071 35.1772H62.4409C62.8662 35.1772 63.135 34.7466 62.9282 34.3969Z" fill="#786B61"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_65_119">
                                <rect width="63" height="63" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                <div class="scheme-work_text">
                    <h4>Доставка и сборка кухонного гарнитура</h4>
                    <p>Доставка и сборка всех комплектующих, осуществляется после подписания клиентом договора купли продажи и оплаты всех товаров и услуг на 100%</p>
                </div>
            </div>
        </div>
    </section>
    <section id="calcKitchen" class="calc">
        <div class="calc_container d-flex flex-row justify-content-between">
            <div class="calc_image">
                <img src="{{asset('storage/images/home/Rectangle70.jpg')}}" alt="изображение конфигуратора">
            </div>
            <div class="calc_form">
                <h5>расчет кухни</h5>
                <form id="configForm" action="{{ route('shop.order') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_name" value="">
                    <input type="hidden" name="user_phone" value="">
                    <input type="hidden" name="subject" value="Узнать примерную стоимость кухни">
                    <input type="hidden" name="product_name" value="Конфигуратор">
                    <div class="custom-label">Выберите конфигурацию:</div>
                    <div class="form-check form-check-inline with-img line">
                        <input class="form-check-input" type="radio" name="CalcForm[type]" id="type1" checked="" value="1">
                        <label class="form-check-label align-self-end" for="type1">
                            <span>Прямая</span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline with-img angle">
                        <input class="form-check-input" type="radio" name="CalcForm[type]" id="type2" value="2">
                        <label class="form-check-label align-self-end" for="type2">
                            <span>Угловая</span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline with-img u-shaped">
                        <input class="form-check-input" type="radio" name="CalcForm[type]" id="type3" value="3">
                        <label class="form-check-label align-self-end" for="type3">
                            <span>П-образная</span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline with-img island">
                        <input class="form-check-input" type="radio" name="CalcForm[type]" id="type4" value="4">
                        <label class="form-check-label align-self-end" for="type4">
                            <span>с островом</span>
                        </label>
                    </div>
                    <div class="custom-label">Выберите материал:</div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="CalcForm[material]" id="ldsp" value="1">
                            <label class="form-check-label" for="ldsp">ЛДСП</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="CalcForm[material]" id="mdf" checked="" value="2">
                            <label class="form-check-label" for="mdf">МДФ</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="CalcForm[material]" id="plastic" value="3">
                            <label class="form-check-label" for="plastic">Пластик</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="CalcForm[material]" id="pvch" value="4">
                            <label class="form-check-label" for="pvch">ПВХ</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="CalcForm[material]" id="enamel" value="5">
                            <label class="form-check-label" for="enamel">Эмаль</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="CalcForm[material]" id="massive" value="6">
                            <label class="form-check-label" for="massive">Массив</label>
                        </div>
                    </div>
                    <div class="labels d-flex flex-row justify-content-between">
                        <div class="col-7">
                            <label class="custom-label" for="size_a">
                                Укажите размеры в метрах:<span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="col-5">
                            <label class="custom-label">Верхний ряд:</label>
                        </div>
                    </div>
                    <div class="row mb-4 justify-content-between">
                        <div class="col-md-7 mb-3 d-flex justify-content-between column-gap-1">
                            <div class="sub-field">
                                <input type="number" id="size_a" class="form-control" name="CalcForm[sizeA]" placeholder="Размер А" step="any" min="0" max="99" aria-required="true" required>
                            </div>
                            <div class="sub-field d-none">
                                <input type="number" id="size_b" class="form-control" name="CalcForm[sizeB]" placeholder="Размер Б" step="any" min="0" max="99" aria-label="Размер Б">
                            </div>
                            <div class="sub-field d-none">
                                <input type="number" id="size_c" class="form-control" name="CalcForm[sizeC]" placeholder="Размер В" step="any" min="0" max="99" aria-label="Размер В">
                            </div>
                        </div>

                        <div class="col-md-5 top-row p-0">
                            <div class="on-off">
                                <input type="radio" name="CalcForm[topRow]" id="top_row_yes" value="on" checked>
                                <label for="top_row_yes" class="label1">
                                    <span>Да</span>
                                </label>
                                <input type="radio" name="CalcForm[topRow]" id="top_row_no" value="off">
                                <label for="top_row_no" class="label2">
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                        <div class="d-flex d-none">
                            <div class="col-sm-6 col-md-3 mb-2 pe-3">
                                <label class="custom-label" for="calcform-name">
                                    Контактные данные:<span class="text-danger">*</span>
                                </label>
                                <input type="text" id="calcform-name" class="form-control" name="CalcForm[name]" placeholder="Представьтесь" aria-required="true">
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <label class="custom-label invisible" for="user_phone">
                                    Телефон<span class="text-danger">*</span>
                                </label>
                                <input type="text" id="user_phone" class="form-control" name="CalcForm[phone]" placeholder="+7(___) ___-__-__" aria-required="true">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-brown w-100" type="submit">
                                Получить стоимость<i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="help-block"></div>
                </form>
            </div>
        </div>
    </section>
@endsection
