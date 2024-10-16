<?php

use App\Http\Router\PostPath;
use App\Http\Router\ProductPath;
use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('home', function (Generator $generator) {
    $generator->push('Главная', route('home'));
});

Breadcrumbs::for('login', function (Generator $generator) {
    $generator->parent('home');
    $generator->push('Логин/Регистрация', route('login'));
});

Breadcrumbs::for('catalog.inner_category', function (Generator $generator, ProductPath $path) {
    if ($path->category && $parent = $path->category->parent) {
        $generator->parent('catalog.inner_category', $path->withCategory($parent));
    } else {
        $generator->parent('home');
    }
    if ($path->category) {
        $generator->push($path->category->name, route('catalog.index', $path));
    } else {
        $generator->push('Каталог', route('catalog.index', $path));
    }
});

Breadcrumbs::for('catalog.index', function (Generator $generator, ProductPath $path = null) {
    $path = $path ?: product_path(null, null);
    $generator->parent('catalog.inner_category', $path->withProduct(null));
    if ($path->product) {
        $generator->push($path->product->name, route('catalog.index'));
    }
});

Breadcrumbs::for('shop.filter', function (Generator $generator) {
    $generator->parent('home');
    $generator->push('Результат поиска', route('shop.filter'));
});

Breadcrumbs::for('blog.inner_category', function (Generator $generator, PostPath $path) {
    if ($path->category && $parent = $path->category->parent) {
        $generator->parent('blog.inner_category', $path->withCategory($parent));
    } else {
        $generator->parent('home');
    }
    if ($path->category) {
        $generator->push($path->category->name, route('blog.index', $path));
    }
});

Breadcrumbs::for('blog.index', function (Generator $generator, PostPath $path = null) {
    $path = $path?:post_path(null,null);
    $generator->parent('blog.inner_category', $path->withPost(null));
    if ($path->post) {
        $generator->push($path->post->title, route('blog.index'));
    }
});
