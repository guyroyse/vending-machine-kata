import { VendingMachine } from '../../src/js/VendingMachine.Class.js';
let underTest = new VendingMachine;

describe('Accept coins', () => {
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
        expect(underTest.accumulator).toEqual(5);
    })
    it('Should accept dimes', () => {
        underTest.insertCoin('dime');
        expect(underTest.accumulator).toEqual(10);
    })
    it('Should accept quarters', () => {
        underTest.insertCoin('Quarter');
        expect(underTest.accumulator).toEqual(25);
    })
    it('Should reject invalid coins, like pennies', () => {
        underTest.insertCoin('penny');
        underTest.insertCoin('farthing');
        underTest.insertCoin('kroner');
        underTest.insertCoin('haypenny');
        expect(underTest.accumulator).toEqual(0);
    })

});