<?php

namespace App\Http\Controllers;

class CommonController extends Controller
{
    public function getSymptoms()
    {
        return response()->json([
            ['name' => 'Fever'],
            ['name' => 'Cough'],
            ['name' => 'BackPain'],
            ['name' => 'Fatigue']
        ]);
    }
}
