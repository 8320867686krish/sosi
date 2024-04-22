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
        $clients = Client::select('id', 'manager_name', 'manager_contact_person_name', 'manager_logo', 'owner_name')
        ->withCount('projects')
        ->get();

        return view('client.client', ['clients' => $clients]);
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
            $inputData = $request->all();
            $inputData['isSameAsManager'] = $request->has('isSameAsManager') ? 1 : 0;

            if ($request->hasFile('manager_logo')) {
                $inputData['manager_logo'] = $this->uploadLogo($request->file('manager_logo'));

                if ($inputData['isSameAsManager']) {
                    $inputData['owner_logo'] = $inputData['manager_logo'];
                }

                if (!empty($id)) {
                    $this->deleteOldImages($id);
                }
            }

            if ($request->hasFile('owner_logo') && !$inputData['isSameAsManager']) {
                $inputData['owner_logo'] = $this->uploadLogo($request->file('owner_logo'));
            } elseif ($inputData['isSameAsManager'] && $id) {
                $inputData['owner_logo'] = Client::where('id', $id)->value('manager_logo');
            }

            Client::updateOrCreate(['id' => $id], $inputData);

            $message = empty($id) ? "Client added successfully" : "Client updated successfully";

            // return redirect('clients')->with('message', $message);
            return response()->json(['isStatus' => true, 'message' => $message]);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    private function deleteOldImages($id)
    {
        $client = Client::findOrFail($id);

        if ($client->manager_logo) {
            $this->deleteLogo($client->manager_logo);
        }

        if ($client->owner_logo && $client->owner_logo !== $client->manager_logo) {
            $this->deleteLogo($client->owner_logo);
        }
    }

    private function uploadLogo($file)
    {
        $imageName = time() . rand(10, 99) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/client'), $imageName);
        return $imageName;
    }

    private function deleteLogo($imageName)
    {
        $path = public_path('images/client/' . $imageName);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function edit(string $id)
    {
        try {
            $client = Client::find($id);
          
            return view('client.clientAdd', ['head_title' => 'Edit', 'button' => 'Update', 'client' => $client]);
        } catch (\Throwable $th) {
            return back()->withError($th->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {

            $projectClient = Client::findOrFail($id);

            // Check if the client has associated projects
            if ($projectClient->projects()->exists()) {
                return response()->json(['isStatus' => false, 'message' => 'Cannot delete client. Client has associated projects.']);
            }

            $this->deleteOldImages($id);
            $projectClient->delete();

            // return redirect('clients')->with('message', 'Client deleted successfully');
            return response()->json(['isStatus' => true, 'message' => 'Client deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'Client not deleted successfully']);
            // return back()->withError($th->getMessage());
        }
    }
}
