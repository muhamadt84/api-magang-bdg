<?php

namespace App\Http\Controllers;


use App\Models\Members;
use Illuminate\Http\Request;
use App\Models\MembersDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class MemberController extends Controller
{
    /**
     * Register a new user.
     */
    
     public function index(Request $request)
     {

         $perPage = $request->input('per_page', 10); // Number of items per page, default is 10
         $members = Members::paginate($perPage);
        //  $members = Members::all();
        //  dd($members);
         $memberDetails = MembersDetail::paginate($perPage);

         return response()->json([
             'success' => true,
             'members' => $members,
             'member_details' => $memberDetails,
         ]);
     }

    


     public function register(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'fullname' => 'required',
             'username' => 'required',
             'email' => 'required|email',
             'password' => 'required|min:6',
         ]);
     
         if ($validator->fails()) {
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors(),
             ], 422);
         }
     
         // Split the fullname into first_name and last_name.
         $name_parts = explode(' ', $request->input('fullname'));
         $first_name = $name_parts[0];
         $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
         
        //  Create a new member record in the 'Members' table.
         $table_member = new Members();
         $table_member->fullname = $request->input('fullname');
         $table_member->username = $request->input('username');
         $table_member->email = $request->input('email');
         $table_member->password = Hash::make($request->input('password')); // Use Hash::make() to bcrypt the password.
         $table_member->save();
         
         
         // Create a new member detail record in the 'MembersDetail' table.
         $memberDetail = new MembersDetail(); // Assuming 'MembersDetail' is the correct model name
         $memberDetail->member_id = $table_member->id;
         $memberDetail->first_name = $first_name;
         $memberDetail->last_name = $last_name;
         $memberDetail->save();
        //  dd($memberDetail);
     
         // Generate token using the newly created member record ($table_member).
        //  $token = $table_member->createToken('APP-TOKEN')->plainTextToken;
     
         return response()->json([
             'success' => true,
             'message' => 'Registration successful',
            //  'data' => $table_member,
             'other_table_data' => $memberDetail,
            //  'token' => $token,
         ], 201);
     }

     

    /**
     * Login with user credentials.
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $table_member = Members::where('email', $email)->first();

        if ($table_member) {
            $token = $table_member->createToken('APP-TOKEN')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'member' => $table_member,
                'token' => $token,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid email or member not found',
        ], 401);
    }

    /**
     * Get a list of all members.
     */
    

    /**
     * Get the details of a specific member.
     */
    public function show(string $id)
    {
        $member = Members::find($id);
    
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found',
            ], 404);
        }
    
        $memberDetail = MembersDetail::where('member_id', $id)->first();
    
        return response()->json([
            'success' => true,
            'member' => $member,
            'member_detail' => $memberDetail,
        ], 200);
    }
    
    

    /**
     * Update the details of a specific member.
     */
   
     
    
     

     public function update(Request $request, string $id)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'member_id' => 'sometimes|required',
        'first_name' => 'sometimes|required',
        'last_name' => 'sometimes|required',
        'dob' => 'sometimes|required',
        'gender' => 'sometimes|required',
        'address' => 'sometimes|required',
        'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:20480',
        'bio' => 'sometimes|required',
        'highschool' => 'sometimes|required',
        'phone_number' => 'sometimes|required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // Find the member detail by id
    $memberDetail = MembersDetail::find($id);

    if (!$memberDetail) {
        return response()->json([
            'success' => false,
            'message' => 'Member not found',
        ], 404);
    }

    // Pastikan nama kolom yang digunakan sesuai dengan struktur tabel dan modelnya
    $dataToUpdate = $request->only([
        'member_id',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'address',
        'image',
        'bio',
        'highschool', // Fix typo here: 'higschool' should be 'highschool'
        'phone_number',
    ]);
    
    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $image->store('images', 'public');
        $dataToUpdate['image'] = $imagePath;
    }

    // Periksa apakah nilai atribut tidak kosong sebelum mengisinya ke dalam model
    foreach ($dataToUpdate as $key => $value) {
        if ($request->filled($key)) {
            $memberDetail->$key = $value;
        }
    }

    if ($request->has('password')) {
        $memberDetail->password = bcrypt($request->input('password'));
    }

    $memberDetail->save();


    return response()->json([
        'success' => true,
        'message' => 'Member updated successfully',
        'member' => $memberDetail,
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

