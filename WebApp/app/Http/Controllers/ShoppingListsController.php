<?php

namespace App\Http\Controllers;

use App\ShoppingList;
use App\Http\Resources\ShoppingListsResource;
use App\Http\Resources\ShoppingListsDetailsResource;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ShoppingListsController extends Controller
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
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        // TODO
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //TODO
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        // TODO
    }

    /**
     * API
     */

    public function getAll()
    {
        try {
            // $shoppingLists = ShoppingList::query()->paginate(5);
            $shoppingLists = ShoppingList::all();
            return ShoppingListsResource::collection($shoppingLists);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function get($id)
    {
        try {
            $shoppingList = ShoppingList::query()->find($id);
            if ($shoppingList != null) {
                return new ShoppingListsDetailsResource($shoppingList);
            }
            return new Response("Shopping list with specified ID doesn't exist!", Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function post(Request $request)
    {
        try {
            $shoppingList = new ShoppingList;
            $name = $request->input('name');
            if ($name != null) {
                $shoppingList->name = $name;
            }
            else {
                return new Response("Name must not be null!", Response::HTTP_BAD_REQUEST);
            }
            $shoppingList->user()->associate(User::query()->find(Auth::id()));

//            $items = $request->input('items');
//            if (count($items) > 0) {
//                $shoppingList->items()->attach($items);
//            }

            $shoppingList->save();
            return new ShoppingListsDetailsResource($shoppingList);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function put(Request $request, $id)
    {
        try {
            $shoppingList = ShoppingList::query()->find($id);

            if ($shoppingList != null) {
                $name = $request->input('name');
                if ($name != null) {
                    $shoppingList->name = $name;
                }
                else {
                    return new Response("Name must not be null!", Response::HTTP_BAD_REQUEST);
                }

//                $items = $request->input('items');
//                if (count($items) > 0) {
//                    $shoppingList->items()->sync($items);
//                }

                $shoppingList->save();
                return new ShoppingListsDetailsResource($shoppingList);
            } else {
                return new Response("Shopping list with specified id does not exist!", Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function putItems(Request $request, $id)
    {
        try {
            $shoppingList = ShoppingList::query()->find($id);

            if ($shoppingList != null) {
                $items = $request->input('items');
                if (count($items) > 0) {
                    $shoppingList->items()->sync($items);
                }
                $shoppingList->save();
                return new ShoppingListsDetailsResource($shoppingList);
            } else {
                return new Response("Shopping list with specified id does not exist!", Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete($id)
    {
        try {
            $item = ShoppingList::query()->find($id);
            if ($item != null) {
                $item->delete();
            } else {
                return new Response("Item with specified id does not exist!", Response::HTTP_BAD_REQUEST);
            }
            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
