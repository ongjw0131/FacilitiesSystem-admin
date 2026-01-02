<?php

namespace App\Http\Controllers;

use App\Models\Society;
use Illuminate\View\View;

class SocietyAdminController extends Controller
{
    /**
     * Show edit society form
     */
    public function edit($societyID): View
    {
        $society = Society::where('societyID', $societyID)
            ->where('isDelete', false)
            ->firstOrFail();

        return view('society.edit', [
            'societyID' => $societyID,
        ]);
    }
}
