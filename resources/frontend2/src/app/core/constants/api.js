import { environment } from 'src/environments/environment';

const apiUrl = environment.api_url;

export const auth = {
    'login': `${apiUrl}/login`
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
    'update_questions': `${apiUrl}/physician/appointment-detail/questions`
}