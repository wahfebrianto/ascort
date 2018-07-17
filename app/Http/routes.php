<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Authentication routes...
Route::get( 'auth/login',               ['as' => 'login',                   'uses' => 'Auth\AuthController@getLogin']);
Route::post('auth/login',               ['as' => 'loginPost',               'uses' => 'Auth\AuthController@postLogin']);
Route::get( 'auth/logout',              ['as' => 'logout',                  'uses' => 'Auth\AuthController@getLogout']);
// Registration routes...
// Route::get( 'auth/register',            ['as' => 'register',                'uses' => 'Auth\AuthController@getRegister']);
// Route::post('auth/register',            ['as' => 'registerPost',            'uses' => 'Auth\AuthController@postRegister']);
// Password reset link request routes...
// Route::get( 'password/email',           ['as' => 'recover_password',        'uses' => 'Auth\PasswordController@getEmail']);
// Route::post('password/email',           ['as' => 'recover_passwordPost',    'uses' => 'Auth\PasswordController@postEmail']);
// Password reset routes...
// Route::get( 'password/reset/{token}',   ['as' => 'reset_password',          'uses' => 'Auth\PasswordController@getReset']);
// Route::post('password/reset',           ['as' => 'reset_passwordPost',      'uses' => 'Auth\PasswordController@postReset']);
// Registration terms
// Route::get( 'faust',                    ['as' => 'faust',                   'uses' => function(){ return view('faust'); }]);

// Application routes...
Route::get( '/',    ['as' => 'backslash',   'uses' => 'HomeController@index']);
Route::get( 'home', ['as' => 'home',        'uses' => 'HomeController@index']);

// Routes in this group must be authorized.
Route::group(['middleware' => ['auth', 'authorize']], function () {
    // Application routes...
    Route::get( 'dashboard',                      ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
    Route::post( 'dashboard/changeagentposition', ['as' => 'dashboard.changeagent', 'uses' => 'DashboardController@changeagent']);
    Route::get( 'dashboard/dismissreminder/{id}', ['as' => 'dashboard.dismiss', 'uses' => 'DashboardController@dismissreminder']);

    Route::group(['prefix' => 'approvals'], function () {
        // approvals routes
        Route::get('/',                   ['as' => 'approvals.index',                 'uses' => 'ApprovalsController@index']);
        Route::get('index',               ['as' => 'approvals.index',                 'uses' => 'ApprovalsController@index']);
        Route::get('disableSelected',     ['as' => 'approvals.disable-selected',      'uses' => 'ApprovalsController@disableSelected']);
        Route::get('{id}',                ['as' => 'approvals.show',                  'uses' => 'ApprovalsController@show']);
        Route::get('delete/{id}',         ['as' => 'approvals.delete',               'uses' => 'ApprovalsController@delete']);
        Route::get('approve/{id}',        ['as' => 'approvals.approve',               'uses' => 'ApprovalsController@approve']);
    });

    Route::get( 'evaluation',             ['as' => 'evaluation', 'uses' => 'EvalController@index']);


    // Site administration section
    Route::group(['prefix' => 'admin'], function () {
        // User routes
        Route::post(  'users/enableSelected',          ['as' => 'admin.users.enable-selected',  'uses' => 'UsersController@enableSelected']);
        Route::post(  'users/disableSelected',         ['as' => 'admin.users.disable-selected', 'uses' => 'UsersController@disableSelected']);
        Route::get(   'users/search',                  ['as' => 'admin.users.search',           'uses' => 'UsersController@searchByName']);
        Route::get(   'users/list',                    ['as' => 'admin.users.list',             'uses' => 'UsersController@listByPage']);
        Route::post(  'users/getInfo',                 ['as' => 'admin.users.get-info',         'uses' => 'UsersController@getInfo']);
        Route::post(  'users',                         ['as' => 'admin.users.store',            'uses' => 'UsersController@store']);
        Route::get(   'users',                         ['as' => 'admin.users.index',            'uses' => 'UsersController@index']);
        Route::get(   'users/create',                  ['as' => 'admin.users.create',           'uses' => 'UsersController@create']);
        Route::get(   'users/{userId}',                ['as' => 'admin.users.show',             'uses' => 'UsersController@show']);
        Route::patch( 'users/{userId}',                ['as' => 'admin.users.patch',            'uses' => 'UsersController@update']);
        Route::put(   'users/{userId}',                ['as' => 'admin.users.update',           'uses' => 'UsersController@update']);
        Route::delete('users/{userId}',                ['as' => 'admin.users.destroy',          'uses' => 'UsersController@destroy']);
        Route::get(   'users/{userId}/edit',           ['as' => 'admin.users.edit',             'uses' => 'UsersController@edit']);
        Route::get(   'users/{userId}/confirm-delete', ['as' => 'admin.users.confirm-delete',   'uses' => 'UsersController@getModalDelete']);
        Route::get(   'users/{userId}/delete',         ['as' => 'admin.users.delete',           'uses' => 'UsersController@destroy']);
        Route::get(   'users/{userId}/enable',         ['as' => 'admin.users.enable',           'uses' => 'UsersController@enable']);
        Route::get(   'users/{userId}/disable',        ['as' => 'admin.users.disable',          'uses' => 'UsersController@disable']);
        Route::get(   'users/{userId}/replayEdit',     ['as' => 'admin.users.replay-edit',      'uses' => 'UsersController@replayEdit']);
        // Role routes
        Route::post(  'roles/enableSelected',          ['as' => 'admin.roles.enable-selected',  'uses' => 'RolesController@enableSelected']);
        Route::post(  'roles/disableSelected',         ['as' => 'admin.roles.disable-selected', 'uses' => 'RolesController@disableSelected']);
        Route::get(   'roles/search',                  ['as' => 'admin.roles.search',           'uses' => 'RolesController@searchByName']);
        Route::post(  'roles/getInfo',                 ['as' => 'admin.roles.get-info',         'uses' => 'RolesController@getInfo']);
        Route::post(  'roles',                         ['as' => 'admin.roles.store',            'uses' => 'RolesController@store']);
        Route::get(   'roles',                         ['as' => 'admin.roles.index',            'uses' => 'RolesController@index']);
        Route::get(   'roles/create',                  ['as' => 'admin.roles.create',           'uses' => 'RolesController@create']);
        Route::get(   'roles/{roleId}',                ['as' => 'admin.roles.show',             'uses' => 'RolesController@show']);
        Route::patch( 'roles/{roleId}',                ['as' => 'admin.roles.patch',            'uses' => 'RolesController@update']);
        Route::put(   'roles/{roleId}',                ['as' => 'admin.roles.update',           'uses' => 'RolesController@update']);
        Route::delete('roles/{roleId}',                ['as' => 'admin.roles.destroy',          'uses' => 'RolesController@destroy']);
        Route::get(   'roles/{roleId}/edit',           ['as' => 'admin.roles.edit',             'uses' => 'RolesController@edit']);
        Route::get(   'roles/{roleId}/confirm-delete', ['as' => 'admin.roles.confirm-delete',   'uses' => 'RolesController@getModalDelete']);
        Route::get(   'roles/{roleId}/delete',         ['as' => 'admin.roles.delete',           'uses' => 'RolesController@destroy']);
        Route::get(   'roles/{roleId}/enable',         ['as' => 'admin.roles.enable',           'uses' => 'RolesController@enable']);
        Route::get(   'roles/{roleId}/disable',        ['as' => 'admin.roles.disable',          'uses' => 'RolesController@disable']);
        // Menu routes
        Route::post(  'menus',                         ['as' => 'admin.menus.save',             'uses' => 'MenusController@save']);
        Route::get(   'menus',                         ['as' => 'admin.menus.index',            'uses' => 'MenusController@index']);
        Route::get(   'menus/getData/{menuId}',        ['as' => 'admin.menus.get-data',         'uses' => 'MenusController@getData']);
        Route::get(   'menus/{menuId}/confirm-delete', ['as' => 'admin.menus.confirm-delete',   'uses' => 'MenusController@getModalDelete']);
        Route::get(   'menus/{menuId}/delete',         ['as' => 'admin.menus.delete',           'uses' => 'MenusController@destroy']);
        // Permission routes
        Route::get(   'permissions/generate',                      ['as' => 'admin.permissions.generate',         'uses' => 'PermissionsController@generate']);
        Route::post(  'permissions/enableSelected',                ['as' => 'admin.permissions.enable-selected',  'uses' => 'PermissionsController@enableSelected']);
        Route::post(  'permissions/disableSelected',               ['as' => 'admin.permissions.disable-selected', 'uses' => 'PermissionsController@disableSelected']);
        Route::post(  'permissions',                               ['as' => 'admin.permissions.store',            'uses' => 'PermissionsController@store']);
        Route::get(   'permissions',                               ['as' => 'admin.permissions.index',            'uses' => 'PermissionsController@index']);
        Route::get(   'permissions/create',                        ['as' => 'admin.permissions.create',           'uses' => 'PermissionsController@create']);
        Route::get(   'permissions/{permissionId}',                ['as' => 'admin.permissions.show',             'uses' => 'PermissionsController@show']);
        Route::patch( 'permissions/{permissionId}',                ['as' => 'admin.permissions.patch',            'uses' => 'PermissionsController@update']);
        Route::put(   'permissions/{permissionId}',                ['as' => 'admin.permissions.update',           'uses' => 'PermissionsController@update']);
        Route::delete('permissions/{permissionId}',                ['as' => 'admin.permissions.destroy',          'uses' => 'PermissionsController@destroy']);
        Route::get(   'permissions/{permissionId}/edit',           ['as' => 'admin.permissions.edit',             'uses' => 'PermissionsController@edit']);
        Route::get(   'permissions/{permissionId}/confirm-delete', ['as' => 'admin.permissions.confirm-delete',   'uses' => 'PermissionsController@getModalDelete']);
        Route::get(   'permissions/{permissionId}/delete',         ['as' => 'admin.permissions.delete',           'uses' => 'PermissionsController@destroy']);
        Route::get(   'permissions/{permissionId}/enable',         ['as' => 'admin.permissions.enable',           'uses' => 'PermissionsController@enable']);
        Route::get(   'permissions/{permissionId}/disable',        ['as' => 'admin.permissions.disable',          'uses' => 'PermissionsController@disable']);
        // Route routes
        Route::get(   'routes/load',                     ['as' => 'admin.routes.load',             'uses' => 'RoutesController@load']);
        Route::post(  'routes/enableSelected',           ['as' => 'admin.routes.enable-selected',  'uses' => 'RoutesController@enableSelected']);
        Route::post(  'routes/disableSelected',          ['as' => 'admin.routes.disable-selected', 'uses' => 'RoutesController@disableSelected']);
        Route::post(  'routes/savePerms',                ['as' => 'admin.routes.save-perms',       'uses' => 'RoutesController@savePerms']);
        Route::get(   'routes/search',                   ['as' => 'admin.routes.search',           'uses' => 'RoutesController@searchByName']);
        Route::post(  'routes/getInfo',                  ['as' => 'admin.routes.get-info',         'uses' => 'RoutesController@getInfo']);
        Route::post(  'routes',                          ['as' => 'admin.routes.store',            'uses' => 'RoutesController@store']);
        Route::get(   'routes',                          ['as' => 'admin.routes.index',            'uses' => 'RoutesController@index']);
        Route::get(   'routes/create',                   ['as' => 'admin.routes.create',           'uses' => 'RoutesController@create']);
        Route::get(   'routes/{routeId}',                ['as' => 'admin.routes.show',             'uses' => 'RoutesController@show']);
        Route::patch( 'routes/{routeId}',                ['as' => 'admin.routes.patch',            'uses' => 'RoutesController@update']);
        Route::put(   'routes/{routeId}',                ['as' => 'admin.routes.update',           'uses' => 'RoutesController@update']);
        Route::delete('routes/{routeId}',                ['as' => 'admin.routes.destroy',          'uses' => 'RoutesController@destroy']);
        Route::get(   'routes/{routeId}/edit',           ['as' => 'admin.routes.edit',             'uses' => 'RoutesController@edit']);
        Route::get(   'routes/{routeId}/confirm-delete', ['as' => 'admin.routes.confirm-delete',   'uses' => 'RoutesController@getModalDelete']);
        Route::get(   'routes/{routeId}/delete',         ['as' => 'admin.routes.delete',           'uses' => 'RoutesController@destroy']);
        Route::get(   'routes/{routeId}/enable',         ['as' => 'admin.routes.enable',           'uses' => 'RoutesController@enable']);
        Route::get(   'routes/{routeId}/disable',        ['as' => 'admin.routes.disable',          'uses' => 'RoutesController@disable']);
        // Audit routes
        Route::get( 'audit',                           ['as' => 'admin.audit.index',             'uses' => 'AuditsController@index']);
        Route::get( 'audit/purge',                     ['as' => 'admin.audit.purge',             'uses' => 'AuditsController@purge']);
        Route::get( 'audit/{auditId}/replay',          ['as' => 'admin.audit.replay',            'uses' => 'AuditsController@replay']);
        Route::get( 'audit/{auditId}/show',            ['as' => 'admin.audit.show',              'uses' => 'AuditsController@show']);
        // Settings routes

        Route::group(['prefix' => 'agentpos'], function () {
            // admin/agentpos routes
            Route::post('index',            ['as' => 'admin.agentpos.store',                 'uses' => 'AgentPositionsController@store']);
            Route::get('/',                 ['as' => 'admin.agentpos.index',                 'uses' => 'AgentPositionsController@index']);
            Route::get('index',             ['as' => 'admin.agentpos.index',                 'uses' => 'AgentPositionsController@index']);
            Route::get('index/{enabledOnly?}',['as' => 'admin.agentpos.index',               'uses' => 'AgentPositionsController@index']);
            Route::get('create',            ['as' => 'admin.agentpos.create',                'uses' => 'AgentPositionsController@create']);
            Route::get('enableSelected',    ['as' => 'admin.agentpos.enable-selected',       'uses' => 'AgentPositionsController@enableSelected']);
            Route::get('disableSelected',   ['as' => 'admin.agentpos.disable-selected',      'uses' => 'AgentPositionsController@disableSelected']);
            Route::get('{id}',              ['as' => 'admin.agentpos.show',                  'uses' => 'AgentPositionsController@show']);
            Route::get('{id}/edit',         ['as' => 'admin.agentpos.edit',                  'uses' => 'AgentPositionsController@edit']);
            Route::patch('{id}/edit',       ['as' => 'admin.agentpos.update',                'uses' => 'AgentPositionsController@update']);
            Route::get('enable/{id}',       ['as' => 'admin.agentpos.enable',                'uses' => 'AgentPositionsController@enable']);
            Route::get('disable/{id}',      ['as' => 'admin.agentpos.disable',               'uses' => 'AgentPositionsController@disable']);
        });

        Route::group(['prefix' => 'target'], function () {
            // admin/target routes
            Route::post('index',            ['as' => 'admin.target.store',                 'uses' => 'SalesTargetsController@store']);
            Route::get('/',                 ['as' => 'admin.target.index',                 'uses' => 'SalesTargetsController@index']);
            Route::get('index',             ['as' => 'admin.target.index',                 'uses' => 'SalesTargetsController@index']);
            Route::get('index/{enabledOnly?}',['as' => 'admin.target.index',               'uses' => 'SalesTargetsController@index']);
            Route::get('create',            ['as' => 'admin.target.create',                'uses' => 'SalesTargetsController@create']);
            Route::get('enableSelected',    ['as' => 'admin.target.enable-selected',       'uses' => 'SalesTargetsController@enableSelected']);
            Route::get('disableSelected',   ['as' => 'admin.target.disable-selected',      'uses' => 'SalesTargetsController@disableSelected']);
            Route::get('{id}',              ['as' => 'admin.target.show',                  'uses' => 'SalesTargetsController@show']);
            Route::get('{id}/edit',         ['as' => 'admin.target.edit',                  'uses' => 'SalesTargetsController@edit']);
            Route::patch('{id}/edit',       ['as' => 'admin.target.update',                'uses' => 'SalesTargetsController@update']);
            Route::get('enable/{id}',       ['as' => 'admin.target.enable',                'uses' => 'SalesTargetsController@enable']);
            Route::get('disable/{id}',      ['as' => 'admin.target.disable',               'uses' => 'SalesTargetsController@disable']);
        });


        Route::group(['prefix' => 'salecommission'], function () {
            // admin/salecommission routes
            Route::post('index',            ['as' => 'admin.salecommission.store',                 'uses' => 'SaleCommissionsController@store']);
            Route::get('/',                 ['as' => 'admin.salecommission.index',                 'uses' => 'SaleCommissionsController@index']);
            Route::get('index',             ['as' => 'admin.salecommission.index',                 'uses' => 'SaleCommissionsController@index']);
            Route::get('index/{enabledOnly?}',['as' => 'admin.salecommission.index',               'uses' => 'SaleCommissionsController@index']);
            Route::get('create',            ['as' => 'admin.salecommission.create',                'uses' => 'SaleCommissionsController@create']);
            Route::get('enableSelected',    ['as' => 'admin.salecommission.enable-selected',       'uses' => 'SaleCommissionsController@enableSelected']);
            Route::get('disableSelected',   ['as' => 'admin.salecommission.disable-selected',      'uses' => 'SaleCommissionsController@disableSelected']);
            Route::get('{id}',              ['as' => 'admin.salecommission.show',                  'uses' => 'SaleCommissionsController@show']);
            Route::get('{id}/edit',         ['as' => 'admin.salecommission.edit',                  'uses' => 'SaleCommissionsController@edit']);
            Route::patch('{id}/edit',       ['as' => 'admin.salecommission.update',                'uses' => 'SaleCommissionsController@update']);
            Route::get('enable/{id}',       ['as' => 'admin.salecommission.enable',                'uses' => 'SaleCommissionsController@enable']);
            Route::get('disable/{id}',      ['as' => 'admin.salecommission.disable',               'uses' => 'SaleCommissionsController@disable']);
        });

        Route::group(['prefix' => 'ovrcommission'], function () {
            // admin/ovrcommission routes
            Route::post('index',            ['as' => 'admin.ovrcommission.store',                 'uses' => 'OverridingCommissionsController@store']);
            Route::get('/',                 ['as' => 'admin.ovrcommission.index',                 'uses' => 'OverridingCommissionsController@index']);
            Route::get('index',             ['as' => 'admin.ovrcommission.index',                 'uses' => 'OverridingCommissionsController@index']);
            Route::get('index/{enabledOnly?}',['as' => 'admin.ovrcommission.index',               'uses' => 'OverridingCommissionsController@index']);
            Route::get('create',            ['as' => 'admin.ovrcommission.create',                'uses' => 'OverridingCommissionsController@create']);
            Route::get('enableSelected',    ['as' => 'admin.ovrcommission.enable-selected',       'uses' => 'OverridingCommissionsController@enableSelected']);
            Route::get('disableSelected',   ['as' => 'admin.ovrcommission.disable-selected',      'uses' => 'OverridingCommissionsController@disableSelected']);
            Route::get('{id}',              ['as' => 'admin.ovrcommission.show',                  'uses' => 'OverridingCommissionsController@show']);
            Route::get('{id}/edit',         ['as' => 'admin.ovrcommission.edit',                  'uses' => 'OverridingCommissionsController@edit']);
            Route::patch('{id}/edit',       ['as' => 'admin.ovrcommission.update',                'uses' => 'OverridingCommissionsController@update']);
            Route::get('enable/{id}',       ['as' => 'admin.ovrcommission.enable',                'uses' => 'OverridingCommissionsController@enable']);
            Route::get('disable/{id}',      ['as' => 'admin.ovrcommission.disable',               'uses' => 'OverridingCommissionsController@disable']);
        });

        Route::group(['prefix' => 'mgi'], function () {
            // admin/mgi routes
            Route::get('/',                 ['as' => 'admin.mgi.index',       'uses' => 'MGISettingController@index']);
            Route::get('index',             ['as' => 'admin.mgi.index',       'uses' => 'MGISettingController@index']);
            Route::get('create',            ['as' => 'admin.mgi.create',      'uses' => 'MGISettingController@create']);
            Route::post('index',            ['as' => 'admin.mgi.store',       'uses' => 'MGISettingController@store']);
            Route::get('{code}/edit',       ['as' => 'admin.mgi.edit',        'uses' => 'MGISettingController@edit']);
            Route::patch('{code}/edit',     ['as' => 'admin.mgi.update',      'uses' => 'MGISettingController@update']);
            Route::get('delete/{code}',     ['as' => 'admin.mgi.delete',      'uses' => 'MGISettingController@delete']);
        });

        Route::group(['prefix' => 'branchoffice'], function () {
            Route::post('index',            ['as' => 'admin.branchoffice.store',                 'uses' => 'BranchOfficesController@store']);
            Route::get('/',                 ['as' => 'admin.branchoffice.index',                 'uses' => 'BranchOfficesController@index']);
            Route::get('index',             ['as' => 'admin.branchoffice.index',                 'uses' => 'BranchOfficesController@index']);
            Route::get('index/{enabledOnly?}',['as' => 'admin.branchoffice.index',               'uses' => 'BranchOfficesController@index']);
            Route::get('create',            ['as' => 'admin.branchoffice.create',                'uses' => 'BranchOfficesController@create']);
            Route::get('enableSelected',    ['as' => 'admin.branchoffice.enable-selected',       'uses' => 'BranchOfficesController@enableSelected']);
            Route::get('disableSelected',   ['as' => 'admin.branchoffice.disable-selected',      'uses' => 'BranchOfficesController@disableSelected']);
            Route::get('{id}',              ['as' => 'admin.branchoffice.show',                  'uses' => 'BranchOfficesController@show']);
            Route::get('{id}/edit',         ['as' => 'admin.branchoffice.edit',                  'uses' => 'BranchOfficesController@edit']);
            Route::patch('{id}/edit',       ['as' => 'admin.branchoffice.update',                'uses' => 'BranchOfficesController@update']);
            Route::get('enable/{id}',       ['as' => 'admin.branchoffice.enable',                'uses' => 'BranchOfficesController@enable']);
            Route::get('disable/{id}',      ['as' => 'admin.branchoffice.disable',               'uses' => 'BranchOfficesController@disable']);
        });

        Route::group(['prefix' => 'boardofdirector'], function () {
            Route::post('index',            ['as' => 'admin.boardofdirector.store',                 'uses' => 'BoardOfDirectorController@store']);
            Route::get('/',                 ['as' => 'admin.boardofdirector.index',                 'uses' => 'BoardOfDirectorController@index']);
            Route::get('index',             ['as' => 'admin.boardofdirector.index',                 'uses' => 'BoardOfDirectorController@index']);
            Route::get('index/{enabledOnly?}',['as' => 'admin.boardofdirector.index',               'uses' => 'BoardOfDirectorController@index']);
            Route::get('create',            ['as' => 'admin.boardofdirector.create',                'uses' => 'BoardOfDirectorController@create']);
            Route::get('enableSelected',    ['as' => 'admin.boardofdirector.enable-selected',       'uses' => 'BoardOfDirectorController@enableSelected']);
            Route::get('disableSelected',   ['as' => 'admin.boardofdirector.disable-selected',      'uses' => 'BoardOfDirectorController@disableSelected']);
            Route::get('{id}',              ['as' => 'admin.boardofdirector.show',                  'uses' => 'BoardOfDirectorController@show']);
            Route::get('{id}/edit',         ['as' => 'admin.boardofdirector.edit',                  'uses' => 'BoardOfDirectorController@edit']);
            Route::patch('{id}/edit',       ['as' => 'admin.boardofdirector.update',                'uses' => 'BoardOfDirectorController@update']);
            Route::get('enable/{id}',       ['as' => 'admin.boardofdirector.enable',                'uses' => 'BoardOfDirectorController@enable']);
            Route::get('disable/{id}',      ['as' => 'admin.boardofdirector.disable',               'uses' => 'BoardOfDirectorController@disable']);
            Route::get('delete/{id}',       ['as' => 'admin.boardofdirector.destroy',                'uses' => 'BoardOfDirectorController@destroy']);
        });

        Route::group(['prefix' => 'holidays'], function () {
            // admin/mgi routes
            Route::get('/',                 ['as' => 'admin.holidays.index',                 'uses' => 'HolidaysController@index']);
            Route::get('index',             ['as' => 'admin.holidays.index',                 'uses' => 'HolidaysController@index']);
            Route::get('load',              ['as' => 'admin.holidays.load',                  'uses' => 'HolidaysController@load']);
            Route::get('create',            ['as' => 'admin.holidays.create',                'uses' => 'HolidaysController@create']);
            Route::post('index',            ['as' => 'admin.holidays.store',                 'uses' => 'HolidaysController@store']);
            Route::get('create',            ['as' => 'admin.holidays.create',                'uses' => 'HolidaysController@create']);
            Route::get('disableSelected',   ['as' => 'admin.holidays.disable-selected',      'uses' => 'HolidaysController@disableSelected']);
            Route::get('{id}',              ['as' => 'admin.holidays.show',                  'uses' => 'HolidaysController@show']);
            Route::get('{id}/edit',         ['as' => 'admin.holidays.edit',                  'uses' => 'HolidaysController@edit']);
            Route::patch('{id}/edit',       ['as' => 'admin.holidays.update',                'uses' => 'HolidaysController@update']);
            Route::get('disable/{id}',      ['as' => 'admin.holidays.disable',               'uses' => 'HolidaysController@disable']);
        });

    }); // End of ADMIN group

    Route::group(['prefix' => 'customers'], function () {
        Route::post('index',            ['as' => 'customers.store',                 'uses' => 'CustomersController@store']);
        Route::get('/',                 ['as' => 'customers.index',                 'uses' => 'CustomersController@index']);
        Route::get('index',             ['as' => 'customers.index',                 'uses' => 'CustomersController@index']);
        Route::get('index/{enabledOnly?}',['as' => 'customers.index',               'uses' => 'CustomersController@index']);
        Route::get('create',            ['as' => 'customers.create',                'uses' => 'CustomersController@create']);
        Route::get('enableSelected',    ['as' => 'customers.enable-selected',       'uses' => 'CustomersController@enableSelected']);
        Route::get('disableSelected',   ['as' => 'customers.disable-selected',      'uses' => 'CustomersController@disableSelected']);
        Route::get('export',            ['as' => 'customers.export',                'uses' => 'CustomersController@export']);
        Route::get('admin_edit',        ['as' => 'customers.admin_edit',            'uses' => 'CustomersController@admin_edit']);
        Route::get('{id}',              ['as' => 'customers.show',                  'uses' => 'CustomersController@show']);
        Route::get('{id}/edit',         ['as' => 'customers.edit',                  'uses' => 'CustomersController@edit']);
        Route::patch('{id}/edit',       ['as' => 'customers.update',                'uses' => 'CustomersController@update']);
        Route::get('enable/{id}',       ['as' => 'customers.enable',                'uses' => 'CustomersController@enable']);
        Route::get('disable/{id}',      ['as' => 'customers.disable',               'uses' => 'CustomersController@disable']);
    });

    Route::group(['prefix' => 'agents'], function () {
        Route::post('index',            ['as' => 'agents.store',                 'uses' => 'AgentsController@store']);
        Route::get('/',                 ['as' => 'agents.index',                 'uses' => 'AgentsController@index']);
        Route::get('index',             ['as' => 'agents.index',                 'uses' => 'AgentsController@index']);
        Route::get('index/{enabledOnly?}',['as' => 'agents.index',               'uses' => 'AgentsController@index']);
        Route::get('create',            ['as' => 'agents.create',                'uses' => 'AgentsController@create']);
        Route::get('summary',           ['as' => 'agents.summary',               'uses' => 'AgentsController@summary']);
        Route::get('summary_export',    ['as' => 'agents.summary_export',        'uses' => 'AgentsController@summary_export']);
        Route::get('history',           ['as' => 'agents.history',               'uses' => 'AgentsController@history']);
        Route::get('enableSelected',    ['as' => 'agents.enable-selected',       'uses' => 'AgentsController@enableSelected']);
        Route::get('disableSelected',   ['as' => 'agents.disable-selected',      'uses' => 'AgentsController@disableSelected']);
        Route::get('export',            ['as' => 'agents.export',                'uses' => 'AgentsController@export']);
        Route::get('export_commission',            ['as' => 'agents.export_commission',                'uses' => 'AgentsController@export_commission']);
        Route::get('{id}',              ['as' => 'agents.show',                  'uses' => 'AgentsController@show']);
        Route::get('{id}/edit',         ['as' => 'agents.edit',                  'uses' => 'AgentsController@edit']);
        Route::patch('{id}/edit',       ['as' => 'agents.update',                'uses' => 'AgentsController@update']);
        Route::get('enable/{id}',       ['as' => 'agents.enable',                'uses' => 'AgentsController@enable']);
        Route::get('disable/{id}',      ['as' => 'agents.disable',               'uses' => 'AgentsController@disable']);
        Route::get('delete/{id}',       ['as' => 'agents.destroy',                'uses' => 'AgentsController@destroy']);
    });

    Route::group(['prefix' => 'products'], function () {
        Route::post('index',            ['as' => 'products.store',                 'uses' => 'ProductsController@store']);
        Route::get('/',                 ['as' => 'products.index',                 'uses' => 'ProductsController@index']);
        Route::get('index',             ['as' => 'products.index',                 'uses' => 'ProductsController@index']);
        Route::get('index/{enabledOnly?}',['as' => 'products.index',               'uses' => 'ProductsController@index']);
        Route::get('create',            ['as' => 'products.create',                'uses' => 'ProductsController@create']);
        Route::get('enableSelected',    ['as' => 'products.enable-selected',       'uses' => 'ProductsController@enableSelected']);
        Route::get('disableSelected',   ['as' => 'products.disable-selected',      'uses' => 'ProductsController@disableSelected']);
        Route::get('{id}',              ['as' => 'products.show',                  'uses' => 'ProductsController@show']);
        Route::get('{id}/edit',         ['as' => 'products.edit',                  'uses' => 'ProductsController@edit']);
        Route::patch('{id}/edit',       ['as' => 'products.update',                'uses' => 'ProductsController@update']);
        Route::get('enable/{id}',       ['as' => 'products.enable',                'uses' => 'ProductsController@enable']);
        Route::get('disable/{id}',      ['as' => 'products.disable',               'uses' => 'ProductsController@disable']);
    });

    Route::group(['prefix' => 'sales'], function () {
        Route::post('index',            ['as' => 'sales.store',                 'uses' => 'SalesController@store']);
        Route::get('/',                 ['as' => 'sales.index',                 'uses' => 'SalesController@index']);
        Route::get('index',             ['as' => 'sales.index',                 'uses' => 'SalesController@index']);
        Route::get('index/{enabledOnly?}',['as' => 'sales.index',               'uses' => 'SalesController@index']);
        Route::get('create',            ['as' => 'sales.create',                'uses' => 'SalesController@create']);
        Route::get('rollover/{id}/{reminder_id}',     ['as' => 'sales.rollover',              'uses' => 'SalesController@rollover']);
        Route::get('due',               ['as' => 'sales.due',                   'uses' => 'SalesController@due']);
        Route::get('due_export',        ['as' => 'sales.due_export',            'uses' => 'SalesController@due_export']);
        Route::get('enableSelected',    ['as' => 'sales.enable-selected',       'uses' => 'SalesController@enableSelected']);
        Route::get('disableSelected',   ['as' => 'sales.disable-selected',      'uses' => 'SalesController@disableSelected']);
        Route::get('export',            ['as' => 'sales.export',                'uses' => 'SalesController@export']);
        Route::get('{id}',              ['as' => 'sales.show',                  'uses' => 'SalesController@show']);
        Route::get('{id}/edit',         ['as' => 'sales.edit',                  'uses' => 'SalesController@edit']);
        Route::patch('{id}/edit',       ['as' => 'sales.update',                'uses' => 'SalesController@update']);
        Route::get('enable/{id}',       ['as' => 'sales.enable',                'uses' => 'SalesController@enable']);
        Route::get('disable/{id}',      ['as' => 'sales.disable',               'uses' => 'SalesController@disable']);
        Route::get('interest/{id}',     ['as' => 'sales.interest',              'uses' => 'SalesController@interest']);
    });

    Route::group(['prefix' => 'slips/commission'], function () {
        Route::get('/',                 ['as' => 'slips.commission.index',                 'uses' => 'CommissionSlipsController@index']);
        Route::get('index',             ['as' => 'slips.commission.index',                 'uses' => 'CommissionSlipsController@index']);
        Route::get('export',            ['as' => 'slips.commission.export',                'uses' => 'CommissionSlipsController@export']);
        Route::get('minus',             ['as' => 'slips.commission.minus',                 'uses' => 'CommissionSlipsController@minus']);
        Route::get('delete_minus',      ['as' => 'slips.commission.delete_minus',          'uses' => 'CommissionSlipsController@delete_minus']);
    });

    Route::group(['prefix' => 'slips/overriding'], function () {
        Route::get('/',                 ['as' => 'slips.overriding.index',                 'uses' => 'OverridingSlipsController@index']);
        Route::get('index',             ['as' => 'slips.overriding.index',                 'uses' => 'OverridingSlipsController@index']);
        Route::get('export',            ['as' => 'slips.overriding.export',                'uses' => 'OverridingSlipsController@export']);
        Route::get('minus',             ['as' => 'slips.overriding.minus',                 'uses' => 'OverridingSlipsController@minus']);
        Route::get('delete_minus',      ['as' => 'slips.overriding.delete_minus',          'uses' => 'OverridingSlipsController@delete_minus']);
    });

    Route::group(['prefix' => 'slips/topoverriding'], function () {
        Route::get('/',                 ['as' => 'slips.topoverriding.index',                 'uses' => 'TopOverridingSlipsController@index']);
        Route::get('index',             ['as' => 'slips.topoverriding.index',                 'uses' => 'TopOverridingSlipsController@index']);
        Route::get('export',            ['as' => 'slips.topoverriding.export',                'uses' => 'TopOverridingSlipsController@export']);
        Route::get('minus',             ['as' => 'slips.topoverriding.minus',                 'uses' => 'TopOverridingSlipsController@minus']);
        Route::get('delete_minus',      ['as' => 'slips.topoverriding.delete_minus',          'uses' => 'TopOverridingSlipsController@delete_minus']);
    });

    Route::group(['prefix' => 'slips/recap'], function () {
        Route::get('/',                 ['as' => 'slips.recap.index',                 'uses' => 'RecapSlipsController@index']);
        Route::get('index',             ['as' => 'slips.recap.index',                 'uses' => 'RecapSlipsController@index']);
        Route::get('export',            ['as' => 'slips.recap.export',                'uses' => 'RecapSlipsController@export']);
    });

    Route::group(['prefix' => 'slips/commissionsummary'], function () {
        Route::get('/',                 ['as' => 'slips.commissionsummary.index',                 'uses' => 'CommissionSlipsSummaryController@index']);
        Route::get('index',             ['as' => 'slips.commissionsummary.index',                 'uses' => 'CommissionSlipsSummaryController@index']);
        Route::get('export',            ['as' => 'slips.commissionsummary.export',                'uses' => 'CommissionSlipsSummaryController@export']);
    });

    Route::group(['prefix' => 'slips/overridingsummary'], function () {
        Route::get('/',                 ['as' => 'slips.overridingsummary.index',                 'uses' => 'OverridingSlipsSummaryController@index']);
        Route::get('index',             ['as' => 'slips.overridingsummary.index',                 'uses' => 'OverridingSlipsSummaryController@index']);
        Route::get('export',            ['as' => 'slips.overridingsummary.export',                'uses' => 'OverridingSlipsSummaryController@export']);
    });

    Route::group(['prefix' => 'slips/topoverridingsummary'], function () {
        Route::get('/',                 ['as' => 'slips.topoverridingsummary.index',                 'uses' => 'TopOverridingSlipsSummaryController@index']);
        Route::get('index',             ['as' => 'slips.topoverridingsummary.index',                 'uses' => 'TopOverridingSlipsSummaryController@index']);
        Route::get('export',            ['as' => 'slips.topoverridingsummary.export',                'uses' => 'TopOverridingSlipsSummaryController@export']);
    });

    Route::group(['prefix' => 'slips/finalsummary'], function () {
        Route::get('/',                 ['as' => 'slips.finalsummary.index',                 'uses' => 'FinalSummaryController@index']);
        Route::get('index',             ['as' => 'slips.finalsummary.index',                 'uses' => 'FinalSummaryController@index']);
        Route::get('export',            ['as' => 'slips.finalsummary.export',                'uses' => 'FinalSummaryController@export']);
    });
    // TODO: Remove this before release...
    if ($this->app->environment('development')) {
        // TEST-ACL routes
        Route::group(['prefix' => 'test-acl'], function () {
            Route::get('home',                  ['as' => 'test-acl.home',                'uses' => 'TestController@test_acl_home']);
            Route::get('do-not-pre-load',       ['as' => 'test-acl.do-not-pre-load',     'uses' => 'TestController@test_acl_do_not_load']);
            Route::get('no-perm',               ['as' => 'test-acl.no-perm',             'uses' => 'TestController@test_acl_no_perm']);
            Route::get('basic-authenticated',   ['as' => 'test-acl.basic-authenticated', 'uses' => 'TestController@test_acl_basic_authenticated']);
            Route::get('guest-only',            ['as' => 'test-acl.guest-only',          'uses' => 'TestController@test_acl_guest_only']);
            Route::get('open-to-all',           ['as' => 'test-acl.open-to-all',         'uses' => 'TestController@test_acl_open_to_all']);
            Route::get('admins',                ['as' => 'test-acl.admins',              'uses' => 'TestController@test_acl_admins']);
            Route::get('power-users',           ['as' => 'test-acl.power-users',         'uses' => 'TestController@test_acl_power_users']);
        }); // End of TEST-ACL group

        // TEST-FLASH routes
        Route::group(['prefix' => 'test-flash'], function () {
            Route::get('home',    ['as' => 'test-flash.home',     'uses' => 'TestController@test_flash_home']);
            Route::get('success', ['as' => 'test-flash.success',  'uses' => 'TestController@test_flash_success']);
            Route::get('info',    ['as' => 'test-flash.info',     'uses' => 'TestController@test_flash_info']);
            Route::get('warning', ['as' => 'test-flash.warning',  'uses' => 'TestController@test_flash_warning']);
            Route::get('error',   ['as' => 'test-flash.error',    'uses' => 'TestController@test_flash_error']);
        }); // End of TEST-FLASH group
        // TEST-MENU routes
        Route::group(['prefix' => 'test-menus'], function () {
            Route::get('home',     ['as' => 'test-menus.home',  'uses' => 'TestMenusController@test_menu_home']);
            Route::get('one',      ['as' => 'test-menus.one',   'uses' => 'TestMenusController@test_menu_one']);
            Route::get('two',      ['as' => 'test-menus.two',   'uses' => 'TestMenusController@test_menu_two']);
            Route::get('two-a',    ['as' => 'test-menus.two-a', 'uses' => 'TestMenusController@test_menu_two_a']);
            Route::get('two-b',    ['as' => 'test-menus.two-b', 'uses' => 'TestMenusController@test_menu_two_b']);
            Route::get('three',    ['as' => 'test-menus.three', 'uses' => 'TestMenusController@test_menu_three']);
        }); // End of TEST-MENU group
    } // End of if DEV environment

}); // end of AUTHORIZE middleware group
