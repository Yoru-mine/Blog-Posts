<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:51200',
        ]);

        $imageFile = $request->file('file');

        $imageManager = new ImageManager(new Driver);

        $image = $imageManager->read($imageFile);

        $image->resize(800, 600);

        $folder = date('Y/m/d');
        $filename = time().'.'.$imageFile->getClientOriginalExtension();
        $fullPath = public_path("uploads/$folder");

        if (! file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        Storage::disk('public')->put("uploads/$filename", (string) $image->encode());

        return back()->with('status', "Изображение сохранено как uploads/$folder/$filename");
    }
}
