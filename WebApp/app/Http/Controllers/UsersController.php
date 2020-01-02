<?php


namespace App\Http\Controllers;


use App\Address;
use App\Http\Resources\UsersDetailsResource;
use App\Http\Resources\UsersResource;
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
            return UsersResource::collection($users);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function get($id)
    {
        try {
            $user = User::query()->find($id);
            if ($user != null) {
                return new UsersDetailsResource($user);
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
            $address = Address::query()->find($user->address->id);

            if ($user != null) {
                $firstName = $request->input('firstName');
                $lastName = $request->input('lastName');
                $email = $request->input('email');
                $password = $request->input('password');
                $passwordConfirmation = $request->input('password_confirmation');
                $telephone = $request->input('telephone');
                $street = $request->input('street');
                $post = $request->input('post');
                $postCode = $request->input('postCode');

                if ($firstName != null) {
                    $user->firstName = $firstName;
                } else {
                    return new Response("First name must not be null!", Response::HTTP_BAD_REQUEST);
                }
                if ($lastName != null) {
                    $user->lastName = $lastName;
                } else {
                    return new Response("Last name must not be null!", Response::HTTP_BAD_REQUEST);
                }
                if ($email != null && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $user->email = $email;
                } else {
                    return new Response("Email is not valid!", Response::HTTP_BAD_REQUEST);
                }
                if ($password != null && strlen($password) >= 8 && $password == $passwordConfirmation) {
                    $user->password = bcrypt($password);
                } else {
                    return new Response("Password must be at least 8 long and must match!", Response::HTTP_BAD_REQUEST);
                }
                if ($telephone != null) {
                    $user->telephone = $telephone;
                } else {
                    return new Response("Telephone must not be null!", Response::HTTP_BAD_REQUEST);
                }
                if ($street != null) {
                    $address->street = $street;
                } else {
                    return new Response("Street must not be null!", Response::HTTP_BAD_REQUEST);
                }
                if ($post != null) {
                    $address->post = $post;
                } else {
                    return new Response("Post must not be null!", Response::HTTP_BAD_REQUEST);
                }
                if ($postCode != null) {
                    $address->postCode = $postCode;
                } else {
                    return new Response("Post code must not be null!", Response::HTTP_BAD_REQUEST);
                }

                $user->generateToken();
                $user->address()->associate($address);
                $user->save();
                return new UsersDetailsResource($user);
            } else {
                return new Response("User with specified id does not exist!", Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return new Response("Error: " . $e, Response::HTTP_BAD_REQUEST);
        }
    }
}
