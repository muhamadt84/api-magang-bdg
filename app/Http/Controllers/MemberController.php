<?php

namespace App\Http\Controllers;


use App\Models\Members;
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
         $table_member = new Members;
         
         
     
       
         $validator = Validator::make($request->all(), [
             'fullname' => 'required',
             'username' => 'required',
             'email' => 'required|email|unique:table_member',
             'password' => 'required|min:6',
         ]);
     
         if ($validator->fails()) {
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors(),
             ], 422);
         }
     
        

         $user = new Members();
         $user->fullname = $request->input('fullname');
         $user->username = $request->input('username');
         $user->email = $request->input('email');
         $user->password = bcrypt($request->input('password'));
         $user->save();
     
         $token = $user->createToken('app-token')->plainTextToken;
     
         return response()->json([
             'success' => true,
             'message' => 'Registration successful',
             'data' => $table_member,
             'token' => $token,
         ], 201);
     }
     

    /**
     * Login with user credentials.
     */
    public function login(Request $request)
    {
        $table_member = new Members;
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $table_member = Auth::members();
            $token = $table_member->createToken('APP-TOKEN')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'member' => $table_member,
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
        $table_member = new Members;
        $users = User::all();

        return response()->json([
            'success' => true,
            'users' => $table_member,
        ]);
    }

    /**
     * Get the details of a specific member.
     */
    public function show(string $id)
    {
        $table_member = new Members;
        $table_member = new Members;
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => $table_member,
        ]);
    }

    /**
     * Update the details of a specific member.
     */
    public function update(Request $request, string $id)
    {
        $table_member = new Members;
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:table_member,email,' . $id,
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
            'member' => $table_member,
        ]);
    }

    /**
     * Remove a specific member.
     */
    public function destroy(string $id)
    {
        $table_member = new Members;
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully',
        ]);
    }
}
