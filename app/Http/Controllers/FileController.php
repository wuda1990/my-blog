<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        // 验证规则支持单个文件和文件数组
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB limit
        ], [
            'file.max' => '文件大小不能超过 10MB',
            'file.required' => '请选择要上传的文件',
            'file.file' => '请选择有效的文件',
        ]);

        $files = $request->file('file');
        // 确保files是数组
        $files = is_array($files) ? $files : [$files];

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('posts/' . $post->id, Str::random(40) . '.' . $file->getClientOriginalExtension(), 'public');

            File::create([
                'name' => $fileName,
                'path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'post_id' => $post->id,
            ]);
        }

        // 返回重定向响应，让Inertia处理页面刷新
        return redirect()->back();
    }

    public function destroy(File $file)
    {
        // 添加日志记录
        \Log::info('FileController@destroy called', [
            'file_id' => $file->id,
            'file_name' => $file->name,
            'file_path' => $file->path,
        ]);

        try {
            // 删除文件
            Storage::disk('public')->delete($file->path);
            // 删除数据库记录
            $file->delete();
            
            \Log::info('File deleted successfully', [
                'file_id' => $file->id,
            ]);

            // 返回重定向响应，让Inertia处理
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Failed to delete file', [
                'file_id' => $file->id,
                'error' => $e->getMessage(),
            ]);
            // 返回重定向响应，让Inertia处理
            return redirect()->back()->with('error', '删除文件失败: ' . $e->getMessage());
        }
    }

    public function download(File $file)
    {
        return Storage::disk('public')->download($file->path, $file->name);
    }
}
