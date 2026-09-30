<?php

return [
    'consultation_created' => [
        'title' => 'Запись подтверждена',
        'body' => 'Консультация для :pet_name успешно забронирована. Будем ждать вас в назначенное время!',
    ],

    'consultation_completed' => [
        'title' => 'Консультация завершена',
        'body' => 'Консультация с :doctor_name завершена',
    ],

    'consultation_cancelled' => [
        'title' => 'Консультация отменена',
        'body' => 'Консультация с :doctor_name отменена',
    ],

    'consultation_cancelled_by_doctor' => [
        'title' => 'Приём не состоится',
        'body' => 'К сожалению, консультация для :pet_name была отменена врачом. Вы можете выбрать другое удобное время.',
    ],

    'consultation_conclusion_created' => [
        'title' => 'Результаты доступны',
        'body' => 'Врач подготовил заключение — вы можете ознакомиться с ним в приложении.',
    ],

    'consultation_conclusion_updated' => [
        'title' => 'Информация обновлена',
        'body' => 'Врач внес изменения в заключение для :pet_name. Проверьте актуальную версию.',
    ],

    'consultation_reminder_24h' => [
        'title' => 'Скоро приём',
        'body' => 'Завтра в :time состоится консультация для :pet_name. До встречи!',
    ],

    'consultation_reminder_1h' => [
        'title' => 'Скоро начало',
        'body' => 'Консультация для :pet_name начнется через час. До встречи!',
    ],

    'doctor_registration_approved' => [
        'title' => 'Регистрация одобрена',
        'body' => 'Ваша регистрация в качестве ветеринара одобрена',
    ],

    'doctor_update_approved' => [
        'title' => 'Изменения одобрены',
        'body' => 'Ваши изменения профиля одобрены',
    ],

    'doctor_update_rejected' => [
        'title' => 'Изменения отклонены',
        'body' => 'Ваши изменения профиля отклонены. Причина: :reason',
    ],

    'chat_message' => [
        'title' => 'Новое сообщение',
        'body' => ':sender_name: :message',
    ],

    'payment_success' => [
        'title' => 'Оплата успешна',
        'body' => 'Ваш платеж на сумму :amount успешно обработан',
    ],

    'payment_failed' => [
        'title' => 'Ошибка оплаты',
        'body' => 'Не удалось обработать платеж. Попробуйте еще раз',
    ],
];
