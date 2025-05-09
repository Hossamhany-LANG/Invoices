<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
/**
* Display a listing of the resource.
*
* @return \Illuminate\Http\Response
*/


function __construct()
{

$this->middleware('CheckPermission:عرض صلاحية', ['only' => ['index']]);
$this->middleware('CheckPermission:اضافة صلاحية', ['only' => ['create','store']]);
$this->middleware('CheckPermission:تعديل صلاحية', ['only' => ['edit','update']]);
$this->middleware('CheckPermission:حذف صلاحية', ['only' => ['destroy']]);

}

/**
* Display a listing of the resource.
*
* @return \Illuminate\Http\Response
*/
public function index(Request $request)
{
$roles = Role::orderBy('id','DESC')->paginate(5);
return view('roles.index',compact('roles'))
->with('i', ($request->input('page', 1) - 1) * 5);
}
/**
* Show the form for creating a new resource.
*
* @return \Illuminate\Http\Response
*/
public function create()
{
$permissions = Permission::get();
return view('roles.create',compact('permissions'));
}
/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(Request $request)
{
    $validate = $request->validate([
        'name' => 'required|unique:roles,name',
        'permissions' => 'required|array|min:1', // تأكد من اختيار صلاحية واحدة على الأقل
    ], [
        'permissions.required' => 'يجب اختيار صلاحية واحدة على الأقل.',
        'permissions.min' => 'يجب اختيار صلاحية واحدة على الأقل.',
    ]);
    

    $role = Role::create(['name' => $request->input('name')]);
    // مزامنة الصلاحيات مع الدور الجديد
    $role->syncPermissions(Permission::whereIn('id', $request->input('permissions'))->get());

    return redirect()->route('roles.index')
        ->with('success', 'تم إنشاء الصلاحية بنجاح');
}

/**
* Display the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function show($id)
{
$role = Role::find($id);
$rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
->where("role_has_permissions.role_id",$id)
->get();
return view('roles.show',compact('role','rolePermissions'));
}
/**
* Show the form for editing the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function edit($id)
{
$role = Role::find($id);
$permission = Permission::get();
$rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
->all();
return view('roles.edit',compact('role','permission','rolePermissions'));
}
/**
* Update the specified resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function update(Request $request, $id)
{
    $validate = $request->validate([
        'name' => 'required',
        'permissions' => 'required|array|min:1', // تأكد من اختيار صلاحية واحدة على الأقل
    ], [
        'permissions.required' => 'يجب اختيار صلاحية واحدة على الأقل.',
        'permissions.min' => 'يجب اختيار صلاحية واحدة على الأقل.',
    ]);
    $role = Role::find($id);
    $role->name = $request->input('name');
    $role->save();
    // مزامنة الصلاحيات مع الدور المُحدث
    $role->syncPermissions(Permission::whereIn('id', $request->input('permissions'))->get());
    return redirect()->route('roles.index')
        ->with('success', 'تم تحديث الصلاحية بنجاح');
}

/**
* Remove the specified resource from storage.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function destroy($id)
{
DB::table("roles")->where('id',$id)->delete();
return redirect()->route('roles.index')
->with('success','Role deleted successfully');
}
}