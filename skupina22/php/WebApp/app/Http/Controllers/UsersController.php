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

    public function getCurrent(Request $request)
    {
        return new UsersDetailsResource($request->user('api'));
    }

    public function put(Request $request, $id)
    {
        try {
            $user = User::query()->find($id);
            $address = Address::query()->find($user->address->id);

            if ($user != null) {
                $firstName = filter_var($request->input('firstName'), FILTER_SANITIZE_SPECIAL_CHARS);
                $lastName = filter_var($request->input('lastName'), FILTER_SANITIZE_SPECIAL_CHARS);
                $email = filter_var($request->input('email'), FILTER_SANITIZE_SPECIAL_CHARS);
                $password = filter_var($request->input('password'), FILTER_SANITIZE_SPECIAL_CHARS);
                $passwordConfirmation = filter_var($request->input('password_confirmation'), FILTER_SANITIZE_SPECIAL_CHARS);
                $telephone = filter_var($request->input('telephone'), FILTER_SANITIZE_SPECIAL_CHARS);
                $street = filter_var($request->input('street'), FILTER_SANITIZE_SPECIAL_CHARS);
                $post = filter_var($request->input('post'), FILTER_SANITIZE_SPECIAL_CHARS);
                $postCode = filter_var($request->input('postCode'), FILTER_SANITIZE_SPECIAL_CHARS);

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
                }
                else {
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
                $address->save();
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

    public function putAdminOrSeller(Request $request, $id)
    {
        try {
            $user = User::query()->find($id);

            if ($user != null) {
                $firstName = filter_var($request->input('firstName'), FILTER_SANITIZE_SPECIAL_CHARS);
                $lastName = filter_var($request->input('lastName'), FILTER_SANITIZE_SPECIAL_CHARS);
                $email = filter_var($request->input('email'), FILTER_SANITIZE_SPECIAL_CHARS);
                $password = filter_var($request->input('password'), FILTER_SANITIZE_SPECIAL_CHARS);
                $passwordConfirmation = filter_var($request->input('password_confirmation'), FILTER_SANITIZE_SPECIAL_CHARS);

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
                }
                else {
                    return new Response("Password must be at least 8 long and must match!", Response::HTTP_BAD_REQUEST);
                }

                $user->generateToken();
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
