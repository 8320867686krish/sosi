<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ClientContoller extends Controller
{
    public function index()
    {
        $client = Client::get();
        $modifiedowners = $client->map(function ($item) {
            $item->imagePath = asset("/images/client/{$item->image}");
            return $item;
        });
        return view('client.client', ['clients' => $modifiedowners]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('client.clientAdd', ['head_title' => 'Add', 'button' => 'Save']);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        print_r($request->file('manager_logo'));
        dd($request->file('owner_logo'));
        try {
            $id = $request->input('id');
            $inputData = $request->input();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                // Use the move method to save the image
                $image->move(public_path('images/client'), $imageName);
                $inputData['image'] = $imageName;

                // Delete old image if exists
                $oldImage = Client::where('id', $id)->value('image');
                if ($oldImage && file_exists(public_path('images/client/' . $oldImage))) {
                    unlink(public_path('images/client/' . $oldImage));
                }
            }

            Client::updateOrCreate(['id' => $id], $inputData);

            $message = empty($id) ? "Client added successfully" : "Client updated successfully";

            return redirect('clients')->with('message', $message);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    public function edit(string $id)
    {
        try {
            $client = Client::find($id);
            $client->imagePath = asset("/images/client/{$client->image}");
            return view('client.clientAdd', ['head_title' => 'Edit', 'button' => 'Update', 'client' => $client]);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $projectClient = Client::findOrFail($id);

            $imagePath = public_path("images/client/{$projectClient->image}");

            if ($imagePath && File::exists($imagePath)) {
                File::delete($imagePath);
            }

            $projectClient->delete();

            return redirect('clients')->with('message', 'Client deleted successfully');
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage());
        }
    }
}
