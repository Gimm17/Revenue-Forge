<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateOfferCopyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'business_type' => 'required|string|max:255',
            'target_audience' => 'required|string|max:255',
            'offer_type' => 'required|string|in:one_time,subscription,credit_pack,saas_plan',
            'goal' => 'required|string|max:500',
            'price_range' => 'nullable|string|max:100',
            'tone' => 'nullable|string|max:50',
            'cta_style' => 'nullable|string|max:50',
        ];
    }
}
