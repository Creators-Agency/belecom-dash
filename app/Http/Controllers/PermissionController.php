<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\UserPermission;

use SweetAlert;
use Redirect;

use Illuminate\Http\Request;

/*
 *                          Set permission
 * =========================================================
 *                      CRUD Operations 
 * ---------------------------------------------------------
 *  Model: UserPermission, Permission
 * ---------------------------------------------------------
 *  Addtional info:
 * *********************************************************
 */

class PermissionController extends Controller
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
    public function stockUpdate(Request $request)
    {
        // return $request->read == 'on' ? 1:0;
        /**
         * check if user exist in the table 
         */
        $data = UserPermission::where('userID',$request->user)->where('permissionID',$request->permID)->first();
        if(empty($data) ){
            $new = new UserPermission();
            $new->userID = $request->user;
            $new->permissionID = $request->permID;
            $new->create = $request->create == 'on' ? 1:0;
            $new->read = $request->read == 'on' ? 1:0;
            $new->update = $request->update == 'on' ? 1:0;
            $new->delete = $request->delete == 'on' ? 1:0;
            $new->isActive = 1;
            if($new->save()){
                alert()->success('Permission added Successful', 'Done');
                return Redirect('/staff');
            }else{
                alert()->error('Unable to set permission', 'Oops');
                return Redirect::back();
            }
        }else{
            $update = UserPermission::where('userID',$request->user)->where('permissionID',$request->permID)
                ->update([
                    'userID' => $request->user,
                    'permissionID' => $request->permID,
                    'create' => $request->create == 'on' ? 1:0,
                    'read' => $request->read == 'on' ? 1:0,
                    'update' => $request->update == 'on' ? 1:0,
                    'delete' => $request->delete == 'on' ? 1:0,
                ]);
                if($update){
                    alert()->success('Permission updated Successful', 'Done');
                    return Redirect('/staff');
                }else{
                    alert()->error('Unable to update permission', 'Oops');
                    return Redirect::back();
                }
        }
    }

    public function clientUpdate(Request $request)
    {
        /**
         * check if user exist in the table 
         */
        $data = UserPermission::where('userID',$request->user)->where('permissionID',$request->permID)->first();
        if(empty($data) ){
            $new = new UserPermission();
            $new->userID = $request->user;
            $new->permissionID = $request->permID;
            $new->create = $request->create == 'on' ? 1:0;
            $new->read = $request->read == 'on' ? 1:0;
            $new->update = $request->update == 'on' ? 1:0;
            $new->delete = $request->delete == 'on' ? 1:0;
            $new->isActive = 1;
            if($new->save()){
                alert()->success('Permission added Successful', 'Done');
                return Redirect('/staff');
            }else{
                alert()->error('Unable to set permission', 'Oops');
                return Redirect::back();
            }
        }else{
            $update = UserPermission::where('userID',$request->user)->where('permissionID',$request->permID)
                ->update([
                    'userID' => $request->user,
                    'permissionID' => $request->permID,
                    'create' => $request->create == 'on' ? 1:0,
                    'read' => $request->read == 'on' ? 1:0,
                    'update' => $request->update == 'on' ? 1:0,
                    'delete' => $request->delete == 'on' ? 1:0,
                ]);
                if($update){
                    alert()->success('Permission updated Successful', 'Done');
                    return Redirect('/staff');
                }else{
                    alert()->error('Unable to update permission', 'Oops');
                    return Redirect::back();
                }
        }
    }

    public function staffUpdate(Request $request)
    {
        /**
         * check if user exist in the table 
         */
        $data = UserPermission::where('userID',$request->user)->where('permissionID',$request->permID)->first();
        if(empty($data) ){
            $new = new UserPermission();
            $new->userID = $request->user;
            $new->permissionID = $request->permID;
            $new->create = $request->create == 'on' ? 1:0;
            $new->read = $request->read == 'on' ? 1:0;
            $new->update = $request->update == 'on' ? 1:0;
            $new->delete = $request->delete == 'on' ? 1:0;
            $new->isActive = 1;
            if($new->save()){
                alert()->success('Permission added Successful', 'Done');
                return Redirect('/staff');
            }else{
                alert()->error('Unable to set permission', 'Oops');
                return Redirect::back();
            }
        }else{
            $update = UserPermission::where('userID',$request->user)->where('permissionID',$request->permID)
                ->update([
                    'userID' => $request->user,
                    'permissionID' => $request->permID,
                    'create' => $request->create == 'on' ? 1:0,
                    'read' => $request->read == 'on' ? 1:0,
                    'update' => $request->update == 'on' ? 1:0,
                    'delete' => $request->delete == 'on' ? 1:0,
                ]);
                if($update){
                    alert()->success('Permission updated Successful', 'Done');
                    return Redirect('/staff');
                }else{
                    alert()->error('Unable to update permission', 'Oops');
                    return Redirect::back();
                }
        }
    }

    public function paymentkUpdate(Request $request)
    {
        /**
         * check if user exist in the table 
         */
        $data = UserPermission::where('userID',$request->user)->where('permissionID',$request->permID)->first();
        if(empty($data) ){
            $new = new UserPermission();
            $new->userID = $request->user;
            $new->permissionID = $request->permID;
            $new->create = $request->create == 'on' ? 1:0;
            $new->read = $request->read == 'on' ? 1:0;
            $new->update = $request->update == 'on' ? 1:0;
            $new->delete = $request->delete == 'on' ? 1:0;
            $new->isActive = 1;
            if($new->save()){
                alert()->success('Permission added Successful', 'Done');
                return Redirect('/staff');
            }else{
                alert()->error('Unable to set permission', 'Oops');
                return Redirect::back();
            }
        }else{
            $update = UserPermission::where('userID',$request->user)->where('permissionID',$request->permID)
                ->update([
                    'userID' => $request->user,
                    'permissionID' => $request->permID,
                    'create' => $request->create == 'on' ? 1:0,
                    'read' => $request->read == 'on' ? 1:0,
                    'update' => $request->update == 'on' ? 1:0,
                    'delete' => $request->delete == 'on' ? 1:0,
                ]);
                if($update){
                    alert()->success('Permission updated Successful', 'Done');
                    return Redirect('/staff');
                }else{
                    alert()->error('Unable to update permission', 'Oops');
                    return Redirect::back();
                }
        }
    }

    public function Upate(Request $request)
    {
        /**
         * check if user exist in the table 
         */
        $data = UserPermission::where('userID',$request->user)->where('permissionID',$request->permID)->first();
        if(empty($data) ){
            $new = new UserPermission();
            $new->userID = $request->user;
            $new->permissionID = $request->permID;
            $new->create = $request->create == 'on' ? 1:0;
            $new->read = $request->read == 'on' ? 1:0;
            $new->update = $request->update == 'on' ? 1:0;
            $new->delete = $request->delete == 'on' ? 1:0;
            $new->isActive = 1;
            if($new->save()){
                alert()->success('Permission added Successful', 'Done');
                return Redirect::back();
            }else{
                alert()->error('Unable to set permission', 'Oops');
                return Redirect::back();
            }
        }else{
            $update = UserPermission::where('userID',$request->user)->where('permissionID',$request->permID)
                ->update([
                    'userID' => $request->user,
                    'permissionID' => $request->permID,
                    'create' => $request->create == 'on' ? 1:0,
                    'read' => $request->read == 'on' ? 1:0,
                    'update' => $request->update == 'on' ? 1:0,
                    'delete' => $request->delete == 'on' ? 1:0,
                ]);
                if($update){
                    alert()->success('Permission updated Successful', 'Done');
                    return Redirect('/staff');
                }else{
                    alert()->error('Unable to update permission', 'Oops');
                    return Redirect::back();
                }
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}