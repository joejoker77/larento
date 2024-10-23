<div class="order_product d-flex flex-column-reverse flex-md-row align-items-md-stretch overflow-hidden">
    <div class="order_product-form">
        <div class="h1 text-white">Бесплатный дизайн проект</div>
        <p>При заказе любой кухни, мы изготовим дизайн проект бесплатно. Закажите кухню своей мечты прямо сейчас</p>
        <form action="{{ route('shop.order') }}" method="post" id="orderDesignForm" novalidate>
            @csrf
            <input type="hidden" name="subject" value="Заявка на дизайн проект">
            <input type="hidden" name="product_name" value="Дизайн проект">
            <div class="input-group-sm mb-2">
                <div class="form-floating me-sm-3 mb-2 mb-sm-0">
                    <input name="user_name" type="text" pattern="^[а-яё\s]+$" minlength="4" maxlength="30" class="form-control" id="userName" placeholder="Имя" autocomplete="off" required>
                    <label for="userName">Имя</label>
                    <div class="invalid-tooltip">Имя должно состоять из русских букв и пробелов. Не более 30 символов</div>
                </div>
                <div class="form-floating">
                    <input name="user_phone" type="text" class="form-control" id="userPhone" placeholder="+7" autocomplete="off" required>
                    <label for="userPhone">+7</label>
                    <div class="invalid-tooltip">Телефон должен содержать 10 цифр, не считая +7</div>
                </div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="userPrivacy" checked disabled>
                <label class="form-check-label" for="userPrivacy">
                    Нажимая на кнопку отправить Вы соглашаетесь с политикой конфиденциальности
                </label>
            </div>
            <button class="btn btn-outline-light" type="submit">Отправить</button>
            <p class="notify-user">*Проект предоставляется клиенту бесплатно в случае приобретения продукции компании Lorento</p>
        </form>
    </div>
    <div class="order_product-image">
        <img src="{{ asset('storage/images/inner pages/order-form-background.webp')  }}" alt="изображение формы заказа">
    </div>
</div>
