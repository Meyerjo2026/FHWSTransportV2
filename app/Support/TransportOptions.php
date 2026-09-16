<?php

namespace App\Support;

class TransportOptions
{
    public const SITES = [
        'AMS', 'Eersterivier', 'Elsies River', 'Groote Schuur', 'Gugulethu',
        'Karl Bremer', 'Khayelitsha', 'Kraaifontein', "Mitchell's Plain", 'Mowbray',
        'Paarl', 'Pinelands', 'Red Cross', 'Stellenbosch', 'Tygerberg',
    ];

    public const TIME_SLOTS = [
        '06:00 - 14:00', '06:00 - 18:00', '07:00 - 17:00', '07:00 - 19:00',
        '18:00 - 06:00', '19:00 - 07:00',
    ];

    public const DEFAULT_RATE = 673.28;
}
