<?php

namespace App\Models;

use App\Models\Web\AboutUs;
use App\Models\Web\CallToAction;
use App\Models\Web\Course;
use App\Models\Web\Faq;
use App\Models\Web\Feature;
use App\Models\Web\Gallery;
use App\Models\Web\News;
use App\Models\Web\Page;
use App\Models\Web\Slider;
use App\Models\Web\Testimonial;
use App\Models\Web\WebEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Language
 * @brief Model for managing system languages.
 *
 * This model represents the 'languages' table, defining
 * available languages for the application and website.
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property bool $direction
 * @property bool $default
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Language extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'direction',
        'default',
        'status',
    ];

    /**
     * Get the About Us content for this language.
     *
     * @return HasMany
     */
    public function aboutUs(): HasMany
    {
        return $this->hasMany(AboutUs::class);
    }

    /**
     * Get the Call To Actions for this language.
     *
     * @return HasMany
     */
    public function callToActions(): HasMany
    {
        return $this->hasMany(CallToAction::class);
    }

    /**
     * Get the Courses for this language.
     *
     * @return HasMany
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get the FAQs for this language.
     *
     * @return HasMany
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    /**
     * Get the Features for this language.
     *
     * @return HasMany
     */
    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }

    /**
     * Get the Galleries for this language.
     *
     * @return HasMany
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Get the News for this language.
     *
     * @return HasMany
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    /**
     * Get the Pages for this language.
     *
     * @return HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    /**
     * Get the Sliders for this language.
     *
     * @return HasMany
     */
    public function sliders(): HasMany
    {
        return $this->hasMany(Slider::class);
    }

    /**
     * Get the Testimonials for this language.
     *
     * @return HasMany
     */
    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    /**
     * Get the Web Events for this language.
     *
     * @return HasMany
     */
    public function webEvents(): HasMany
    {
        return $this->hasMany(WebEvent::class);
    }
}
