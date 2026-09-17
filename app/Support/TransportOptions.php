<?php

namespace App\Support;

class TransportOptions
{
    /**
     * The pickup point for every trip — students are always collected
     * from and returned to CPUT Bellville Campus. Coordinates verified
     * against both OpenStreetMap/Nominatim and Google Maps (agree
     * within ~70m) — the previous values were ~1.2km off, closer to
     * UWC than CPUT's own Bellville Campus grounds.
     */
    public const PICKUP_POINT = 'Cape Peninsula University of Technology (Bellville Campus)';

    public const PICKUP_LAT = -33.931874;

    public const PICKUP_LNG = 18.642764;

    /**
     * Seed data for clinical_sites: name => [address, lat, lng].
     * Compiled from every destination actually billed across the HG
     * Travelling Services invoices in use (student placements, not
     * invented). Coordinates are geocoded (OpenStreetMap Nominatim,
     * cross-checked against Google Maps for a sample of sites —
     * Tygerberg, Groote Schuur, Khayelitsha District, Cape Town
     * International Airport — all within ~350m), with one exception
     * kept as a suburb-level manual estimate where geocoding returned
     * a false-positive match: 'Ikhwezi Clinic' (matched a same-named
     * facility ~18km away in Strand). Suitable for the admin placement
     * map overview, not turn-by-turn navigation. Used only by the
     * seeder — the live dropdown/map read from the database so admins
     * can add/edit sites without a code change.
     */
    public const SITE_SEED = [
        'Red Cross Air Mercy Service (AMS)' => ['Air Mercy Service, Cape Town International Airport, Matroosfontein, Cape Town', -33.968871, 18.599760],
        '107 Emergency Centre & Ambulance Service' => ['107 Emergency Centre & Ambulance Service, Cape Town', -33.9233245, 18.41804300000001],
        'Air Mercy Services' => ['Air Mercy Services, Cape Town', -33.978167, 18.594918000000007],
        'Alexandra Hospital' => ['Alexandra Hospital, Maitland, Cape Town', -33.9225, 18.493056],
        'Anchusa CPOA' => ['Anchusa Home for the Aged, Elsies River, Cape Town', -33.910436, 18.568955],
        'Avondrust CPOA' => ['Avondrust Home for the Aged, Kraaifontein, Cape Town', -33.827029, 18.648119],
        'Bishop Lavis CHC' => ['Bishop Lavis Community Health Centre, Bishop Lavis, Cape Town', -33.949335, 18.581822],
        'College of Emergency Care' => ['College of Emergency Care, Cape Town', -33.915494699999999, 18.61186090000001],
        'Delft Community Health Centre' => ['Delft Community Health Centre, Delft, Cape Town', -33.973854, 18.641662],
        'Emergency Medical Services (EMS) - Vredendal' => ['Emergency Medical Services (EMS), Vredendal', -31.6699315, 18.503888200000006],
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
        'Hout Bay Volunteer EMS' => ['Hout Bay Volunteer EMS, Hout Bay, Cape Town', -34.0295662, 18.35704570000007],
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
        'Life Vincent Pallotti Hospital' => ['Life Vincent Pallotti Hospital, Cape Town', -33.9443605, 18.49009860000001],
        'Lotus River CHC' => ['Lotus River Community Health Centre, Lotus River, Cape Town', -34.026705, 18.507736],
        'Louis Leipoldt Medi-Clinic' => ['Louis Leipoldt Medi-Clinic, Cape Town', -33.9013298, 18.613294900000028],
        'Lotus River CPOA' => ['Lotus River Home for the Aged, Lotus River, Cape Town', -34.039204, 18.51935],
        'Macassar CHC' => ['Macassar Community Health Centre, Macassar, Cape Town', -34.066116, 18.767495],
        'Macassar MOU' => ['Macassar Midwife Obstetric Unit, Macassar, Cape Town', -34.066116, 18.767495],
        'Manor Care CPOA' => ['Manor Care Home for the Aged, Plumstead, Cape Town', -34.02, 18.477222],
        'Metro Ambulance Services' => ['Metro Ambulance Services, Cape Town', -33.8917999, 18.609369900000047],
        'Metro EMS Northern Division' => ['Metro EMS Northern Division, Cape Town', -33.9127589, 18.6160621],
        'Metro Emergency Medical Service - Western Division' => ['Metro Emergency Medical Service - Western Division, Cape Town', -33.9351959, 18.48955239999998],
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
        'South African Paramedic Services' => ['South African Paramedic Services, Cape Town', -33.874696, 18.51337799999999],
        'St Vincent CDC' => ['St Vincent Community Day Centre, Nyanga, Cape Town', -33.992819, 18.559813],
        'St Vincent CHC' => ['St Vincent Community Health Centre, Nyanga, Cape Town', -33.992819, 18.559813],
        'Stellenbosch Hospital' => ['Stellenbosch Hospital, Rosenhof St, Stellenbosch', -33.930375, 18.869975],
        'Strand CHC' => ['Strand Community Health Centre, Strand, Cape Town', -34.109, 18.823],
        'Symphony CDC' => ['Symphony Community Day Centre, Symphony Way, Bellville, Cape Town', -33.919493, 18.640506],
        'Tygerberg Hospital' => ['Tygerberg Hospital, Francie van Zijl Dr, Parow, Cape Town', -33.913661, 18.614302],
        'Tygerberg Hospital Anesthetic Department' => ['Tygerberg Hospital Anesthetic Department, Francie van Zijl Dr, Parow, Cape Town', -33.9105696, 18.612292900000057],
        'Vangate MOU' => ['Vangate Midwife Obstetric Unit, Athlone, Cape Town', -33.966667, 18.505],
        'Vanguard CHC' => ['Vanguard Community Health Centre, Bonteheuwel, Cape Town', -33.947635, 18.543696],
        'Vanguard MOU' => ['Vanguard Midwife Obstetric Unit, Bonteheuwel, Cape Town', -33.945304, 18.502718],
        'Victoria Hospital' => ['Victoria Hospital, Wynberg, Cape Town', -34.012182, 18.458624],
        'Western Cape Rehabilitation Centre' => ['Western Cape Rehabilitation Centre, Lentegeur, Mitchell\'s Plain, Cape Town', -34.049524, 18.619158],
    ];

    /**
     * Clinical site categories used for the admin filter. "Ambulance
     * Base" and "Tertiary Hospital" are assigned explicitly below since
     * they don't follow a name-suffix convention; everything else is
     * derived from the naming conventions already present in
     * SITE_SEED (CHC/MOU/CDC/CPOA suffixes, "Hospital"/"Clinic" in the
     * name). Kept as a static list so an admin editing a site's type
     * picks from the same fixed set the filter offers.
     */
    public const TYPE_OPTIONS = [
        'Ambulance Base', 'Tertiary Hospital', 'District Hospital',
        'CHC', 'MOU', 'CDC', 'CPOA', 'Clinic', 'Other',
    ];

    private const AMBULANCE_BASES = [
        'Red Cross Air Mercy Service (AMS)', '107 Emergency Centre & Ambulance Service',
        'Air Mercy Services', 'College of Emergency Care', 'Metro Ambulance Services',
        'Metro EMS Northern Division', 'Metro Emergency Medical Service - Western Division',
        'Emergency Medical Services (EMS) - Vredendal', 'Hout Bay Volunteer EMS',
        'South African Paramedic Services',
    ];

    private const TERTIARY_HOSPITALS = [
        'Groote Schuur Hospital', 'Tygerberg Hospital', 'Tygerberg Hospital Anesthetic Department',
    ];

    private const OTHER_SITES = [
        'Lentegeur Psychiatric Hospital', 'Mowbray Maternity Hospital',
        "Red Cross War Memorial Children's Hospital", 'Western Cape Rehabilitation Centre',
        'Life Vincent Pallotti Hospital', 'Louis Leipoldt Medi-Clinic', 'Pinelands Place',
    ];

    public static function deriveType(string $name): string
    {
        if (in_array($name, self::AMBULANCE_BASES, true)) {
            return 'Ambulance Base';
        }
        if (in_array($name, self::TERTIARY_HOSPITALS, true)) {
            return 'Tertiary Hospital';
        }
        if (in_array($name, self::OTHER_SITES, true)) {
            return 'Other';
        }
        if (str_ends_with($name, 'CHC')) {
            return 'CHC';
        }
        if (str_ends_with($name, 'MOU')) {
            return 'MOU';
        }
        if (str_ends_with($name, 'CDC')) {
            return 'CDC';
        }
        if (str_ends_with($name, 'CPOA')) {
            return 'CPOA';
        }
        if (str_contains($name, 'Clinic')) {
            return 'Clinic';
        }
        if (str_contains($name, 'Hospital')) {
            return 'District Hospital';
        }

        return 'Other';
    }

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

    public const YEAR_OPTIONS = ['Year 0', 'Year 1', 'Year 2', 'Year 3', 'Year 4'];

    /**
     * Which study years apply to a given qualification, so the year
     * dropdown on the student request form only offers years that make
     * sense for what they're studying — a Higher Certificate has one
     * year, a Bachelor with an Extended Curriculum Programme (ECP) has
     * a Year 0 foundation year, an Articulation entry starts at Year 2,
     * etc. Matched by keyword rather than a hand-built list per
     * qualification since the naming conventions in
     * QUALIFICATIONS_BY_DEPARTMENT are consistent.
     */
    public static function yearsFor(?string $qualification): array
    {
        if (! $qualification || str_contains($qualification, 'Non-Diploma/Degree')) {
            return ['N/A'];
        }
        if (str_contains($qualification, 'Higher Certificate')) {
            return ['Year 1'];
        }
        if (str_contains($qualification, 'Extended Curriculum Programme')) {
            return ['Year 0', 'Year 1', 'Year 2', 'Year 3', 'Year 4'];
        }
        if (str_contains($qualification, 'Articulation')) {
            return ['Year 2', 'Year 3', 'Year 4'];
        }
        if (str_contains($qualification, 'Advanced Diploma')) {
            return ['Year 4'];
        }
        if (str_contains($qualification, 'Diploma')) {
            return ['Year 1', 'Year 2', 'Year 3'];
        }

        return ['Year 1', 'Year 2', 'Year 3', 'Year 4'];
    }

    public static function isValidYear(string $qualification, string $year): bool
    {
        return in_array($year, self::yearsFor($qualification), true);
    }
}
