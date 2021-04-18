<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\Patient;
use App\Models\Appointment;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointments = Appointment::all();
        foreach ($appointments  as $appointment) {
            $patient = Patient::firstOrNew([
                'email' => $appointment->patient_email
            ]);

            $patient->name = $appointment->patient_name;
            if (!$appointment->phone)
                $patient->phone = $appointment->phone;

            if (!$patient->created_at)
                $patient->created_at = $appointment->created_at;

            $patient->save();
        }
    }
}
