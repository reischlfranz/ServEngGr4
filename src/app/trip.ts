import { Driver } from './driver';
import { Car } from './car';

export class Trip { // Transportation
    tripid: number;
    direction: string;
    driver: Driver;
    car: Car;
    timestart: number;
    timearrival: number;
    triptime: number;

    constructor(tripid: number, direction: string, driver: Driver, car: Car, timestart: number, timearrival: number, triptime: number) {
        this.tripid = tripid;
        this.direction = direction;
        this.driver = driver;
        this.car = car;
        this.timestart = timestart;
        this.timearrival = timearrival;
        this.triptime = triptime;
   }
}
