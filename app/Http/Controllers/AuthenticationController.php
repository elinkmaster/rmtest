<?php

namespace App\Http\Controllers;

use App\Helpers\MonthHelper;
use App\Models\Author;
use App\Models\PodTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard(Request $request)
    {
        $months = MonthHelper::getMonths();
        $books = [];
        $authors = Author::all();
        if($request->author){
            foreach($authors as $author){
                if($request->author ==$authors->id){
                    $books = PodTransaction::where('author_id', $authors->id)->first();
                    $salesOp = PodTransaction::where('author_id', $authors->id)->first();
                }
            }
        }

        return view('dashboard', compact('authors', 'books', 'months'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
