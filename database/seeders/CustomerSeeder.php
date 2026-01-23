<?php

namespace Database\Seeders;

use App\Models\DBModels\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::query()->delete();

        $customers = [
            [
                'id' => 1,
                'name' => 'Дамир Кашинов',
                'phone_number' => '+7 (778) 030 03 13',
                'email' => 'mrc007@gmail.com',
            ],
            [
                'id' => 2,
                'name' => 'Амир Какойто',
                'phone_number' => '+7 (706) 618 08 21',
                'email' => 'vek007@gmail.com',
            ],
            [
                'id' => 3,
                'name' => 'Александр Иванов',
                'phone_number' => '+7 (999) 123 45 67',
                'email' => 'alex.ivanov@example.com',
            ],
            [
                'id' => 4,
                'name' => 'Мария Петрова',
                'phone_number' => '+7 (999) 987 65 43',
                'email' => 'maria.petrova@example.com',
            ],
            [
                'id' => 5,
                'name' => 'Сергей Сидоров',
                'phone_number' => '+7 (999) 555 44 33',
                'email' => 'sergey.sidorov@example.com',
            ],
            [
                'id' => 6,
                'name' => 'Ольга Козлова',
                'phone_number' => '+7 (999) 222 11 00',
                'email' => 'olga.kozlova@example.com',
            ],
            [
                'id' => 7,
                'name' => 'Дмитрий Волков',
                'phone_number' => '+7 (999) 777 88 99',
                'email' => 'dmitry.volkov@example.com',
            ],
            [
                'id' => 8,
                'name' => 'Анна Смирнова',
                'phone_number' => '+7 (999) 333 22 11',
                'email' => 'anna.smirnova@example.com',
            ],
            [
                'id' => 9,
                'name' => 'Иван Кузнецов',
                'phone_number' => '+7 (999) 444 55 66',
                'email' => 'ivan.kuznetsov@example.com',
            ],
            [
                'id' => 10,
                'name' => 'Екатерина Новикова',
                'phone_number' => '+7 (999) 666 77 88',
                'email' => 'ekaterina.novikova@example.com',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

    }
}
