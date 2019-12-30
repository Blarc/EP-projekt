<?php

namespace App\Http\Controllers;

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

    /**
     * Returns all items in json format
     *
     * @return Response
     */
    public function getAll()
    {
        try {
            $items = Item::all();
            return new Response($items, Response::HTTP_OK);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Returns item with @param int $id in json format
     *
     * @return Response
     */
    public function get($id)
    {
        try {
            $item = Item::query()->find($id);
            if ($item != null) {
                return new Response($item, Response::HTTP_OK);
            }
            return new Response("Item with specified ID doesn't exist!", Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Post request for creating new item
     *
     * @param Request $request
     * @return Response
     */

    public function post(Request $request)
    {
        try {
            $item = new Item;
            $item->name = $request->input('name');
            $item->description = $request->input('description');
            $item->save();

            return new Response($item, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Put request for updating an item
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */

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
                if ($description != null) {
                    $item->description = $description;
                }
                $item->save();
                return new Response($item, Response::HTTP_OK);
            } else {
                return new Response([], Response::HTTP_BAD_REQUEST);
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
