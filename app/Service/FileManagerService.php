<?php

namespace App\Service;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FileManagerService
{
    public static function saveFile($file, $folder, $post_id, $model, $file_type): void
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = uniqid() . Carbon::now()->format('s-i-H-d-m-Y') . '.' . $extension;
        $main_image = $file->storeAs(
            "$folder/$post_id",
            $fileName,
            'public'
        );
        $main_image_url = Storage::url($main_image);

        File::create([
            'type' => $file_type,
            'model' => $model,
            'model_id' => $post_id,
            'src' => $main_image_url,
            'adder' => auth()->user()->id
        ]);
    }

    public static function getFile($post_id, $model, $file_type): ?File
    {
        return File::where('type', $file_type)
            ->where('model', $model)
            ->where('model_id', $post_id)
            ->latest()
            ->first();
    }

    public static function deleteFile($post_id, $model, $file_type): void
    {
        File::where('type', $file_type)
            ->where('model', $model)
            ->where('model_id', $post_id)
            ->delete();
    }
}