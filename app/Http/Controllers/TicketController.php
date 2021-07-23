<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Category;
use App\Notifications\CommentEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use UxWeb\SweetAlert\SweetAlert;
use App\User;
use Illuminate\Support\Facades\DB;



class TicketController extends Controller
{
    use MediaUploadingTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        $categories = Category::get();
        return view('tickets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        //de string a entero con funcion intval
        $category_id = intval($request->category_id);
        //dd($category_id);

        //$user = DB::table('users')->whereHas('category_assign_id', $category_id)->first();
        $user = User::where('category_assign_id', 2)->whereHas('roles', function($query) {
            $query->whereId(2);
        })->get();

        //dd($user->category_assign_id);
        $category = User::whereHas('roles', function($query) {
            $query->whereId(2);
        })->get()->random()->id;
        //dd($request->category_id);

        $request->validate([
            'title'         => ['required', 'string', 'regex:/^([A-Za-zÀ-ÿ\s]{3,100})$/'],
            'content'       => ['required', 'regex:/^([0-9-\sA-Za-zÀ-ÿ\s]{3,255})$/'],
            'author_name'   => ['required', 'string', 'regex:/^([A-Za-zÀ-ÿ\s]{3,45})$/'],
            'author_email'  => 'required|email',
        ]);

        $request->request->add([
            'status_id'     => 1,
            'priority_id'   => 1
        ]);

        if (count($request->input('attachments', [])) < 3 || count($request->input('attachments', [])) == 0) {

                $ticket = Ticket::create(([
                    'assigned_to_user_id' => $category,
                    'title' => $request->title,
                    'content'  => $request->content,
                    'author_name' => $request->author_name,
                    'author_email' =>$request->author_email,
                    'category_id' => $request->category_id,
                    'status_id'     => 1,
                    'priority_id'   => 1
                ]));

                //$ticket = Ticket::create($request->all());
                foreach ($request->input('attachments', []) as $file) {
                    $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
                }
                alert()->success('Tu ticket es el N°: ' . $ticket->id, '¡Enhorabuena! ' . $ticket->author_name . ', ahora puedes ver el estado de tu requerimiento')->autoclose(7000);
                return redirect()->route('tickets.show', $ticket->id);

        } else {
            alert()->error('Solo se permite un máximo de 2 archivos')->autoclose(4000);
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        $ticket->load('comments');

        return view('tickets.show', compact('ticket'));
    }

    public function storeComment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'comment_text' => 'required'
        ]);

        $comment = $ticket->comments()->create([
            'author_name'   => $ticket->author_name,
            'author_email'  => $ticket->author_email,
            'comment_text'  => $request->comment_text
        ]);

        $ticket->sendCommentNotification($comment);

        alert()->success('Tu comentario se envio correctamente')->autoclose(4000);
        return redirect()->back();
    }
}
