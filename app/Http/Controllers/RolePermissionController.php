<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::where('name', '!=', 'Superadmin')->get()->toArray();
        $permissions = Permission::orderBy('category_name', 'asc')
            ->orderBy('method_name', 'asc')
            ->get(['id', 'name', 'category_name', 'method_name'])->toArray();
        $permissions_categories = Permission::select('category_name')
            ->groupBy('category_name')->orderBy('category_name', 'asc')->get()->pluck('category_name')->toArray();
        return Inertia::render('Settings/RolePermission', [
            'roles' => $roles,
            'permissions' => $permissions,
            'permissions_categories' => $permissions_categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function assignPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required',
            'role_id' => 'required',
        ], [
            'permission_id.required' => 'Please select user',
            'role_id.required' => 'Please select role',
        ]);
        if ($validator->fails()) {

            return response($validator->errors(), 422);
        }
        $input = $request->only(['permission_id', 'role_id']);
        DB::beginTransaction();
        try {
            $role = Role::find($input['role_id']);
            $permission = Permission::find($input['permission_id']);
            $result = $role->givePermissionTo($permission->name);
            DB::commit();

            return response()->json([
                'message' => 'Permission assigned to role!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }

    public function revokePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required',
            'role_id' => 'required',
        ], [
            'permission_id.required' => 'Please select user',
            'role_id.required' => 'Please select role',
        ]);
        if ($validator->fails()) {

            return response($validator->errors(), 422);
        }
        $input = $request->only(['permission_id', 'role_id']);
        DB::beginTransaction();
        try {
            $role = Role::find($input['role_id']);
            $permission = Permission::find($input['permission_id']);
            $result = $role->revokePermissionTo($permission->name);
            DB::commit();

            return response()->json([
                'message' => 'Permission revoked!'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $th) {
            DB::rollback();
        }
    }

    public function roleHasPermissions($role_id)
    {
        $role = Role::find($role_id);
        return $role->permissions;
    }
}
