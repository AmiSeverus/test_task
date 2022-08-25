<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use App\Models\Magazine;
use App\Models\Author;
use App\Models\MagazineAuthor;
use App\Rules\UniqueImgName;


class MagazineController extends Controller
{
    public function list(Request $request)
    {
        $perPage = (int) $request->query('perPage');

        $page = (int) $request->query('page');

        $magazines = Magazine::with('authors')->orderBy('id');

        if ($perPage && $page && $page >= 1 && $perPage > 0)
        {
            $magazines->limit($perPage)->offset(($page-1)*$perPage);
        }

        $res = $magazines->get();

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
            'title' => [
                'required',
                'unique:magazines,title',
                'string'
            ],
            'description' => [
                'sometimes'
            ],
            'issued_at' => [
                'required',
                'date_format:Y-m-d'
            ],
            'authors' => [
                'required',
                'array',
                'min:1'
            ],
            'authors.*' => [
                'required',
                'distinct',
                'exists:authors,id'
            ],
            'img' => [
                'required',
                'file',
                'mimes:jpg,png',
                'max:2000',
                new UniqueImgName(),
            ]
        ]);

        $img = $validatedFields['img'];

        $validatedFields['img'] = config('app.url') . '/files/' . $img->getClientOriginalName();

        $img->move(public_path() . '/files/' , $img->getClientOriginalName());

        $magazine = new Magazine($validatedFields);

        $magazine->save();

        if (!$magazine->id)
        {
            return response()->json([
                'message' => 'Ошибка сохранения в базу, попробуйте еще раз позже',
                'errors' => $validatedFields,
            ], 400);
        }

        $inserts = [];

        foreach($validatedFields['authors'] as $author)
        {
            $inserts[] = ['author_id'=>$author, 'magazine_id'=>$magazine->id];
        }

        MagazineAuthor::insert($inserts);

        return Magazine::with('authors')->where('id', $magazine->id)->get()->toJson();

    }

    public function update(Request $request)
    {
        $validatedFields = $request->validate([
            'id' => [
                'required',
                'exists:magazines,id'
            ],
            'title' => [
                'required',
                'string'
            ],
            'description' => [
                'sometimes'
            ],
            'issued_at' => [
                'required',
                'date_format:Y-m-d'
            ],
            'authors' => [
                'required',
                'array',
                'min:1'
            ],
            'authors.*' => [
                'required',
                'distinct',
                'exists:authors,id'
            ],
            'img' => [
                'sometimes',
                'file',
                'mimes:jpg,png',
                'max:2000',
                new UniqueImgName(),
            ]
        ]);

        if (Magazine::where([
            ['title', $validatedFields['title']],
            ['id', '<>', $validatedFields['id']]
        ])->count() > 0)
        {
            return response()->json([
                'message' => 'Такой title уже существует',
                'errors' => ['Такой title уже существует'],
            ], 400);
        }

        $magazine = Magazine::find($validatedFields['id']);

        if(isset($validatedFields['img']))
        {

            File::delete(public_path(parse_url($magazine->img, PHP_URL_PATH)));

            $img = $validatedFields['img'];

            $validatedFields['img'] = config('app.url') . '/files/' . $img->getClientOriginalName();
    
            $img->move(public_path() . '/files/' , $img->getClientOriginalName());
        }

        $magazine->update($validatedFields);
        MagazineAuthor::where("magazine_id", $validatedFields['id'])->delete();
        $inserts = [];

        foreach($validatedFields['authors'] as $author)
        {
            $inserts[] = ['author_id'=>$author, 'magazine_id'=>$magazine->id];
        }

        MagazineAuthor::insert($inserts);

        return Magazine::with('authors')->where('id', $magazine->id)->get()->toJson();
    }

    public function delete(Request $request)
    {
        $validatedFields = $request->validate([
            'id' => [
                'required',
                'exists:magazines,id'
            ]
        ]);

        $magazine = Magazine::find($validatedFields['id']);

        File::delete(public_path(parse_url($magazine->img, PHP_URL_PATH)));

        MagazineAuthor::where('magazine_id', $magazine->id)->delete();

        $magazine->delete();

        if (Magazine::find($validatedFields['id']))
        {
            return response()->json([
                'message' => 'Ошибка удаления из базы, попробуйте еще раз позже',
                'errors' => $validatedFields,
            ], 400);
        };

        return response()->json([
            'message' => 'Журнал удален'
        ]);
    }
}
