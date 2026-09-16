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
     * invented). Coordinates are geocoded (OpenStreetMap Nominatim,
     * cross-checked against Google Maps for a sample of major
     * hospitals — Tygerberg, Groote Schuur, Khayelitsha District —
     * all within ~250m), with two exceptions kept as suburb-level
     * manual estimates where geocoding returned a false-positive
     * match: 'AMS' (matched an unrelated park) and 'Ikhwezi Clinic'
     * (matched a same-named facility ~18km away in Strand). Suitable
     * for the admin placement map overview, not turn-by-turn
     * navigation. Used only by the seeder — the live dropdown/map
     * read from the database so admins can add/edit sites without a
     * code change.
     */
    public const SITE_SEED = [
        'AMS' => ['Ambulance Emergency Services, Bellville, Cape Town', -33.9, 18.63],
        'Alexandra Hospital' => ['Alexandra Hospital, Maitland, Cape Town', -33.9225, 18.493056],
        'Anchusa CPOA' => ['Anchusa Home for the Aged, Elsies River, Cape Town', -33.910436, 18.568955],
        'Avondrust CPOA' => ['Avondrust Home for the Aged, Kraaifontein, Cape Town', -33.827029, 18.648119],
        'Bishop Lavis CHC' => ['Bishop Lavis Community Health Centre, Bishop Lavis, Cape Town', -33.949335, 18.581822],
        'Delft Community Health Centre' => ['Delft Community Health Centre, Delft, Cape Town', -33.973854, 18.641662],
        'Eastridge Clinic' => ['Eastridge Clinic, Eastridge, Mitchell\'s Plain, Cape Town', -34.049524, 18.619158],
        'Eerste River Hospital' => ['Eerste River Hospital, Eerste River, Cape Town', -33.997928, 18.719136],
        'Elsies River CHC & MOU' => ['Elsies River Community Health Centre & MOU, Epping Ave, Elsies River, Cape Town', -33.910436, 18.568955],
        'Elsies River Clinic' => ['Elsies River Clinic, Elsies River, Cape Town', -33.92971, 18.57522],
        'Erica Place CPOA' => ['Erica Place Home for the Aged, Strand, Cape Town', -34.109, 18.823],
        'Goodwood CDC' => ['Goodwood Community Day Centre, Goodwood, Cape Town', -33.911318, 18.550612],
        'Goodwood CHC' => ['Goodwood Community Health Centre, Goodwood, Cape Town', -33.911318, 18.550612],
        'Grassy Park CHC' => ['Grassy Park Community Health Centre, Grassy Park, Cape Town', -34.045129, 18.503253],
        'Groote Schuur Hospital' => ['Groote Schuur Hospital, Main Rd, Observatory, Cape Town', -33.94117, 18.462616],
        'Gugulethu CHC' => ['Gugulethu Community Health Centre, NY1, Gugulethu, Cape Town', -33.968341, 18.570018],
        'Gugulethu Clinic' => ['Gugulethu Clinic, Gugulethu, Cape Town', -33.968341, 18.570018],
        'Gugulethu MOU' => ['Gugulethu Midwife Obstetric Unit, Gugulethu, Cape Town', -33.968341, 18.570018],
        'Hanover Park CHC' => ['Hanover Park Community Health Centre, Hanover Park, Cape Town', -33.992222, 18.533056],
        'Heideveld Clinic' => ['Heideveld Clinic, Heideveld, Cape Town', -33.968891, 18.562067],
        'Helderberg Hospital' => ['Helderberg Hospital, Somerset West, Cape Town', -34.112855, 18.838041],
        'Ikhwezi Clinic' => ['Ikhwezi Clinic, Site C, Khayelitsha, Cape Town', -34.03, 18.695],
        'Karl Bremer Hospital' => ['Karl Bremer Hospital, Mike Pienaar Blvd, Bellville, Cape Town', -33.891966, 18.608043],
        'Kasselsvlei CDC' => ['Kasselsvlei Community Day Centre, Bellville, Cape Town', -33.919493, 18.640506],
        'Khayelitsha District Hospital' => ['Khayelitsha District Hospital, Walter Sisulu Rd, Khayelitsha, Cape Town', -34.05006, 18.674],
        'Khayelitsha Site B MOU' => ['Site B Midwife Obstetric Unit, Khayelitsha, Cape Town', -34.040591, 18.66742],
        'Kraaifontein Community Health Centre' => ['Kraaifontein Community Health Centre, Wesbank Rd, Kraaifontein, Cape Town', -33.827029, 18.648119],
        'Kuyasa Clinic' => ['Kuyasa Clinic, Kuyasa, Khayelitsha, Cape Town', -34.055886, 18.689418],
        'Lady Michaelis CHC' => ['Lady Michaelis Community Health Centre, Vrygrond, Cape Town', -34.083927, 18.48761],
        'Landsdowne Clinic' => ['Lansdowne Clinic, Lansdowne, Cape Town', -33.996341, 18.506893],
        'Langa CHC' => ['Langa Community Health Centre, Langa, Cape Town', -33.945556, 18.53],
        'Langa Clinic' => ['Langa Clinic, Langa, Cape Town', -33.945556, 18.53],
        'Lentegeur Clinic' => ['Lentegeur Clinic, Lentegeur, Mitchell\'s Plain, Cape Town', -34.049524, 18.619158],
        'Lentegeur Psychiatric Hospital' => ['Lentegeur Psychiatric Hospital, Mitchell\'s Plain, Cape Town', -34.023417, 18.621604],
        'Lotus River CHC' => ['Lotus River Community Health Centre, Lotus River, Cape Town', -34.026705, 18.507736],
        'Lotus River CPOA' => ['Lotus River Home for the Aged, Lotus River, Cape Town', -34.039204, 18.51935],
        'Macassar CHC' => ['Macassar Community Health Centre, Macassar, Cape Town', -34.066116, 18.767495],
        'Macassar MOU' => ['Macassar Midwife Obstetric Unit, Macassar, Cape Town', -34.066116, 18.767495],
        'Manor Care CPOA' => ['Manor Care Home for the Aged, Plumstead, Cape Town', -34.02, 18.477222],
        'Michael Mpongwana MOU' => ['Michael Mpongwana Midwife Obstetric Unit, Khayelitsha, Cape Town', -34.040591, 18.66742],
        'Mitchell\'s Plain CHC & MOU' => ['Mitchell\'s Plain Community Health Centre & MOU, Mitchell\'s Plain, Cape Town', -34.049524, 18.619158],
        'Mitchell\'s Plain District Hospital' => ['Mitchell\'s Plain District Hospital, AZ Berman Dr, Mitchell\'s Plain, Cape Town', -34.022274, 18.608669],
        'Mowbray Maternity Hospital' => ['Mowbray Maternity Hospital, Symonds St, Mowbray, Cape Town', -33.949775, 18.47436],
        'New Somerset Hospital' => ['New Somerset Hospital, Green Point, Cape Town', -33.90511, 18.416309],
        'Nolungile CHC' => ['Nolungile Community Health Centre, Site C, Khayelitsha, Cape Town', -34.040591, 18.66742],
        'Nyanga CHC' => ['Nyanga Community Health Centre, Nyanga, Cape Town', -33.992819, 18.559813],
        'Paarl Hospital' => ['Paarl Hospital, Hospital St, Paarl', -33.726168, 18.970488],
        'Parow CDC' => ['Parow Community Day Centre, Parow, Cape Town', -33.926917, 18.484486],
        'Parow CHC' => ['Parow Community Health Centre, Parow, Cape Town', -33.926917, 18.484486],
        'Pinelands Place' => ['Pinelands Place, Pinelands, Cape Town', -33.936963, 18.510148],
        'Ravensmead CHC' => ['Ravensmead Community Health Centre, Ravensmead, Cape Town', -33.917298, 18.603385],
        'Red Cross War Memorial Children\'s Hospital' => ['Red Cross War Memorial Children\'s Hospital, Klipfontein Rd, Rondebosch, Cape Town', -33.954247, 18.487935],
        'Reed Street CDC' => ['Reed Street Community Day Centre, Bellville, Cape Town', -33.919493, 18.640506],
        'Retreat CHC' => ['Retreat Community Health Centre, Retreat, Cape Town', -34.058822, 18.48071],
        'Retreat MOU' => ['Retreat Midwife Obstetric Unit, Retreat, Cape Town', -34.054167, 18.476389],
        'Rocklands Clinic' => ['Rocklands Clinic, Mitchell\'s Plain, Cape Town', -34.064437, 18.610706],
        'Sea Point CPOA' => ['Sea Point Home for the Aged, Sea Point, Cape Town', -33.917222, 18.392222],
        'St Vincent CDC' => ['St Vincent Community Day Centre, Nyanga, Cape Town', -33.992819, 18.559813],
        'St Vincent CHC' => ['St Vincent Community Health Centre, Nyanga, Cape Town', -33.992819, 18.559813],
        'Stellenbosch Hospital' => ['Stellenbosch Hospital, Rosenhof St, Stellenbosch', -33.930375, 18.869975],
        'Strand CHC' => ['Strand Community Health Centre, Strand, Cape Town', -34.109, 18.823],
        'Symphony CDC' => ['Symphony Community Day Centre, Symphony Way, Bellville, Cape Town', -33.919493, 18.640506],
        'Tygerberg Hospital' => ['Tygerberg Hospital, Francie van Zijl Dr, Parow, Cape Town', -33.913661, 18.614302],
        'Vangate MOU' => ['Vangate Midwife Obstetric Unit, Athlone, Cape Town', -33.966667, 18.505],
        'Vanguard CHC' => ['Vanguard Community Health Centre, Bonteheuwel, Cape Town', -33.947635, 18.543696],
        'Vanguard MOU' => ['Vanguard Midwife Obstetric Unit, Bonteheuwel, Cape Town', -33.945304, 18.502718],
        'Victoria Hospital' => ['Victoria Hospital, Wynberg, Cape Town', -34.012182, 18.458624],
        'Western Cape Rehabilitation Centre' => ['Western Cape Rehabilitation Centre, Lentegeur, Mitchell\'s Plain, Cape Town', -34.049524, 18.619158],
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
