<?php

namespace App\Http\Controllers;

use App\Contracts\JSONFormatter;
use App\Facades\CustomJsend;
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
        return CustomJsend::success([
            'attachments' => $attachments,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->createRules());

        $file = $request->file('file');

        $path = Storage::disk('local')->putFile(self::FILES_PATH, $file);

        $attachment = Auth::user()->attachments()->create([
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'size' => $file->getSize(),
            'path' => $path,
            'format' => $file->getClientOriginalExtension(),
        ]);
        return CustomJsend::success([
            'attachment' => $attachment,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $file = Attachment::find($id);

        if (($response = $this->checkFile($file)) !== true) return $response;

        return CustomJsend::success([
            'attachment' => $file
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $attachment = Attachment::find($id);

        if (($response = $this->checkFile($attachment)) !== true) return $response;

        $request->validate($this->updateRules());

        $attachment->update([
            'name' => $request->name,
        ]);

        return CustomJsend::success([
            'attachment' => $attachment
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $file = Attachment::find($id);

        if (($response = $this->checkFile($file)) !== true) return $response;

        $file->delete();

        return CustomJsend::success();
    }

    public function download(int $id)
    {
        $file = Attachment::find($id);

        if (($response = $this->checkFile($file)) !== true) return $response;

        if (!Storage::disk('local')->exists($file->path)) {
            return CustomJsend::error('File not found', 404);
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

    private function checkFile($file)
    {
        if ($file === null) return CustomJsend::error('File not found', 404);

        if (Auth::id() !== $file->user_id) return CustomJsend::error('Access denied', 403);

        return true;
    }
}
