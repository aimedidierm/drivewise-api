<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::all();
        return response()->json([
            'groups' => $groups
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupRequest $request)
    {
        $group = Group::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'message' => 'Group created',
            'group' => $group,
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $group = Group::where('id', $id)->first();
        if ($group) {
            return response()->json([
                'group' => $group,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Group not found',
            ], Response::HTTP_OK);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupRequest $request, string $id)
    {
        $group = Group::find($id);

        if ($group) {
            $group->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);

            return response()->json([
                'message' => 'Group updated',
                'group' => $group,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Group not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = Group::find($id);

        if ($group) {
            $group->delete();
            return response()->json([
                'message' => 'Group deleted',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Group not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
