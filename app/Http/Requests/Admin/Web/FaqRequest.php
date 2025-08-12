<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @class FaqRequest
 * @brief Request for managing Frequently Asked Questions (FAQs).
 *
 * This model represents the 'faq' form request
 * for public display on the website.
 *
 * @property string $title The question of the FAQ.
 * @property string $description The answer to the FAQ.
 * @property string|null $icon The icon associated with the FAQ.
 * @property bool|null $status The publication status of the FAQ.
 */
class FaqRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $faqId = $this->route('faq') ? $this->route('faq')->id : null;

        return [
            /**
             * The question of the FAQ.
             * Must be unique across all FAQs.
             * @var string $title
             * @example "How do I apply for admission?"
             */
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('faqs', 'title')->ignore($faqId),
            ],

            /**
             * The answer to the FAQ.
             * @var string $description
             * @example "You can apply online through our admissions portal by filling out the application form and submitting the required documents."
             */
            'description' => 'required|string',

            /**
             * The icon associated with the FAQ.
             * @var string|null $icon
             * @example "question-circle"
             */
            'icon' => 'nullable|string|max:255',

            /**
             * The publication status of the FAQ.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The FAQ title/question is required.',
            'title.max' => 'The FAQ title/question may not be greater than 255 characters.',
            'title.unique' => 'This FAQ title/question already exists. Please choose a different one.',
            'description.required' => 'The FAQ description/answer is required.',
            'icon.max' => 'The icon name may not be greater than 255 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default status if not provided
        if (!$this->has('status')) {
            $this->merge([
                'status' => true,
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'FAQ title/question',
            'description' => 'FAQ description/answer',
            'icon' => 'FAQ icon',
            'status' => 'publication status',
        ];
    }
}
