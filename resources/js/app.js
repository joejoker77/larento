import '/resources/js/bootstrap';
import Swiper from "swiper";
import 'swiper/css';
import {
    Autoplay,
    EffectCoverflow,
    FreeMode,
    Mousewheel,
    Navigation,
    Pagination,
    Scrollbar,
    Thumbs
} from "swiper/modules";
import videojs from "video.js";
import IMask from "imask";

const mainBanner          = document.getElementById('mainBanner'),
    reviewSlider          = document.querySelector('.reviews_swiper'),
    productTeasers        = document.querySelectorAll('.product_item-images'),
    promotionModule       = document.getElementById('promotionModule'),
    tooltipTriggerList    = document.querySelectorAll('[data-bs-toggle="tooltip"]'),
    tooltipList           = [...tooltipTriggerList].map(tooltipTriggerEl => new window.Tooltip(tooltipTriggerEl)),
    calcControls          = document.querySelectorAll("input[name='CalcForm[type]']"),
    popoverLink           = document.querySelector('.popover-link'),
    commentForm           = document.getElementById('sendComment'),
    collapseFilterItemBnt = document.querySelectorAll('.filter-item .btn-link'),
    swiperScrollbar       = document.querySelector('.swiper-scrollbar-block'),
    formFilter            = document.querySelector('form.form-filter'),
    scrollUpButton        = document.getElementById('scrollUp'),
    modal                 = document.getElementById('mainModal'),
    modalWindow           = new Modal(modal),
    modalButton           = document.querySelectorAll('[name="js-modal"]'),
    userPhone             = document.getElementById('userPhone'),
    configForm            = document.getElementById('configForm'),
    orderDesignForm       = document.getElementById("orderDesignForm"),
    menuMobile            = document.getElementById('mainMenuMobile'),
    sortFilterControlsBtn = document.querySelectorAll('.sort-controls button'),
    popOverLinkBig        = document.querySelector('.popover-link');

let mediaTillLG = window.matchMedia('(max-width: 992px)');

modal.addEventListener('hidden.bs.modal', event => {
    modal.querySelector('.modal-body').innerHTML = '';
    modal.querySelector('.modal-title').innerHTML = '';
});

if (mediaTillLG.matches && popoverLink) {
    popoverLink.setAttribute('data-bs-offset', '0,-172');
}

if (sortFilterControlsBtn.length > 0) {
    const filterBlock = document.querySelector('.filter-wrapper'),
        sortBlock     = document.querySelector('.product-sort');

    sortFilterControlsBtn.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.stopPropagation();
            event.preventDefault();
            if (button.textContent === 'Фильтр') {
                filterBlock.classList.toggle('show');
            } else {
                sortBlock.classList.toggle('show');
            }
        });
    });
}

if (userPhone) {
    IMask(userPhone, {mask:[{mask: '+{7} 000 000 00 00'},{mask: /^\s*@?\s*$/}]});
}

if (orderDesignForm) {
    orderDesignForm.addEventListener('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();

        const userPhoneInput = orderDesignForm.querySelector('input[name=user_phone]'),
            userNameInput    = orderDesignForm.querySelector('input[name=user_name]');

        userNameInput.addEventListener('keyup', function (event) {
            event.target.classList.remove('is-invalid');
        });

        userPhoneInput.addEventListener('keyup', function (event) {
            event.target.classList.remove('is-invalid');
        });

        if (userPhoneInput.value.length === 16) {
            userPhoneInput.classList.remove('is-invalid');
        } else {
            userPhoneInput.classList.add('is-invalid');
        }

        if (userNameInput.validity.valid) {
            userNameInput.classList.remove('is-invalid');
        } else {
            userNameInput.classList.add('is-invalid');
        }

        if (userNameInput.validity.valid && userPhoneInput.value.length === 16) {
            orderDesignForm.submit();
        }
    });
}

function getVirtualForm()
{
    const formElement    = document.createElement('form'),
        inputUserName    = document.createElement('input'),
        inputUserPhone   = document.createElement('input'),
        buttonSubmit     = document.createElement('button'),
        wrapperUserName  = document.createElement('div'),
        wrapperUserPhone = document.createElement('div'),
        labelUserName    = document.createElement('label'),
        labelUserPhone   = document.createElement('label');

    formElement.action = '/shop/order';
    formElement.method = 'POST';
    formElement.setAttribute('novalidate', 'novalidate');
    formElement.classList.add('needs-validation');

    inputUserName.id       = 'userName';
    inputUserName.type     = 'text';
    inputUserName.name     = 'user_name';
    inputUserName.required = true;
    inputUserName.classList.add('form-control');
    inputUserName.setAttribute('placeholder', 'Укажите ваше имя');
    inputUserName.setAttribute('autocomplete', 'off');
    inputUserName.setAttribute('pattern', '^[а-яё\\s]+$');
    inputUserName.setAttribute('minlength', '3');
    inputUserName.setAttribute('maxlength', '30');

    inputUserPhone.id        = 'userPhone';
    inputUserPhone.type      = 'text';
    inputUserPhone.name      = 'user_phone';
    inputUserPhone.required  = true;
    inputUserPhone.classList.add('form-control', 'js-phone');
    inputUserPhone.setAttribute('placeholder', 'Укажите ваш номер телефона');
    inputUserPhone.setAttribute('autocomplete', 'off');

    IMask(inputUserPhone, {mask:[{mask: '+{7} 000 000 00 00'},{mask: /^\s*@?\s*$/}]});

    buttonSubmit.classList.add('btn', 'btn-brown');
    buttonSubmit.textContent = 'Отправить';

    wrapperUserName.classList.add('form-floating', 'mb-3');
    wrapperUserPhone.classList.add('form-floating', 'mb-3');

    labelUserName.setAttribute('for', 'userName');
    labelUserName.textContent  = 'Укажите ваше имя';
    labelUserPhone.setAttribute('for', 'userPhone');
    labelUserPhone.textContent = 'Укажите ваш номер телефона';

    wrapperUserName.append(inputUserName);
    wrapperUserName.append(labelUserName);
    wrapperUserPhone.append(inputUserPhone);
    wrapperUserPhone.append(labelUserPhone);
    formElement.append(wrapperUserName);
    formElement.append(wrapperUserPhone);
    formElement.append(buttonSubmit);

    return formElement;
}

if (modalButton.length > 0) {
    const formElement         =  getVirtualForm();
    let subject, product_name = null;
    modalButton.forEach(function (button) {
        button.addEventListener('click', function (event) {
            subject      = event.target.tagName === 'INPUT' ? event.target.value : event.target.textContent;
            product_name = event.target.tagName === 'INPUT' ?
                button.closest('.info').querySelector('h3.h3').textContent :
                button.dataset.productName;

            let title = subject+': '+product_name;

            modal.querySelector('.modal-body').append(formElement);
            modal.querySelector('.modal-title').append(title.toUpperCase());
            modalWindow.show();

            formElement.addEventListener('submit',function (event) {
                event.preventDefault();
                event.stopPropagation();

                const formData = new FormData(formElement);

                formData.append('subject', subject);
                formData.append('product_name', product_name);

                const userPhoneInput = formElement.querySelector('input[name=user_phone]'),
                    userNameInput    = formElement.querySelector('input[name=user_name]'),
                    userNameError    = document.createElement('div'),
                    userPhoneError   = document.createElement('div');

                userNameError.classList.add('invalid-tooltip');
                userPhoneError.classList.add('invalid-tooltip');

                userNameError.textContent = 'Имя должно состоять из русских букв и пробелов. Не более 30 символов';
                userPhoneError.textContent = 'Телефон должен содержать 10 цифр, не считая +7';

                userPhoneInput.addEventListener('keyup', function (eventKeyUp) {
                    eventKeyUp.target.classList.remove('is-invalid');
                });

                userNameInput.addEventListener('keyup', function (eventKeyUp) {
                    eventKeyUp.target.classList.remove('is-invalid');
                });

                if(userPhoneInput.value.length !== 16) {
                    if (!userNameInput.parentElement.querySelector('.invalid-tooltip')) {
                        userPhoneInput.parentElement.append(userPhoneError);
                    }
                    userPhoneInput.classList.add('is-invalid');

                } else {
                    userPhoneInput.classList.remove('is-invalid');
                    userPhoneError.remove();
                }

                if(userNameInput.validity.valid) {
                    userNameError.remove();
                    userNameInput.classList.remove('is-invalid');
                } else {
                    if(!userNameInput.parentElement.querySelector('.invalid-tooltip')) {
                        userNameInput.parentElement.append(userNameError);
                    }
                    userNameInput.classList.add('is-invalid');
                }

                if(userNameInput.validity.valid && userPhoneInput.value.length === 16) {
                    modal.querySelector('.modal-body').innerHTML = '<div class="loader"></div>';
                    submitForm(formElement, formData);
                }

                function submitForm(formElement, formData) {
                    axios.post(formElement.action, formData).then(function (response) {
                        if (response.status === 200 && response.statusText === 'OK') {
                            modal.querySelector('.modal-body').innerHTML = '<p>Спасибо за вашу заявку.</p><p>Наши менеджеры свяжутся с вами в ближайшее время.</p>';

                            setTimeout(function () {
                                modalWindow.hide();
                                formElement.querySelector('input[name=user_phone]').value = '';
                                formElement.querySelector('input[name=user_name]').value  = '';
                                modal.querySelector('.modal-body').innerHTML = '';
                                modal.querySelector('.modal-title').innerHTML = '';
                            }, 4000);
                        }
                    }).catch(function (error) {
                        console.error(error);
                    });
                }

                return false;
            });
        });
    });
}

if (scrollUpButton) {
    scrollUpButton.addEventListener('click', function (event) {
        event.preventDefault();

        window.scrollTo(0, 0);
    });

    document.addEventListener('scroll', function (event) {
        if (window.scrollY >= 1000) {
            scrollUpButton.classList.add('show');
        } else {
            scrollUpButton.classList.remove('show');
        }
    });
}

if (commentForm) {
    const ratingBlock = commentForm.querySelector('.c-rating');
    ratingBlock.addEventListener('click', function (event) {
        this.nextElementSibling.value = event.target.textContent;
        this.dataset.ratingValue      = event.target.textContent;
    });
    ratingBlock.addEventListener('mouseover', function (event) {
        if (this.nextElementSibling.value <= event.target.textContent) {
            this.dataset.ratingValue = event.target.textContent;
        }
    });
    ratingBlock.addEventListener('mouseout', function (event) {
        if(!event.target.parentNode.matches(":hover") && this.nextElementSibling.value === "0") {
            this.dataset.ratingValue = "0";
        }
    });
}

if (popoverLink) {
    new window.Popover(popoverLink, {html: true, trigger: 'hover'});
}

if (swiperScrollbar) {

    const commentCount = document.querySelectorAll('.commentary').length;

    if (commentCount > 3 && mediaTillLG.matches) {
        document.querySelector('.swiper-scrollbar-vertical').classList.remove('d-none');
        new Swiper(swiperScrollbar, {
            direction: 'vertical',
            slidesPerView: 'auto',
            freeMode: true,
            scrollbar : {
                el: '.swiper-scrollbar',
                hide: false,
                draggable: true,
            },
            mousewheel:true,
            modules: [Scrollbar, Mousewheel, FreeMode],
        });
    } else {
        document.querySelector('.swiper-scrollbar-vertical').classList.add('d-none');
    }
}

if (menuMobile && mediaTillLG.matches) {
    const collapseContent = menuMobile.querySelector('.navbar-collapse');

    collapseContent.addEventListener('shown.bs.collapse', event => {
        document.body.classList.add('blocked');
    });

    collapseContent.addEventListener('hide.bs.collapse', event => {
        document.body.classList.remove('blocked');
    });
}

if (mainBanner) {
    const textBlocks = mainBanner.querySelectorAll('.text-block');
    if (mediaTillLG.matches) {
        textBlocks.forEach(function (textBlock) {
            textBlock.removeAttribute('style');
        });
    }
    new Swiper(mainBanner, {
        loop:true,
        height:640,
      //  autoplay: {delay: 5000},
        navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev'},
        pagination: {el:'.swiper-pagination',clickable: true},
        modules: [Navigation, Autoplay, Pagination]
    });
}

if (reviewSlider) {

    const prevButton = document.querySelector('#reviews .reviews_swiper-button-prev'),
        nextButton = document.querySelector('#reviews .reviews_swiper-button-next');

    new Swiper(reviewSlider, {
        loop:true,
        height:290,
        width: 1179,
        slidesPerView: 3,
        spaceBetween: 6,
        autoplay: {delay: 5000},
        navigation: {nextEl: nextButton, prevEl: prevButton},
        modules: [Navigation, Autoplay]
    });
}

if(productTeasers.length > 0) {
    productTeasers.forEach(function (teaser) {

        const teaserSwiper = new Swiper(teaser, {
            loop:false,
            height: 245,
            slidesPerView: 1,
            spaceBetween:2,
            pagination: {el: teaser.querySelector('.swiper-pagination'), clickable: true},
            modules: [Pagination]
        });

        teaser.addEventListener('mouseout', teaserMouseOut);

        function teaserMouseOut (event) {
            event.preventDefault();
            event.stopPropagation();
            event.cancelBubble = true;
            if (event.target.tagName === 'IMG') {
                teaserSwiper.slideTo(0);
            }
        }
    });
}

if (promotionModule) {

    const prevButtonPromotion = promotionModule.querySelector('.swiper-button-prev'),
        nextButtonPromotion   = promotionModule.querySelector('.swiper-button-next'),
        swiperElement         = promotionModule.querySelector('.swiper');

    new Swiper(swiperElement, {
        loop: true,
        width: 1400,
        slidesPerView: 5,
        spaceBetween: 16,
        autoHeight: true,
        autoplay: {delay: 5000},
        navigation: {nextEl: nextButtonPromotion, prevEl: prevButtonPromotion},
        modules: [Navigation, Autoplay],
        breakpoints: {
            360: {
                slidesPerView: 1,
                width: 267,
                spaceBetween: 8,
                loop: false
            },
            520: {
                slidesPerView: 2,
                width: 534,
                loop: false
            },
            990: {
                slidesPerView: 3,
                width: 950,
                loop: false
            },
            1024: {
                slidesPerView: 4,
                width: 984
            },
            1400: {
                slidesPerView: 5,
                width: 1400
            }
        }
    });
}

if (calcControls.length > 0) {
    [].forEach.call(calcControls, (el) => {
        el.addEventListener('change', (e) => {
            let inputB = document.getElementById('size_b'),
                inputC = document.getElementById('size_c');

            switch (e.target.id) {
                case 'type2' :
                    inputB.parentElement.classList.remove('d-none');
                    inputB.setAttribute('required', 'required');
                    inputC.parentElement.classList.add('d-none');
                    inputC.removeAttribute('required');
                    break;
                case 'type3' :
                    inputB.parentElement.classList.remove('d-none');
                    inputB.setAttribute('required', 'required');
                    inputC.parentElement.classList.remove('d-none');
                    inputC.setAttribute('required', 'required');
                    break;
                case 'type4' :
                    inputB.parentElement.classList.remove('d-none');
                    inputB.setAttribute('required', 'required');
                    inputC.parentElement.classList.add('d-none');
                    inputC.removeAttribute('required');
                    break;
                default:
                    inputC.parentElement.classList.add('d-none');
                    inputC.removeAttribute('required');
                    inputB.parentElement.classList.add('d-none');
                    inputB.removeAttribute('required');
                    break;
            }
        });
    });
}

if(collapseFilterItemBnt.length > 0) {
    collapseFilterItemBnt.forEach(function (button) {
        const container = button.closest('.filter-item'),
            defaultText = button.textContent,
            altText     = 'Скрыть';

        button.addEventListener('click', function (event) {
            container.classList.toggle('collapsed');
            if (container.classList.contains('collapsed')) {
                button.textContent = defaultText;
            } else {
                button.textContent = altText;
            }
        });
    });

    if(mediaTillLG.matches) {
        const closeFilter = document.getElementById('closeFilter'),
            filter        = document.querySelector('.filter-wrapper');

        if (closeFilter) {
            closeFilter.addEventListener('click', function (event) {
                event.preventDefault();
                console.log('check');
                filter.classList.remove('show');
                window.scrollTo(0, 0);
            })
        }
    }
}

if (formFilter) {
    formFilter.querySelectorAll('input').forEach(function (elInput) {
        elInput.addEventListener('change', function (event) {
            formFilter.submit();
        });
    });
}

document.addEventListener("DOMContentLoaded", function() {
    if (window.innerWidth > 992) {
        document.querySelectorAll('.navbar .nav-item').forEach(function(everyitem){

            everyitem.addEventListener('show.bs.dropdown', function (event) {
                location.href = event.target.href;
            });

            everyitem.addEventListener('hide.bs.dropdown', function (event) {
                location.href = event.target.href;
            });

            everyitem.addEventListener('mouseover', function(e){
                let el_link = this.querySelector('a.dropdown-toggle');
                if(el_link != null && el_link.nextElementSibling){
                    let nextEl = el_link.nextElementSibling;
                    el_link.classList.add('show');
                    nextEl.classList.add('show');
                }
            });

            everyitem.addEventListener('mouseleave', function(e){
                let el_link = this.querySelector('a.dropdown-toggle');

                if(el_link != null && el_link.nextElementSibling){
                    let nextEl = el_link.nextElementSibling;
                    el_link.classList.remove('show');
                    nextEl.classList.remove('show');
                }
            });
        });
    }
});

class MainGallery extends HTMLElement
{
    constructor() {
        super();
        this.fullSwiper    = this.querySelector('.full-swiper');
        this.thumbSwiper   = this.querySelector('.thumbs-swiper');
        this.fullScreenBtn = this.querySelector('.full-screen-button');

        this.fullScreenBtn.addEventListener('click', this.fullScreenToggle.bind(this));

        this.initVideoPlayer();
        this.initSwiper(this.fullSwiper, this.thumbSwiper);

        this.sizeReplace = this.dataset.sizereplace ?? 'medium';
    }

    fullScreenToggle(event) {
        event.preventDefault();
        event.stopPropagation();
        event.cancelBubble = true;

        const images = this.querySelectorAll('.full-swiper img'),
            self = this;

        if (!document.fullscreenElement) {
            images.forEach(function (image) {
                const src = image.src;
                image.src = src.replace(self.sizeReplace+'_', 'full_');
            });
            this.classList.add('fullscreen');
            this.requestFullscreen().then(function () {
                event.target.parentElement.querySelector('.close').classList.remove('d-none');
                event.target.parentElement.querySelector('.open').classList.add('d-none');
                new window.Tooltip(event.target.parentElement.querySelector('.close'), {container: document.querySelector('.fullscreen')});
            });
        } else {
            images.forEach(function (image) {
                const src = image.src;
                image.src = src.replace('full_', self.sizeReplace+'_');
            });
            this.classList.remove('fullscreen');
            document.exitFullscreen().then(function () {
                event.target.parentElement.querySelector('.close').classList.add('d-none');
                event.target.parentElement.querySelector('.open').classList.remove('d-none');
            });
        }
    }

    initVideoPlayer() {
        const videos = this.querySelectorAll('video');
        if (videos.length > 0) {
            videos.forEach(function (video) {
                videojs(video, {
                    controls:true
                });
            });
        }
    }

    initSwiper(fullSwiper, thumbSwiper) {
        let swiper = null;
        if (thumbSwiper) {
            swiper = new Swiper(thumbSwiper, {
                spaceBetween: 6,
                slidesPerView: 'auto',
                freeMode: true,
                watchSlidesProgress: true,
                modules: [Navigation, Autoplay, Pagination, Thumbs]
            });
        }
        if (fullSwiper && swiper) {
            new Swiper(fullSwiper, {
                slidesPerView: 1,
                spaceBetween:10,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
                thumbs: {
                    swiper: swiper,
                },
                modules: [Navigation, Autoplay, Pagination, Thumbs]
            });
        } else if (fullSwiper) {
            new Swiper(fullSwiper, {
                spaceBetween:10
            });
        }
    }
}

customElements.define('main-gallery', MainGallery);

if(configForm) {
    configForm.addEventListener('submit', function (event) {
        event.preventDefault();
        event.stopPropagation();

        const userForm  = getVirtualForm(),
            subject     = configForm.querySelector('input[name=subject]').value;

        modal.querySelector('.modal-body').append(userForm);
        modal.querySelector('.modal-title').innerHTML = subject;

        modalWindow.show();

        userForm.addEventListener('submit', function (eventSubmit) {
            eventSubmit.preventDefault();
            eventSubmit.stopPropagation();

            const userPhoneInput = userForm.querySelector('input[name=user_phone]'),
                userNameInput    = userForm.querySelector('input[name=user_name]'),
                userNameError    = document.createElement('div'),
                userPhoneError   = document.createElement('div');

            userNameError.classList.add('invalid-tooltip');
            userPhoneError.classList.add('invalid-tooltip');

            userNameError.textContent = 'Имя должно состоять из русских букв и пробелов. Не более 30 символов';
            userPhoneError.textContent = 'Телефон должен содержать 10 цифр, не считая +7';

            userPhoneInput.addEventListener('keyup', function (eventKeyUp) {
                eventKeyUp.target.classList.remove('is-invalid');
            });

            userNameInput.addEventListener('keyup', function (eventKeyUp) {
                eventKeyUp.target.classList.remove('is-invalid');
            });

            configForm.querySelector('input[name=user_name]').value  = userNameInput.value;
            configForm.querySelector('input[name=user_phone]').value = userPhoneInput.value;

            if(userPhoneInput.value.length !== 16) {
                if (!userNameInput.parentElement.querySelector('.invalid-tooltip')) {
                    userPhoneInput.parentElement.append(userPhoneError);
                }
                userPhoneInput.classList.add('is-invalid');
            } else {
                userPhoneInput.classList.remove('is-invalid');
                userPhoneError.remove();
            }

            if(userNameInput.validity.valid) {
                userNameError.remove();
                userNameInput.classList.remove('is-invalid');
            } else {
                if(!userNameInput.parentElement.querySelector('.invalid-tooltip')) {
                    userNameInput.parentElement.append(userNameError);
                }
                userNameInput.classList.add('is-invalid');
            }
            configForm.submit();
            return false;
        });
        return false;
    });
}

function scrollObserver() {
    const options = {
        rootMargin: '0px',
        threshold: 1
    }
    let previousY      = 0,
        previousRatio  = 0;
    const callback = function (entries) {
        entries.forEach(entry => {
            const currentY = entry.boundingClientRect.y,
                currentRatio = entry.intersectionRatio;
            if (currentY < previousY) {
                document.querySelector('.header-bottom').classList.add('position-fixed', 'top-0');
            } else {
                document.querySelector('.header-bottom').classList.remove('position-fixed', 'top-0');
            }
            previousY = currentY;
            previousRatio = currentRatio;
        });
    }

    const observer = new IntersectionObserver(callback, options);

    observer.observe(document.querySelector('.header-top'));
}

scrollObserver();
