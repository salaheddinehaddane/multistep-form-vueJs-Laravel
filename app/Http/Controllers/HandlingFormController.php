<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HandlingFormController extends Controller
{
    public function handleSubmitForm(Request $request){
            //Handle form data here
        return response()->json(
            ['data_received'=>$request->all()], 
            200
        );
    }
}
