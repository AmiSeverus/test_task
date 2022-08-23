<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Author;
use App\Models\Magazine;
use App\Services\UniqueAuthorService;

class AuthorController extends Controller
{
    public function list()
    {
        return Author::all()->toJson();
    }

    public function add(Request $request)
    {
        $validatedFields = $request->validate([
            'name' => [
                'required',
                'min:3'
            ],
            'surname' => [
                'required'
            ],
            'patronic' => [
                'sometimes'
            ]
        ]);

        $not_unique_author = UniqueAuthorService::check($validatedFields);

        if ($not_unique_author)
        {
            return $not_unique_author;
        }

        $author = new Author;

        $author->name = $validatedFields['name'];
        $author->surname = $validatedFields['surname'];
        if (isset($validatedFields['patronic']))
        {
            $author->patronic = $validatedFields['patronic'];
        };

        $author->save();

        if (isset($author->id))
        {
            return response()->json([
                'message' => 'Автор создан',
                'author' => $author->toArray()
            ], 201);
        };

        return response()->json([
            'message' => 'Ошибка сохранения в базу, попробуйте еще раз позже',
            'errors' => $validatedFields,
        ], 400);
    }

    public function update(Request $request)
    {
        $validatedFields = $request->validate([
            'id' => [
                'required',
                'exists:authors,id'
            ],
            'name' => [
                'required',
                'min:3'
            ],
            'surname' => [
                'required'
            ],
            'patronic' => [
                'sometimes'
            ]
        ]);

        $not_unique_author = UniqueAuthorService::check($validatedFields);

        if ($not_unique_author)
        {
            return $not_unique_author;
        }

        $author = Author::find($validatedFields['id']);

        $author->name = $validatedFields['name'];
        $author->surname = $validatedFields['surname'];
        if(isset($validatedFields['patronic']))
        {
            $author->patronic = $validatedFields['patronic'];
        } else {
            $author->patronic = NULL;
        }

        $author->save();

        return response()->json([
            'message' => 'Автор изменен',
            'author' => $author->toArray()
        ], 200);
    }

    public function delete(Request $request)
    {
        $validatedFields = $request->validate([
            'id' => [
                'required',
                'exists:authors,id'
            ]
        ]);

        $author = Author::find($validatedFields['id']);

        $author->delete();

        if (Author::find($validatedFields['id']))
        {
            return response()->json([
                'message' => 'Ошибка удаления из базы, попробуйте еще раз позже',
                'errors' => $validatedFields,
            ], 400);
        }

        return response()->json([
            'message' => 'Автор удален'
        ]);
    }
}
