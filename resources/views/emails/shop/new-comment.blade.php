<x-mail::message>
# Внимание!

На сайте larento.ru, был оставлен новый комментарий. Для модерации комментария нажмите кнопку ниже. Внимание, вы должны иметь право на доступ в админ панель сайте larenot.

### Текст комментария

> {{$textComment}}

<x-mail::button :url="$url">
Перейти к комментариям для модерации
</x-mail::button>

Спасибо,<br>
{{ config('app.name') }}
</x-mail::message>
