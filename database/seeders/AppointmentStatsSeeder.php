<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\AppointmentStat;

class AppointmentStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appointments = Appointment::join('patients', 'patients.email', 'appointments.patient_email')
            ->select('appointments.*', 'patients.created_at as patient_created_at')
            ->get();
        $emails = [];
        foreach ($appointments  as $appointment) {
            $date = Carbon::parse($appointment->appointment_date);
            $stat = AppointmentStat::firstOrNew([
                'user_id'   => $appointment->meeting->user_id,
                'stat_month' => $date->month,
                'stat_year' => $date->year,
                'stat_date' => $date->format('Y-m-t')
            ]);

            if (in_array($appointment->patient_email, $emails)) {
                $patDate = Carbon::parse($appointment->patient_created_at);
                if ($patDate->month == $date->month && $patDate->year == $date->year) {
                    $stat->old_patients_count = $stat->old_patients_count ? $stat->old_patients_count + 1 : 1;
                } else {
                    $stat->new_patients_count = $stat->new_patients_count ? $stat->new_patients_count + 1 : 1;
                }
            } else {
                $stat->new_patients_count = $stat->new_patients_count ? $stat->new_patients_count + 1 : 1;
            }
            $stat->save();

            $emails[] = $appointment->patient_email;
        }
    }
}
