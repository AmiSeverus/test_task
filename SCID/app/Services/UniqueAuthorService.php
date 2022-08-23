<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Http\Response;

class UniqueAuthorService
{
    public static function check($validatedFields)
    {
        $query = Author::where([
            ['name',$validatedFields['name']],
            ['surname',$validatedFields['surname']]
        ]);

        if (isset($validatedFields['patronic']))
        {
            $query->where('patronic', $validatedFields['patronic']);
        } else {
            $query->whereNull('patronic');
        }

        if ($query->count() > 0)
        {
            return response()->json([
                'message' => 'Автор не уникален',
                'errors' => $validatedFields,
            ], 400);
        };
    }
}