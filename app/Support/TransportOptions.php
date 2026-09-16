<?php

namespace App\Support;

class TransportOptions
{
    /**
     * Seed data for clinical_sites: name => address.
     * Used only by the seeder — the live dropdown reads from the database
     * so admins can add/edit sites without a code change.
     */
    public const SITE_SEED = [
        'AMS' => 'Ambulance Emergency Services, Bellville, Cape Town',
        'Eersterivier' => 'Eersterivier Community Health Centre, Eersteriver, Cape Town',
        'Elsies River' => 'Elsies River Community Health Centre, Epping Ave, Elsies River, Cape Town',
        'Groote Schuur' => 'Groote Schuur Hospital, Main Rd, Observatory, Cape Town',
        'Gugulethu' => 'Gugulethu Community Health Centre, NY1, Gugulethu, Cape Town',
        'Karl Bremer' => 'Karl Bremer Hospital, Mike Pienaar Blvd, Bellville, Cape Town',
        'Khayelitsha' => 'Khayelitsha District Hospital, Walter Sisulu Rd, Khayelitsha, Cape Town',
        'Kraaifontein' => 'Kraaifontein Community Health Centre, Wesbank Rd, Kraaifontein, Cape Town',
        "Mitchell's Plain" => "Mitchell's Plain District Hospital, AZ Berman Dr, Mitchell's Plain, Cape Town",
        'Mowbray' => 'Mowbray Maternity Hospital, Symonds St, Mowbray, Cape Town',
        'Paarl' => 'Paarl Hospital, Hospital St, Paarl',
        'Pinelands' => 'Vincent Pallotti Hospital area, Pinelands, Cape Town',
        'Red Cross' => 'Red Cross War Memorial Children\'s Hospital, Klipfontein Rd, Rondebosch, Cape Town',
        'Stellenbosch' => 'Stellenbosch Hospital, Rosenhof St, Stellenbosch',
        'Tygerberg' => 'Tygerberg Hospital, Francie van Zijl Dr, Parow, Cape Town',
    ];

    public const TIME_SLOTS = [
        '06:00 - 14:00', '06:00 - 18:00', '07:00 - 17:00', '07:00 - 19:00',
        '18:00 - 06:00', '19:00 - 07:00',
    ];

    public const DEPARTMENTS = [
        'Emergency Medical Sciences',
        'Nursing Sciences',
        'Medical Laboratory Sciences',
        'Radiography',
        'Environmental & Occupational Studies',
        'Somatology',
        'Dental Technology',
        'Human Nutrition & Dietetics',
    ];

    public const QUALIFICATIONS = [
        'Higher Certificate in Ambulance Emergency Assistant',
        'Diploma in Emergency Medical Care',
        'Bachelor of Emergency Medical Care',
        'Diploma in Nursing',
        'Bachelor of Nursing',
        'Postgraduate Diploma in Nursing Education',
        'Diploma in Medical Laboratory Sciences',
        'Diploma in Radiography',
        'Diploma in Somatology',
    ];

    public const DEFAULT_RATE = 673.28;

    public const MAX_STUDENTS_PER_TRIP = 20;
}
