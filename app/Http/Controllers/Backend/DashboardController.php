<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Inertia\Inertia;


class DashboardController extends Controller
{


   /**
    * Display a listing of the resource
    */
   public function index(Request $request)
   {
      
      $admin = auth('admin')->user(); // sesuaikan ID
// $admin->getRoleNames(); // cek role
// $admin->getPermissionNames(); // cek permission langsung
// $admin->hasRole('Master'); // harus true
// dd($admin->hasPermissionTo('View Page')); // harus true
// $admin->hasPermissionTo('Delete User'); // harus true
      //dd($statususer);
      return Inertia::render('Dashboard', [
         'status' => 1,
         'data' => []
      ]);
   }

}
