<?php

namespace Database\Seeders;

use App\Models\DBModels\Ticket;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::query()->delete();

        $topics = [
            'Проблема с доступом к системе',
            'Ошибка при оплате',
            'Вопрос по функционалу',
            'Техническая неполадка',
            'Запрос на добавление функции',
            'Проблема с отображением на мобильном',
            'Сбой в работе модуля',
            'Консультация по настройке',
            'Жалоба на работу сервиса',
            'Предложение по улучшению',
            'Проблема с загрузкой файлов',
            'Ошибка в отчете',
            'Восстановление доступа',
            'Вопрос по тарифам',
            'Проблема с синхронизацией',
        ];

        $descriptions = [
            'Не могу войти в систему уже второй день.',
            'При попытке оплаты возникает ошибка 500.',
            'Как добавить нового пользователя в команду?',
            'Сайт не грузится на мобильном телефоне.',
            'Хотелось бы видеть возможность экспорта в Excel.',
            'На странице профиля не отображается аватар.',
            'Модуль отчетов выдает некорректные данные.',
            'Помогите настроить уведомления по email.',
            'Сервис работает очень медленно последнюю неделю.',
            'Предлагаю добавить темную тему для интерфейса.',
            'Не загружаются файлы размером больше 5МБ.',
            'В отчете за апрель некорректные суммы.',
            'Забыл пароль, нужна помощь в восстановлении.',
            'Какие тарифы доступны для малого бизнеса?',
            'Данные не синхронизируются между устройствами.',
        ];

        $tickets = [];
        $statuses = [0, 1, 2, 3];

        for ($i = 1; $i <= 30; $i++) {
            $clientId = rand(1, 10);
            $status = $statuses[array_rand($statuses)];

            $createdAt = Carbon::now()->subDays(rand(0, 30));

            $tickets[] = [
                'client_id' => $clientId,
                'topic' => $topics[array_rand($topics)],
                'description' => $descriptions[array_rand($descriptions)],
                'status' => $status,
                'response_date' => now(),
                'created_at' => $createdAt,
                'updated_at' => $createdAt->addHours(rand(1, 24)),
            ];
        }

        usort($tickets, function($a, $b) {
            return strtotime($a['created_at']) - strtotime($b['created_at']);
        });


        foreach ($tickets as $ticket) {
            Ticket::create($ticket);
        }

    }
}
