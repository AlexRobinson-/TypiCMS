<?php
Route::model('menulinks', 'TypiCMS\Modules\Menulinks\Models\Menulink');

Route::group(array('prefix' => 'admin', 'before' => 'auth.admin'), function () {
    Route::resource(
        'menus.menulinks',
        'TypiCMS\Modules\Menulinks\Controllers\AdminController'
    );
    Route::post(
        'menus/{menus}/menulinks/sort',
        array(
            'as' => 'admin.menus.menulinks.sort',
            'uses' => 'TypiCMS\Modules\Menulinks\Controllers\AdminController@sort'
        )
    );
});
