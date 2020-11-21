<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function get()
    {
        $contacts =auth()->user()->contacts??null;
        return response()->success($contacts);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(["number" => "required", "email" => "required", "name" => "required"]);
        Contact::insert([
            "user_id" => auth()->user()->id,
            "email" => request()->email,
            "name" => request()->name,
            "number" => request()->number
        ]);
        return response()->success("Contact added");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $contact->update([
            'number' => $request->number ?? $contact->number,
            'name' => $request->name ?? $contact->name,
            'email' => $request->email ?? $contact->email
        ]);
        return response()->success('Contact Updated!');
    }

}
