<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Notifications\CommentEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use UxWeb\SweetAlert\SweetAlert;


class TicketController extends Controller
{
    use MediaUploadingTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'title'         => ['required', 'string', 'regex:/^([A-Za-zÀ-ÿ\s]{3,100})$/'],
            'content'       => ['required', 'regex:/^([0-9-\sA-Za-zÀ-ÿ\s]{3,255})$/'],
            'author_name'   => ['required', 'string', 'regex:/^([A-Za-zÀ-ÿ\s]{3,45})$/'],
            'author_email'  => 'required|email',

        ]);

        $request->request->add([
            'category_id'   => 1,
            'status_id'     => 1,
            'priority_id'   => 1
        ]);

        $ticket = Ticket::create($request->all());

        foreach ($request->input('attachments', []) as $file) {
            $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        }
        alert()->success( 'Tu ticket es el N°: '.$ticket->id, '¡Enhorabuena! '.$ticket->author_name.', ahora puedes ver el estado de tu requerimiento')->autoclose(7000);
        //redirect()->with('success', 'Tu ticket ha sido enviado, puedes ver el estado de tu ticket ');
        return redirect()->route('tickets.show', $ticket->id);
        //return redirect()->back()->withStatus();
        //return redirect()->back()->with('Tu ticket ha sido enviado, muy pronto nos comunicaremos contigo. puedes ver el estado de tu ticket <a href="'.route('tickets.show', $ticket->id).'">aqui</a>');
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

        alert()->success( 'Tu comentario se envio correctamente')->autoclose(4000);
        return redirect()->back();
    }
}
