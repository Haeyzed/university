<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class SlugHelper
 *
 * A static helper class for generating and validating unique slugs.
 */
class SlugHelper
{
    /**
     * Generate a unique slug for a given title and model.
     *
     * @param string $title The title to generate the slug from.
     * @param string $modelClass The fully qualified class name of the Eloquent model (e.g., App\Models\Web\News).
     * @param int|null $excludeId The ID to exclude from the uniqueness check (for updates).
     * @return string The unique slug.
     */
    public static function generateUniqueSlug(string $title, string $modelClass, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($title, '-');
        $slug = $baseSlug;
        $counter = 1;

        while (self::slugExists($slug, $modelClass, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if a slug already exists in the database for a given model.
     *
     * @param string $slug The slug to check.
     * @param string $modelClass The fully qualified class name of the Eloquent model.
     * @param int|null $excludeId The ID to exclude from the check.
     * @return bool True if the slug exists, false otherwise.
     */
    public static function slugExists(string $slug, string $modelClass, ?int $excludeId = null): bool
    {
        /** @var Model $model */
        $query = $modelClass::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
