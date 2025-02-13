<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Posting;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    // index: display all postings & comments
    function index() {
        $postings = Posting::with([
            'user:id,name',
            'comments:posting_id,message,updated_at,user_id',
            'comments.user:id,name'])->get();
        
        $postings = Posting::orderBy('updated_at', 'desc')->get();

        return view('postings.index', [
            'postings' => $postings
        ]);
    }

    // create: display create posting form
    function create() {
        return view('postings.create');
    }

    // store: store posting info into database
    function store(Request $request) {
        $formFields = $request->validate([
            'title' => ['required', 'max:15'],
            'description' => 'required'
        ]);

        $formFields['image'] = $request->file('image')->store('images', 'public');
        $formFields['user_id'] = auth()->id();

        Posting::create($formFields);

        return redirect('/');
    }

    // store: store comment info into database
    function storeComments(Request $request, Posting $posting) {
        $formFields = $request->validate([
            'message' => 'required'
        ]);

        $formFields['user_id'] = auth()->id();
        $formFields['posting_id'] = $posting->id;

        Comment::create($formFields);

        return redirect('/' . '#posting-' . $posting->id);
    }

    // destroy: delete posting
    function destroy (Posting $posting) {
        if ($posting->user_id != auth()->id()){
            abort(403, 'Unauthorised Action');
        }

        $posting->delete();
        return redirect('/');
    }
}
