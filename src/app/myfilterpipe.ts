import { Pipe, PipeTransform } from '@angular/core';
import { Trip } from './trip';

@Pipe({
    name: 'myfilter',
    pure: false
})
export class MyFilterPipe implements PipeTransform {
    transform(items: Trip[], filter: Trip): any {
        if (!items || !filter) {
            return items;
        }
        // filter items array, items which match and return true will be
        // kept, false will be filtered out
        return items.filter(item => item.direction.indexOf(filter.direction) !== -1);
    }
}