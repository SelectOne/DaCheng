<?php
//Route::resource('admin/user','Admin\UserController');

//Route::get('verify', 'Admin\LoginController@verify');
Route::any('test','Test\TestController@index');
Route::get('admin/login', 'Admin\LoginController@index');
Route::post('admin/login', 'Admin\LoginController@login');
Route::group(['prefix' => 'admin','namespace'=>"Admin",'middleware'=>'admin'], function() {
    Route::get('/','IndexController@main');
    Route::get('index','IndexController@index');
    Route::get('logout', 'LoginController@outLogin');

    // 权限
    Route::resource('admin','AdminController');
    Route::get('getData','AdminController@getData')->name("admin.getData");

//    Route::resource('node','NodeController');
    Route::get('node', 'NodeController@index')->name('node.index');
    Route::get('node/create', 'NodeController@create')->name('node.create');
    Route::post('node/create', 'NodeController@store')->name('node.store');
    Route::get('node/{id}', 'NodeController@show')->name('node.show');
    Route::get('node/{id}/edit', 'NodeController@edit')->name('node.edit');
    Route::patch('node/{id}', 'NodeController@update')->name('node.update');
    Route::delete('node/{id}', 'NodeController@destroy')->name('node.destroy');

    Route::resource('role','RoleController');
//    Route::get('role/index','RoleController@index');
    Route::get('api/getData','RoleController@getData')->name("role.getData");
    /*Route::post('role/store', 'RoleController@store');
    Route::post('role/update', 'RoleController@update');
    Route::get('role/destroy', 'RoleController@destroy');
    Route::get('role/create', 'RoleController@create');*/
// PermissionController
    Route::get('permission/index', 'PermissionController@index');
    Route::get('permission/getData', 'PermissionController@getData');
    Route::post('permission/store', 'PermissionController@store');
    Route::post('permission/update', 'PermissionController@update');
    Route::get('permission/destroy', 'PermissionController@destroy');

    // MemberController
    Route::get('member/index', 'MemberController@index');
    Route::get('member/getData', 'MemberController@getData');                                            // 数据表格接口
    Route::get('member/checkStatus', 'MemberController@checkStatus');                                    // 冻结/解冻
    Route::get('member/recharge', 'MemberController@recharge');                                          // 冻结/解冻
    Route::get('member/limit', 'MemberController@limit');                                                // 限制列表

    // RestrictController
    Route::get('restrict/index', 'RestrictController@index');
    Route::get('restrict/getData', 'RestrictController@getData');
    Route::post('restrict/store', 'RestrictController@store');
    Route::post('restrict/update', 'RestrictController@update');
    Route::get('restrict/destroy', 'RestrictController@destroy');

    // OrderController
    Route::get('order/index', 'OrderController@index');
    Route::get('order/getData', 'OrderController@getData');
    Route::get('order/test', 'OrderController@test');

    // CardController
    Route::resource('card','CardController');
    Route::get('card/getData', 'CardController@getData')->name("card.getData");
});