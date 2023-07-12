<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
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

        $member = new Member();
        $member->fullname = $request->input('fullname');
        $member->username = $request->input('username');
        $member->email = $request->input('email');
        $member->password = bcrypt($request->input('password'));
        $member->save();

        $token = $member->createToken('app-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => $member,
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
            $member = Auth::user();
            $token = $member->createToken('app-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $member,
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
            'members' => $members,
        ]);
    }

    /**
     * Get the details of a specific member.
     */
    public function show(string $id)
    {
        $member = Member::findOrFail($id);

        return response()->json([
            'success' => true,
            'member' => $member,
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

        $member = Member::findOrFail($id);
        $member->fullname = $request->input('fullname');
        $member->username = $request->input('username');
        $member->email = $request->input('email');

        if ($request->has('password')) {
            $member->password = bcrypt($request->input('password'));
        }

        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Member updated successfully',
            'user' => $member,
        ]);
    }

    /**
     * Remove a specific member.
     */
    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully',
        ]);
    }
}
