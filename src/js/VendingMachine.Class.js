export class VendingMachine {
    constructor() {
        this._accumulator = 0;
    }

    insertCoin(name) {
        if (name.toLowerCase() === 'nickel') {
            this._accumulator += 5;
        } else if (name.toLowerCase() === 'dime') {
            this._accumulator += 10;
        } else if (name.toLowerCase() === 'quarter') {
            this._accumulator += 25;
        } else return "I'm not familiar with the kind of thing I'm seeing"
    }

    get accumulator() {
        return this._accumulator;
    }
}