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
use App\BranchOffice;
use App\SalesTarget;
use App\Product;

class ProductionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(/* User $user, Role $role */)
    {
        ////////////////////////////////////
        // Load the routes
        Route::loadLaravelRoutes();
        // Look for and delete route named 'do-not-load' if it exist.
        // That route is used to test the Authorization middleware and should not be loaded automatically.
        $routeToDelete = Route::where('name', 'do-not-load')->get()->first();
        if ($routeToDelete) Route::destroy($routeToDelete->id);

        ////////////////////////////////////
        // Create basic set of permissions
        $permInsertCustomers = Permission::create([
            'name' => 'insert-customers',
            'display_name' => 'insert-customers',
            'description' => 'insert-customers',
            'enabled' => true,
        ]);
        $permDeleteCustomers = Permission::create([
            'name' => 'delete-customers',
            'display_name' => 'delete-customers',
            'description' => 'delete-customers',
            'enabled' => true,
        ]);
        $permUpdateCustomers = Permission::create([
            'name' => 'update-customers',
            'display_name' => 'update-customers',
            'description' => 'update-customers',
            'enabled' => true,
        ]);
        $permAdminUpdateCustomers = Permission::create([
            'name' => 'admin-update-customers',
            'display_name' => 'admin-update-customers',
            'description' => 'admin-update-customers',
            'enabled' => true,
        ]);
        $permViewCustomers = Permission::create([
            'name' => 'view-customers',
            'display_name' => 'view-customers',
            'description' => 'view-customers',
            'enabled' => true,
        ]);


        $permInsertSales = Permission::create([
            'name' => 'insert-sales',
            'display_name' => 'insert-sales',
            'description' => 'insert-sales',
            'enabled' => true,
        ]);
        $permDeleteSales = Permission::create([
            'name' => 'delete-sales',
            'display_name' => 'delete-sales',
            'description' => 'delete-sales',
            'enabled' => true,
        ]);
        $permUpdateSales = Permission::create([
            'name' => 'update-sales',
            'display_name' => 'update-sales',
            'description' => 'update-sales',
            'enabled' => true,
        ]);
        $permViewSales = Permission::create([
            'name' => 'view-sales',
            'display_name' => 'view-sales',
            'description' => 'view-sales',
            'enabled' => true,
        ]);

        $permInsertAgents = Permission::create([
            'name' => 'insert-agents',
            'display_name' => 'insert-agents',
            'description' => 'insert-agents',
            'enabled' => true,
        ]);
        $permDeleteAgents = Permission::create([
            'name' => 'delete-agents',
            'display_name' => 'delete-agents',
            'description' => 'delete-agents',
            'enabled' => true,
        ]);
        $permUpdateAgents = Permission::create([
            'name' => 'update-agents',
            'display_name' => 'update-agents',
            'description' => 'update-agents',
            'enabled' => true,
        ]);
        $permViewAgents = Permission::create([
            'name' => 'view-agents',
            'display_name' => 'view-agents',
            'description' => 'view-agents',
            'enabled' => true,
        ]);

        $permManageProducts = Permission::create([
            'name' => 'manage-products',
            'display_name' => 'manage-products',
            'description' => 'manage-products',
            'enabled' => true,
        ]);
        $permManageAudit = Permission::create([
            'name' => 'manage-audit',
            'display_name' => 'manage-audit',
            'description' => 'manage-audit',
            'enabled' => true,
        ]);

        $permPrintSlips = Permission::create([
            'name' => 'print-slips',
            'display_name' => 'print-slips',
            'description' => 'print-slips',
            'enabled' => true,
        ]);

        $permAdminSettings = Permission::create([
            'name' => 'admin-settings',
            'display_name' => 'admin-settings',
            'description' => 'admin-settings',
            'enabled' => true,
        ]);
        $permHolidaySettings = Permission::create([
            'name' => 'holiday-settings',
            'display_name' => 'holiday-settings',
            'description' => 'holiday-settings',
            'enabled' => true,
        ]);

        $permDashboard = Permission::create([
            'name' => 'dashboard',
            'display_name' => 'dashboard',
            'description' => 'dashboard',
            'enabled' => true,
        ]);

        $permApproval = Permission::create([
            'name' => 'approval',
            'display_name' => 'approval',
            'description' => 'approval',
            'enabled' => true,
        ]);

        $permEvaluation = Permission::create([
            'name' => 'evaluation',
            'display_name' => 'evaluation',
            'description' => 'evaluation',
            'enabled' => true,
        ]);

        $permOpenToAll = Permission::create([
            'name' => 'open-to-all',
            'display_name' => 'Open to all',
            'description' => 'Everyone can access these.',
            'enabled' => true,
        ]);


        ////////////////////////////////////
        // Associate open-to-all permission to some routes
        $routeBackslash = Route::where('name', 'backslash')->get()->first();
        $routeBackslash->permission()->associate($permOpenToAll);
        $routeBackslash->save();
        $routeHome = Route::where('name', 'home')->get()->first();
        $routeHome->permission()->associate($permOpenToAll);
        $routeHome->save();
        // Associate manage-menus permissions to routes starting with 'admin.'
        $manageMenusRoutes = Route::where('name', 'like', "admin.%")->get()->all();
        foreach ($manageMenusRoutes as $route) {
            $route->permission()->associate($permAdminSettings);
            $route->save();
        }

        // Associate the audit-log permissions
        $routeAuditView = Route::where('name', 'LIKE', 'admin.audit.%')->get()->all();
        foreach ($routeAuditView as $routeAudit) {
            $routeAudit->permission()->associate($permManageAudit);
            $routeAudit->save();
        }
        $routeHolidays = Route
            ::where('name', 'LIKE', 'admin.holidays.%')
            ->get()->all();
        foreach ($routeHolidays as $routeHoliday) {
            $routeHoliday->permission()->associate($permHolidaySettings);
            $routeHoliday->save();
        }

        // Manage-customers
        $routeCustomers = Route
            ::whereIn('name', ['customers.create', 'customers.store'])
            ->get()->all();
        foreach ($routeCustomers as $routeCustomer) {
            $routeCustomer->permission()->associate($permInsertCustomers);
            $routeCustomer->save();
        }
        $routeCustomers = Route
            ::whereIn('name',
                ['customers.edit', 'customers.update'])
            ->get()->all();
        foreach ($routeCustomers as $routeCustomer) {
            $routeCustomer->permission()->associate($permUpdateCustomers);
            $routeCustomer->save();
        }
        $routeCustomers = Route
            ::whereIn('name',
                ['customers.admin_edit'])
            ->get()->all();
        foreach ($routeCustomers as $routeCustomer) {
            $routeCustomer->permission()->associate($permAdminUpdateCustomers);
            $routeCustomer->save();
        }

        $routeCustomers = Route
            ::whereIn('name', ['customers.index', 'customers.export', 'customers.show'])
            ->get()->all();
        foreach ($routeCustomers as $routeCustomer) {
            $routeCustomer->permission()->associate($permViewCustomers);
            $routeCustomer->save();
        }
        $routeCustomers = Route
            ::whereIn('name', ['customers.enable', 'customers.disable',
                'customers.enable-selected', 'customers.disable-selected'])
            ->get()->all();
        foreach ($routeCustomers as $routeCustomer) {
            $routeCustomer->permission()->associate($permDeleteCustomers);
            $routeCustomer->save();
        }


        // Manage-agents
        $routeAgents = Route
            ::whereIn('name', ['agents.create', 'agents.store'])
            ->get()->all();
        foreach ($routeAgents as $routeAgent) {
            $routeAgent->permission()->associate($permInsertAgents);
            $routeAgent->save();
        }
        $routeAgents = Route
            ::whereIn('name',
                ['agents.edit', 'agents.update'])
            ->get()->all();
        foreach ($routeAgents as $routeAgent) {
            $routeAgent->permission()->associate($permUpdateAgents);
            $routeAgent->save();
        }
        $routeAgents = Route
            ::whereIn('name', ['agents.index', 'agents.export', 'agents.show', 'agents.summary', 'agents.summary_export', 'agents.history'])
            ->get()->all();
        foreach ($routeAgents as $routeAgent) {
            $routeAgent->permission()->associate($permViewAgents);
            $routeAgent->save();
        }
        $routeAgents = Route
            ::whereIn('name', ['agents.enable', 'agents.disable',
                'agents.enable-selected', 'agents.disable-selected'])
            ->get()->all();
        foreach ($routeAgents as $routeAgent) {
            $routeAgent->permission()->associate($permDeleteAgents);
            $routeAgent->save();
        }


        // Manage-sales
        $routeSales = Route
            ::whereIn('name', ['sales.create', 'sales.store'])
            ->get()->all();
        foreach ($routeSales as $routeSale) {
            $routeSale->permission()->associate($permInsertSales);
            $routeSale->save();
        }
        $routeSales = Route
            ::whereIn('name',
                ['sales.edit', 'sales.update'])
            ->get()->all();
        foreach ($routeSales as $routeSale) {
            $routeSale->permission()->associate($permUpdateSales);
            $routeSale->save();
        }
        $routeSales = Route
            ::whereIn('name', ['sales.index', 'sales.export', 'sales.show', 'sales.due', 'sales.due_export', 'sales.interest', 'sales.rollover'])
            ->get()->all();
        foreach ($routeSales as $routeSale) {
            $routeSale->permission()->associate($permViewSales);
            $routeSale->save();
        }
        $routeSales = Route
            ::whereIn('name', ['sales.enable', 'sales.disable',
                'sales.enable-selected', 'sales.disable-selected'])
            ->get()->all();
        foreach ($routeSales as $routeSale) {
            $routeSale->permission()->associate($permDeleteSales);
            $routeSale->save();
        }

        // Manage-products
        $routeProducts = Route
            ::whereIn('name', ['products.index', 'products.create', 'products.store',
                'products.show', 'products.edit', 'products.update', 'products.enable', 'products.disable',
                'products.enable-selected', 'products.disable-selected'])
            ->get()->all();
        foreach ($routeProducts as $routeProduct) {
            $routeProduct->permission()->associate($permManageProducts);
            $routeProduct->save();
        }

        // Print-slips
        $routeSlips = Route
            ::where('name', 'LIKE', 'slips.%')
            ->get()->all();
        foreach ($routeSlips as $routeSlip) {
            $routeSlip->permission()->associate($permPrintSlips);
            $routeSlip->save();
        }

        // Print-slips
        $routeApprovals = Route
            ::where('name', 'LIKE', 'approvals.%')
            ->get()->all();
        foreach ($routeApprovals as $routeApproval) {
            $routeApproval->permission()->associate($permApproval);
            $routeApproval->save();
        }

        // Print-slips
        $routeEvals = Route
            ::where('name', 'LIKE', 'evaluation')
            ->get()->all();
        foreach ($routeEvals as $routeEval) {
            $routeEval->permission()->associate($permEvaluation);
            $routeEval->save();
        }

        // Print-slips
        $routeDashboards = Route
            ::where('name', 'LIKE', 'dashboard%')
            ->get()->all();
        foreach ($routeDashboards as $routeDashboard) {
            $routeDashboard->permission()->associate($permDashboard);
            $routeDashboard->save();
        }


        ////////////////////////////////////
        // Create role: owner
        $roleOwner = Role::create([
            "name" => "owner",
            "display_name" => "Owner",
            "description" => "Owner have no restrictions",
            "enabled" => true
        ]);
        foreach (Permission::get()->all() as $perm) {
            $roleOwner->perms()->attach($perm->id);
        }

        // Create role: super-admin
        // Assign permission basic-authenticated
        $roleSuperAdmin = Role::create([
            "name" => "super-admin",
            "display_name" => "Super-Admin",
            "description" => "Super admin only can update and delete datas",
            "enabled" => true
        ]);
        $roleSuperAdmin->perms()->attach($permViewCustomers->id);
        $roleSuperAdmin->perms()->attach($permUpdateCustomers->id);
        $roleSuperAdmin->perms()->attach($permAdminUpdateCustomers->id);
        $roleSuperAdmin->perms()->attach($permDeleteCustomers->id);
        $roleSuperAdmin->perms()->attach($permUpdateAgents->id);
        $roleSuperAdmin->perms()->attach($permDeleteAgents->id);
        $roleSuperAdmin->perms()->attach($permViewAgents->id);
        $roleSuperAdmin->perms()->attach($permUpdateSales->id);
        $roleSuperAdmin->perms()->attach($permDeleteSales->id);
        $roleSuperAdmin->perms()->attach($permViewSales->id);
        $roleSuperAdmin->perms()->attach($permPrintSlips->id);
        $roleSuperAdmin->perms()->attach($permDashboard->id);
        $roleSuperAdmin->perms()->attach($permOpenToAll->id);

        // Create role: otor
        // Assign permission basic-authenticated
        $roleOtor = Role::create([
            "name" => "otor",
            "display_name" => "Otor",
            "description" => "Otor approve data that changed by website operator",
            "enabled" => true
        ]);
        $roleOtor->perms()->attach($permApproval->id);
        $roleOtor->perms()->attach($permDashboard->id);
        $roleOtor->perms()->attach($permOpenToAll->id);


        // Create role: users
        // Assign permission basic-authenticated
        $roleAdmin = Role::create([
            "name" => "admin",
            "display_name" => "Website-Operator",
            "description" => "Website and system operator",
            "enabled" => true
        ]);
        $roleAdmin->perms()->attach($permInsertCustomers->id);
        $roleAdmin->perms()->attach($permViewCustomers->id);
        $roleAdmin->perms()->attach($permInsertAgents->id);
        $roleAdmin->perms()->attach($permViewAgents->id);
        $roleAdmin->perms()->attach($permInsertSales->id);
        $roleAdmin->perms()->attach($permViewSales->id);
        $roleAdmin->perms()->attach($permPrintSlips->id);
        $roleAdmin->perms()->attach($permDashboard->id);
        $roleAdmin->perms()->attach($permHolidaySettings->id);
        $roleAdmin->perms()->attach($permEvaluation->id);
        $roleAdmin->perms()->attach($permOpenToAll->id);

        ////////////////////////////////////
        // Create user: root
        // Assign membership to role admins, membership to role users is
        // automatic.
        $userRoot = User::create([
            "first_name" => "Owner",
            "last_name" => "(Superuser)",
            "username" => "owner",
            "email" => "owner@email.com",
            "password" => "Password1",
            "auth_type" => "internal",
            "enabled" => true
        ]);
        $userRoot->roles()->attach($roleOwner->id);

        $userOtor = User::create([
            "first_name" => "Otor",
            "last_name" => "(Otor)",
            "username" => "otor",
            "email" => "otor@email.com",
            "password" => "Password2",
            "auth_type" => "internal",
            "enabled" => true
        ]);
        $userOtor->roles()->attach($roleOtor->id);

        $userSA = User::create([
            "first_name" => "Super-Admin",
            "last_name" => "(Super-Admin)",
            "username" => "superadmin",
            "email" => "superadmin@email.com",
            "password" => "Password3",
            "auth_type" => "internal",
            "enabled" => true
        ]);
        $userSA->roles()->attach($roleSuperAdmin->id);

        $userAdmin = User::create([
            "first_name" => "Admin",
            "last_name" => "(Website Admin)",
            "username" => "admin",
            "email" => "admin@email.com",
            "password" => "admin",
            "auth_type" => "internal",
            "enabled" => true
        ]);
        $userAdmin->roles()->attach($roleAdmin->id);


        ////////////////////////////////////
        // Create menu: root
        $menuRoot = Menu::create([
//            'id'          => 0,                   // Hard-coded
            'name' => 'root',
            'label' => 'Root',
            'position' => 0,
            'icon' => 'fa fa-folder',      // No point setting this as root is not visible.
            'separator' => false,
            'url' => null,                // No URL, root is not rendered or visible.
            'enabled' => true,                // Must be enabled or sub-menus will not be available.
//            'parent_id'     => 0,                   // Parent of itself.
            'route_id' => null,                // No route, root cannot be reached.
            'permission_id' => $permOpenToAll->id,  // Must be visible to all, for all sub-menus to be visible.
        ]);
        // Force root parent to itself.
        $menuRoot->parent_id = $menuRoot->id;
        $menuRoot->save();
        // Create Home menu
        $menuHome = Menu::create([
            'name' => 'home',
            'label' => 'Home',
            'position' => 0,
            'icon' => 'fa fa-home fa-colour-green',
            'separator' => false,
            'url' => '/',
            'enabled' => true,
            'parent_id' => $menuRoot->id,       // Parent is root.
            'route_id' => $routeHome->id,      // Route to home
            'permission_id' => $permOpenToAll->id,                // Get permission from route.
        ]);
        // Create Dashboard menu
        $menuDashboard = Menu::create([
            'name' => 'dashboard',
            'label' => 'Dashboard',
            'position' => 0,
            'icon' => 'fa fa-dashboard',
            'separator' => false,
            'url' => '/dashboard',
            'enabled' => true,
            'parent_id' => $menuHome->id,       // Parent is root.
            'route_id' => 'dashboard',
            'permission_id' => $permDashboard->id, // Get permission from route.
        ]);

        // Create Dashboard menu
        $menuApprovals = Menu::create([
            'name' => 'approvals',
            'label' => 'Approvals',
            'position' => 1,
            'icon' => 'fa fa-check-square-o',
            'separator' => false,
            'url' => '/approvals',
            'enabled' => true,
            'parent_id' => $menuHome->id,       // Parent is root.
            'route_id' => 'approvals',
            'permission_id' => $permApproval->id, // Get permission from route.
        ]);

        // Create Admin container.
        $menuAdmin = Menu::create([
            'name' => 'settings',
            'label' => 'Settings',
            'position' => 999,                 // Artificially high number to ensure that it is rendered last.
            'icon' => 'fa fa-cog',
            'separator' => false,
            'url' => null,                // No url.
            'enabled' => true,
            'parent_id' => $menuHome->id,       // Parent is root.
            'route_id' => null,                // No route
            'permission_id' => $permOpenToAll->id,                // Get permission from sub-items. If the user has permission to see/use
            // any sub-items, the admin menu will be rendered, otherwise it will
            // not.
        ]);
        // Create Audit sub-menu
        $menuAudit = Menu::create([
            'name' => 'audit',
            'label' => 'Audit',
            'position' => 0,
            'icon' => 'fa fa-binoculars',
            'separator' => false,
            'url' => null,                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuAdmin->id,      // Parent is admin.
            'route_id' => Route::where('name', '=', 'admin.audit.index')->first()->id,
            'permission_id' => null,                // Get permission from route.
        ]);
        // Create Security container.
        $menuSecurity = Menu::create([
            'name' => 'security',
            'label' => 'Security',
            'position' => 1,
            'icon' => 'fa fa-user-secret fa-colour-red',
            'separator' => false,
            'url' => null,                // No url.
            'enabled' => true,
            'parent_id' => $menuAdmin->id,      // Parent is admin.
            'route_id' => null,                // No route
            'permission_id' => null,                // Get permission from sub-items.
        ]);
        // Create Menus sub-menu
        $menuMenus = Menu::create([
            'name' => 'menus',
            'label' => 'Menus',
            'position' => 0,
            'icon' => 'fa fa-bars',
            'separator' => false,
            'url' => null,                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSecurity->id,   // Parent is security.
            'route_id' => Route::where('name', 'like', "admin.menus.index")->get()->first()->id,
            'permission_id' => null,                // Get permission from route.
        ]);
        // Create Branches sub-menu
        // $menuBranches = Menu::create([
        //     'name' => 'branches',
        //     'label' => 'Branches',
        //     'position' => 1,
        //     'icon' => 'fa fa-bars',
        //     'separator' => false,
        //     'url' => null,                // Get URL from route.
        //     'enabled' => true,
        //     'parent_id' => $menuSecurity->id,   // Parent is security.
        //     'route_id' => Route::where('name', 'like', "admin.branches.index")->get()->first()->id,
        //     'permission_id' => null,                // Get permission from route.
        // ]);
        // Create Users sub-menu
        $menuUsers = Menu::create([
            'name' => 'users',
            'label' => 'Users',
            'position' => 2,
            'icon' => 'fa fa-user',
            'separator' => false,
            'url' => null,                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSecurity->id,   // Parent is security.
            'route_id' => Route::where('name', 'like', "admin.users.index")->get()->first()->id,
            'permission_id' => null,                // Get permission from route.
        ]);
        // Create Roles sub-menu
        $menuRoles = Menu::create([
            'name' => 'roles',
            'label' => 'Roles',
            'position' => 3,
            'icon' => 'fa fa-users',
            'separator' => false,
            'url' => null,                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSecurity->id,   // Parent is security.
            'route_id' => Route::where('name', 'like', "admin.roles.index")->get()->first()->id,
            'permission_id' => null,                // Get permission from route.
        ]);
        // Create Permissions sub-menu
        $menuPermissions = Menu::create([
            'name' => 'permissions',
            'label' => 'Permissions',
            'position' => 4,
            'icon' => 'fa fa-bolt',
            'separator' => false,
            'url' => null,                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSecurity->id,   // Parent is security.
            'route_id' => Route::where('name', 'like', "admin.permissions.index")->get()->first()->id,
            'permission_id' => null,                // Get permission from route.
        ]);
        // Create Routes sub-menu
        $menuRoutes = Menu::create([
            'name' => 'routes',
            'label' => 'Routes',
            'position' => 5,
            'icon' => 'fa fa-road',
            'separator' => false,
            'url' => null,                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSecurity->id,   // Parent is security.
            'route_id' => Route::where('name', 'like', "admin.routes.index")->get()->first()->id,
            'permission_id' => null,                // Get permission from route.
        ]);

        // Create Settings sub-menu
        $menuSettings = Menu::create([
            'name' => 'system',
            'label' => 'System',
            'position' => 2,
            'icon' => 'fa fa-cogs',
            'separator' => false,
            'url' => null,                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuAdmin->id,      // Parent is admin.
            'route_id' => null,
            'permission_id' => $permOpenToAll->id,                // Get permission from route.
        ]);

        Menu::create([
            'name' => 'agent_pos',
            'label' => 'Agent Position',
            'position' => 1,
            'icon' => 'fa fa-cogs',
            'separator' => false,
            'url' => '/admin/agentpos',                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSettings->id,      // Parent is setting.
            'route_id' => 'admin.agentpos.index',
            'permission_id' => $permAdminSettings->id,                // Get permission from route.
        ]);

        Menu::create([
            'name' => 'sales_target',
            'label' => 'Sales Target',
            'position' => 2,
            'icon' => 'fa fa-cogs',
            'separator' => false,
            'url' => '/admin/target',                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSettings->id,      // Parent is setting.
            'route_id' => 'admin.target.index',
            'permission_id' => $permAdminSettings->id,                // Get permission from route.
        ]);

        $menuSettingsCommission = Menu::create([
            'name' => 'commission',
            'label' => 'Agent Commission',
            'position' => 3,
            'icon' => 'fa fa-cogs',
            'separator' => false,
            'url' => null,                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSettings->id,      // Parent is setting.
            'route_id' => null,
            'permission_id' => $permAdminSettings->id,                // Get permission from route.
        ]);

        Menu::create([
            'name' => 'sale_comm',
            'label' => 'Sale Commission',
            'position' => 1,
            'icon' => 'fa fa-cogs',
            'separator' => false,
            'url' => '/admin/salecommission',                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSettingsCommission->id,      // Parent is commission.
            'route_id' => 'admin.salecommission.index',
            'permission_id' => $permAdminSettings->id,                // Get permission from route.
        ]);

        Menu::create([
            'name' => 'ovr_comm',
            'label' => 'Ovr Commission',
            'position' => 2,
            'icon' => 'fa fa-cogs',
            'separator' => false,
            'url' => '/admin/ovrcommission',                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSettingsCommission->id,      // Parent is commission.
            'route_id' => 'admin.ovrcommission.index',
            'permission_id' => $permAdminSettings->id,                // Get permission from route.
        ]);

        Menu::create([
            'name' => 'mgi',
            'label' => 'MGI',
            'position' => 4,
            'icon' => 'fa fa-cogs',
            'separator' => false,
            'url' => '/admin/mgi',                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSettings->id,      // Parent is setting.
            'route_id' => 'admin.mgi.index',
            'permission_id' => $permAdminSettings->id,                // Get permission from route.
        ]);

        Menu::create([
            'name' => 'branch_office',
            'label' => 'Branch Office',
            'position' => 5,
            'icon' => 'fa fa-cogs',
            'separator' => false,
            'url' => '/admin/branchoffice',                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSettings->id,      // Parent is setting.
            'route_id' => 'admin.mgi.index',
            'permission_id' => $permAdminSettings->id,                // Get permission from route.
        ]);

        Menu::create([
            'name' => 'holiday',
            'label' => 'Holidays',
            'position' => 6,
            'icon' => 'fa fa-cogs',
            'separator' => false,
            'url' => '/admin/holidays',                // Get URL from route.
            'enabled' => true,
            'parent_id' => $menuSettings->id,      // Parent is setting.
            'route_id' => 'admin.mgi.index',
            'permission_id' => $permHolidaySettings->id,                // Get permission from route.
        ]);

        // create customers menu
        $menuCustomerParent = Menu::create([
            'name' => 'customers.parent',
            'label' => 'Customers',
            'position' => 20,
            'icon' => 'fa fa-users',
            'separator' => false,
            'url' => null,
            'enabled' => true,
            'parent_id' => $menuHome->id,       // Parent is home.
            'route_id' => null,
            'permission_id' => $permInsertCustomers->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'customers.index',
            'label' => 'Master',
            'position' => 0,
            'icon' => 'fa fa-th-list',
            'separator' => false,
            'url' => '/customers/index',
            'enabled' => true,
            'parent_id' => $menuCustomerParent->id,
            'route_id' => 'customers.index',
            'permission_id' => $permViewCustomers->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'customers.create',
            'label' => 'Create new',
            'position' => 10,
            'icon' => 'fa fa-plus',
            'separator' => false,
            'url' => '/customers/create',
            'enabled' => true,
            'parent_id' => $menuCustomerParent->id,
            'route_id' => 'customers.create',
            'permission_id' => $permInsertCustomers->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'customers.show',
            'label' => 'Create new',
            'position' => 10,
            'icon' => 'fa fa-eye',
            'separator' => false,
            'url' => '/customers/show',
            'enabled' => false,
            'parent_id' => $menuCustomerParent->id,
            'route_id' => 'customers.show',
            'permission_id' => $permViewCustomers->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'customers.edit',
            'label' => 'Create new',
            'position' => 10,
            'icon' => 'fa fa-edit',
            'separator' => false,
            'url' => '/customers/edit',
            'enabled' => false,
            'parent_id' => $menuCustomerParent->id,
            'route_id' => 'customers.edit',
            'permission_id' => $permUpdateCustomers->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'customers.admin_edit',
            'label' => 'Edit',
            'position' => 99,
            'icon' => 'fa fa-edit',
            'separator' => false,
            'url' => '/customers/admin_edit',
            'enabled' => true,
            'parent_id' => $menuCustomerParent->id,
            'route_id' => 'customers.admin_edit',
            'permission_id' => $permAdminUpdateCustomers->id,  // Specify open to all for url.
        ]);

        // create agents menu
        $menuAgentParent = Menu::create([
            'name' => 'agents.parent',
            'label' => 'Agents',
            'position' => 30,
            'icon' => 'fa fa-briefcase',
            'separator' => false,
            'url' => null,
            'enabled' => true,
            'parent_id' => $menuHome->id,       // Parent is home.
            'route_id' => null,
            'permission_id' => $permInsertAgents->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'agents.index',
            'label' => 'Master',
            'position' => 0,
            'icon' => 'fa fa-th-list',
            'separator' => false,
            'url' => '/agents/index',
            'enabled' => true,
            'parent_id' => $menuAgentParent->id,
            'route_id' => 'agents.index',
            'permission_id' => $permViewAgents->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'agents.create',
            'label' => 'Create new',
            'position' => 20,
            'icon' => 'fa fa-plus',
            'separator' => false,
            'url' => '/agents/create',
            'enabled' => true,
            'parent_id' => $menuAgentParent->id,
            'route_id' => 'agents.create',
            'permission_id' => $permInsertAgents->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'agents.show',
            'label' => 'Show',
            'position' => 10,
            'icon' => 'fa fa-eye',
            'separator' => false,
            'url' => '/agents/show',
            'enabled' => false,
            'parent_id' => $menuAgentParent->id,
            'route_id' => 'agents.show',
            'permission_id' => $permViewAgents->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'agents.edit',
            'label' => 'Edit',
            'position' => 10,
            'icon' => 'fa fa-edit',
            'separator' => false,
            'url' => '/agents/edit',
            'enabled' => false,
            'parent_id' => $menuAgentParent->id,
            'route_id' => 'agents.edit',
            'permission_id' => $permUpdateAgents->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'agents.summary',
            'label' => 'Summary',
            'position' => 30,
            'icon' => 'fa fa-sitemap',
            'separator' => false,
            'url' => '/agents/summary',
            'enabled' => true,
            'parent_id' => $menuAgentParent->id,
            'route_id' => 'agents.summary',
            'permission_id' => $permViewAgents->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'evaluation',
            'label' => 'Evaluation',
            'position' => 40,
            'icon' => 'fa fa-line-chart',
            'separator' => false,
            'url' => '/evaluation',
            'enabled' => true,
            'parent_id' => $menuAgentParent->id,
            'route_id' => 'evaluation',
            'permission_id' => $permEvaluation->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'history',
            'label' => 'History',
            'position' => 50,
            'icon' => 'fa fa-hourglass-o',
            'separator' => false,
            'url' => '/agents/history',
            'enabled' => true,
            'parent_id' => $menuAgentParent->id,
            'route_id' => 'agents.history',
            'permission_id' => $permViewAgents->id,
        ]);

        // create products menu
        $menuProductParent = Menu::create([
            'name' => 'products.parent',
            'label' => 'Products',
            'position' => 40,
            'icon' => 'fa fa-cubes',
            'separator' => false,
            'url' => null,
            'enabled' => true,
            'parent_id' => $menuHome->id,       // Parent is home.
            'route_id' => null,
            'permission_id' => $permManageProducts->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'products.index',
            'label' => 'Master',
            'position' => 0,
            'icon' => 'fa fa-th-list',
            'separator' => false,
            'url' => '/products/index',
            'enabled' => true,
            'parent_id' => $menuProductParent->id,
            'route_id' => 'products.index',
            'permission_id' => $permManageProducts->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'products.create',
            'label' => 'Create new',
            'position' => 10,
            'icon' => 'fa fa-plus',
            'separator' => false,
            'url' => '/products/create',
            'enabled' => true,
            'parent_id' => $menuProductParent->id,
            'route_id' => 'products.create',
            'permission_id' => $permManageProducts->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'products.show',
            'label' => 'Show',
            'position' => 10,
            'icon' => 'fa fa-eye',
            'separator' => false,
            'url' => '/products/show',
            'enabled' => false,
            'parent_id' => $menuProductParent->id,
            'route_id' => 'products.show',
            'permission_id' => $permManageProducts->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'products.edit',
            'label' => 'Edit',
            'position' => 10,
            'icon' => 'fa fa-edit',
            'separator' => false,
            'url' => '/products/edit',
            'enabled' => false,
            'parent_id' => $menuProductParent->id,
            'route_id' => 'products.edit',
            'permission_id' => $permManageProducts->id,  // Specify open to all for url.
        ]);

        // create sales menu
        $menuSalesParent = Menu::create([
            'name' => 'sales.parent',
            'label' => 'Sales',
            'position' => 50,
            'icon' => 'fa fa-money',
            'separator' => false,
            'url' => null,
            'enabled' => true,
            'parent_id' => $menuHome->id,       // Parent is home.
            'route_id' => null,
            'permission_id' => $permInsertSales->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'sales.index',
            'label' => 'Master',
            'position' => 0,
            'icon' => 'fa fa-th-list',
            'separator' => false,
            'url' => '/sales/index',
            'enabled' => true,
            'parent_id' => $menuSalesParent->id,
            'route_id' => 'sales.index',
            'permission_id' => $permViewSales->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'sales.create',
            'label' => 'Create new',
            'position' => 20,
            'icon' => 'fa fa-plus',
            'separator' => false,
            'url' => '/sales/create',
            'enabled' => true,
            'parent_id' => $menuSalesParent->id,
            'route_id' => 'sales.create',
            'permission_id' => $permInsertSales->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'sales.due',
            'label' => 'Due Notification',
            'position' => 30,
            'icon' => 'fa fa-calendar',
            'separator' => false,
            'url' => '/sales/due',
            'enabled' => true,
            'parent_id' => $menuSalesParent->id,
            'route_id' => 'sales.due',
            'permission_id' => $permViewSales->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'sales.show',
            'label' => 'Show',
            'position' => 10,
            'icon' => 'fa fa-eye',
            'separator' => false,
            'url' => '/sales/show',
            'enabled' => false,
            'parent_id' => $menuSalesParent->id,
            'route_id' => 'sales.show',
            'permission_id' => $permViewSales->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'sales.edit',
            'label' => 'Edit',
            'position' => 10,
            'icon' => 'fa fa-edit',
            'separator' => false,
            'url' => '/sales/edit',
            'enabled' => false,
            'parent_id' => $menuSalesParent->id,
            'route_id' => 'sales.edit',
            'permission_id' => $permUpdateSales->id,  // Specify open to all for url.
        ]);

        // create slips menu
        $menuSlipParent = Menu::create([
            'name' => 'slips.parent',
            'label' => 'Slips',
            'position' => 60,
            'icon' => 'fa fa-file-text',
            'separator' => false,
            'url' => null,
            'enabled' => true,
            'parent_id' => $menuHome->id,       // Parent is home.
            'route_id' => null,
            'permission_id' => $permPrintSlips->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'slips.commission',
            'label' => 'Commission',
            'position' => 10,
            'icon' => 'fa fa-file-o',
            'separator' => false,
            'url' => '/slips/commission',
            'enabled' => true,
            'parent_id' => $menuSlipParent->id,       // Parent is home.
            'route_id' => null,
            'permission_id' => $permPrintSlips->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'slips.overriding',
            'label' => 'Overriding',
            'position' => 20,
            'icon' => 'fa fa-file-o',
            'separator' => false,
            'url' => '/slips/overriding',
            'enabled' => true,
            'parent_id' => $menuSlipParent->id,       // Parent is home.
            'route_id' => null,
            'permission_id' => $permPrintSlips->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'slips.topoverriding',
            'label' => 'Rec Fee',
            'position' => 30,
            'icon' => 'fa fa-file-o',
            'separator' => false,
            'url' => '/slips/topoverriding',
            'enabled' => true,
            'parent_id' => $menuSlipParent->id,       // Parent is home.
            'route_id' => null,
            'permission_id' => $permPrintSlips->id,  // Specify open to all for url.
        ]);

        Menu::create([
            'name' => 'slips.tax',
            'label' => 'Tax',
            'position' => 40,
            'icon' => 'fa fa-file-o',
            'separator' => false,
            'url' => '/slips/tax',
            'enabled' => true,
            'parent_id' => $menuSlipParent->id,       // Parent is home.
            'route_id' => null,
            'permission_id' => $permPrintSlips->id,  // Specify open to all for url.
        ]);

        AgentPosition::create([
            'id'        => 1,
            'parent_id' => null,
            'name'      => 'Senior Business Director',
            'acronym'   => 'SBD',
            'level'     => 1,
            'is_active' => 0
        ]);
        AgentPosition::create([
            'id'        => 2,
            'parent_id' => null,
            'name'      => 'Business Director',
            'acronym'   => 'BD',
            'level'     => 2,
            'is_active' => 1
        ]);
        AgentPosition::create([
            'id'        => 3,
            'parent_id' => 2,
            'name'      => 'Business Manager',
            'acronym'   => 'BM',
            'level'     => 3,
            'is_active' => 1
        ]);
        AgentPosition::create([
            'id'        => 4,
            'parent_id' => 3,
            'name'      => 'Financial Advisor',
            'acronym'   => 'FA',
            'level'     => 4,
            'is_active' => 1
        ]);


        SaleCommissionPercentage::create([
            'id'                 => 1,
            'agent_position_id'  => 1,
            'percentage'         => 1.0
        ]);

        SaleCommissionPercentage::create([
            'id'                 => 2,
            'agent_position_id'  => 2,
            'percentage'         => 1.5
        ]);

        SaleCommissionPercentage::create([
            'id'                 => 3,
            'agent_position_id'  => 3,
            'percentage'         => 1.5
        ]);

        SaleCommissionPercentage::create([
            'id'                 => 3,
            'agent_position_id'  => 4,
            'percentage'         => 1.5
        ]);

        // SBD -> SBD
        OverridingCommissionPercentage::create([
            'id'                 => 1,
            'agent_position_id'  => 1,
            'override_from'      => 1,
            'level'              => 1,
            'percentage'         => 0.3
        ]);

        OverridingCommissionPercentage::create([
            'id'                 => 2,
            'agent_position_id'  => 1,
            'override_from'      => 1,
            'level'              => 2,
            'percentage'         => 0.3
        ]);

        OverridingCommissionPercentage::create([
            'id'                 => 3,
            'agent_position_id'  => 1,
            'override_from'      => 1,
            'level'              => 3,
            'percentage'         => 0.3
        ]);

        // SBD -> BD
        OverridingCommissionPercentage::create([
            'id'                 => 4,
            'agent_position_id'  => 1,
            'override_from'      => null,
            'percentage'         => 0.5
        ]);

        // BD -> BD
        OverridingCommissionPercentage::create([
            'id'                 => 5,
            'agent_position_id'  => 2,
            'override_from'      => null,
            'percentage'         => 0.7
        ]);

        // BM -> BM
        OverridingCommissionPercentage::create([
            'id'                 => 7,
            'agent_position_id'  => 3,
            'override_from'      => null,
            'percentage'         => 0.9
        ]);

        // FA -> FA
        OverridingCommissionPercentage::create([
            'id'                 => 9,
            'agent_position_id'  => 4,
            'override_from'      => null,
            'percentage'         => 0
        ]);


        SalesTarget::create([
            'id'                => 1,
            'agent_position_id' => 3,
            'target_amount'     => 15000000
        ]);

        SalesTarget::create([
            'id'                => 2,
            'agent_position_id' => 2,
            'target_amount'     => 20000000
        ]);

        SalesTarget::create([
            'id'                => 3,
            'agent_position_id' => 1,
            'target_amount'     => 25000000
        ]);

        foreach(range(1,5) as $i){
            Product::create([
                'product_code' => 'PRO0' . strval($i),
                'product_name' => 'PRODUCT ' . strval($i),
                'is_active' => 1
            ]);
        }

        foreach(range(1,5) as $i){
            BranchOffice::create([
                'branch_name' => 'PRO0' . strval($i),
                'address' => 'ADDRESS ' . strval($i),
                'state' => 'STATE ' . strval($i),
                'city' => 'CITY ' . strval($i),
                'zipcode' => '11111',
                'phone_number' => '123123123',
                'is_active' => 1
            ]);
        }
    }
}
