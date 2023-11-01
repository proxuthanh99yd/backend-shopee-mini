<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function users()
    {
        try {
            return User::where('role', 1)->orderBy('updated_at', 'desc')->paginate(20);
        } catch (\Exception $th) {
            throw $th;
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                throw new \Exception("Unauthorized", 500);
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception("Error in Login", 500);
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'user' => $user,
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
            ]);
        } catch (ValidationException $validationException) {
            throw new \Exception("Email not valid", 500);
        } catch (\Exception $exception) {
            throw new \Exception("Error in Login", 500);
        }
    }

    public function register(Request $request)
    {
        try {

            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->address = $request->input('address');
            $user->phone = $request->input('phone');
            $user->gender = 2;
            $user->status = 1;
            $user->role = 1;
            $user->email_verified_at = now();
            $user->save();
            return  $user;
        } catch (\PDOException $exception) {
            throw new \Exception("Email or Phone number is exist!", 500);
        } catch (\Exception $exception) {
            throw new \Exception("Error in register", 500);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return response()->json([
                'message' => 'Succeeded',
            ]);
        } catch (\Exception $exception) {
            throw new \Error(join([
                'message' => 'Error in logout',
                'exception' => $exception
            ]), 500);
        }
    }

    public function auth(Request $request)
    {
        try {
            return $request->user();
        } catch (\Exception $exception) {
            throw new \Error(join([
                'message' => 'Error in logout',
                'exception' => $exception
            ]), 500);
        }

    }

    public function update(Request $request)
    {
        try {
            $user = User::find($request->user()->id);
            $user->name = $request->input('name');
            $user->address = $request->input('address');
            $user->gender = $request->input('gender');
            $user->save();
            return $user;
        } catch (\Exception $exception) {
            throw new \Error(join([
                'message' => 'Error in update',
                'exception' => $exception
            ]), 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $old_pass = $request->input('old_password');
            $new_pass = $request->input('new_password');
            $conf_pass = $request->input('confirm_password');
            $user = User::find($request->user()->id);
            if ($new_pass === $conf_pass && Hash::check($old_pass, $user->password, [])) {
                $user->password = Hash::make($new_pass);
                $user->save();
                return $user;
            }
            throw new \Exception('Error in change password', 500);
        } catch (\Exception $exception) {
            throw new \Exception('Error in change password', 500);
        }
    }

    public function changeStatus(string $id)
    {
        try {
            $user = User::find($id);
            if ($user->status == 1) {
                $user->status = 0;
            } else {
                $user->status = 1;
            }
            $user->save();
            return $user;
        } catch (\Exception $exception) {
            throw new \Error(join([
                'message' => 'Error in blockUser',
                'exception' => $exception
            ]), 500);
        }
    }
}
