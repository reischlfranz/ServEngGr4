import { Driver } from './driver';
import { Car } from './car';
import { Guest } from './guest';

export class Trip { // Transportation
    tripid: number;
    direction: string;
    driver: Driver;
    car: Car;
    passengers: Guest[];
    tripdate: string;
    timestart: number;
    timearrival: number;
    triptime: number;

    constructor(tripid: number, direction: string, driver: Driver, car: Car, passengers: Guest[],
                tripdate: string, timestart: number, timearrival: number, triptime: number) {
        this.tripid = tripid;
        this.direction = direction;
        this.driver = driver;
        this.car = car;
        this.passengers = passengers;
        this.tripdate = tripdate;
        this.timestart = timestart;
        this.timearrival = timearrival;
        this.triptime = triptime;
   }
}
