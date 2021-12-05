<?php

namespace App\Http\Controllers;

use App\Events\ShippingStatusUpdated;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Events\NewMessage;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

class ContactsController extends Controller
{
    public function get()
    {
        $contacts = User::where('id', '!=', auth()->id())->get();
//        // get all users except the authenticated one
        if (auth()->user()->type =='instructor'){
            $type = 'student';
            $ids = User::where('instructor_id', auth()->user()->id)
                ->where('type', $type)->select('id')->get();
        }
        $idsArray = [];
        foreach ($ids as $item){
           $idsArray[] =   $item->id;
        }


//        elseif (auth()->user()->type =='student'){
//            $type = 'instructor';
//        }
//
//        $contacts =  User::where(function ($query) use ($type) {
//            $query->where('id', '!=', auth()->user()->id);
//            if ($type == 'student'){
//                $query->where('type', '=', $type)
//                    ->where('instructor_id', auth()->user()->id);
//                }
//            elseif ($type == 'instructor'){
//                $query->where('type', '=', $type)
//                    ->where('id', auth()->user()->instructor_id);
//            }
//        })->get();

        // get a collection of items where sender_id is the user who sent us a message
        // and messages_count is the number of unread messages we have from him

        $unreadIds = Message::select(\DB::raw('`from` as sender_id, count(`from`) as messages_count'))
            ->where('to', auth()->id())
            ->whereIn('from', $ids)
            ->where('read', false)
            ->groupBy('from')
            ->get();

        // add an unread key to each contact with the count of unread messages
        $contacts = $contacts->map(function($contact) use ($unreadIds) {
        $contactUnread = $unreadIds->where('sender_id', $contact->id)->first();
        $contact->unread = $contactUnread ? $contactUnread->messages_count : 0;
        return $contact;
        });
        return response()->json($contacts);
    }

    public function getMessagesFor($id)
    {
        // mark all messages with the selected contact as read
        Message::where('from', $id)->where('to', auth()->id())->update(['read' => true]);

        // get all messages between the authenticated user and the selected user
        $messages = Message::where(function($q) use ($id) {
            $q->where('from', auth()->id());
            $q->where('to', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('from', $id);
            $q->where('to', auth()->id());
        })
        ->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $message = Message::create([
            'from' => auth()->id(),
            'to' => $request->contact_id,
            'text' => $request->text
        ]);


        broadcast(new NewMessage($message));
        return response()->json($message);
    }
}
