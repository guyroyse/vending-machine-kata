import { VendingMachine } from '../../src/js/VendingMachine.Class.js';
let underTest = new VendingMachine;

describe('Deal with coins', () => {
    beforeEach(() => {
        underTest = new VendingMachine;
    })
    it('Should have a method for accepting coins', () => {
        expect(underTest.insertCoin).toEqual(jasmine.any(Function));
    })
    it('Should have a way of keeping track of the total value of coins accumulated', () => {
        underTest.insertCoin('nickel');
        expect(underTest.accumulator).toEqual(jasmine.any(Number));
    })
    it('Should accept nickels', () => {
        underTest.insertCoin('Nickel');
        expect(underTest.accumulator).toEqual(0.05);
    })
    it('Should accept dimes', () => {
        underTest.insertCoin('dime');
        expect(underTest.accumulator).toEqual(0.10);
    })
    it('Should accept quarters', () => {
        underTest.insertCoin('Quarter');
        expect(underTest.accumulator).toEqual(0.25);
    })
    it('Should reject invalid coins, like pennies', () => {
        underTest.insertCoin('penny');
        underTest.insertCoin('farthing');
        underTest.insertCoin('kroner');
        underTest.insertCoin('haypenny');
        expect(underTest.accumulator).toEqual(0);
    })
    it('Should have a place to put rejected coins', () => {
        underTest.insertCoin("CoinWeKnowDoesn'tExist");
        expect(underTest.accumulator).toEqual(0);
        expect(underTest.coinReturn).toEqual(jasmine.any(Array));
    })
});

describe('Vend products', () => {
    beforeEach(() => {
        underTest = new VendingMachine;
        underTest.insertCoin('quarter');
        underTest.insertCoin('quarter');
        underTest.insertCoin('quarter');
        underTest.insertCoin('quarter');
    });
    it('Should have a list of products', () => {
        expect(underTest._productList).toEqual(jasmine.any(Object));
    });
    it('Should have cola, chips, and candy', () => {
        expect(underTest._productList.hasOwnProperty('cola')).toBeTruthy();
        expect(underTest._productList.hasOwnProperty('chips')).toBeTruthy();
        expect(underTest._productList.hasOwnProperty('candy')).toBeTruthy();
    });
    it('Knows the correct price for each item to vend', () => {
        expect(parseFloat(underTest._productList.cola)).toEqual(1.00);
        expect(parseFloat(underTest._productList.chips)).toEqual(0.50);
        expect(parseFloat(underTest._productList.candy)).toEqual(0.65);
    })
    it('Dispenses cola when cola button pressed', () => {
        underTest.makeSelection('cola');
        expect(underTest.productVendingArea).toContain('cola');
    })
    it('Dispenses candy when candy button pressed', () => {
        underTest.makeSelection('candy');
        expect(underTest.productVendingArea).toContain('candy');
    });
    it('Dispenses chips when chips button pressed', () => {
        underTest.makeSelection('chips');
        expect(underTest.productVendingArea).toContain('chips');
    });
});

describe("Doesn't vend products sometimes", () => {
    beforeEach(() => {
        underTest = new VendingMachine;
    })
    it("Doesn't vend products if sufficient funds haven't been added", () => {
        underTest.makeSelection('cola');
        expect(underTest.productVendingArea).not.toContain('cola');
        underTest.makeSelection('candy');
        expect(underTest.productVendingArea).not.toContain('candy');
        underTest.makeSelection('chips');
        expect(underTest.productVendingArea).not.toContain('chips');
    });
    it("Doesn't vend products if an invalid selection is made", () => {
        underTest.makeSelection('One Million Dollars');
        expect(underTest.productVendingArea).not.toContain('One Million Dollars');
    })

});