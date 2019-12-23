<?php

namespace App\Http\Controllers;

use App\ShoppingList;
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
        return new Response(ShoppingList::all(), Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //TODO
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // TODO exception validator

        // Create Shopping list
        $shoppingList = new ShoppingList;
        $shoppingList->name = $request->input('name');
        $shoppingList->user()->associate(User::query()->find(Auth::id()));
        $shoppingList->save();

        $items = $request->input('items');
        if (count($items) > 0) {
            $shoppingList->items()->attach($items);
        }

        return new Response($shoppingList, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $shoppingList = ShoppingList::query()->find($id);

        if ($shoppingList != null) {
            return new Response(ShoppingList::query()->find($id), Response::HTTP_OK);
        }
        return new Response([], Response::HTTP_BAD_REQUEST);

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
        $shoppingList = ShoppingList::query()->find($id);

        if ($shoppingList != null && $shoppingList instanceof ShoppingList) {

            $name = $request->input('name');
            if ($name != null) {
                $shoppingList->name = $name;
            }

            $items = $request->input('items');
            if (count($items) > 0) {
                $shoppingList->items()->sync($items);
            }

            // TODO check if this works
            $shoppingList->save();
            return new Response($shoppingList, Response::HTTP_OK);
        } else {
            return new Response($shoppingList, Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $shoppingList = ShoppingList::query()->find($id);
        if ($shoppingList != null) {
            try {
                $shoppingList->delete();
                return new Response([], Response::HTTP_NO_CONTENT);
            } catch (Exception $e) {
                return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }
        return new Response([], Response::HTTP_BAD_REQUEST);
    }
}
