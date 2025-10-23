<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
       return view('admin.logs.index',[
        'logs' =>  Activity::query()
                      ->with(['subject','causer'])
                      ->latest()
                      ->paginate('10'),
       ]);
    }
    public function delete()
    {
      Activity::query()->delete();
      return response()->json([
        'success' => true,
        'message' => 'Activity logs deleted successfully'
      ],200);
    }
}
