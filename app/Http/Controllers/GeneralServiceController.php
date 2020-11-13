<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coin_purse;
use App\Models\Game;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth;

class GeneralServiceController extends Controller
{
    public function get_coins(){
        return response()->json(Coin_purse::select('coins')->where('user_id',Auth::user()->id)->first()); 
    }

    public function games_active(Request $request){   
        return response()->json(Game::all());
    }
}
