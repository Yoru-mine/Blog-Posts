<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FileUploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120',
        ]);

        $imageFile = $request->file('file');

        try {
            $imageManager = new ImageManager(new Driver);
            $image = $imageManager->read($imageFile->getRealPath());

            // Только уменьшаем, маленькие картинки не растягиваем
            $image->scaleDown(width: 800);

            $encodedImage = $image->toWebp(85);

            $filename = Str::random(40) . '.webp';
            $folder = date('Y/m/d');

            $storagePath = "uploads/$folder/$filename";

            Storage::disk('public')->put($storagePath, (string) $encodedImage);

            return back()->with('status', "Изображение успешно сохранено по пути: storage/$storagePath");
        } catch (\Exception $e) {
            Log::error('Ошибка обработки изображения в FileUploadController: ' . $e->getMessage());

            return back()->withErrors(['file' => 'Не удалось обработать и сохранить изображение.']);
        }
    }
}
