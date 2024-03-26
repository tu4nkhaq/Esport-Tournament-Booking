<?php

namespace App\Http\Controllers;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id =  auth()->user()->id;
        $exists = Team::where('user_id',$user_id)->first();
        if($exists){
            return redirect('/editprofile')->with('message','Your team is already exists');
        }
        $formFields = $request->validate([
            'name'=>'required',
            'logo'=>'required|image',
            'player_1'=>'required',
            'player_2'=>'required',
            'player_3'=>'required',
            'player_4'=>'required'
        ]);
        if($request->hasFile('logo')){
            $logoteam= $request->file('logo');
            $uniqueName=time().'-'.Str::random(10).'.'.$logoteam->getClientoriginalExtension();
            $logoteam->move('team_logo',$uniqueName);
            $formFields['logo']="team_logo/".$uniqueName;
        }
        Team::create($formFields + ['user_id'=>$user_id]);
        return redirect('/myprofile')->with('message','Your team has been updated');
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
        //
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
        $user_id = auth()->id();
        // Make sure logged in user is owner
        if($teams->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        //if user select new image
        if($request->hasFile('image')){
            $formFields = $request->validate([
                'name'=>'required',
                'logo'=>'required|image',
                'player_1'=>'required',
                'player_2'=>'required',
                'player_3'=>'required',
                'player_4'=>'required'
            ]);
            Storage::delete($teams->logo);
            $image= $request->file('image');
            $uniqueName=time().'-'.Str::random(10).'.'.$image->getClientoriginalExtension();
            $image->move('users',$uniqueName);
            $formFields['image']="users/".$uniqueName;
            $teams->update($formFields);
        }
        //if user didn't select new image
        $fields = $request->validate([
            'name'=>'required',
            'player_1'=>'required',
            'player_2'=>'required',
            'player_3'=>'required',
            'player_4'=>'required'
        ]);
        $teams->update($fields);
        return redirect('/myprofile')->with('message','Your profile has been updated');
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
        // Make sure logged in user is owner
         if($team->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        Storage::delete($team->logo);
        $team->delete();
        return redirect('/editprofile')->with('message','Team has been deleted successfully');
    }
}
