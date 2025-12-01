<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB limit
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('posts/' . $post->id, Str::random(40) . '.' . $file->getClientOriginalExtension(), 'public');

        $fileModel = File::create([
            'name' => $fileName,
            'path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'post_id' => $post->id,
        ]);

        return response()->json($fileModel);
    }

    public function destroy(File $file)
    {
        Storage::disk('public')->delete($file->path);
        $file->delete();

        return response()->json(['success' => true]);
    }

    public function download(File $file)
    {
        return Storage::disk('public')->download($file->path, $file->name);
    }
}
