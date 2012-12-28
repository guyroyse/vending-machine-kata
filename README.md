Vending Machine Kata
====================

In this exercise you will build the brains of a vending machine.  It will accept money, make change, maintain
inventory, and dispense products.  All the things that you might expect a vending machine to accomplish.

The point of this kata to to provide an larger than trivial exercise that can be used to practice TDD.  A significant
portion of the effort will be in determining what tests should be written and, more importantly, written next.

Features
========

Accept Coins
------------

As a vendor  
I want a vending machine that accepts coins  
So that I can collect money from the customer  

The vending machine will accept valid coins (nickels, dimes, and quarters) and reject invalid one (pennies).  When a
valid coin is inserted the amount of the coin will be added to the current amount and the display will be updated.
When there are no coins inserted, the machine displays INSERT COIN.  Rejected coins are placed in the coin return.

NOTE: The temptation here will be to create Coin objects that know their value.  However, this is not how a real
  vending machine works.  Instead, it identifies coins by their weight and size and then assigned a value to what
  was inserted.  You will need to do something similar.  This can be simulated using symbols.

Select Product
--------------

As a vendor  
I want customers to select products  
So that I can give them an incentive to put money in the machine  

There are three products: cola for $1.00, chips for $0.50, and candy for $0.65.  When the respective button is pressed
and enough money has been inserted, the product is dispensed and the machine displays THANK YOU.  If there is not enough
money then the machine displays PRICE and the price of the item.  In either case, if the display is checked again,
it will display INSERT COINS and the current amount will be set to $0.00. 

Make Change
-----------

As a vendor  
I want customers to receive correct change  
So that they will use the vending machine again  

When a product is selected that costs less than the amount of money in the machine, then the remaining amount is placed
in the coin return.

Return Coins
------------

Sold Out
--------

Exact Change Only
-----------------