export class VendingMachine {
    constructor() {
        this._accumulator = 0;
        this._coinReturn = [];
        this._productList = {
            'cola': '1.00',
            'chips': '0.50',
            'candy': '0.65'
        };
        this._productVendingArea = [];
        this._displayText = [];
    }

    insertCoin(name) {
        if (name.toLowerCase() === 'nickel') {
            this._accumulator += 0.05;
        } else if (name.toLowerCase() === 'dime') {
            this._accumulator += 0.10;
        } else if (name.toLowerCase() === 'quarter') {
            this._accumulator += 0.25;
        } else {
            this._coinReturn.push(name);
            return "I'm not familiar with the kind of thing I'm seeing"
        }
    }
    makeSelection(product) {
        if (this._productList.hasOwnProperty(product)) {
            if (this._accumulator >= this._productList[product]) {
                this._productVendingArea.push(product);
            }

        }
    }

    displayOnce(textArray) {
        return textArray;
    }

    get displayText() {
        return this.displayText;
    }

    set displayText(textArray) {
        this._displayText = textArray;
    }

    get accumulator() {
        return this._accumulator;
    }

    set accumulator(value) {
        this._accumulator = value;
    }

    get coinReturn() {
        return this._coinReturn;
    }

    get productList() {
        return this._productList;
    }

    get productVendingArea() {
        return this._productVendingArea;
    }
}