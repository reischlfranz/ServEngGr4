import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

import { Observable, of} from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';

import { Car } from './car';
import { Driver } from './driver';
import { Guest } from './guest';
import { Trip } from './trip';
import { Title } from '@angular/platform-browser';

@Injectable({
  providedIn: 'root'
})

export class DataService {

  apiUrl = 'https://hotelserviceservenggr4.azurewebsites.net/api/index.php';

  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' })
  };

  constructor(private http: HttpClient) { }

  //////// -------------------- //////////
  //////// Rest Call - Add Trips to Guests //////////

  updateGuestPickup(guestid: number, tripid: number): Observable<any> {
    const url = `${this.apiUrl + '/guests'}/${guestid + '/pickup'}`;

    return this.http.post<Guest>(url, {tripid}, this.httpOptions).pipe(
      catchError(this.handleError<Guest>('updateGuestPickup'))
    );
  }

  updateGuestDropoff(guestid: number, tripid: number): Observable<any> {
    const url = `${this.apiUrl + '/guests'}/${guestid + '/dropoff'}`;

    return this.http.post<Guest>(url, {tripid}, this.httpOptions).pipe(
      catchError(this.handleError<Guest>('updateGuestDropoff'))
    );
  }

  //////// -------------------- //////////
  //////// Rest Calls - Trips //////////

    /** GET trip from the server */
  getTrips(): Observable<Trip[]> {
    return this.http.get<Trip[]>(this.apiUrl + '/trips')
        .pipe(
          catchError(this.handleError<Trip[]>('getTrips', []))
        );
  }

  /** GET trip by id. Will 404 if id not found */
  getTrip(id: number): Observable<Trip> {
    const url = `${this.apiUrl + '/trips'}/${id}`;
    return this.http.get<Trip>(url).pipe(
      catchError(this.handleError<Trip>(`getTrip id=${id}`))
    );
  }

  //////// -------------------- //////////
  //////// Rest Calls - Guests //////////

  /** GET guests from the server */
  getGuests(): Observable<Guest[]> {
    return this.http.get<Guest[]>(this.apiUrl + '/guests')
        .pipe(
          catchError(this.handleError<Guest[]>('getGuests', []))
        );
  }

  /** GET guest by id. Will 404 if id not found */
  getGuest(id: number): Observable<Guest> {
    const url = `${this.apiUrl + '/guests'}/${id}`;
    return this.http.get<Guest>(url).pipe(
      catchError(this.handleError<Guest>(`getGuest id=${id}`))
    );
  }

  /** POST: add a new guest to the server */
  addGuest(guest: Guest): Observable<Guest> {
    return this.http.post<Guest>(this.apiUrl + '/guests', guest, this.httpOptions).pipe(
      catchError(this.handleError<Guest>('addGuest'))
    );
  }

  /** DELETE: delete the guest from the server */
  deleteGuest(guest: Guest | number): Observable<Guest> {
    const id = typeof guest === 'number' ? guest : guest.guestid;
    const url = `${this.apiUrl + '/guests'}/${id}`;

    return this.http.delete<Guest>(url, this.httpOptions).pipe(
      catchError(this.handleError<Guest>('deleteGuest'))
    );
  }

  //////// -------------------- //////////
  //////// Rest Calls - Drivers //////////

  /** GET drivers from the server */
  getDrivers(): Observable<Driver[]> {
    return this.http.get<Driver[]>(this.apiUrl + '/drivers')
        .pipe(
          catchError(this.handleError<Driver[]>('getDrivers', []))
        );
  }

  /** GET driver by id. Will 404 if id not found */
  getDriver(id: number): Observable<Driver> {
    const url = `${this.apiUrl + '/drivers'}/${id}`;
    return this.http.get<Driver>(url).pipe(
      catchError(this.handleError<Driver>(`getDriver id=${id}`))
    );
  }

  /** POST: add a new driver to the server */
  addDriver(driver: Driver): Observable<Driver> {
    return this.http.post<Driver>(this.apiUrl + '/drivers', driver, this.httpOptions).pipe(
      catchError(this.handleError<Driver>('addDriver'))
    );
  }

  /** DELETE: delete the driver from the server */
  deleteDriver(driver: Driver | number): Observable<Driver> {
    const id = typeof driver === 'number' ? driver : driver.driverid;
    const url = `${this.apiUrl + '/drivers'}/${id}`;

    return this.http.delete<Driver>(url, this.httpOptions).pipe(
      catchError(this.handleError<Driver>('deleteDriver'))
    );
  }

  //////// ----------------- //////////
  //////// Rest Calls - Cars //////////

  /** GET cars from the server */
  getCars(): Observable<Car[]> {
    return this.http.get<Car[]>(this.apiUrl + '/cars')
        .pipe(
          catchError(this.handleError<Car[]>('getCars', []))
        );
  }

  /** GET car by id. Will 404 if id not found */
  getCar(id: number): Observable<Car> {
    const url = `${this.apiUrl + '/cars'}/${id}`;
    return this.http.get<Car>(url).pipe(
      catchError(this.handleError<Car>(`getCar id=${id}`))
    );
  }

  /** POST: add a new car to the server */
  addCar(car: Car): Observable<Car> {
    return this.http.post<Car>(this.apiUrl + '/cars', car, this.httpOptions).pipe(
      catchError(this.handleError<Car>('addCar'))
    );
  }

  /** DELETE: delete the car from the server */
  deleteCar(car: Car | number): Observable<Car> {
    const id = typeof car === 'number' ? car : car.carid;
    const url = `${this.apiUrl + '/cars'}/${id}`;

    return this.http.delete<Car>(url, this.httpOptions).pipe(
      catchError(this.handleError<Car>('deleteCar'))
    );
  }

  private handleError<T>(operation = 'operation', result?: T) {
    return (error: any): Observable<T> => {

      console.error(error); // log to console

      // Let the app keep running by returning an empty result.
      return of(result as T);
    };
  }
}
