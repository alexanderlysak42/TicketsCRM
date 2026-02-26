<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'customer' => ['required', 'array'],

            'customer.name' => ['required', 'string', 'min:2', 'max:120'],
            'customer.phone' => [
                'required',
                'string',
                'regex:/^\+[1-9]\d{7,14}$/',
            ],
            'customer.email' => ['nullable', 'email', 'max:190'],

            'subject' => ['required', 'string', 'min:3', 'max:190'],
            'message' => ['required', 'string', 'min:3', 'max:5000'],

            'files' => ['nullable', 'array', 'max:10'],
            'files.*' => [
                'file',
                'max:10240', // 10 MB
                'mimetypes:image/jpeg,image/png,application/pdf,text/plain',
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $phone = (string) data_get($this->input(), 'customer.phone');
            $email = data_get($this->input(), 'customer.email');

            $start = Carbon::now()->startOfDay();
            $end = Carbon::now()->endOfDay();

            $exists = Ticket::query()
                ->whereBetween('created_at', [$start, $end])
                ->whereHas('customer', function ($q) use ($phone, $email) {
                    $q->where('phone', $phone);

                    if (! empty($email)) {
                        $q->orWhere('email', $email);
                    }
                })
                ->exists();

            if ($exists) {
                $msg = 'Можно отправлять не более одной заявки в сутки с одного телефона или email.';
                $validator->errors()->add('customer.phone', $msg);
            }
        });
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'customer.phone.regex' => 'Phone format must be +380501234567',

            'files.max' => 'Max 10 files',

            'files.*.max' => 'Nax file size is 10MB',
        ];
    }

    public function payload(): array
    {
        return $this->validated();
    }
}
