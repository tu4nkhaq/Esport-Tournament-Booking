<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::orderBy('created_at','desc')->simplePaginate(5);
        return view('admin.teams.index',compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('admin.teams.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name'=>'required',
            'logo'=>'required|image',
            'user_id'=>'required',
            'player_1'=>'required',
            'player_2'=>'required',
            'player_3'=>'required',
            'player_4'=>'required'
        ]);
        $user_id = $formFields['user_id'];
        $exists = Team::where('user_id',$user_id)->first();
        if($exists){
            return redirect()->route('teams')->with('message','Team is already exists');
        }
        if($request->hasFile('logo')){
            $logoteam= $request->file('logo');
            $uniqueName=time().'-'.Str::random(10).'.'.$logoteam->getClientoriginalExtension();
            $logoteam->move('team_logo',$uniqueName);
            $formFields['logo']="team_logo/".$uniqueName;
        }
        Team::create($formFields);
        return redirect()->route('teams')->with('message','Your team has been updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = Team::findOrFail($id);
        $users = User::all();
        return view('admin.teams.edit',compact('team','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $teams = Team::findOrFail($id);
        //if user select new image
        if($request->hasFile('image')){
            $formFields = $request->validate([
                'name'=>'required',
                'logo'=>'required|image',
                'user_id'=>'required',
                'player_1'=>'required',
                'player_2'=>'required',
                'player_3'=>'required',
                'player_4'=>'required'
            ]);
            $image= $request->file('image');
            $uniqueName=time().'-'.Str::random(10).'.'.$image->getClientoriginalExtension();
            $image->move('users',$uniqueName);
            $formFields['image']="users/".$uniqueName;
            Storage::delete($teams->id);
            $teams->update($formFields);
        }
        //if user didn't select new image
        $fields = $request->validate([
            'name'=>'required',
            'user_id'=>'required',
            'player_1'=>'required',
            'player_2'=>'required',
            'player_3'=>'required',
            'player_4'=>'required'
        ]);
        $teams->update($fields);
        return redirect()->route('teams')->with('message','Team has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        return redirect()->route('teams')->with('message','Team has been deleted successfully');
    }
}
