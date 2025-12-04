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
        // 添加日志记录
        \Log::info('FileController@store called', [
            'post_id' => $post->id,
            'files' => $request->file('file'),
            'request_method' => $request->method(),
            'request_url' => $request->url(),
        ]);

        // 验证规则修改为支持数组
        $request->validate([
            'file.*' => 'required|file|max:10240', // 10MB limit
        ]);

        $files = $request->file('file');
        // 确保files是数组
        $files = is_array($files) ? $files : [$files];
        $uploadedFiles = [];

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('posts/' . $post->id, Str::random(40) . '.' . $file->getClientOriginalExtension(), 'public');

            $fileModel = File::create([
                'name' => $fileName,
                'path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'post_id' => $post->id,
            ]);

            $uploadedFiles[] = $fileModel;
        }

        return response()->json($uploadedFiles);
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
