<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Register a new user.
     */
    
    
     public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:members',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = new Member();
        $user->fullname = $request->input('fullname');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        $token = $user->createToken('app-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login with user credentials.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $member->createToken('app-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }
    }

    /**
     * Get a list of all members.
     */
    public function index()
    {
        $members = Member::all();

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }

    /**
     * Get the details of a specific member.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    /**
     * Update the details of a specific member.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:members,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Member::findOrFail($id);
        $user->fullname = $request->input('fullname');
        $user->username = $request->input('username');
        $user->email = $request->input('email');

        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Member updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Remove a specific member.
     */
    public function destroy(string $id)
    {
        dd($request);
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully',
        ]);
    }
}
