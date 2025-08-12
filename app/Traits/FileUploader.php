<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

trait FileUploader
{
    /**
     * Upload a media file to the specified directory and disk.
     *
     * @param Request $request The incoming HTTP request.
     * @param string $attach The name of the file input field (e.g., 'attach').
     * @param string $directory The subdirectory within the storage disk (e.g., 'page', 'news').
     * @param string|null $disk The storage disk to use (e.g., 'public', 's3').
     * @return string|null The stored file name, or null if no file was uploaded or extension is invalid.
     */
    public function uploadMedia(Request $request, string $attach, string $directory, string $disk = null): ?string
    {
        $disk = $disk ?? config('filesystems.default');

        if ($request->hasFile($attach)) {
            $file = $request->file($attach);

            if ($this->validateFile($file)) {
                $fileNameToStore = $this->generateFileName($file);
                Storage::disk($disk)->putFileAs($directory, $file, $fileNameToStore);

                // Log file upload
                Log::info("File uploaded: {$fileNameToStore} to {$directory} on {$disk} disk");

                return $fileNameToStore;
            }
        }
        return null;
    }

    /**
     * Upload multiple media files to the specified directory and disk.
     *
     * @param Request $request The incoming HTTP request.
     * @param string $attach The name of the file input field (e.g., 'attach').
     * @param string $directory The subdirectory within the storage disk.
     * @param string|null $disk The storage disk to use.
     * @return array Array of uploaded file names.
     */
    public function uploadMultipleMedia(Request $request, string $attach, string $directory, string $disk = null): array
    {
        $disk = $disk ?? config('filesystems.default');
        $uploadedFiles = [];

        if ($request->hasFile($attach)) {
            $files = is_array($request->file($attach)) ? $request->file($attach) : [$request->file($attach)];

            foreach ($files as $file) {
                if ($this->validateFile($file)) {
                    $fileNameToStore = $this->generateFileName($file);
                    Storage::disk($disk)->putFileAs($directory, $file, $fileNameToStore);
                    $uploadedFiles[] = $fileNameToStore;

                    Log::info("File uploaded: {$fileNameToStore} to {$directory} on {$disk} disk");
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Update a media file in the specified directory and disk, deleting the old one if it exists.
     *
     * @param Request $request The incoming HTTP request.
     * @param string $attach The name of the file input field (e.g., 'attach').
     * @param string $directory The subdirectory within the storage disk.
     * @param object $model The Eloquent model instance.
     * @param string|null $disk The storage disk to use.
     * @return string|null The new stored file name, or the old one if no new file, or null if old file was deleted and no new file.
     */
    public function updateMedia(Request $request, string $attach, string $directory, object $model, string $disk = null): ?string
    {
        $disk = $disk ?? config('filesystems.default');

        if ($request->hasFile($attach)) {
            // Backup old file before deletion (optional)
            $this->backupFile($directory, $model->attach, $disk);

            // Delete old file if it exists
            if ($model->attach && Storage::disk($disk)->exists($directory . '/' . $model->attach)) {
                Storage::disk($disk)->delete($directory . '/' . $model->attach);
            }
            return $this->uploadMedia($request, $attach, $directory, $disk);
        } elseif ($request->input('attach_removed')) { // Check for explicit removal flag
            $this->deleteMedia($directory, $model, $disk);
            return null;
        }
        return $model->attach; // Return existing file name if no new file and not marked for removal
    }

    /**
     * Delete a media file from the specified directory and disk.
     *
     * @param string $directory The subdirectory within the storage disk.
     * @param object $model The Eloquent model instance.
     * @param string|null $disk The storage disk to use.
     * @return bool True if the file was deleted, false otherwise.
     */
    public function deleteMedia(string $directory, object $model, string $disk = null): bool
    {
        $disk = $disk ?? config('filesystems.default');

        if ($model->attach && Storage::disk($disk)->exists($directory . '/' . $model->attach)) {
            // Backup file before deletion
            $this->backupFile($directory, $model->attach, $disk);

            Storage::disk($disk)->delete($directory . '/' . $model->attach);
            Log::info("File deleted: {$model->attach} from {$directory} on {$disk} disk");
            return true;
        }
        return false;
    }

    /**
     * Delete multiple media files from the specified directory and disk.
     *
     * @param string $directory The subdirectory within the storage disk.
     * @param array $fileNames Array of file names to delete.
     * @param string|null $disk The storage disk to use.
     * @return array Array of successfully deleted files.
     */
    public function deleteMultipleMedia(string $directory, array $fileNames, string $disk = null): array
    {
        $disk = $disk ?? config('filesystems.default');
        $deletedFiles = [];

        foreach ($fileNames as $fileName) {
            if ($fileName && Storage::disk($disk)->exists($directory . '/' . $fileName)) {
                $this->backupFile($directory, $fileName, $disk);
                Storage::disk($disk)->delete($directory . '/' . $fileName);
                $deletedFiles[] = $fileName;
                Log::info("File deleted: {$fileName} from {$directory} on {$disk} disk");
            }
        }

        return $deletedFiles;
    }

    /**
     * Update multiple media file in the specified directory and disk, deleting the old one if it exists.
     *
     * @param Request $request The incoming HTTP request.
     * @param string $attach The name of the file input field (e.g., 'attach').
     * @param string $directory The subdirectory within the storage disk.
     * @param object $model The Eloquent model instance.
     * @param string $field The model's attribute holding the file name (e.g., 'attach', 'image').
     * @param string|null $disk The storage disk to use.
     * @return string|null The new stored file name, or the old one if no new file, or null if old file was deleted and no new file.
     */
    public function updateMultiMedia(Request $request, string $attach, string $directory, object $model, string $field, string $disk = null): ?string
    {
        $disk = $disk ?? config('filesystems.default');

        if ($request->hasFile($attach)) {
            $file = $request->file($attach);

            if ($this->validateFile($file)) {
                // Delete old file if it exists
                if ($model->$field && Storage::disk($disk)->exists($directory . '/' . $model->$field)) {
                    $this->backupFile($directory, $model->$field, $disk);
                    Storage::disk($disk)->delete($directory . '/' . $model->$field);
                }

                $fileNameToStore = $this->generateFileName($file);
                Storage::disk($disk)->putFileAs($directory, $file, $fileNameToStore);

                Log::info("File updated: {$fileNameToStore} in {$directory} on {$disk} disk");
                return $fileNameToStore;
            } else {
                return $model->$field;
            }
        }
        return $model->$field;
    }

    /**
     * Delete multiple media file from the specified directory and disk.
     *
     * @param string $directory The subdirectory within the storage disk.
     * @param object $model The Eloquent model instance.
     * @param string $field The model's attribute holding the file name.
     * @param string|null $disk The storage disk to use.
     * @return bool True if the file was deleted, false otherwise.
     */
    public function deleteMultiMedia(string $directory, object $model, string $field, string $disk = null): bool
    {
        $disk = $disk ?? config('filesystems.default');

        if ($model->$field && Storage::disk($disk)->exists($directory . '/' . $model->$field)) {
            $this->backupFile($directory, $model->$field, $disk);
            Storage::disk($disk)->delete($directory . '/' . $model->$field);
            Log::info("File deleted: {$model->$field} from {$directory} on {$disk} disk");
            return true;
        }
        return false;
    }

    /**
     * Upload and resize an image file to the specified directory and disk using Intervention Image v3.
     *
     * @param Request $request The incoming HTTP request.
     * @param string $attach The name of the file input field (e.g., 'attach').
     * @param string $directory The subdirectory within the storage disk.
     * @param int|null $width The desired width for the image.
     * @param int|null $height The desired height for the image.
     * @param string|null $disk The storage disk to use.
     * @param int $quality The image quality (1-100).
     * @return string|null The stored file name, or null if no file was uploaded or extension is invalid.
     */
    public function uploadImage(Request $request, string $attach, string $directory, ?int $width = null, ?int $height = null, string $disk = null, int $quality = 90): ?string
    {
        $disk = $disk ?? config('filesystems.default');

        if ($request->hasFile($attach)) {
            $file = $request->file($attach);

            if ($this->validateImageFile($file)) {
                $fileNameToStore = $this->generateFileName($file);

                // Create an image manager with the desired driver
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());

                // Get original dimensions for logging
                $originalWidth = $image->width();
                $originalHeight = $image->height();

                if ($width || $height) {
                    $image->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // Get the appropriate encoder for the file extension
                $encoder = $this->getEncoder($file->getClientOriginalExtension(), $quality);

                // Store the image using the specified disk with proper encoder
                Storage::disk($disk)->put($directory . '/' . $fileNameToStore, $image->encode($encoder));

                Log::info("Image uploaded: {$fileNameToStore} ({$originalWidth}x{$originalHeight} -> {$width}x{$height}) to {$directory} on {$disk} disk");

                return $fileNameToStore;
            }
        }
        return null;
    }

    /**
     * Upload image with multiple sizes (thumbnails).
     *
     * @param Request $request The incoming HTTP request.
     * @param string $attach The name of the file input field.
     * @param string $directory The subdirectory within the storage disk.
     * @param array $sizes Array of sizes ['thumb' => [150, 150], 'medium' => [300, 300]].
     * @param string|null $disk The storage disk to use.
     * @param int $quality The image quality.
     * @return array Array of uploaded file names with sizes.
     */
    public function uploadImageWithSizes(Request $request, string $attach, string $directory, array $sizes = [], string $disk = null, int $quality = 90): array
    {
        $disk = $disk ?? config('filesystems.default');
        $uploadedFiles = [];

        if ($request->hasFile($attach)) {
            $file = $request->file($attach);

            if ($this->validateImageFile($file)) {
                $baseFileName = pathinfo($this->generateFileName($file), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();

                // Create an image manager
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());

                // Upload original
                $originalFileName = $baseFileName . '.' . $extension;
                $encoder = $this->getEncoder($extension, $quality);
                Storage::disk($disk)->put($directory . '/' . $originalFileName, $image->encode($encoder));
                $uploadedFiles['original'] = $originalFileName;

                // Upload different sizes
                foreach ($sizes as $sizeName => $dimensions) {
                    $sizedFileName = $baseFileName . '_' . $sizeName . '.' . $extension;
                    $sizedImage = clone $image;

                    $sizedImage->resize($dimensions[0], $dimensions[1], function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    Storage::disk($disk)->put($directory . '/' . $sizedFileName, $sizedImage->encode($encoder));
                    $uploadedFiles[$sizeName] = $sizedFileName;
                }

                Log::info("Image with multiple sizes uploaded: " . json_encode($uploadedFiles));
            }
        }

        return $uploadedFiles;
    }

    /**
     * Update an image file in the specified directory and disk with resizing using Intervention Image v3.
     * Deletes the old file if a new one is uploaded.
     *
     * @param Request $request The incoming HTTP request.
     * @param string $attach The name of the file input field (e.g., 'attach').
     * @param string $directory The subdirectory within the storage disk.
     * @param int|null $width The desired width for the image.
     * @param int|null $height The desired height for the image.
     * @param object $model The Eloquent model instance.
     * @param string $field The model's attribute holding the file name.
     * @param string|null $disk The storage disk to use.
     * @param int $quality The image quality.
     * @return string|null The new stored file name, or the old one if no new file, or null if old file was deleted and no new file.
     */
    public function updateImage(Request $request, string $attach, string $directory, ?int $width, ?int $height, object $model, string $field, string $disk = null, int $quality = 90): ?string
    {
        $disk = $disk ?? config('filesystems.default');

        if ($request->hasFile($attach)) {
            // Delete old file if it exists
            if ($model->$field && Storage::disk($disk)->exists($directory . '/' . $model->$field)) {
                $this->backupFile($directory, $model->$field, $disk);
                Storage::disk($disk)->delete($directory . '/' . $model->$field);
            }
            return $this->uploadImage($request, $attach, $directory, $width, $height, $disk, $quality);
        } elseif ($request->input($field . '_removed')) { // Check for explicit removal flag
            $this->deleteMultiMedia($directory, $model, $field, $disk);
            return null;
        }
        return $model->$field; // Return existing file name if no new file and not marked for removal
    }

    /**
     * Get file metadata (size, dimensions for images, mime type).
     *
     * @param string $filePath The file path.
     * @param string|null $disk The storage disk.
     * @return array File metadata.
     */
    public function getFileMetadata(string $filePath, string $disk = null): array
    {
        $disk = $disk ?? config('filesystems.default');
        $metadata = [];

        if (Storage::disk($disk)->exists($filePath)) {
            $metadata['size'] = Storage::disk($disk)->size($filePath);
            $metadata['last_modified'] = Storage::disk($disk)->lastModified($filePath);
            $metadata['mime_type'] = Storage::disk($disk)->mimeType($filePath);

            // For images, get dimensions
            if (str_starts_with($metadata['mime_type'], 'image/')) {
                try {
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read(Storage::disk($disk)->get($filePath));
                    $metadata['width'] = $image->width();
                    $metadata['height'] = $image->height();
                } catch (\Exception $e) {
                    Log::warning("Could not get image dimensions for {$filePath}: " . $e->getMessage());
                }
            }
        }

        return $metadata;
    }

    /**
     * Generate a signed URL for private files.
     *
     * @param string $filePath The file path.
     * @param int $expiration Expiration time in minutes.
     * @param string|null $disk The storage disk.
     * @return string|null The signed URL.
     */
    public function generateSignedUrl(string $filePath, int $expiration = 60, string $disk = null): ?string
    {
        $disk = $disk ?? config('filesystems.default');

        try {
            if ($disk === 's3') {
                return Storage::disk($disk)->temporaryUrl($filePath, now()->addMinutes($expiration));
            }
            // For other disks, return regular URL
            return Storage::disk($disk)->url($filePath);
        } catch (\Exception $e) {
            Log::error("Could not generate signed URL for {$filePath}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if file is duplicate based on hash.
     *
     * @param UploadedFile $file The uploaded file.
     * @param string $directory The directory to check.
     * @param string|null $disk The storage disk.
     * @return string|null The existing file name if duplicate found.
     */
    public function checkDuplicate(UploadedFile $file, string $directory, string $disk = null): ?string
    {
        $disk = $disk ?? config('filesystems.default');
        $fileHash = hash_file('md5', $file->getRealPath());

        $files = Storage::disk($disk)->files($directory);

        foreach ($files as $existingFile) {
            $existingFileContent = Storage::disk($disk)->get($existingFile);
            $existingFileHash = hash('md5', $existingFileContent);

            if ($fileHash === $existingFileHash) {
                return basename($existingFile);
            }
        }

        return null;
    }

    /**
     * Validate file based on extension and size.
     *
     * @param UploadedFile $file The uploaded file.
     * @return bool True if valid, false otherwise.
     */
    private function validateFile(UploadedFile $file): bool
    {
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif', 'ico', 'svg', 'webp', 'pdf', 'doc', 'docx', 'txt', 'zip', 'rar', 'csv', 'xls', 'xlsx', 'ppt', 'pptx', 'mp3', 'avi', 'mp4', 'mpeg', '3gp', 'mov', 'ogg', 'mkv'];
        $file_ext = $file->getClientOriginalExtension();
        $maxSize = config('filesystems.max_file_size', 10240); // 10MB default

        return in_array(strtolower($file_ext), array_map('strtolower', $valid_extensions), true)
            && $file->getSize() <= ($maxSize * 1024);
    }

    /**
     * Validate image file specifically.
     *
     * @param UploadedFile $file The uploaded file.
     * @return bool True if valid, false otherwise.
     */
    private function validateImageFile(UploadedFile $file): bool
    {
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif', 'ico', 'svg', 'webp'];
        $file_ext = $file->getClientOriginalExtension();
        $maxSize = config('filesystems.max_image_size', 5120); // 5MB default

        return in_array(strtolower($file_ext), array_map('strtolower', $valid_extensions), true)
            && $file->getSize() <= ($maxSize * 1024);
    }

    /**
     * Generate a unique file name.
     *
     * @param UploadedFile $file The uploaded file.
     * @return string The generated file name.
     */
    private function generateFileName(UploadedFile $file): string
    {
        $filenameWithExt = $file->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        return str_replace([' ', '-', '&', '#', '$', '%', '^', ';', ':'], '_', $filename) . '_' . time() . '.' . $extension;
    }

    /**
     * Backup file before deletion.
     *
     * @param string $directory The directory.
     * @param string $fileName The file name.
     * @param string $disk The storage disk.
     * @return bool True if backup successful.
     */
    private function backupFile(string $directory, string $fileName, string $disk): bool
    {
        if (!config('filesystems.backup_before_delete', false)) {
            return false;
        }

        try {
            $backupDirectory = 'backups/' . $directory;
            $backupFileName = date('Y-m-d_H-i-s') . '_' . $fileName;

            if (Storage::disk($disk)->exists($directory . '/' . $fileName)) {
                $fileContent = Storage::disk($disk)->get($directory . '/' . $fileName);
                Storage::disk($disk)->put($backupDirectory . '/' . $backupFileName, $fileContent);
                Log::info("File backed up: {$fileName} to {$backupDirectory}/{$backupFileName}");
                return true;
            }
        } catch (\Exception $e) {
            Log::error("Backup failed for {$fileName}: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Get the appropriate encoder for the given file extension.
     *
     * @param string $extension The file extension.
     * @param int $quality The image quality.
     * @return mixed The encoder instance.
     */
    private function getEncoder(string $extension, int $quality = 90): mixed
    {
        return match (strtolower($extension)) {
            'jpg', 'jpeg' => new JpegEncoder(quality: $quality),
            'png' => new PngEncoder(),
            'gif' => new GifEncoder(),
            'webp' => new WebpEncoder(quality: $quality),
            default => new AutoEncoder(),
        };
    }
}
