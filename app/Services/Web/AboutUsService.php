<?php

namespace App\Services\Web;

use App\Models\Web\AboutUs;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutUsService
{
    use FileUploader;

    /**
     * Retrieve the About Us content.
     *
     * @param Request $request
     * @return AboutUs|null
     */
    public function getAboutUsContent(Request $request): ?AboutUs
    {
        $query = AboutUs::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed());

        return $query->first();
    }

    /**
     * Store or update the About Us content.
     *
     * @param array $data
     * @param Request $request
     * @return AboutUs
     * @throws Exception
     */
    public function saveAboutUsContent(array $data, Request $request): AboutUs
    {
        return DB::transaction(function () use ($data, $request) {
            $aboutUs = AboutUs::query()->firstOrNew();

            $aboutUs->fill($data);
            $aboutUs->status = $data['status'] ?? true;

            // Handle main image upload/update
            $aboutUs->attach = $this->updateImage($request, 'attach', 'about-us', null, 800, $aboutUs, 'attach');
            // Handle vision image upload/update
            $aboutUs->vision_image = $this->updateImage($request, 'vision_image', 'about-us/vision', 500, 280, $aboutUs, 'vision_image');
            // Handle mission image upload/update
            $aboutUs->mission_image = $this->updateImage($request, 'mission_image', 'about-us/mission', 500, 280, $aboutUs, 'mission_image');

            // Set created_by/updated_by if user is authenticated
            if (auth()->check()) {
                if (!$aboutUs->exists) {
                    $aboutUs->created_by = auth()->id();
                }
                $aboutUs->updated_by = auth()->id();
            }

            $aboutUs->save();

            return $aboutUs;
        });
    }
}
