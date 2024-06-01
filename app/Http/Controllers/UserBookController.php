<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserBookController extends Controller
{
    /**
     * Display a listing of the user-book relationships.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userBooks = User::with('books')->get();
        return response()->json($userBooks);
    }

    /**
     * Store a newly created user-book relationship in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'status' => 'required|in:Available,Onloan,Deleted',
            'applied_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($request->user_id);
        $user->books()->attach($request->book_id, [
            'status' => $request->status,
            'applied_date' => $request->applied_date
        ]);

        return response()->json(['message' => 'Relationship created successfully'], 201);
    }

    /**
     * Display the specified user-book relationship.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with(['books' => function ($query) use ($id) {
            $query->where('books.id', $id);
        }])->find($id);

        if (!$user) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * Update the specified user-book relationship in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Available,Onloan,Deleted',
            'applied_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->books()->updateExistingPivot($request->book_id, [
            'status' => $request->status,
            'applied_date' => $request->applied_date
        ]);

        return response()->json(['message' => 'Relationship updated successfully']);
    }

    /**
     * Remove the specified user-book relationship from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($userId, $bookId)
{
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Check if the user actually has this book associated
    if (!$user->books()->find($bookId)) {
        return response()->json(['message' => 'Book not associated with this user'], 404);
    }

    // Detach the book from the user
    $user->books()->detach($bookId);
    return response()->json(['message' => 'Relationship deleted successfully'], 204);
}
}
