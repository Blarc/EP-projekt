<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShoppingListsDetailsResource;
use App\Http\Resources\ShoppingListsResource;
use App\ShoppingList;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShoppingListsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $shoppingLists = ShoppingList::all();
        return view('seller.shopping-lists')->with('shoppingLists', $shoppingLists);
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

    public function slshow($id)
    {

        $sl = ShoppingList::find($id);
        return view('seller.slshow')->with('sl', $sl);

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
        $sl = ShoppingList::find($id);
        $sl->delete();
        return redirect('/seller/shoppingLists');
    }

    public function accept($id)
    {
        DB::table('shopping_lists')->where('id', $id)->update(['status' => '1']);
        return redirect('/seller/shoppingLists');
    }

    public function stornate($id)
    {
        DB::table('shopping_lists')->where('id', $id)->update(['status' => '2']);
        return redirect('/seller/shoppingLists');
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
            $userId = $request->input('userId');
            if ($name != null) {
                $shoppingList->name = $name;
            } else {
                return new Response("Name must not be null!", Response::HTTP_BAD_REQUEST);
            }

            $shoppingList->user_id = $request->user('api')->id;

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
                } else {
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

    public function addItem(Request $request, $id)
    {
        try {
            $shoppingList = ShoppingList::query()->find($id);

            if ($shoppingList != null) {

                if ($request != null) {

                    $itemId = $request->input('id');

                    if ($shoppingList->items()->where('item_id', $itemId)->exists()) {
                        $items_amount = $shoppingList->items()->where('item_id', $itemId)->first()->pivot->items_amount;
                        $shoppingList->items()->updateExistingPivot($itemId, array('items_amount' => $items_amount + 1));
                    } else {
                        $shoppingList->items()->attach($itemId, array('items_amount' => 1));
                    }

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

    public function decreaseItemAmount(Request $request, $id)
    {
        try {
            $shoppingList = ShoppingList::query()->find($id);

            if ($shoppingList != null) {

                if ($request != null) {

                    $itemId = $request->input('id');

                    if ($shoppingList->items()->where('item_id', $itemId)->exists()) {
                        $items_amount = $shoppingList->items()->where('item_id', $itemId)->first()->pivot->items_amount;

                        if ($items_amount - 1 == 0) {
                            $shoppingList->items()->detach($itemId, array('items_amount' => 1));
                        } else {
                            $shoppingList->items()->updateExistingPivot($itemId, array('items_amount' => $items_amount - 1));
                        }
                    }
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

    public function removeItem(Request $request, $id)
    {
        try {
            $shoppingList = ShoppingList::query()->find($id);

            if ($shoppingList != null) {
                if ($request != null) {
                    $shoppingList->items()->detach($request->input('id'));
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
            $shoppingList = ShoppingList::query()->find($id);
            if ($shoppingList != null) {
                $shoppingList->items()->detach();
                $shoppingList->delete();
            } else {
                return new Response("Item with specified id does not exist!", Response::HTTP_BAD_REQUEST);
            }
            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
