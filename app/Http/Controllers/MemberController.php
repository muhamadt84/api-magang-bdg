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
    
     public function index()
     {
        
         $table_member = Members::all();
 
         return response()->json([
             'success' => true,
             'data'    => $table_member,
             'users' => $table_member,
         ]);
     }

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
     
        

         $table_member = new Members();
         $table_member->fullname = $request->input('fullname');
         $table_member->username = $request->input('username');
         $table_member->email = $request->input('email');
         $table_member->password = bcrypt($request->input('password'));
         $table_member->save();
     
         $token = $table_member->createToken('app-token')->plainTextToken;
     
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
    

    /**
     * Get the details of a specific member.
     */
    public function show(string $id)
    {
        
        $table_member = Members::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $table_member,
        ], 200);
    }

    /**
     * Update the details of a specific member.
     */
    public function update(Request $request, string $id)
    {
        
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|sometimes',
            'firstname' => 'required|sometimes',
            'lastname' => 'required|sometimes',
            'dob' => 'required|sometimes' ,
            'gender' => 'required|sometimes',
            'address' => 'required|sometimes',
            'image' => 'required|sometimes',
            'bio' => 'required|sometimes',
            'highschool' => 'required|sometimes',
            'phone_number' => 'required|sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $table_member = Members::findOrFail($id);
        $table_member->fullname = $request->input('fullname');
        $table_member->username = $request->input('username');
        $table_member->email = $request->input('email');

        if ($request->has('password')) {
            $table_member->password = bcrypt($request->input('password'));
        }

        $table_member->save();

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
        $table_member = Members::findOrFail($id);
        $table_member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully',
        ]);
    }
}
