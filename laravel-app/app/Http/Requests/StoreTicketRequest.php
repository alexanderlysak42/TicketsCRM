<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * @return bool
     */
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

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'customer.phone.regex' =>
                'Телефон должен быть в международном формате, например +380501234567',

            'files.max' =>
                'Можно загрузить не более 10 файлов',

            'files.*.max' =>
                'Размер файла не должен превышать 10 МБ',
        ];
    }

    /**
     * @return array
     */
    public function payload(): array
    {
        return $this->validated();
    }
}
