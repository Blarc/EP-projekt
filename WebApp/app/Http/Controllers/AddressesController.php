<?php

namespace App\Http\Controllers;

use App\Address;
use App\Http\Resources\AddressResource;
use App\Http\Resources\ItemsDetailsResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // TODO
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // TODO
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // TODO
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        // TODO
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        // TODO
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // TODO
    }

    public function getAll()
    {
        try {
            $addresses = Address::all();
            return AddressResource::collection($addresses);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function get($id)
    {
        try {
            $address = Address::query()->find($id);
            if ($address != null) {
                return new AddressResource($address);
            }
            return new Response("Address with specified ID doesn't exist!", Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function post(Request $request)
    {
        try {
            $address = new Address;
            $street = $request->input('street');
            if ($street != null) {
                $address->street = $street;
            }
            else {
                return new Response("Street must not be null!", Response::HTTP_BAD_REQUEST);
            }

            $post = $request->input('post');
            if ($post != null) {
                $address->post = $post;
            }
            else {
                return new Response("Post must not be null!", Response::HTTP_BAD_REQUEST);
            }

            $postCode = $request->input('postCode');
            if ($postCode != null) {
                $address->postCode = $postCode;
            }
            else {
                return new Response("Post code must not be null!", Response::HTTP_BAD_REQUEST);
            }

            $address->save();
            return new AddressResource($address);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function put(Request $request, $id)
    {
        try {
            $address = Address::query()->find($id);

            if ($address != null) {
                $street = $request->input('street');
                if ($street != null) {
                    $address->street = $street;
                } else {
                    return new Response("Street must not be null!", Response::HTTP_BAD_REQUEST);
                }

                $post = $request->input('post');
                if ($post != null) {
                    $address->post = $post;
                } else {
                    return new Response("Post must not be null!", Response::HTTP_BAD_REQUEST);
                }

                $postCode = $request->input('postCode');
                if ($postCode != null) {
                    $address->postCode = $postCode;
                } else {
                    return new Response("Post code must not be null!", Response::HTTP_BAD_REQUEST);
                }

                $address->save();
                return new AddressResource($address);
            } else {
                return new Response("Address with specified id does not exist!", Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete($id)
    {
        try {
            $item = Address::query()->find($id);
            if ($item != null) {
                $item->delete();
            } else {
                return new Response("Address does not exist!", Response::HTTP_BAD_REQUEST);
            }
            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
