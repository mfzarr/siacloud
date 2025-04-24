<?php

namespace App\Http\Controllers\Masterdata;

use App\Models\Masterdata\Roles;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRolesRequest;
use App\Http\Requests\UpdateRolesRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve data from the database
        $items = Roles::all(); // You can also use pagination if needed

        // Pass data to the view
        return view('masterdata.user_role.index', [
            'items' => $items,
            'table' => 'user_role'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRolesRequest $request)
    {
        $request->validate([
            'nama_role' => 'required',
        ]);

        Roles::create($request->all());

        return redirect()->route('user_role.index')->with('success', 'Roles created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Roles $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Roles::find($id);
        $menus = DB::table('user_access_menu')
        ->join('user_menu', 'user_access_menu.id_menu', '=', 'user_menu.id_menu')
        ->where('user_access_menu.id_role', $id)
        ->select('user_access_menu.*', 'user_menu.nama_menu')
        ->get();

        if (!$role) {
            return redirect()->route('user_role.index')->with('error', 'Role not found');
        }
    
        return view('masterdata.user_role.edit', compact('role', 'menus'));
    }  
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $roles = Roles::findOrFail($id);
        $roles->update($request->all());

        return response()->json(['success' => 'Data updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $roles = Roles::findOrFail($id);
        $roles->delete();
        return response()->json(['success' => true]);
    }
}
