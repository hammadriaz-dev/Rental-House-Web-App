<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;

class RegisteredUserController extends Controller {
    /**
    * Display the registration view.
    */

    public function create(): View {
        // Fetch all roles except admin
        $roles = Role::where( 'name', '!=', 'admin' )->pluck( 'name' );
        return view( 'auth.register',  compact('roles') );
    }

    /**
    * Handle an incoming registration request.
    *
    * @throws \Illuminate\Validation\ValidationException
    */

    public function store( Request $request ): RedirectResponse {
        $request->validate( [
            'name' => [ 'required', 'string', 'max:255' ],
            'email' => [ 'required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class ],
            'CNIC'     => [ 'required', 'string', 'max:50', 'unique:users,CNIC' ],
            'password' => [ 'required', 'confirmed', Rules\Password::defaults() ],
            'role' => 'required|in:tenant,landlord',
        ] );

        $user = User::create( [
            'name' => $request->name,
            'email' => $request->email,
            'CNIC'     => $request->CNIC,
            'password' => Hash::make( $request->password ),
        ] );
        // Assign the role selected by the user
        $user->assignRole( $request->role );

        event( new Registered( $user ) );

        Auth::login( $user );

        return redirect()->route('dashboard');
    }
}
