import { Component, OnInit } from '@angular/core';

import { Car } from './car';
import { Driver } from './driver';
import { Guest } from './guest';

import { DataService } from './data.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  title = 'HotelService';
  isVisible = false;
  classApplied = false;

  cars: Car[];
  drivers: Driver[];
  guests: Guest[];

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

  constructor(private dataService: DataService) { }

  ngOnInit() { /* Get all Cars & Drivers at init */
    this.getCars();
    this.getDrivers();
  }

  //////// GET //////////
  getCars(): void {
    this.dataService.getCars()
    .subscribe(cars => this.cars = cars);
  }

  getDrivers(): void {
    this.dataService.getDrivers()
    .subscribe(drivers => this.drivers = drivers);
  }

  getGuests(): void {
    this.dataService.getGuests()
    .subscribe(guests => this.guests = guests);
  }

  //////// ADD //////////
  addCar(carname: string, carpassengers: number): void {
    carname = carname.trim();
    if (!carname || !carpassengers) { return; }
    this.dataService.addCar({ carname, carpassengers } as Car)
        .subscribe(car => {
          this.cars.push(car);
        });
  }

  addDriver(drivername: string): void {
    drivername = drivername.trim();
    if (!drivername) { return; }
    this.dataService.addDriver({ drivername } as Driver)
        .subscribe(driver => {
          this.drivers.push(driver);
        });
  }

  addGuest(guestname: string): void {
    guestname = guestname.trim();
    if (!guestname) { return; }
    this.dataService.addGuest({ guestname } as Guest)
        .subscribe(guest => {
          this.guests.push(guest);
        });
  }

  //////// Delete //////////
  deleteCar(car: Car): void {
    this.cars = this.cars.filter(c => c !== car);
    this.dataService.deleteCar(car).subscribe();
  }

  deleteDriver(driver: Driver): void {
    this.drivers = this.drivers.filter(d => d !== driver);
    this.dataService.deleteDriver(driver).subscribe();
  }

  deleteGuest(guest: Guest): void {
    this.guests = this.guests.filter(g => g !== guest);
    this.dataService.deleteGuest(guest).subscribe();
  }

}

