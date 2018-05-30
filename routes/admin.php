<?php
Route::get('/', function (){
    throw new \Exception("路径错误!", 1);
//    abort(403);
});
Route::any('test', 'Test\TestController@index');
Route::get('admin/login', 'Admin\LoginController@index');
Route::post('admin/login', 'Admin\LoginController@login');
Route::group(['prefix' => 'admin', 'namespace'=>"Admin", 'middleware'=>'admin'], function() {
    Route::get('/', 'IndexController@main');
    Route::get('index', 'IndexController@index');
    Route::get('logout', 'LoginController@outLogin');

    // 权限
    Route::resource('admin', 'AdminController');
    Route::get('getData1','AdminController@getData')->name("admin.getData");

    Route::resource('role', 'RoleController');
    Route::get('api/getData','RoleController@getData')->name("role.getData");

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
    Route::get('member/recharge', 'MemberController@recharge');                                          // 充值
    Route::get('member/limit', 'MemberController@limit');                                                // 限制列表

    // RestrictController
    Route::get('restrict/index', 'RestrictController@index');
    Route::get('restrict/getData', 'RestrictController@getData');
    Route::post('restrict/store', 'RestrictController@store');
    Route::post('restrict/update', 'RestrictController@update');
    Route::get('restrict/destroy', 'RestrictController@destroy');
    Route::get('limit_member', 'RestrictController@limit_member');
    Route::get('getMember', 'RestrictController@getMember');

    // OrderController
    Route::get('order/index', 'OrderController@index');
    Route::get('order/getData', 'OrderController@getData');

    // CardController
    Route::resource('card', 'CardController');
    Route::get('getData', 'CardController@getData')->name("card.getData");
    Route::get('getCard', 'CardController@getData1')->name("cardinfo.getData");
    Route::get('getType', 'CardController@getData2')->name("type.getData");
    Route::get('getPrice/{id}', function ($id, \App\Models\Type $type){
        return json_encode($type->where('id', $id)->get());
    })->name("type.getPrice");

    // TypeController
    Route::resource('type', 'TypeController');

    // LogController
    Route::resource('log', 'LogController');
    Route::get('getLog', 'LogController@getData')->name("log.getData");

    // 数据分析
    Route::get('order/amount', 'OrderController@getAmount');
    Route::get('api/amount', 'OrderController@orderAmount');
    Route::get('member/statistics', 'MemberController@statistics');
    Route::get('cztj', 'MemberController@cztj');
    Route::get('member/inRoom', 'MemberController@inRoom');
    Route::get('mInRoom', 'MemberController@mInRoom');
    Route::get('member/active', 'MemberController@activePlayer');
    Route::get('lively1', 'MemberController@lively1');
    Route::get('lively2', 'MemberController@lively2');

    // 报表
    Route::get('collect', 'IndexController@collect');
    Route::get('coinChange', 'IndexController@coinChange');
    Route::get('coin', 'IndexController@coin');

    // SettingController
    Route::resource('settings', 'SettingController', ['only' => [ 'index', 'update' ]]);
});