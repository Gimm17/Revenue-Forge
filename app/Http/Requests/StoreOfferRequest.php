<?php

namespace App\Http\Requests;

use App\Data\Enums\OfferType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => ['required', Rule::enum(OfferType::class)],
            'tagline' => 'nullable|string|max:255',
            'short_pitch' => 'nullable|string|max:1000',
            'long_copy' => 'nullable|string|max:50000',
            'benefits' => 'nullable|array|max:10',
            'benefits.*' => 'string|max:500',
            'faq' => 'nullable|array|max:10',
            'faq.*.question' => 'required_with:faq|string|max:500',
            'faq.*.answer' => 'required_with:faq|string|max:2000',
            'cta_text' => 'nullable|string|max:50',
            'price' => 'required|integer|min:0',
            'currency' => 'nullable|string|max:10',
            'interval' => 'nullable|string|in:monthly,yearly,once',
            'credit_amount' => 'nullable|integer|min:0',
        ];
    }
}
