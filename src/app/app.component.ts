import { Component, OnInit } from '@angular/core';

import { Trip } from './trip';
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
  isVisibleService = false;
  isVisibleAdmin = false;

  classApplied = false;
  addingCar = false;
  addingDriver = false;
  addingGuest = false;
  addingBooking = false;
  newCarName = '';
  newCarPassengers = 0;
  newDriverName = '';
  newGuestName = '';
  guestId = 0;
  pickupId = 0;
  dropoffId = 0;

  tripFilterDropoffs = {direction: 'Hotel->Airport'};
  tripFilterPickups = {direction: 'Airport->Hotel'};

  trips: Trip[]; // Array for Transportations
  cars: Car[];
  drivers: Driver[];
  guests: Guest[];

  pickupTrips: Trip[];

  onGuestSelected(id: number) {
    this.guestId = id;
  }

  onPickupSelected(id: number) {
    this.pickupId = id;
  }

  onDropoffSelected(id: number) {
    this.dropoffId = id;
  }

  checkGuestPickup(guestId: number): number {
    let tripId = 0;
    this.trips.forEach((trip) => {
      if (trip.direction === 'Airport->Hotel') {
        trip.passengers.forEach((passenger) => {
          if (passenger.guestid === guestId) {
            tripId = trip.tripid;
          }
        });
      }
    });
    return tripId;
  }

  checkGuestDropoff(guestId: number): number {
    let tripId = 0;
    this.trips.forEach((trip) => {
      if (trip.direction === 'Hotel->Airport') {
        trip.passengers.forEach((passenger) => {
          if (passenger.guestid === guestId) {
            tripId = trip.tripid;
          }
        });
      }
    });
    return tripId;
  }

  scrollToElement($element): void {
    console.log($element);
    $element.scrollIntoView({behavior: 'smooth', block: 'start', inline: 'nearest'});
  }
  displayService($element): void {
    this.isVisibleService = true;
    $element.scrollIntoView({behavior: 'smooth', block: 'start', inline: 'nearest'});
  }
  displayAdmin($element): void {
    this.isVisibleAdmin = true;
    $element.scrollIntoView({behavior: 'smooth', block: 'start', inline: 'nearest'});
  }
  openNav(): void {
    this.classApplied = !this.classApplied;

  }

  constructor(private dataService: DataService) { }

  ngOnInit() { /* Get all Trips, Cars, Drivers and Guests at init */
    this.getTrips();
    this.getCars();
    this.getDrivers();
    this.getGuests();
  }

  //////// GET //////////
  getTrips(): void {
    this.dataService.getTrips()
    .subscribe(trips => this.trips = trips);
  }

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

  //////// ADD Guest to Trip //////////
  updateGuest(guestid: number, pickupid: number, dropoffid: number): void {
    if (pickupid !== 0) {
      this.dataService.updateGuestPickup(guestid, pickupid).subscribe();
    }
    if (dropoffid !== 0) {
      this.dataService.updateGuestDropoff(guestid, dropoffid).subscribe();
    }
    // this.getTrips(); // Get the updated Trip-List
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

