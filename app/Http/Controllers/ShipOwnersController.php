<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipOwnerRequest;
use App\Models\shipOwners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ShipOwnersController extends Controller
{
    public function index()
    {
        $owners = shipOwners::get();
       
        $modifiedowners = $owners->map(function ($item) {
            $item->imagePath = asset("/images/ship/owner/{$item->image}");
            return $item;
        });
        return view('shipOwners.owner', ['owners' => $modifiedowners]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shipOwners.add', ['head_title' => 'Add', 'button' => 'Add Owner']);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(ShipOwnerRequest $request)
    {
        try {
            $id = $request->input('id');
            $inputData = $request->input();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                // Use the move method to save the image
                $image->move(public_path('images/ship/owner'), $imageName);
                $inputData['image'] = $imageName;

                // Delete old image if exists
                $oldImage = shipOwners::where('id', $id)->value('image');
                if ($oldImage && file_exists(public_path('images/ship/owner/' . $oldImage))) {
                    unlink(public_path('images/ship/owner/' . $oldImage));
                }
            }

            shipOwners::updateOrCreate(['id' => $id], $inputData);

            $message = empty($id) ? "Owner added successfully" : "Owner updated successfully";

            return redirect(url('shipOwners'))->with('message', $message);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        try {
            $owner = shipOwners::find($id);
            $owner->imagePath = asset("/images/ship/owner/{$owner->image}");
            return view('shipOwners.add', ['head_title' => 'Edit', 'button' => 'Update', 'owner' => $owner]);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $projectOwner = shipOwners::findOrFail($id);

            $imagePath = public_path("images/ship/owner/{$projectOwner->image}");

            if ($imagePath && File::exists($imagePath)) {
                File::delete($imagePath);
            }

            $projectOwner->delete();

            return redirect('shipOwners')->with('message', 'Owner deleted successfully');
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }
}
