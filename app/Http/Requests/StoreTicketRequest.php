<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Измените на нужную логику авторизации
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'description' => 'required|string|min:10',
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
            'name.required' => 'Поле имя обязательно для заполнения',
            'name.max' => 'Имя не должно превышать 255 символов',

            'email.required' => 'Поле email обязательно для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'email.max' => 'Email не должен превышать 255 символов',

            'phone_number.required' => 'Поле телефон обязательно для заполнения',
            'phone_number.max' => 'Телефон не должен превышать 20 символов',

            'description.required' => 'Поле сообщение обязательно для заполнения',
            'description.min' => 'Сообщение должно содержать минимум 10 символов',

            'files.*.max' => 'Каждый файл не должен превышать 10MB',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'имя',
            'email' => 'email',
            'phone_number' => 'телефон',
            'description' => 'сообщение',
            'files' => 'файлы',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Ошибка валидации'
            ], 422)
        );
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('files')) {
                $files = $this->file('files');
                if (count($files) > 5) {
                    $validator->errors()->add('files', 'Максимум 5 файлов');
                }
            }
        });
    }
}
