<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;


class DashboardController extends Controller
{


   /**
    * Display a listing of the resource
    */
   public function index(Request $request)
   {
      //dd($statususer);
      return Inertia::render('Dashboard', [
         'status' => 1,
         'data' => []
      ]);
   }

}
