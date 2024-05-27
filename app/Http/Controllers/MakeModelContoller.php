<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeModelRequest;
use App\Models\Hazmat;
use App\Models\MakeModel;
use Illuminate\Http\Request;

class MakeModelContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = MakeModel::with('hazmat:id,name')->get();

        return view('makeModel.makeModel', ['models'=>$models]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hazmat = Hazmat::get(['id', 'name']);

        return view('makeModel.makeModelAdd', ['hazmats'=>$hazmat, 'button' => 'Save', 'head_title' => 'Add']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MakeModelRequest $request)
    {
        try {
            $id = $request->input('id');
            $inputData = $request->input();

            if ($request->hasFile('document1')) {
                if (!empty($id)) {
                    $this->deleteOldDocument($id, 'document1');
                }
                $inputData['document1'] = $this->uploadDocument($request->file('document1'));
            }

            if ($request->hasFile('document2')) {
                if (!empty($id)) {
                    $this->deleteOldDocument($id, 'document2');
                }
                $inputData['document2'] = $this->uploadDocument($request->file('document2'));
            }

            MakeModel::updateOrCreate(['id' => $id], $inputData);

            $message = empty($id) ? "Document added successfully" : "Document updated successfully";

            return response()->json(['isStatus' => true, 'message' => $message]);
        } catch (\Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hazmat = Hazmat::get(['id', 'name']);

        $model = MakeModel::find($id);

        return view('makeModel.makeModelAdd', ['hazmats'=>$hazmat, 'model'=>$model, 'button' => 'Save Change', 'head_title' => 'Edit']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $makemodel = MakeModel::findOrFail($id);

            if (!$makemodel) {
                return response()->json(['isStatus' => false, 'message' => 'This document not found']);
            }

            $this->deleteDocument($makemodel->document1['name']);

            $this->deleteDocument($makemodel->document2['name']);

            $makemodel->delete();

            return response()->json(['isStatus' => true, 'message' => 'Document deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'Document not deleted successfully']);
        }
    }

    private function deleteOldDocument($id, $document)
    {
        $model = MakeModel::findOrFail($id);

        if ($model->$document) {
            $file = $model->$document['name'];
            $this->deleteDocument($file);
        }
    }

    private function uploadDocument($file)
    {
        $imageName = "document_" . time() . rand(10, 99) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/modelDocument'), $imageName);
        return $imageName;
    }

    private function deleteDocument($imageName)
    {
        if(!empty($imageName)){
            $path = public_path('images/modelDocument/' . $imageName);
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
