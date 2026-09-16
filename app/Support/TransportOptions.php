<?php

namespace App\Support;

class TransportOptions
{
    /**
     * The pickup point for every trip — students are always collected
     * from and returned to CPUT Bellville Campus.
     */
    public const PICKUP_POINT = 'CPUT Bellville Campus';

    public const PICKUP_LAT = -33.932098;

    public const PICKUP_LNG = 18.629311;

    /**
     * Seed data for clinical_sites: name => [address, lat, lng].
     * Compiled from every destination actually billed across the HG
     * Travelling Services invoices in use (student placements, not
     * invented). Used only by the seeder — the live dropdown/map read
     * from the database so admins can add/edit sites without a code
     * change. Coordinates are approximate (suitable for the admin
     * placement map, not turn-by-turn navigation).
     */
    public const SITE_SEED = [
        'AMS' => ['Ambulance Emergency Services, Bellville, Cape Town', -33.9000, 18.6300],
        'Alexandra Hospital' => ['Alexandra Hospital, Maitland, Cape Town', -33.9186, 18.5106],
        'Anchusa CPOA' => ['Anchusa Home for the Aged, Elsies River, Cape Town', -33.9170, 18.5730],
        'Avondrust CPOA' => ['Avondrust Home for the Aged, Kraaifontein, Cape Town', -33.8500, 18.6600],
        'Bishop Lavis CHC' => ['Bishop Lavis Community Health Centre, Bishop Lavis, Cape Town', -33.9300, 18.5980],
        'Delft Community Health Centre' => ['Delft Community Health Centre, Delft, Cape Town', -33.9730, 18.6630],
        'Eastridge Clinic' => ['Eastridge Clinic, Eastridge, Mitchell\'s Plain, Cape Town', -34.0410, 18.6160],
        'Eerste River Hospital' => ['Eerste River Hospital, Eerste River, Cape Town', -33.9515, 18.6931],
        'Elsies River CHC & MOU' => ['Elsies River Community Health Centre & MOU, Epping Ave, Elsies River, Cape Town', -33.9265, 18.5732],
        'Elsies River Clinic' => ['Elsies River Clinic, Elsies River, Cape Town', -33.9280, 18.5750],
        'Erica Place CPOA' => ['Erica Place Home for the Aged, Strand, Cape Town', -34.0800, 18.8500],
        'Goodwood CDC' => ['Goodwood Community Day Centre, Goodwood, Cape Town', -33.9037, 18.5501],
        'Goodwood CHC' => ['Goodwood Community Health Centre, Goodwood, Cape Town', -33.9037, 18.5501],
        'Grassy Park CHC' => ['Grassy Park Community Health Centre, Grassy Park, Cape Town', -34.0480, 18.4940],
        'Groote Schuur Hospital' => ['Groote Schuur Hospital, Main Rd, Observatory, Cape Town', -33.9403, 18.4640],
        'Gugulethu CHC' => ['Gugulethu Community Health Centre, NY1, Gugulethu, Cape Town', -33.9758, 18.5715],
        'Gugulethu Clinic' => ['Gugulethu Clinic, Gugulethu, Cape Town', -33.9760, 18.5700],
        'Gugulethu MOU' => ['Gugulethu Midwife Obstetric Unit, Gugulethu, Cape Town', -33.9770, 18.5720],
        'Hanover Park CHC' => ['Hanover Park Community Health Centre, Hanover Park, Cape Town', -33.9830, 18.5390],
        'Heideveld Clinic' => ['Heideveld Clinic, Heideveld, Cape Town', -33.9600, 18.5620],
        'Helderberg Hospital' => ['Helderberg Hospital, Somerset West, Cape Town', -34.0850, 18.8500],
        'Ikhwezi Clinic' => ['Ikhwezi Clinic, Site C, Khayelitsha, Cape Town', -34.0300, 18.6950],
        'Karl Bremer Hospital' => ['Karl Bremer Hospital, Mike Pienaar Blvd, Bellville, Cape Town', -33.8961, 18.6094],
        'Kasselsvlei CDC' => ['Kasselsvlei Community Day Centre, Bellville, Cape Town', -33.9120, 18.6350],
        'Khayelitsha District Hospital' => ['Khayelitsha District Hospital, Walter Sisulu Rd, Khayelitsha, Cape Town', -34.0396, 18.6743],
        'Khayelitsha Site B MOU' => ['Site B Midwife Obstetric Unit, Khayelitsha, Cape Town', -34.0380, 18.6790],
        'Kraaifontein Community Health Centre' => ['Kraaifontein Community Health Centre, Wesbank Rd, Kraaifontein, Cape Town', -33.8459, 18.6825],
        'Kuyasa Clinic' => ['Kuyasa Clinic, Kuyasa, Khayelitsha, Cape Town', -34.0320, 18.7070],
        'Lady Michaelis CHC' => ['Lady Michaelis Community Health Centre, Vrygrond, Cape Town', -34.0600, 18.4800],
        'Landsdowne Clinic' => ['Lansdowne Clinic, Lansdowne, Cape Town', -33.9840, 18.5000],
        'Langa CHC' => ['Langa Community Health Centre, Langa, Cape Town', -33.9470, 18.5340],
        'Langa Clinic' => ['Langa Clinic, Langa, Cape Town', -33.9480, 18.5350],
        'Lentegeur Clinic' => ['Lentegeur Clinic, Lentegeur, Mitchell\'s Plain, Cape Town', -34.0310, 18.6280],
        'Lentegeur Psychiatric Hospital' => ['Lentegeur Psychiatric Hospital, Mitchell\'s Plain, Cape Town', -34.0310, 18.6280],
        'Lotus River CHC' => ['Lotus River Community Health Centre, Lotus River, Cape Town', -34.0380, 18.4970],
        'Lotus River CPOA' => ['Lotus River Home for the Aged, Lotus River, Cape Town', -34.0380, 18.4970],
        'Macassar CHC' => ['Macassar Community Health Centre, Macassar, Cape Town', -34.0430, 18.7670],
        'Macassar MOU' => ['Macassar Midwife Obstetric Unit, Macassar, Cape Town', -34.0430, 18.7670],
        'Manor Care CPOA' => ['Manor Care Home for the Aged, Plumstead, Cape Town', -34.0050, 18.4750],
        'Michael Mpongwana MOU' => ['Michael Mpongwana Midwife Obstetric Unit, Khayelitsha, Cape Town', -34.0370, 18.6810],
        "Mitchell's Plain CHC & MOU" => ["Mitchell's Plain Community Health Centre & MOU, Mitchell's Plain, Cape Town", -34.0361, 18.6203],
        "Mitchell's Plain District Hospital" => ["Mitchell's Plain District Hospital, AZ Berman Dr, Mitchell's Plain, Cape Town", -34.0361, 18.6203],
        'Mowbray Maternity Hospital' => ['Mowbray Maternity Hospital, Symonds St, Mowbray, Cape Town', -33.9494, 18.4739],
        'New Somerset Hospital' => ['New Somerset Hospital, Green Point, Cape Town', -33.9120, 18.4150],
        'Nolungile CHC' => ['Nolungile Community Health Centre, Site C, Khayelitsha, Cape Town', -34.0280, 18.6890],
        'Nyanga CHC' => ['Nyanga Community Health Centre, Nyanga, Cape Town', -33.9720, 18.5980],
        'Paarl Hospital' => ['Paarl Hospital, Hospital St, Paarl', -33.7342, 18.9623],
        'Parow CDC' => ['Parow Community Day Centre, Parow, Cape Town', -33.9078, 18.5976],
        'Parow CHC' => ['Parow Community Health Centre, Parow, Cape Town', -33.9078, 18.5976],
        'Pinelands Place' => ['Pinelands Place, Pinelands, Cape Town', -33.9337, 18.5090],
        'Ravensmead CHC' => ['Ravensmead Community Health Centre, Ravensmead, Cape Town', -33.9180, 18.5880],
        'Red Cross War Memorial Children\'s Hospital' => ['Red Cross War Memorial Children\'s Hospital, Klipfontein Rd, Rondebosch, Cape Town', -33.9548, 18.4661],
        'Reed Street CDC' => ['Reed Street Community Day Centre, Bellville, Cape Town', -33.9000, 18.6000],
        'Retreat CHC' => ['Retreat Community Health Centre, Retreat, Cape Town', -34.0380, 18.4780],
        'Retreat MOU' => ['Retreat Midwife Obstetric Unit, Retreat, Cape Town', -34.0380, 18.4780],
        'Rocklands Clinic' => ['Rocklands Clinic, Mitchell\'s Plain, Cape Town', -34.0520, 18.6150],
        'Sea Point CPOA' => ['Sea Point Home for the Aged, Sea Point, Cape Town', -33.9170, 18.3860],
        'St Vincent CDC' => ['St Vincent Community Day Centre, Nyanga, Cape Town', -33.9700, 18.5850],
        'St Vincent CHC' => ['St Vincent Community Health Centre, Nyanga, Cape Town', -33.9700, 18.5850],
        'Stellenbosch Hospital' => ['Stellenbosch Hospital, Rosenhof St, Stellenbosch', -33.9366, 18.8636],
        'Strand CHC' => ['Strand Community Health Centre, Strand, Cape Town', -34.1090, 18.8230],
        'Symphony CDC' => ['Symphony Community Day Centre, Symphony Way, Bellville, Cape Town', -33.9300, 18.6280],
        'Tygerberg Hospital' => ['Tygerberg Hospital, Francie van Zijl Dr, Parow, Cape Town', -33.8961, 18.6098],
        'Vangate MOU' => ['Vangate Midwife Obstetric Unit, Athlone, Cape Town', -33.9650, 18.5150],
        'Vanguard CHC' => ['Vanguard Community Health Centre, Bonteheuwel, Cape Town', -33.9580, 18.5440],
        'Vanguard MOU' => ['Vanguard Midwife Obstetric Unit, Bonteheuwel, Cape Town', -33.9580, 18.5440],
        'Victoria Hospital' => ['Victoria Hospital, Wynberg, Cape Town', -34.0026, 18.4708],
        'Western Cape Rehabilitation Centre' => ['Western Cape Rehabilitation Centre, Lentegeur, Mitchell\'s Plain, Cape Town', -34.0450, 18.6300],
    ];

    public const TIME_SLOTS = [
        '06:00 - 14:00', '06:00 - 18:00', '07:00 - 17:00', '07:00 - 19:00',
        '18:00 - 06:00', '19:00 - 07:00',
    ];

    /**
     * Classify a "HH:MM - HH:MM" time slot as a day or night shift,
     * based on its start time. Anything starting 18:00 or later (or
     * before 06:00) is a night shift.
     */
    public static function shiftFor(?string $time): string
    {
        if (! $time || ! preg_match('/^(\d{1,2}):(\d{2})/', trim($time), $m)) {
            return 'day';
        }
        $hour = (int) $m[1];

        return ($hour >= 18 || $hour < 6) ? 'night' : 'day';
    }

    /**
     * CPUT Faculty of Health & Wellness Sciences departments, each mapped
     * to its undergraduate qualifications
     * (https://www.cput.ac.za/faculties/fhws/courses). Postgraduate
     * qualifications (PG Dip, Master's, Doctorates) are intentionally
     * excluded — undergraduate only. The qualification dropdown is
     * dependent on the selected department.
     */
    public const QUALIFICATIONS_BY_DEPARTMENT = [
        'Biomedical Sciences' => [
            'Higher Certificate in Biomedical Sciences',
            'Bachelor of Health Sciences in Medical Laboratory Science (Extended Curriculum Programme)',
            'Bachelor of Health Sciences in Medical Laboratory Science (Articulation)',
            'Bachelor of Health Sciences in Medical Laboratory Science',
        ],
        'Dental Sciences' => [
            'Higher Certificate in Dental Assisting',
        ],
        'Emergency Medical Sciences' => [
            'Higher Certificate in Emergency Medical Care',
            'Diploma in Emergency Care',
            'Bachelor of Emergency Medical Care (Extended Curriculum Programme)',
            'Bachelor of Emergency Medical Care',
        ],
        'Medical Imaging & Therapeutic Sciences' => [
            'Bachelor of Science in Diagnostic Radiography',
            'Bachelor of Science in Diagnostic Ultrasound',
            'Bachelor of Science in Nuclear Medicine Technology',
            'Bachelor of Science in Radiation Therapy',
        ],
        'Nursing Sciences' => [
            'Bachelor of Nursing (Extended Curriculum Programme)',
            'Bachelor of Nursing',
        ],
        'Ophthalmic Sciences' => [
            'Bachelor of Health Sciences in Opticianry',
        ],
        'Wellness Sciences' => [
            'Diploma in Somatology',
            'Advanced Diploma in Somatology',
            'Health and Wellness: Non-Diploma/Degree',
        ],
    ];

    public const DEFAULT_RATE = 673.28;

    public const MAX_STUDENTS_PER_TRIP = 20;

    public static function departments(): array
    {
        return array_keys(self::QUALIFICATIONS_BY_DEPARTMENT);
    }

    public static function qualificationsFor(?string $department): array
    {
        return self::QUALIFICATIONS_BY_DEPARTMENT[$department] ?? [];
    }

    public static function allQualifications(): array
    {
        return array_values(array_unique(array_merge(...array_values(self::QUALIFICATIONS_BY_DEPARTMENT))));
    }

    public static function isValidPair(string $department, string $qualification): bool
    {
        return in_array($qualification, self::qualificationsFor($department), true);
    }
}
