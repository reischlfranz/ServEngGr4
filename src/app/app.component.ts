import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'HotelService';
  isVisible = false;
  classApplied = false;
  scrollToElement($element): void {
    console.log($element);
    $element.scrollIntoView({behavior: 'smooth', block: 'start', inline: 'nearest'});
  }
  displayService($element): void {
    this.isVisible = true;
    $element.scrollIntoView({behavior: 'smooth', block: 'start', inline: 'nearest'});
  }
  openNav(): void {
    this.classApplied = !this.classApplied;

  }
}

