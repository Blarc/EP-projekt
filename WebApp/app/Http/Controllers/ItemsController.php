<?php

namespace App\Http\Controllers;

use App\ShoppingList;
use DB;
use App\Http\Resources\ItemsDetailsResource;
use App\Http\Resources\ItemsResource;
use App\Item;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Auth;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $items = DB::table('items')->paginate(10);
        return view('items.index')->with('items', $items);
    }

    public function sellerindex()
    {
        $items = DB::table('items')->paginate(10);
        return view('seller.manage')->with('items', $items);
    }

    public function shopItems()
    {
        $item = DB::table('items')->paginate(10);

        return view('customer.home')->with('items', $item);

    }

    public function toBasket($id)
    {
        $user = auth()->user();
        
        if (!$user || $user->role != 'customer') {
            return redirect('/register')->with('warning', 'You need to be registered as customer to add item to basket!');
        }

        $sl = $user->shoppingLists;

        $item = Item::find($id);

        return view('customer.choose-basket')->with('item', $item)->with('shoppingLists', $sl);

    }

    public function addItemShop($slid, $iid){
        $shoppingList = ShoppingList::find($slid);

        if ($shoppingList->items()->where('item_id', $iid)->exists()) {
            $items_amount = $shoppingList->items()->where('item_id', $iid)->first()->pivot->items_amount;
            $shoppingList->items()->updateExistingPivot($iid, array('items_amount' => $items_amount + 1));
        } else {
            $shoppingList->items()->attach($iid, array('items_amount' => 1));
        }



        $shoppingList->save();

        $user = auth()->user();
        $sl = $user->shoppingLists;

        $item = DB::table('items')->paginate(10);

        return view('customer.home')->with('items', $item)->with('shoppingLists', $sl);
//        return redirect()->intended('/shop')->with('success', 'Item added successfully');
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
        $this->validate($request, [
           'name' => 'required',
           'description' => 'required',
           'price' => 'required'
        ]);
        return 123;
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

    public function sellershow($id)
    {
        $item = Item::find($id);
        return view('seller.show')->with('item', $item);
    }

    public function shopShow($id)
    {
        $item = Item::find($id);
        return view('items.item-show')->with('item', $item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        // TODO
    }

    public function selleredit($id)
    {
        $item = Item::find($id);
        return view('seller.show')->with('item', $item);
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
        $item = Item::find($id);
        $item->delete();
        return redirect('/item-manage');
    }

    /**
     * API
     */

    public function getAll()
    {
        try {
            $items = Item::all();
            return ItemsResource::collection($items);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function get($id)
    {
        try {
            $item = Item::query()->find($id);
            if ($item != null) {
                return new ItemsDetailsResource($item);
            }
            return new Response("Item with specified ID doesn't exist!", Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function post(Request $request)
    {
        try {
            $item = new Item;
            $name = $request->input('name');
            $price = $request->input('price');
            if ($name != null) {
                $item->name = $name;
            } else {
                return new Response("Name must not be null!", Response::HTTP_BAD_REQUEST);
            }
            if ($price != null) {
                $item->price = $price;
            } else {
                return new Response("Price must not be null!", Response::HTTP_BAD_REQUEST);
            }

            $item->description = $request->input('description');
            $item->save();

            return new ItemsDetailsResource($item);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    public function put(Request $request, $id)
    {
        try {
            $item = Item::query()->find($id);

            if ($item != null) {
                $name = $request->input('name');
                $description = $request->input('description');
                $price = $request->input('price');

                if ($name != null) {
                    $item->name = $name;
                } else {
                    return new Response("Name must not be null!", Response::HTTP_BAD_REQUEST);
                }
                if ($price != null) {
                    $item->price = $price;
                } else {
                    return new Response("Price must not be null!", Response::HTTP_BAD_REQUEST);
                }
                $item->description = $description;
                $item->save();

                return new ItemsDetailsResource($item);
            } else {
                return new Response("Item with specified id does not exist!", Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete request for updating an item
     *
     * @param int $id
     * @return Response
     */

    public function delete($id)
    {
        try {
            $item = Item::query()->find($id);
            if ($item != null) {
                $item->delete();
            } else {
                return new Response("Item does not exist!", Response::HTTP_BAD_REQUEST);
            }
            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }
}
