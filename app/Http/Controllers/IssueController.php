<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Requests\IssueRequest;
use App\Models\Issue;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->type == UserType::ADMIN->value) {
            $issues = Issue::all();
            $issues->load('user.vehicle');
        } else {
            $issues = Issue::where('user_id', Auth::id())->get();
            $issues->load('user.vehicle');
        }

        return response()->json([
            'issues' => $issues
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IssueRequest $request)
    {
        $issue = Issue::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'vehicle_id' => Auth::user()->vehicle->id,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Issue created',
            'issue' => $issue,
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $issue = Issue::where('id', $id)->first();
        if ($issue) {
            $issue->load('user.vehicle.group');
            return response()->json([
                'issue' => $issue,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Issue not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IssueRequest $request, string $id)
    {
        $issue = Issue::find($id);

        if ($issue) {
            $issueData = [
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
            ];

            $issue->update($issueData);

            return response()->json([
                'message' => 'Issue updated',
                'issue' => $issue,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Issue not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $issue = Issue::find($id);

        if ($issue) {
            $issue->delete();
            return response()->json([
                'message' => 'Issue deleted',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'Issue not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
