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
    public function store(ClientRequest $request)
    {
        try {
            $id = $request->input('id');
            $inputData = $request->input(); // Assuming validation rules are defined in ClientRequest

            // Handle manager logo
            if ($request->hasFile('manager_logo')) {
                $inputData['manager_logo'] = $this->uploadLogo($request->file('manager_logo'));
                // If 'same_as_above' is checked, set owner_logo too
                if ($request->has('same_as_above')) {
                    $inputData['owner_logo'] = $inputData['manager_logo'];
                }
            }

            // Handle owner logo
            if ($request->hasFile('owner_logo') && !$request->has('same_as_above')) {
                $inputData['owner_logo'] = $this->uploadLogo($request->file('owner_logo'));
            } elseif ($request->has('same_as_above') && $id) {
                // If 'same_as_above' is checked and ID is provided, update owner_logo with manager_logo
                $inputData['owner_logo'] = Client::where('id', $id)->value('manager_logo');
            }

            // Update or create client
            unset($inputData['same_as_above']);
            $client = Client::updateOrCreate(['id' => $id], $inputData);

            // Delete old images
            if ($id) {
                $this->deleteOldImages($id, $client->manager_logo, $client->owner_logo);
            }

            $message = empty($id) ? "Client added successfully" : "Client updated successfully";

            return redirect('clients')->with('message', $message);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    private function deleteOldImages($id, $managerLogo, $ownerLogo)
    {
        if ($managerLogo && $managerLogo != $ownerLogo) {
            $this->deleteLogo($managerLogo);
        }

        if ($ownerLogo) {
            $this->deleteLogo($ownerLogo);
        }
    }

    private function uploadLogo($file)
    {
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/client'), $imageName);
        return $imageName;
    }

    private function deleteLogo($imageName)
    {
        if ($imageName && file_exists(public_path('images/client/' . $imageName))) {
            unlink(public_path('images/client/' . $imageName));
        }
    }


    public function edit(string $id)
    {
        try {
            $client = Client::find($id);
            $client->managerLogoPath = asset("/images/client/{$client->manager_logo}");
            $client->ownerLogoPath = asset("/images/client/{$client->owner_logo}");
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
