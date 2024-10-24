<x-mail::message>
# Внимание!

На сайте larento.ru, был создан новый заказ. Для просмотра заказа нажмите кнопку ниже. Внимание, вы должны иметь право на просмотр заказов на сайте lareno.

<x-mail::button :url="$url">
Посмотреть заказ
</x-mail::button>

Спасибо,<br>
{{ config('app.name') }}
</x-mail::message>
