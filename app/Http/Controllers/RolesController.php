<?php namespace App\Http\Controllers;

use App\Repositories\Criteria\Role\RolesWithPermissions;
use App\Repositories\Criteria\Role\RolesByNamesAscending;
use App\Repositories\Criteria\Role\RolesWhereDisplayNameOrDescriptionLike;
use App\Repositories\RoleRepository as Role;
use App\Repositories\PermissionRepository as Permission;
use App\Repositories\UserRepository as User;
use Illuminate\Http\Request;
use Flash;
use DB;
use App\Repositories\AuditRepository as Audit;
use Auth;
use App\Repositories\AgentRepository as Agent;

class RolesController extends Controller {

    /**
     * @var Role
     */
    private $role;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @var User
     */
    private $user;

    /**
     * @param Role $role
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(Role $role, Permission $permission, User $user)
    {
        $this->role = $role;
        $this->permission = $permission;
        $this->user = $user;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, request()->ip(), trans('admin/roles/general.audit-log.category'), trans('admin/roles/general.audit-log.msg-index'));

        $page_title = trans('admin/roles/general.page.index.title'); // "Admin | Roles";
        $page_description = trans('admin/roles/general.page.index.description'); // "List of roles";

        $roles = $this->role->pushCriteria(new RolesWithPermissions())->pushCriteria(new RolesByNamesAscending())->paginate(10);
        return view('admin.roles.index', compact('roles', 'page_title', 'page_description'));
        /*$stringjson = '{"id":"59","agent_code":"A2310790","parent_id":"58","NIK":"3578016310790003","name":"Anna Lisayani","birth_place":"Pamekasan","DOB":"1979-10-23 00:00:00","gender":"F","address":"Kemlaten 12\/1, RT 002 \/ RW 007, Kebraon, Karang Pilang","state":"Jawa Timur","city":"Surabaya","zipcode":"60222","phone_number":"123456","handphone_number":"08563091870","email":"m25498039@yahoo.com","type":"Individu","agent_position_id":"3","join_date":"14\/12\/2017","NPWP":"674951157618000","bank":"BCA","bank_branch":"Delta Plaza, Surabaya","account_name":"Anna Lisayani","account_number":"384-202778-8","branch_office_id":"6","is_active":1,"created_at":"2018-01-31 10:59:31","updated_at":"2018-01-31 11:33:24"}';
        $json = (array)json_decode($stringjson);
        DB::table('agents')->insert(
			    $json
			);
			echo 'sukses';
	$result = DB::select(DB::raw("select content from reminders where title like '%Approval Approved%' and ( content like '%\"id\":\"58%' or content like '%\"id\":\"128%' or content like '%\"id\":\"69%' or content like '%\"id\":\"82%' or content like '%\"id\":\"77%' or content like '%\"id\":\"92%' or content like '%\"id\":\"111%' or content like '%\"id\":\"110%' or content like '%\"id\":\"113%' or content like '%\"id\":\"116%' or content like '%\"id\":\"114%' or content like '%\"id\":\"123%' or content like '%\"id\":\"124%' ) "));
	foreach($result as $r){
		$ob = (array)json_decode($r->content);
		$ob = $ob['table'];
		print_r(json_encode($ob));
		echo '<br><br>';
	}
        //$newAgent->is_active = 2;
//dd($newAgent);
//        $newAgent->save($json);
	//SUBSTR(content, 17, LOCATE('\"',content,17)-17)
	//$ob = DB::select(DB::raw("select * from reminders where title = 'Approval Approved' and content like '%Add New Agent%' and SUBSTR(content, 17, LOCATE('\"',content,17)-17) not in (select id from agents)"));
	//dd($ob);*/
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $role = $this->role->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/roles/general.audit-log.category'), trans('admin/roles/general.audit-log.msg-show', ['name' => $role->name]));

        $page_title = trans('admin/roles/general.page.show.title'); // "Admin | Role | Show";
        $page_description = trans('admin/roles/general.page.show.description', ['name' => $role->name]); // "Displaying role";

        $perms = $this->permission->all();
//        $userCollection = \App\User::take(10)->get(['id', 'first_name', 'last_name', 'username'])->lists('full_name_and_username', 'id');
//        $userList = [''=>''] + $userCollection->all();

        return view('admin.roles.show', compact('role', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = trans('admin/roles/general.page.create.title'); // "Admin | Role | Create";
        $page_description = trans('admin/roles/general.page.create.description'); // "Creating a new role";

        $role = new \App\Models\Role();
        $perms = $this->permission->all();

        return view('admin.roles.create', compact('role', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $this->validate($request, array(    'name'          => 'required|unique:roles',
                                            'display_name'  => 'required'
        ));

        $attributes = $request->all();

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/roles/general.audit-log.category'), trans('admin/roles/general.audit-log.msg-store', ['name' => $attributes['name']]));

        if ( (array_key_exists('selected_users', $attributes)) && (!empty($attributes['selected_users'])) ) {
            $attributes['users'] = explode(",", $attributes['selected_users']);
        }
        else {
            $attributes['users'] = null;
        }

        $role = $this->role->create($attributes);

        $role->savePermissions($request->get('perms'));
        //$role->forcePermission('basic-authenticated');
        $role->saveUsers($attributes['users']);

        Flash::success( trans('admin/roles/general.status.created') ); // 'Role successfully created');

        return redirect('/admin/roles');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = $this->role->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/roles/general.audit-log.category'), trans('admin/roles/general.audit-log.msg-edit', ['name' => $role->name]));

        $page_title = trans('admin/roles/general.page.edit.title'); // "Admin | Role | Edit";
        $page_description = trans('admin/roles/general.page.edit.description', ['name' => $role->name]); // "Editing role";

        if( !$role->isEditable() &&  !$role->canChangePermissions() )
        {
            abort(403);
        }

        $perms = $this->permission->all();
//        $rolePerms = $role->perms();
//        $userCollection = \App\User::take(10)->get(['id', 'first_name', 'last_name', 'username'])->lists('full_name_and_username', 'id');
//        $userList = [''=>''] + $userCollection->all();

        return view('admin.roles.edit', compact('role', 'perms', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(    'name'          => 'required|unique:roles,name,' . $id,
                                            'display_name'  => 'required',
        ));

        $role = $this->role->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/roles/general.audit-log.category'), trans('admin/roles/general.audit-log.msg-update', ['name' => $role->name]));

        $attributes = $request->all();

        if ( (array_key_exists('selected_users', $attributes)) && (!empty($attributes['selected_users'])) ) {
            $attributes['users'] = explode(",", $attributes['selected_users']);
        } else {
            $attributes['users'] = [];
        }

        if ($role->isEditable())
        {
            $role->update($attributes);
        }

        if ($role->canChangePermissions())
        {
            $role->savePermissions($request->get('perms'));
        }

        //$role->forcePermission('basic-authenticated');

        if ($role->canChangeMembership())
        {
            $role->saveUsers($attributes['users']);
        }

        Flash::success( trans('admin/roles/general.status.updated') ); // 'Role successfully updated');

        return redirect('/admin/roles');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $role = $this->role->find($id);

        if (!$role->isdeletable())
        {
            abort(403);
        }

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/roles/general.audit-log.category'), trans('admin/roles/general.audit-log.msg-destroy', ['name' => $role->name]));

        $this->role->delete($id);

        Flash::success( trans('admin/roles/general.status.deleted') ); // 'Role successfully deleted');

        return redirect('/admin/roles');
    }

    /**
     * Delete Confirm
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $error = null;

        $role = $this->role->find($id);

        if (!$role->isdeletable())
        {
            abort(403);
        }

        $modal_title = trans('admin/roles/dialog.delete-confirm.title');

        $role = $this->role->find($id);
        $modal_route = route('admin.roles.delete', array('id' => $role->id));

        $modal_body = trans('admin/roles/dialog.delete-confirm.body', ['id' => $role->id, 'name' => $role->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $role = $this->role->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/roles/general.audit-log.category'), trans('admin/roles/general.audit-log.msg-enable', ['name' => $role->name]));

        $role->enabled = true;
        $role->save();

        Flash::success(trans('admin/roles/general.status.enabled'));

        return redirect('/admin/roles');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $role = $this->role->find($id);

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/roles/general.audit-log.category'), trans('admin/roles/general.audit-log.msg-disabled', ['name' => $role->name]));

        $role->enabled = false;
        $role->save();

        Flash::success(trans('admin/roles/general.status.disabled'));

        return redirect('/admin/roles');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkRoles = $request->input('chkRole');

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/roles/general.audit-log.category'), trans('admin/roles/general.audit-log.msg-enabled-selected'), $chkRoles);

        if (isset($chkRoles))
        {
            foreach ($chkRoles as $role_id)
            {
                $role = $this->role->find($role_id);
                $role->enabled = true;
                $role->save();
            }
            Flash::success(trans('admin/roles/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('admin/roles/general.status.no-role-selected'));
        }
        return redirect('/admin/roles');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkRoles = $request->input('chkRole');

        Audit::log(Auth::user()->id, request()->ip(), trans('admin/roles/general.audit-log.category'), trans('admin/roles/general.audit-log.msg-disabled-selected'), $chkRoles);

        if (isset($chkRoles))
        {
            foreach ($chkRoles as $role_id)
            {
                $role = $this->role->find($role_id);
                $role->enabled = false;
                $role->save();
            }
            Flash::success(trans('admin/roles/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('admin/roles/general.status.no-role-selected'));
        }
        return redirect('/admin/roles');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */
    public function searchByName(Request $request)
    {
        $return_arr = null;

        $query = $request->input('query');

        $roles = $this->role->pushCriteria(new RolesWhereDisplayNameOrDescriptionLike($query))->all();

        foreach ($roles as $role) {
            $id = $role->id;
            $display_name = $role->display_name;
            $description = $role->description;

            $entry_arr = [ 'id' => $id, 'text' => "$display_name ($description)"];
            $return_arr[] = $entry_arr;
        }

        return $return_arr;

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $role = $this->role->find($id);

        return $role;
    }

}
