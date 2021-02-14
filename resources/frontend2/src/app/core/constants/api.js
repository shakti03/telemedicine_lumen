import { environment } from 'src/environments/environment';

const apiUrl = environment.api_url;

export const auth = {
    'login': `${apiUrl}/login`
}

export const profile = {
    'get_profile': `${apiUrl}/profile`,
    'update_profile': `${apiUrl}/profile`
}

export const appointment = {
    'get_appointment_detail': `${apiUrl}/appointment-detail`,
    'update_appointment_info': `${apiUrl}/appointment-detail`,
    'update_schedules': `${apiUrl}/appointment-detail/schedules`,
    'update_questions': `${apiUrl}/appointment-detail/questions`
}