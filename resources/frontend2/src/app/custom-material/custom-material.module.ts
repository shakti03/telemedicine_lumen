import { NgModule, LOCALE_ID } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatMomentDateModule } from '@angular/material-moment-adapter';
import { MAT_DATE_FORMATS } from '@angular/material/core';
import { MatButtonToggleModule } from '@angular/material/button-toggle';
import { MatCheckboxModule, MAT_CHECKBOX_DEFAULT_OPTIONS, MatCheckboxDefaultOptions } from '@angular/material/checkbox';
import { MatBadgeModule } from '@angular/material/badge';
import { MatCardModule } from '@angular/material/card';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonModule } from '@angular/material/button';
import { MatInputModule } from '@angular/material/input';
import { MatSnackBarModule } from '@angular/material/snack-bar';
import { MatDialogModule } from '@angular/material/dialog';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatToolbarModule } from '@angular/material/toolbar';
import { MatListModule } from '@angular/material/list';
import { MatProgressBarModule } from '@angular/material/progress-bar';
import { MatMenuModule } from '@angular/material/menu';
import { MatSidenavModule } from '@angular/material/sidenav';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { MatTableModule } from '@angular/material/table';
import { MatSelectModule } from '@angular/material/select';
import { MatAutocompleteModule } from '@angular/material/autocomplete';
import { MatTabsModule } from '@angular/material/tabs';
import { MatTooltipModule } from '@angular/material/tooltip';
import { MatPaginatorModule } from '@angular/material/paginator';
import { MatChipsModule } from '@angular/material/chips';
import { MatSortModule } from '@angular/material/sort';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { MatExpansionModule } from '@angular/material/expansion';
import { MatGridListModule } from '@angular/material/grid-list';

// import {
//   MAT_DATE_FORMATS, MatIconModule, MatToolbarModule, MatButtonModule,
//   MatListModule, MatCardModule, MatProgressBarModule, MatInputModule,
//   MatSnackBarModule, MatMenuModule, MatSidenavModule,
//   MatProgressSpinnerModule, MatDatepickerModule, MatTableModule,
//   MatAutocompleteModule, MatDialogModule, MatTabsModule, MatTooltipModule,
//   MatSelectModule, MatPaginatorModule, MatChipsModule, MatButtonToggleModule,
//   MatSlideToggleModule, MatBadgeModule, MatCheckboxModule, MatExpansionModule, MatSortModule
// } from '@angular/material';
import { SelectCheckAllComponent } from './select-check-all/select-check-all.component';
import { DragDropModule } from '@angular/cdk/drag-drop';
import { MatSpinner } from '@angular/material/progress-spinner';

import { OverlayModule } from '@angular/cdk/overlay'

export const MY_FORMATS = {
  parse: {
    dateInput: 'DD MMM YYYY',
  },
  display: {
    dateInput: 'DD MMM YYYY',
    monthYearLabel: 'MMM YYYY',
    dateA11yLabel: 'LL',
    monthYearA11yLabel: 'MMMM YYYY'
  }
};

@NgModule({
  imports: [
    CommonModule,
    MatMomentDateModule,
    MatSidenavModule, MatIconModule, MatToolbarModule, MatButtonModule,
    MatListModule, MatCardModule, MatProgressBarModule, MatInputModule,
    MatSnackBarModule, MatProgressSpinnerModule, MatDatepickerModule,
    MatAutocompleteModule, MatTableModule, MatDialogModule, MatTabsModule,
    MatTooltipModule, MatSelectModule, MatPaginatorModule, MatChipsModule,
    MatButtonToggleModule, MatSlideToggleModule, MatBadgeModule, MatCheckboxModule,
    MatExpansionModule, DragDropModule, MatSortModule, MatGridListModule
  ],
  exports: [
    CommonModule,
    MatSidenavModule, MatIconModule, MatToolbarModule, MatButtonModule,
    MatListModule, MatCardModule, MatProgressBarModule, MatInputModule,
    MatSnackBarModule, MatMenuModule, MatProgressSpinnerModule, MatDatepickerModule,
    MatAutocompleteModule, MatTableModule, MatDialogModule, MatTabsModule,
    MatTooltipModule, MatSelectModule, MatPaginatorModule, MatChipsModule,
    MatButtonToggleModule, MatSlideToggleModule, MatBadgeModule, MatCheckboxModule,
    MatExpansionModule, SelectCheckAllComponent, DragDropModule, MatSortModule, MatGridListModule
  ],
  providers: [
    {
      provide: MAT_DATE_FORMATS,
      useValue: MY_FORMATS
    },
    { provide: MAT_CHECKBOX_DEFAULT_OPTIONS, useValue: { clickAction: 'check' } as MatCheckboxDefaultOptions },
    { provide: LOCALE_ID, useValue: 'en-gb' }
  ],
  entryComponents: [
    MatSpinner
  ],
  declarations: [SelectCheckAllComponent]
})
export class CustomMaterialModule {
  static forRoot() {
    return {
      ngModule: CustomMaterialModule,
      providers: [
      ]
    };
  }
}
