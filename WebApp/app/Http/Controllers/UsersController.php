<?php


namespace App\Http\Controllers;


use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    public function getAll()
    {
        try {
            $users = User::all();
            return new Response($users, Response::HTTP_OK);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function get($id)
    {
        try {
            $item = User::query()->find($id);
            if ($item != null) {
                return new Response($item, Response::HTTP_OK);
            }
            return new Response("User with specified ID doesn't exist!", Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function put(Request $request, $id)
    {
        try {
            $user = User::query()->find($id);

            if ($user != null) {
                $firstName = $request->input('firstName');
                $lastName = $request->input('lastName');
                $email = $request->input('email');
                $password = $request->input('password');
                $passwordConfirmation = $request->input('password_confirmation');
                if ($firstName != null) {
                    $user->firstName = $firstName;
                }
                else {
                    return new Response("First name must not be null!", Response::HTTP_BAD_REQUEST);
                }
                if ($lastName != null) {
                    $user->lastName = $lastName;
                }
                else {
                    return new Response("Last name must not be null!", Response::HTTP_BAD_REQUEST);
                }
                if ($email != null && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $user->email = $email;
                }
                else {
                    return new Response("Email is not valid!", Response::HTTP_BAD_REQUEST);
                }
                if ($password != null && strlen($password) >= 8 && $password == $passwordConfirmation) {
                    $user->password = $password;
                }
                else {
                    return new Response("Password must be at least 8 long and must match!", Response::HTTP_BAD_REQUEST);
                }
                $user->generateToken();
                $user->save();
                return new Response($user, Response::HTTP_OK);
            } else {
                return new Response("User does not exist!", Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }
}