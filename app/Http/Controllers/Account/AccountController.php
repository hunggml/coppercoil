<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\UserLibraries;
use App\Libraries\RoleLibraries;

class AccountController extends Controller
{
    protected $user;
    protected $role;

    public function __construct(
    	UserLibraries $userLibraries
        ,RoleLibraries $roleLibraries
    ){
    	$this->middleware('auth');
    	$this->user = $userLibraries;
        $this->role = $roleLibraries;
    }

    public function index()
    {
    	$data = $this->user->get_all_list_user();

    	return view('account.user.index', [
    		'data'	=> $data
    	]);
    }

    public function index_role()
    {
        $data = $this->role->get_all_list_role();

        return view('account.role.index', [
            'data' => $data
        ]);
    }

    public function show(Request $request)
    {
        if (!$request->username) 
        {
            $data = null;
        } else
        {
            $data = $this->user->show($request);
        }
        $roles = $this->role->get_all_list_role();

        return view('account.user.add_or_update', ['data' => $data, 'roles' => $roles]);
    }

    public function reset_my_password()
    {
        return view('auth.reset_pass');
    }

    public function check_password(Request $request)
    {
        $data = $this->user->check_pass($request);

        return response()->json([
            'status' => $data,
        ]);
    }

    public function show_role(Request $request)
    {
        if (!$request->username) 
        {
            $data = null;
        } else
        {
            $data = $this->user->show($request);
        }

        return view('account.role.add_or_update', ['data' => $data]);
    }

    public function reset_password(Request $request)
    {
        $data = $this->user->reset_password((object)[
            'id'    => $request->idUser,
            'password' => $request->Password
        ]);

        return redirect()->back()->with('success', __('Reset').' '.__('Success'));
    }

    public function reset_new_my_password(Request $request)
    {
        $data = $this->user->reset_my_password((object)[
            'password' => $request->Password
        ]);
        
        return redirect()->route('home')->with('success',  __('Reset').' '.__('Success'));
    }

    public function add_or_update(Request $request)
    {
        $check = $this->user->check_user($request);
        $data  = $this->user->add_or_update($request);

        return redirect()->route('account')->with('success', $data->status);
    }

    public function destroy(Request $request)
    {
        $data = $this->user->destroy($request);

        return redirect()->back()->with('danger', $data);
    }
}
