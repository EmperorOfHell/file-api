<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;

class AttachmentController extends Controller
{
    private const FILES_PATH = 'files';

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attachments = Auth::user()->attachments()->get();
        return response()->json([
            'status' => 'success',
            'attachments' => $attachments,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->createRules());
        $file = $request->file('file');
        $path = Storage::disk('local')->putFile(self::FILES_PATH, $file);
        $fileDB = Auth::user()->attachments()->create([
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'size' => $file->getSize(),
            'path' => $path,
            'format' => $file->getClientOriginalExtension(),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'File uploaded successfully',
            'file' => $fileDB,

        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment $file)
    {
        if (Auth::id() !== $file->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json(['attachment' => $file]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attachment $file)
    {
        // Check if the authenticated user is the owner of the attachment
        if (Auth::id() !== $file->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate the request data
        $request->validate($this->updateRules());

        // Update the file record
        $file->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'File updated successfully',
            'attachment' => $file
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $file)
    {
        // Check if the authenticated user is the owner of the attachment
        if (Auth::id() !== $file->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the file record
        $file->delete();

        // You may also want to delete the actual file from storage if needed

        return response()->json([
            'message' => 'File deleted successfully',
        ]);
    }

    public function download(Attachment $file)
    {
        if (Auth::id() !== $file->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if (!Storage::disk('local')->exists($file->path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Generate a download response
        return response()->download(storage_path('app/' . $file->path), "{$file->name}.{$file->format}");
    }

    private function updateRules(): array
    {
        return [
            'name' => 'required|max:255',

        ];
    }

    private function createRules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240'
        ];
    }
}
