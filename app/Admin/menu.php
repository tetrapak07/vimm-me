<?php

/*
 * Describe your menu here.
 *
 * There is some simple examples what you can use:
 *
 * 		Admin::menu()->url('/')->label('Start page')->icon('fa-dashboard')->uses('\AdminController@getIndex');
 * 		Admin::menu(User::class)->icon('fa-user');
 * 		Admin::menu()->label('Menu with subitems')->icon('fa-book')->items(function ()
 * 		{
 * 			Admin::menu(\Foo\Bar::class)->icon('fa-sitemap');
 * 			Admin::menu('\Foo\Baz')->label('Overwrite model title');
 * 			Admin::menu()->url('my-page')->label('My custom page')->uses('\MyController@getMyPage');
 * 		});
 */


Admin::menu()->url('/')->label('Start page')->icon('fa-dashboard')->uses('\App\Http\Controllers\Admin\AdminController@dashboard');

Admin::menu()->url('settings')->label('Настройки')->icon('fa-cog');

Admin::menu()->url('questions')->label('Видео-Вопросы')->icon('fa-question');

Admin::menu()->url('answers')->label('Видео-Ответы')->icon('fa-exclamation');

Admin::menu()->url('members')->label('Пользователи')->icon('fa-users');

Admin::menu()->url('ratings')->label('Рейтинги')->icon('fa-thumbs-up');

Admin::menu()->url('queues')->label('Очереди')->icon('fa-bars');

