<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user() && ! $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cart_item_id' => ['required', 'integer', 'exists:cart_items,id'],
            'proof' => [
                'required',
                File::types(['jpg', 'jpeg', 'png', 'pdf'])->max(config('codex.payments.proof_max_size_kb')),
            ],
        ];
    }
}
