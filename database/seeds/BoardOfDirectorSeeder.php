<?php

use App\AgentPosition;
use App\OverridingCommissionPercentage;
use App\SaleCommissionPercentage;
use Illuminate\Database\Seeder;
use App\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Route;
use App\Models\Menu;
use App\BoardOfDirector;
class BoardOfDirectorSeeder extends Seeder
{
	public function run(/* User $user, Role $role */)
    {
        ////////////////////////////////////
        // Load the routes
        Route::loadLaravelRoutes();
        $routeToDelete = Route::where('name', 'do-not-load')->get()->first();
        if ($routeToDelete) Route::destroy($routeToDelete->id);

        Menu::create([
            'name' => 'boardofdirector',
            'label' => 'Board of Director',
            'position' => 7,
            'icon' => 'fa fa-cogs',
            'separator' => false,
            'url' => '/admin/boardofdirector',                // Get URL from route.
            'enabled' => true,
            'parent_id' => 13,      // Parent is setting.
            'route_id' => false,
            'permission_id' => 17,                // Get permission from route.
        ]);

    }
}