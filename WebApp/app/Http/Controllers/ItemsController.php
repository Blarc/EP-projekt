<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemsDetailsResource;
use App\Http\Resources\ItemsResource;
use App\Item;
use App\ShoppingList;
use DB;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $items = DB::table('items')->where('active', '1')->paginate(8);
        return view('items.index')->with('items', $items);
    }

    public function sellerindex()
    {
        $user = auth()->user();

        if ($user->role == 'seller') {
            $items = DB::table('items')->where('active', '1')->orderBy('updated_at', 'desc')->paginate(8);
            return view('seller.manage')->with('items', $items);
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

    }

    public function sellerindexDeactivated()
    {

        $user = auth()->user();

        if ($user->role == 'seller') {
            $notActive = DB::table('items')->where('active', '0')->orderBy('updated_at', 'desc')->paginate(8);
            return view('seller.managedeactivated')->with('itemsNotActive', $notActive);
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

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

    public function addItemShop($slid, $iid)
    {

        $user = auth()->user();

        if ($user->role == 'customer') {
            $shoppingList = ShoppingList::find($slid);

            if ($shoppingList->items()->where('item_id', $iid)->exists()) {
                $items_amount = $shoppingList->items()->where('item_id', $iid)->first()->pivot->items_amount;
                $shoppingList->items()->updateExistingPivot($iid, array('items_amount' => $items_amount + 1));
            } else {
                $shoppingList->items()->attach($iid, array('items_amount' => 1));
            }

            $shoppingList->save();

            $user = auth()->user();
            $sl = $shoppingList;

            return view('customer.slshow')->with('sl', $sl);
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

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
        $user = auth()->user();

        if ($user->role == 'seller') {
            $item = Item::find($id);
            return view('seller.show')->with('item', $item);
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');
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
        $user = auth()->user();

        if ($user->role == 'seller') {
            $item = Item::find($id);
            return view('seller.show')->with('item', $item);
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

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

        $user = auth()->user();

        if ($user->role == 'seller') {
            $item = Item::find($id);
            $item->active = 0;
            $item->save();
            return redirect('/item-manage')->with('success', 'Item deactivated successfully.');
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');
    }

    public function activate($id)
    {
        $user = auth()->user();

        if ($user->role == 'seller') {
            $item = Item::find($id);
            $item->active = 1;
            $item->save();
            return redirect('/item-deactived')->with('success', 'Item reactivated successfully.');
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

    }

    /**
     * API
     */

    public function getAll()
    {
        try {
            $items = Item::all()->where('active', '1');
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
            $name = filter_var($request->input('name'), FILTER_SANITIZE_SPECIAL_CHARS);
            $price = filter_var($request->input('price'), FILTER_SANITIZE_SPECIAL_CHARS);
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

            $item->description = filter_var($request->input('description'), FILTER_SANITIZE_SPECIAL_CHARS);
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
                $name = filter_var($request->input('name'), FILTER_SANITIZE_SPECIAL_CHARS);
                $price = filter_var($request->input('price'), FILTER_SANITIZE_SPECIAL_CHARS);

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
                $item->description = filter_var($request->input('description'), FILTER_SANITIZE_SPECIAL_CHARS);
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
