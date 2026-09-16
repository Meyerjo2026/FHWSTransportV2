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

    /**
     * Undergraduate qualifications offered by CPUT's Faculty of Health &
     * Wellness Sciences (https://www.cput.ac.za/faculties/fhws/courses).
     * Postgraduate qualifications (PG Dip, Master's, Doctorates) are
     * intentionally excluded — this list is undergraduate-only.
     */
    public const QUALIFICATIONS = [
        // Biomedical Sciences
        'Higher Certificate in Biomedical Sciences',
        'Bachelor of Health Sciences in Medical Laboratory Science (Extended Curriculum Programme)',
        'Bachelor of Health Sciences in Medical Laboratory Science (Articulation)',
        'Bachelor of Health Sciences in Medical Laboratory Science',
        // Dental Sciences
        'Higher Certificate in Dental Assisting',
        // Emergency Medical Sciences
        'Higher Certificate in Emergency Medical Care',
        'Diploma in Emergency Care',
        'Bachelor of Emergency Medical Care (Extended Curriculum Programme)',
        'Bachelor of Emergency Medical Care',
        // Medical Imaging & Therapeutic Sciences
        'Bachelor of Science in Diagnostic Radiography',
        'Bachelor of Science in Diagnostic Ultrasound',
        'Bachelor of Science in Nuclear Medicine Technology',
        'Bachelor of Science in Radiation Therapy',
        // Nursing Sciences
        'Bachelor of Nursing (Extended Curriculum Programme)',
        'Bachelor of Nursing',
        // Ophthalmic Sciences
        'Bachelor of Health Sciences in Opticianry',
        // Somatology
        'Diploma in Somatology',
        'Advanced Diploma in Somatology',
        // Non-diploma/degree study (Health and Wellness)
        'Health and Wellness: Non-Diploma/Degree',
    ];

    public const DEFAULT_RATE = 673.28;

    public const MAX_STUDENTS_PER_TRIP = 20;
}
