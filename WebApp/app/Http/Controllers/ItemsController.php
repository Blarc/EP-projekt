<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemsDetailsResource;
use App\Http\Resources\ItemsResource;
use App\Item;
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
        $items = Item::all();
        return view('items.index')->with('items', $items);
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
        // TODO
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
            if ($name != null) {
                $item->name = $name;
            }
            else {
                return new Response("Name must not be null!", Response::HTTP_BAD_REQUEST);
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
                if ($name != null) {
                    $item->name = $name;
                }
                else {
                    return new Response("Name must not be null!", Response::HTTP_BAD_REQUEST);
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
