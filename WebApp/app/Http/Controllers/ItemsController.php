<?php

namespace App\Http\Controllers;

use App\Item;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return new Response(Item::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
//        $this -> validate($request, [
//            'name' => 'required'
//        ]);
//        TODO validator
//        Create Item
        $item = new Item;
        $item->name = $request->input('name');
        $item->save();

        return new Response($item, Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $item = Item::query()->find($id);
        if ($item != null) {
            return new Response($item, Response::HTTP_OK);
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
        //
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
        // TODO add validator?
        $item = Item::query()->find($id);

        if ($item != null) {
            $name = $request->input('name');
            if ($name != null) {
                $item->name = $name;
            }
            $item->save();
            return new Response($item, Response::HTTP_OK);
        } else {
            return new Response([], Response::HTTP_BAD_REQUEST);
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
        $item = Item::query()->findOrFail($id);
        try {
            $item->delete();
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
