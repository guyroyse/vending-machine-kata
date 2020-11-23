import { VendingMachine } from './VendingMachine.Class.js';

let vendingMachine = new VendingMachine();
let colaButton = document.querySelector(".cola__button");
let candyButton = document.querySelector(".candy__button");
let chipsButton = document.querySelector(".chips__button");
let accumulatorReadout = document.querySelector(".accumulator-readout")
let displayPosition = 0;
let displayText = ["$0.00"];

const updateDisplay(vendingMachine) {
    if (displayPosition > vendingMachine.displayText.length()) {
        displayText = vendingMachine.displayText[0]
    } else {
        displayText = vendingMachine.displayText[displayPosition];
        displayPosition += 1;
    }
}

const everySecondDo = window.setInterval(
    updateDisplay(vendingMachine), 1000);