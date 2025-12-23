<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = Auth::user();
       return view('profile.edit', compact('user'));
  
    }

    /**  
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
       $user = Auth::user();
 
        // validate input
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id, // exclude current user
        
        ]);

        // update user
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
           
        ]);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }  

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function updatePassword(Request $request)
{
    $request->validate([
        'oldPassword' => ['required', 'current_password'], // Laravel 8+ has this rule
        'newPassword' => ['required', 'min:3', 'confirmed'], // confirmed = check confirmPassword
    ]);

    $user = $request->user();
    $user->password = Hash::make($request->newPassword);
    $user->save();

    return back()->with('success', 'Password updated successfully!');
}

}