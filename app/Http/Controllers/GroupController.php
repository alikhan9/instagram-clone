<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Rules\UserIds;
use Exception;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * @throws Exception
     */
    public function store(Request $request)
    {
        $request->validate([
            'members' => ['required', 'array', new UserIds]
        ]);

        $group = Group::create(['owner_id' => auth()->user()->id]);

        foreach ($request->members as $user) {
            GroupMember::create([
                'group_id' => $group->id,
                'user_id' => $user->id
            ]);
        }

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => auth()->id()
        ]);

        return redirect('/direct/g/' . $group->id);
    }

    public function destroy(Group $group)
    {
        if ($group->owner_id !== auth()->user()->id) {
            abort(403);
        }
        $group->delete();
    }

    public function add(Request $request, Group $group)
    {
        if ($group->owner_id !== auth()->user()->id) {
            abort(403);
        }

        $values = $request->validate([
            'user_id' => 'required|numeric|exists:users,id'
        ]);

        $group->members()->attach($values->user_id);
    }

    public function remove(Request $request, Group $group)
    {

        if ($group->owner_id !== auth()->user()->id) {
            abort(403);
        }

        $values = $request->validate([
            'user_id' => 'required|numeric|exists:users,id'
        ]);

        $group->members()->detach($values->user_id);
    }

    public function update(Request $request, Group $group)
    {

        if ($group->owner_id !== auth()->user()->id) {
            abort(403);
        }
        $request->validate([
            'user_id' => 'required|numeric|exists:users,id'
        ]);

        $group->update(['owner_id' => $request->user_id]);
    }


    public function check(Request $request)
    {
        $values = $request->validate([
            'group_id' => 'required|numeric|exists:groups,id'
        ]);

        auth()->user()->unreadNotifications()->where('data', 'like', '%' . '"group_id":' . $values->group_id . '%')->delete();
    }

}
