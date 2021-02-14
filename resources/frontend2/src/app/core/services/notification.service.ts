import { Injectable } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';

@Injectable({
    providedIn: 'root'
})
export class NotificationService {

    constructor(private snackBar: MatSnackBar) { }

    public openSnackBar(message: string, duration?: number) {
        if (duration) {
            this.snackBar.open(message, 'close', {
                duration: duration
            });
        } else {
            this.snackBar.open(message, 'close');
        }
    }
}
