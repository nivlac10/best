<?php

namespace App\Http\Controllers;
use App\Models\Keyword;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function show()
    {
        $keyword = Keyword::inRandomOrder()->first();

        return view('keyword', compact('keyword'));
    }
}
