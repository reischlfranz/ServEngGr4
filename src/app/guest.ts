export class Guest {
    guestid: number;
    guestname: string;
    pickuptrip: number;
    dropofftrip: number;

    constructor(guestid: number, guestname: string, pickuptrip: number, dropofftrip: number) {
        this.guestid = guestid;
        this.guestname = guestname;
        this.pickuptrip = 0;
        this.dropofftrip = 0;
    }
}
