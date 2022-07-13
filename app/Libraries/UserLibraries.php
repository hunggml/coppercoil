<?php

namespace App\Libraries;

use Illuminate\Validation\Rule;
use Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Auth;
use Hash;

/**
 * 
 */
class UserLibraries
{

	public function get_all_list_user()
	{
		$data = User::where('IsDelete', 0)->where('level', '!=', 9999)
			->with([
				'role'
			])
			->get();

		return $data;
	}

	public function filter($request)
	{
		$id       = $request->id;
		$name     = $request->name;
		$username = $request->username;
		$data = User::where('IsDelete', 0)
			->where('level', '!=', 9999)
			->when($id, function ($q, $id) {
				return $q->where('id', $id);
			})
			->when($name, function ($q, $name) {
				return $q->where('name', $name);
			})
			->when($username, function ($q, $username) {
				return $q->where('username', $username);
			})
			->select('id', 'name', 'username')->first();

		return $data;
	}

	public function check_pass($request)
	{
		return Hash::check($request->Password, Auth::user()->password);
	}

	public function check_user($request)
	{
		$id       = $request->id;
		$email    = $request->email;
		$username = $request->username;
		$message  = [
			'unique.username'   => $request->username . ' ' . __('Already Exist') . '!',
			'unique.email'      => $request->email . ' ' . __('Already Exist') . '!',
		];

		$validation = Validator::make($request->all(), [
			'name'  => ['required', 'max:255'],
			'username' => [
				'required',
				'max:255',
				Rule::unique('users')->where(function ($q) use ($id, $username) {
					$q->where('id', '!=', $id)->where('IsDelete', 0);
				}),
			],
			'email' => [
				'required',
				'max:255',
				'email',
				Rule::unique('users')->where(function ($q) use ($id, $email) {
					$q->where('id', '!=', $id)->where('IsDelete', 0);
				}),
			],
		], $message)->validate();

		return $validation;
	}

	public function show($request)
	{
		$id       = $request->id;
		$name     = $request->name;
		$username = $request->username;

		$find = User::where('IsDelete', 0)
			->where('level', '!=', 9999)
			->when($id, function ($q, $id) {
				return $q->where('id', $id);
			})
			->when($name, function ($q, $name) {
				return $q->where('name', $name);
			})
			->when($username, function ($q, $username) {
				return $q->where('username', $username);
			})
			->first();

		return $find;
	}

	public function reset_password($request)
	{
		if (Auth::user()->level == 9999 && Auth::user()->username == 'admin') {
			$find = User::where('IsDelete', 0)->where('id', $request->id)->first();
			// dd($request->id);

			if ($find) {
				$find->password = bcrypt($request->password);
				$find->save();
			}
		}

		return __('Success');
	}

	public function reset_my_password($request)
	{

		$find = User::where('IsDelete', 0)->where('id', Auth::user()->id)->first();

		if ($find) {
			$find->password = bcrypt($request->password);
			$find->save();
		}

		return __('Success');
	}

	public function add_or_update($request)
	{
		// dd($request);
		$find   = User::where('IsDelete', 0)
			->where('id', $request->id)->first();

		$status = __('No Action');

		if ($find) {
			$find->name     = $request->name;
			$find->username = $request->username;
			$find->email    = $request->email;
			$find->save();

			$status = __('Update') . ' ' . __('Account') . ' ' . __('Success');
		} else {
			$find = User::create([
				'name'     => $request->name,
				'username' => $request->username,
				'email'    => $request->email,
				'password' => bcrypt('123')
			]);

			$status = __('Create') . ' ' . __('Account') . ' ' . __('Success');
		}

		$find->refresh()->role()->detach();
		$find->refresh()->role()->attach($request->role);

		return (object)[
			'status'	=> $status,
			'data'	    => $find->refresh()
		];
	}

	public function destroy($request)
	{
		$find = User::where('IsDelete', 0)
			->where('level', '!=', 9999)
			->where('id', $request->ID)
			->first();

		$status = __('Account') . ' ' . __('Does Not Exist');

		if ($find) {
			$find->IsDelete = 1;
			$find->save();

			$status = __('Destroy') . ' ' . __('Account') . ' ' . __('Success');
		}

		return $status;
	}
}
