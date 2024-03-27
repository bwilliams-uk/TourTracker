export default class MacroService {
    constructor() {
        this.registry = [];
        this.showLogs = true;
    }

    run(macroName, data = null) {
        this.log(`Request to run macro '${macroName}' sent to MacroService.`);
        var actionsTaken = 0;
        for (var i = 0; i < this.registry.length; i++) {
            var r = this.registry[i];
            if (r.name === macroName) {
                r.action(data);
                actionsTaken++;
            }
        }
        if (actionsTaken === 0) {
            console.warn(`No action was taken to resolve request to run macro '${macroName}'.`);
        }
    }

    register(macroName, fn) {
        this.log(`Action for macro '${macroName}' registered with MacroService.`);
        var reg = {
            name: macroName,
            action: fn
        };
        this.registry.push(reg);
    }

    listRegisteredMacros() {
        var macros = [];
        for (let i = 0; i < this.registry.length; i++) {
            macros.push(this.registry[i].name);
        }
        macros.sort();
        return macros;
    }

    log(text) {
        if (this.showLogs) {
            console.log(text);
        }
    }

}
