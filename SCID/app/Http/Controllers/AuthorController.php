<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Author;
use App\Services\UniqueAuthorService;

class AuthorController extends Controller
{
    public function list(Request $request)
    {

        $perPage = (int) $request->query('perPage');

        $page = (int) $request->query('page');

        $authors = Author::orderBy('id');

        if ($perPage && $page && $page >= 1 && $perPage >= 1)
        {
            $pages = ceil(Author::count()/$perPage);

            $authors->limit($perPage)->offset(($page-1)*$perPage);
        }

        $res = $authors->get();

        if($res->count() < 1)
        {
            return response()->json([
                'message' => 'Ничего не найдено',
                'errors' => [
                    'page' => $page,
                    'perPage' => $perPage,
                ],
            ], 404);
        }

        return $res->toJson();
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

        if(!$author->id)
        {
            return response()->json([
                'message' => 'Ошибка сохранения в базу, попробуйте еще раз позже',
                'errors' => $validatedFields,
            ], 400);
        }

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
                'exists:authors,id',
                'unique:magazine_author,author_id'
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
        };

        return response()->json([
            'message' => 'Автор удален'
        ]);
    }
}
