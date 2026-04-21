<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StaffProfileController extends Controller
{
    public function edit()
    {
        return view('staff.profile.edit', [
            'user' => Auth::guard('staff')->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('staff')->user();

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname'  => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('staff')->ignore($user->staffno, 'staffno')],
        ]);

        // Update using the staff guard
        $user->update($validated);

        return redirect()->route('staff.profile.edit')->with('status', 'profile-updated');
    }
}
