export default class EventService {
    constructor() {
        this.registry = [];
        this.showLogs = true;
    }

    send(eventName, data = null) {
        this.log(`Event '${eventName}' sent to eventService.`);
        var actionsTaken = 0;
        for (var i = 0; i < this.registry.length; i++) {
            var r = this.registry[i];
            if (r.name === eventName) {
                r.action(data);
                actionsTaken++;
            }
        }
        if (actionsTaken === 0) {
            console.warn(`No action was taken to resolve event '${eventName}'`);
        }
    }

    register(eventName, fn) {
        this.log(`Response to event '${eventName}' registered with eventService.`);
        var reg = {
            name: eventName,
            action: fn
        };
        this.registry.push(reg);
    }

    listRegisteredEvents() {
        var events = [];
        for (let i = 0; i < this.registry.length; i++) {
            events.push(this.registry[i].name);
        }
        events.sort();
        return events;
    }
    log(text) {
        if (this.showLogs) {
            console.log(text);
        }
    }

}
