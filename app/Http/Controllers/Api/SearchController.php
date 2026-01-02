<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search users by name or email (API endpoint)
     * Only returns users with 'student' role
     */
    public function users(Request $request)
    {
        $search = $request->query('q', '');
        
        $users = User::where('role', 'student')
                     ->where(function($query) use ($search) {
                         $query->where('name', 'like', "%$search%")
                               ->orWhere('email', 'like', "%$search%");
                     })
                     ->select('id', 'name', 'email')
                     ->limit(10)
                     ->get();

        return response()->json($users);
    }
}
