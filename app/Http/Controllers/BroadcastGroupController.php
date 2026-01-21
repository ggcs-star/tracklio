<?php

namespace App\Http\Controllers;

use App\Models\BroadcastGroup;
use App\Models\BroadcastGroupContact;
use App\Models\Contact;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BroadcastGroupController extends Controller
{
    /**
     * GET /broadcast-groups
     */
    public function index()
    {
        $userId = (string) Auth::id();

        $groups = BroadcastGroup::where('user_id', $userId)
            ->latest()
            ->get()
            ->map(function ($group) use ($userId) {

                $contactIds = BroadcastGroupContact::where('group_id', (string)$group->_id)
                    ->pluck('contact_id')
                    ->toArray();

                $contacts = Contact::whereIn('_id', $contactIds)->get();

                return [
                    'id'             => (string) $group->_id,
                    'name'           => $group->name,
                    'description'    => $group->description,
                    'contacts_count' => $contacts->count(),
                    'contacts'       => $contacts->map(fn ($c) => [
                        'id'    => (string)$c->_id,
                        'name'  => $c->name ?? 'Unknown',
                        'phone' => $c->phone_number,
                    ]),
                    'contact_ids'    => $contactIds,
                    'created_at'     => $group->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $groups,
        ]);
    }

    /**
     * POST /broadcast-groups
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_ids' => 'required|array|min:1',
        ]);

        $userId = (string) Auth::id();

        $group = BroadcastGroup::create([
            'user_id'     => $userId,
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        foreach ($request->contact_ids as $contactId) {
            BroadcastGroupContact::create([
                'user_id'    => $userId,
                'group_id'   => (string) $group->_id,
                'contact_id' => $contactId,
            ]);
        }

        return $this->index();
    }

    /**
     * PUT /broadcast-groups/{id}
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'contact_ids' => 'required|array|min:1',
        ]);
       

        $group = BroadcastGroup::findOrFail($id);

        $group->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        // remove old links
        BroadcastGroupContact::where('group_id', (string)$id)->delete();

        // add new links
        foreach ($request->contact_ids as $contactId) {
            BroadcastGroupContact::create([
                'user_id'    => (string) Auth::id(),
                'group_id'   => (string)$id,
                'contact_id' => $contactId,
            ]);
        }
        Notification::create([
            'user_id' => (string) Auth::id(),
            'type'    => 'broadcast_group_updated',
            'message' => 'Broadcast group updated successfully',
            'is_read' => false,
        ]);

        return $this->index();
    }

    /**
     * DELETE /broadcast-groups/{id}
     */
    public function destroy($id)
    {
        BroadcastGroup::where('_id', $id)->delete();
        BroadcastGroupContact::where('group_id', (string)$id)->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * DELETE /broadcast-groups/{groupId}/contacts/{contactId}
     */
    public function removeContact($groupId, $contactId)
    {
        BroadcastGroupContact::where('group_id', (string)$groupId)
            ->where('contact_id', $contactId)
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * POST /broadcast-groups/send
     */
    public function send(Request $request)
    {
        $request->validate([
            'group_id' => 'required|string',
            'template' => 'required|string',
        ]);

        $contacts = BroadcastGroupContact::where('group_id', $request->group_id)
            ->pluck('contact_id')
            ->toArray();

        $numbers = Contact::whereIn('_id', $contacts)
            ->pluck('phone_number')
            ->toArray();

        // 🔥 yahin se tum WhatsApp API call karoge
        Log::info('Broadcast send', [
            'group_id' => $request->group_id,
            'count'    => count($numbers),
        ]);
        $count = count($numbers);

Notification::create([
    'user_id' => (string) Auth::id(),
    'type'    => 'broadcast_sent',
    'message' => "Broadcast sent to {$count} contacts",
    'is_read' => false,
]);


        return response()->json([
            'success' => true,
            'sent'    => count($numbers),
        ]);
    }
}
