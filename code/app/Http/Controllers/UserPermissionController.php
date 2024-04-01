<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Crypt;
use DB,Auth;

class UserPermissionController extends Controller
{
    public function get($id)
    { 
        $id =\Crypt::decrypt($id);
        $data['user'] = User::with('role')->find($id);
        if(empty($data['user']))
        {
            return \Redirect::back()->withInput(['error' => 'User not Found!!!']);
        }    
         $data['pages'] = DB::table('pages as p')
                        ->leftJoin('user_permissions as u', function ($join) use ($id){
                            $join->on('p.id', '=', 'u.page_id')
                                ->where('u.user_id', '=', $id);
                        })
                        ->select(
                            'p.id',
                            'p.pagename',
                            DB::raw('IFNULL(u.`read`, null) as `read`'),
                            DB::raw('IFNULL(u.`write`, null) as `write`'),
                            DB::raw('IFNULL(u.`update`, null) as `update`'),
                            DB::raw('IFNULL(u.`delete`, null) as `delete`'),
                            DB::raw('IFNULL(u.`download`, null) as `download`'),
                            DB::raw('IFNULL(u.`print`, null) as `print`'),
                            DB::raw('IFNULL(u.user_id, null) as user_id'),
                            DB::raw('IFNULL(u.role_id, null) as role_id')
                        )
                        ->get();
        return view('common-app.card.user-permission',compact('data'));
    }

   

}
