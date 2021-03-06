import { environment } from 'src/environments/environment';

const apiUrl = environment.api_url;

export const auth = {
    'login': `${apiUrl}/login`,
    'register': `${apiUrl}/register`,
    'send_reset_link': `${apiUrl}/send-reset-link`,
    'reset_password': `${apiUrl}/reset-password`
}

export const general = {
    'get_symptoms': `${apiUrl}/symptoms`,
    'get_physician_appointment_detail': (adminSlug) => { return `${apiUrl}/physician/${adminSlug}/meeting-detail` },
    'create_appointment': `${apiUrl}/appointments`,
    'email_invite': `${apiUrl}/invite-via-email`
}

export const profile = {
    'get_profile': `${apiUrl}/physician/profile`,
    'update_profile': `${apiUrl}/physician/profile`,
    'update_password': `${apiUrl}/physician/password`
}

export const appointment = {
    'get_appointment_detail': `${apiUrl}/physician/appointment-detail`,
    'update_appointment_info': `${apiUrl}/physician/appointment-detail`,
    'update_schedules': `${apiUrl}/physician/appointment-detail/schedules`,
    'update_questions': `${apiUrl}/physician/appointment-detail/questions`,
    'appointments': `${apiUrl}/physician/appointments`,
    'waiting_appointments': `${apiUrl}/physician/waiting-appointments`,
    'appointment_stats': `${apiUrl}/physician/appointment-stats`,
    'earnings': `${apiUrl}/physician/earnings`,
    'appointment_status': (appointmentId) => `${apiUrl}/physician/appointments/${appointmentId}/status`
}