<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\Site\HomeController@index')->name('home');
Auth::routes();

Route::group([
    'prefix'    => 'catalog',
    'as'        => 'catalog.',
    'namespace' => 'App\Http\Controllers\Catalog'
], function () {
    Route::get('/{product_path?}', 'CatalogController@index')->name('index')->where('product_path', '.+');
});


Route::group([
    'prefix' => 'shop',
    'as' => 'shop.',
    'namespace' => 'App\Http\Controllers\Catalog'
], function () {

    Route::get('/filter', 'CatalogController@filter')->name('filter');
    Route::get('/search', 'CatalogController@search')->name('search');
    Route::post('/add-comment/{product}', 'CatalogController@addComment')->name('add-comment');
    Route::post('/ajax-search', 'CatalogController@ajaxSearch')->name('ajax-search');
    Route::post('/order', 'OrderController@sendOrder')->name('order');
});

Route::group([
    'prefix'    => 'blog',
    'as'        => 'blog.',
    'namespace' => 'App\Http\Controllers\Blog'
], function () {
    Route::get('/{post_path?}', 'BlogController@index')->name('index')->where('post_path', '.+');
});

Route::group(
    [
        'prefix'     => 'admin',
        'as'         => 'admin.',
        'namespace'  => 'App\Http\Controllers\Admin',
        'middleware' => ['auth', 'can:admin-panel'],
    ],
    function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::post('/settings-update', 'HomeController@update')->name('home.update');

        Route::get('/file-manager', 'FileManagerController@index')->name('file-manager');

        Route::resource('/navigations', 'NavigationController');
        Route::post('/navigations/find', 'NavigationController@find');
        Route::delete('/navigations/menu-item-delete/{navItem}', 'NavigationController@destroyItem');
        Route::delete('/navigations/menu-item-delete-image/{navItem}', 'NavigationController@deleteImage');
        Route::post('ajax/get-form-menu', 'NavigationController@getFormMenu')->name('ajax.form-menu');
        Route::post('ajax/get-form-menu-items', 'NavigationController@getFormMenuItems')->name('ajax.form-menu-items');

        Route::post('photos/get-photos', 'PhotoController@getPhotos')->name('photos.get-photos');
        Route::post('photos/update-photo', 'PhotoController@updatePhoto')->name('photos.update-photo');
        Route::post('photos/get-variant-photos', 'PhotoController@getVariantPhotos')->name('photos.get-variant-photos');
        Route::post('photos/update-variant-photo', 'PhotoController@updateVariantPhoto')->name('photos.update-variant-photo');

//        Route::resource('users', 'UsersController');
//        Route::post('users/multi-delete', 'UsersController@multiDelete')->name('users.multi-delete');

        Route::group(['prefix' => 'shop', 'as' => 'shop.', 'namespace' => 'Shop'], function () {
            Route::resource('categories', 'CategoryController');
            Route::post('categories/{category}/first', 'CategoryController@first')->name('categories.first');
            Route::post('categories/{category}/up', 'CategoryController@up')->name('categories.up');
            Route::post('categories/{category}/down', 'CategoryController@down')->name('categories.down');
            Route::post('categories/{category}/last', 'CategoryController@last')->name('categories.last');
            Route::post('categories/{category}/toggle-publish', 'CategoryController@togglePublished')->name('category.toggle.published');

            Route::post('categories/photo/{category}/{photo}/up', 'CategoryController@photoUp')->name('categories.photo.up');
            Route::post('categories/photo/{category}/{photo}/down', 'CategoryController@photoDown')->name('categories.photo.down');
            Route::post('categories/photo/{category}/{photo}/remove', 'CategoryController@photoRemove')->name('categories.photo.remove');

            Route::resource('attributes', 'AttributeController');
            Route::post('attributes/assign-categories/{attribute}', 'AttributeController@assignCategories')->name('attributes.assign-categories');
            Route::post('attributes/un-assign-categories/{attribute}', 'AttributeController@unAssignCategory')->name('attributes.un-assign-category');

            Route::resource('tags', 'TagController');
            Route::post('tags/create-ajax', 'TagController@ajaxCreate')->name('tags.ajax-create');

            Route::resource('products', 'ProductController');

            Route::post('products/get-attributes-form', 'ProductController@getAttributesForm')->name('products.get-attributes-form');
            Route::post('products/set-active/{product}', 'ProductController@setActive')->name('product.set-active');
            Route::post('products/set-un-active/{product}', 'ProductController@setUnActive')->name('product.set-un-active');
            Route::post('products/set-status', "ProductController@setStatus")->name('products.set-status');

            Route::post('products/search-relation', 'ProductController@searchRelation')->name('products.search-relation');
            Route::post('products/delete-relation', 'ProductController@deleteRelation')->name('products.delete-relation');
            Route::post('products/add-relation', 'ProductController@addRelation')->name('products.addRelation');

            Route::post('products/photo/{product}/{photo}/up', 'ProductController@photoUp')->name('products.photo.up');
            Route::post('products/photo/{product}/{photo}/down', 'ProductController@photoDown')->name('products.photo.down');
            Route::post('products/photo/{product}/{photo}/remove', 'ProductController@photoRemove')->name('products.photo.remove');

            Route::resource('filters', 'FilterController');
            Route::post('filters/remove-batch', 'FilterController@removeBatch')->name('filters.remove-batch');
            Route::post('filters/add-group', 'FilterController@addGroup')->name('filters.add-group');

            Route::resource('promotion', 'PromotionController');
            Route::post('promotion/remove-batch', 'PromotionController@removeBatch')->name('promotion.remove-batch');
            Route::post('promotion/photo/{promotion}/{photo}/remove', 'PromotionController@photoRemove')->name('promotion.photo.remove');


            Route::resource('orders', 'OrderController');
            Route::post('orders/multi-delete', 'OrderController@multiDelete')->name('orders.multi-delete');
            Route::post('orders/set-status', 'OrderController@setStatus')->name('orders.set-status');
            Route::post('orders/add-note/{order}', 'OrderController@addNote')->name('orders.add-note');

            Route::get('commentaries', 'CommentariesController@index')->name('commentaries.index');
            Route::post('commentaries/set-status', 'CommentariesController@setStatus')->name('commentaries.set-status');
            Route::delete('commentaries/destroy/{comment}', 'CommentariesController@destroy')->name('commentaries.destroy');
        });

        Route::group(['prefix' => 'blog', 'as' => 'blog.', 'namespace' => 'Blog'], function () {
            Route::resource('categories', 'CategoryController');
            Route::post('categories/{category}/first', 'CategoryController@first')->name('categories.first');
            Route::post('categories/{category}/up', 'CategoryController@up')->name('categories.up');
            Route::post('categories/{category}/down', 'CategoryController@down')->name('categories.down');
            Route::post('categories/{category}/last', 'CategoryController@last')->name('categories.last');
            Route::post('categories/{category}/toggle-status', 'CategoryController@toggleStatus')->name('category.toggle.status');

            Route::post('categories/photo/{category}/{photo}/up', 'CategoryController@photoUp')->name('categories.photo.up');
            Route::post('categories/photo/{category}/{photo}/down', 'CategoryController@photoDown')->name('categories.photo.down');
            Route::post('categories/photo/{category}/{photo}/remove', 'CategoryController@photoRemove')->name('categories.photo.remove');

            Route::resource('posts', 'PostController');
            Route::post('posts/{post}/set-status', 'PostController@setStatus')->name('posts.set-status');
            Route::post('posts/photo/{post}/{photo}/up', 'PostController@photoUp')->name('posts.photo.up');
            Route::post('posts/photo/{post}/{photo}/down', 'PostController@photoDown')->name('posts.photo.down');
            Route::post('posts/photo/{post}/{photo}/remove', 'PostController@photoRemove')->name('posts.photo.remove');
        });
    }
);
