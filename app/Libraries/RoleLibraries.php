<?php

namespace App\Libraries;

use App\Models\Role;

/**
 * 
 */
class RoleLibraries
{
	public function get_all_list_role()
	{
		$data = Role::where('isdelete', 0)->get();

		return $data;
	}
	
}