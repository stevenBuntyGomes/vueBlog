<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct(){
        $this->global_role_id = null;
    }

    public function index(Request $request){
        // first chech if you are logged in and as admin user
        // return Auth::check();
        if(!Auth::check() && $request->path() != 'login'){
            return redirect('/login');
        }

        if(!Auth::check() && $request->path() == 'login'){
            return view('welcome');
        }

        // in this place, you are already logged in. Now check for if you are an admin user
        $user = Auth::user();
        if($user->userType == 'User'){
            return redirect('/login');
        }

        if($request->path() == 'login'){
            return redirect('/');
        }
        return $this->checkForPermission($user, $request);
    }


    public function checkForPermission($user, $request){
        $permission = json_decode($user->role->permission);
        if($permission == null || !$permission){
            return view('welcome');
        }
        // test permission
        $hasPermission = false;
        // foreach($permission as $p){
        //     print_r($p->name);
        //     echo '<br>';
        // }
        // echo $request->path();
        // test permission
        foreach($permission as $p){
            if($p->name == $request->path()){
                if($p->read){
                    $hasPermission = true;
                }
            }
        }
        if($hasPermission == true){
            return view('welcome');
        }else{
            return view('error');
        }
        // $permission[0]->name;
        // echo $request->path();
    }

    public function storeUserImage(Request $request){
        $imgName = time() . '.' . $request->file->extension();
        $request->file->move(base_path('public/uploads/user/'), $imgName);
        return response($imgName);
    }

    public function createUser(Request $request){
        $request->validate([
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'required',
            'image' => 'required',
        ]);
        $user = Role::where('id', $request->role_id)->first();
        $user_id = User::insertGetId([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'userType' => $user->roleName,
            'image' => $request->image,
            'created_at' => Carbon::now(),
        ]);
        $user_get = User::where('id', $user_id)->first();
        return response($user_get);
    }


    public function delete_image(Request $request){
        $fileName = $request->imgName;
        $file_path = base_path('public/uploads/user/') . $fileName;
        if(file_exists($file_path)){
            unlink($file_path);
        }

        return response('done');

    }


    public function getUser(){
        $get_users = User::orderBy('id', 'desc')->get();
        // $get_admin_user = User::where('role_id', 1)->gt();
        // $get_role = Role::orderBy('id', 'desc')->get();
        return response()->json($get_users);
    }


    public function update(Request $request){
        $request->validate([
                'id' => 'required',
                'fullName' => 'required',
                'email' => "bail|required|email|unique:users,email,$request->id",
                'userType' => 'required',
                'image' => 'required',
            ]);
            $edit_user = User::where('id', $request->id)->update([
                'fullName' => $request->fullName,
                'email' => $request->email,
                'userType' => $request->userType,
                'image' => $request->image,
                'created_at' => Carbon::now(),
            ]);

            return response()->json($edit_user);
    }


    public function changePassword(Request $request){
        $request->validate([
            'oldPass' => 'required|min:6',
            'newPass' => 'required|min:6',
            'confirmPass' => 'required|min:6',
        ]);
        $user_password = User::where('id', $request->id)->first();
        if(Hash::check($request->oldPass, $user_password->password)){
            if($request->oldPass ==  $request->newPass){
                $status = 2;
                return response($status);
            }else{
                if($request->newPass != $request->confirmPass){
                    $status = 3;
                    return response($status);
                }else{
                    User::where('id', $request->id)->update([
                        'password' => Hash::make($request->newPass)
                    ]);

                    $status = 4;
                    return response($status);
                }
            }
        }else{
            $status = 1;
            return response($status);
        }



    }


    // login starts

    public function login(Request $request){
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'required|min:6',
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            // Log::info($user->role_id);
            if($user->role->isAdmin == 0){
                Auth::logout();
                return response()->json([
                    'status' => 0,
                ]);
            }
            return response()->json([
                'msg' => 'you are loggedin',
                'user' => $user,
            ]);
            return redirect('/home');
        }else{
                return response()->json([
                    'msg' => 'incorrect login details'
                ]);
            }
        // $user = User::where('email', $request->email)->first();
        // if($user == ''){
        //     $status = 1;
        //     return response($status);
        // }elseif($user != ''){
        //     if(Hash::check($request->password, $user->password)){
        //         return response()->json([
        //             'msg' => 'your logged in',
        //         ]);
        //     }else{
        //        return response()->json([
        //             'msg' => 'incorrect password and user information',
        //         ]);
        //     }
        // }
    }

    // login ends

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }



    // creating rol starts

    public function createRole(Request $request){
        $request->validate([
            'roleName' => 'required',
        ]);

        $role_id = Role::insertGetId([
            'roleName' => $request->roleName,
            'created_at' => Carbon::now(),
        ]);

        $get_role = Role::where('id', $role_id)->first();
        return response($get_role);
    }


    public function getRole(){
        $get_role = Role::orderBy('id', 'Desc')->get();
        return response()->json($get_role);
    }

    public function editRole(Request $request){
        $request->validate([
            'id' => 'required',
            'roleName' => 'required',
        ]);

        $edit_role = Role::where('id', $request->id)->update([
            'roleName' => $request->roleName,
        ]);

        return response()->json($edit_role);
    }


    public function assign_roles(Request $request){
        $request->validate([
            'permission' => 'required',
            'role_id' => 'required',
        ]);

        $assign_roles = Role::where('id', $request->role_id)->update([
            'permission' => $request->permission,
        ]);
        session()->put('global_role_id', $request->role_id);
        return response()->json($assign_roles);

    }

    public function getRoleId(){
        $updated_role_id = session()->get('global_role_id');
        $no_response = 1;
        if($updated_role_id != null){
            return response()->json($updated_role_id);
        }elseif($updated_role_id == null){
            return response()->json($no_response);
        }
    }

    // creating rol ends

}
