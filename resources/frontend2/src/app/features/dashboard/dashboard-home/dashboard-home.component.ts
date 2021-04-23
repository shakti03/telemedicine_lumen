import { Component, OnInit } from '@angular/core';
import { NotificationService } from 'src/app/core/services/notification.service';
import { Title } from '@angular/platform-browser';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';

import { AuthenticationService } from 'src/app/core/services/auth.service';
import { EmailInviteModalComponent } from 'src/app/shared/modals/email-invite-modal/email-invite-modal.component';

@Component({
  selector: 'app-dashboard-home',
  templateUrl: './dashboard-home.component.html',
  styleUrls: ['./dashboard-home.component.scss']
})
export class DashboardHomeComponent implements OnInit {
  currentUser: any;

  constructor(
    public dialog: MatDialog,
    private notificationService: NotificationService,
    private authService: AuthenticationService,
    private titleService: Title) {
  }

  ngOnInit() {
    this.currentUser = this.authService.getCurrentUser();
    this.titleService.setTitle('Dashboard');
  }

  copyLink() {
    // this.clipboard.copy(this.currentUser.room_link);
    this.notificationService.openSnackBar('Link Copied!', 3000);
  }

  emailLink() {
    const dialogRef = this.dialog.open(EmailInviteModalComponent, {
      data: {
        link: this.currentUser.room_link
      }
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log(result);
    });
  }
}
