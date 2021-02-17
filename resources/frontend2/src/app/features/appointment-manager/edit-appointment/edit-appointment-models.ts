export class Schedule {
    start: string;
    end: string;
}
export class AppointmentDetail {
    name: string = '';
    location: string = 'phone';
    description: string = '';
    accept_payment: boolean | number;
    physician_fee: number;
}
export class Question {
    title: string = '';
}