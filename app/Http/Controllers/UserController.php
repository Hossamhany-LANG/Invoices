<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
/**
* Display a listing of the resource.
*
* @return \Illuminate\Http\Response
*/
public function __construct()
{
    $this->middleware(function ($request, $next) {
        if (Auth::check() && Auth::user()->status !== 'مفعل') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'حسابك غير مفعل.');
        }
        return $next($request);
    });
}

public function index(Request $request)
{
$data = User::orderBy('id','DESC')->paginate(5);
return view('users.show_users',compact('data'))
->with('i', ($request->input('page', 1) - 1) * 5);
}


/**
* Show the form for creating a new resource.
*
* @return \Illuminate\Http\Response
*/
public function create()
{
$roles = Role::pluck('name','name')->all();

return view('users.Add_user',compact('roles'));

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
'name' => 'required',
'email' => 'required|email|unique:users,email',
'password' => 'required|same:confirm-password',
'status' => 'required',
'roles_name' => 'required'
]);

$input = $request->all();


$input['password'] = Hash::make($input['password']);

$user = User::create($input);
$user->assignRole($request->input('roles_name'));
return redirect()->route('users.index')
->with('success','تم اضافة المستخدم بنجاح');
}

/**
* Display the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function show($id)
{
$user = User::find($id);
return view('users.show',compact('user'));
}
/**
* Show the form for editing the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function edit($id)
{
$user = User::find($id);
$roles = Role::pluck('name','name')->all();
$userRole = $user->roles->pluck('name','name')->all();
return view('users.edit',compact('user','roles','userRole'));
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
'email' => 'required|email|unique:users,email,'.$id,
'password' => 'same:confirm-password',
'roles' => 'required'
]);
$input = $request->all();
if(!empty($input['password'])){
$input['password'] = Hash::make($input['password']);
}else{
$input = Arr::except($input, ['key', 'otherKey']);
}
$user = User::find($id);
$user->update($input);
DB::table('model_has_roles')->where('model_id',$id)->delete();
$user->assignRole($request->input('roles'));
return redirect()->route('users.index')
->with('success','تم تحديث معلومات المستخدم بنجاح');
}
/**
* Remove the specified resource from storage.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function destroy(Request $request)
{
User::find($request->user_id)->delete();
return redirect()->route('users.index')->with('success','تم حذف المستخدم بنجاح');
}
}