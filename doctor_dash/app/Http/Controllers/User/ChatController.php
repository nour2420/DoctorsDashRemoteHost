<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function doctors()
    {
        $currentUser = Auth::user();

        $doctors = User::whereIn('role', ['admin', 'assistant'])
            ->orderBy('name')
            ->get();

        $doctors = $this->hydrateDoctorContactsWithLastMessage($currentUser, $doctors);

        return view('user.chats.doctors', [
            'user' => $currentUser,
            'doctors' => $doctors,
            'activeDoctor' => null,
            'conversation' => null,
            'messages' => collect(),
        ]);
    }

    public function doctorConversation(User $doctor)
    {
        abort_unless(in_array($doctor->role, ['admin', 'assistant'], true), 404);

        $currentUser = Auth::user();

        $conversation = $this->findOrCreateDoctorConversation($currentUser, $doctor);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        $doctors = User::whereIn('role', ['admin', 'assistant'])
            ->orderBy('name')
            ->get();

        $doctors = $this->hydrateDoctorContactsWithLastMessage($currentUser, $doctors);

        return view('user.chats.doctors', [
            'user' => $currentUser,
            'doctors' => $doctors,
            'activeDoctor' => $doctor,
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    public function sendToDoctor(Request $request, User $doctor)
    {
        abort_unless(in_array($doctor->role, ['admin', 'assistant'], true), 404);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $currentUser = Auth::user();

        $conversation = $this->findOrCreateDoctorConversation($currentUser, $doctor);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $currentUser->id,
            'body' => $data['body'],
        ]);

        return redirect()->route('user.chats.doctors.show', $doctor);
    }

    public function groupConversation()
    {
        $currentUser = Auth::user();

        $conversation = Conversation::firstOrCreate([
            'type' => 'user_doctors_group',
            'admin_id' => $currentUser->id,
            'participant_id' => $currentUser->id,
        ]);

        // Ensure all doctors and the current user are participants in this group conversation
        $doctorIds = User::whereIn('role', ['admin', 'assistant'])->pluck('id');
        $participantIds = $doctorIds->push($currentUser->id)->unique()->all();
        $conversation->participants()->syncWithoutDetaching($participantIds);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return view('user.chats.group', [
            'user' => $currentUser,
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    public function sendToGroup(Request $request)
    {
        $currentUser = Auth::user();

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $conversation = Conversation::firstOrCreate([
            'type' => 'user_doctors_group',
            'admin_id' => $currentUser->id,
            'participant_id' => $currentUser->id,
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $currentUser->id,
            'body' => $data['body'],
        ]);

        return redirect()->route('user.chats.group');
    }

    protected function findOrCreateDoctorConversation(User $user, User $doctor): Conversation
    {
        $conversation = Conversation::where('type', 'admin_user')
            ->where(function ($query) use ($user, $doctor) {
                $query->where([
                    'admin_id' => $user->id,
                    'participant_id' => $doctor->id,
                ])->orWhere(function ($query) use ($user, $doctor) {
                    $query->where([
                        'admin_id' => $doctor->id,
                        'participant_id' => $user->id,
                    ]);
                });
            })
            ->first();

        if (! $conversation) {
            $conversation = Conversation::create([
                'type' => 'admin_user',
                'admin_id' => $user->id,
                'participant_id' => $doctor->id,
            ]);
        }

        return $conversation;
    }

    protected function hydrateDoctorContactsWithLastMessage(User $currentUser, $doctors)
    {
        return $doctors->map(function (User $doctor) use ($currentUser) {
            $conversation = Conversation::where('type', 'admin_user')
                ->where(function ($query) use ($currentUser, $doctor) {
                    $query->where([
                        'admin_id' => $currentUser->id,
                        'participant_id' => $doctor->id,
                    ])->orWhere(function ($query) use ($currentUser, $doctor) {
                        $query->where([
                            'admin_id' => $doctor->id,
                            'participant_id' => $currentUser->id,
                        ]);
                    });
                })
                ->with(['messages' => function ($query) {
                    $query->latest()->limit(1);
                }])
                ->first();

            $lastMessage = null;
            $lastMessageAt = null;
            $lastFromSelf = false;

            if ($conversation && $conversation->messages && $conversation->messages->isNotEmpty()) {
                $last = $conversation->messages->first();
                $lastMessage = $last->body;
                $lastMessageAt = $last->created_at;
                $lastFromSelf = $last->sender_id === $currentUser->id;
            }

            $doctor->last_message = $lastMessage;
            $doctor->last_message_at = $lastMessageAt;
            $doctor->last_message_from_self = $lastFromSelf;

            return $doctor;
        });
    }

    public function notifications(Request $request)
    {
        $currentUser = $request->user();

        $doctors = User::whereIn('role', ['admin', 'assistant'])
            ->orderBy('name')
            ->get();

        $doctorContacts = $this->hydrateDoctorContactsWithLastMessage($currentUser, $doctors)
            ->filter(function (User $doctor) {
                return $doctor->last_message && empty($doctor->last_message_from_self) === true;
            });

        return view('user.notifications.index', [
            'currentUser' => $currentUser,
            'doctorNotifications' => $doctorContacts,
        ]);
    }
}
