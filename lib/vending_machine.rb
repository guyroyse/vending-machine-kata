class VendingMachine
  attr_accessor :value

  def initialize
    @value = 0.0
  end

  def display(str)
    puts str
  end

  def prompt(str='> ')
    print str
  end

  def get_input
    prompt
    gets.chomp
  end

  def display_help
    display "Vending Machine Help Menu:"
    display "Type 'q' to exit."
    display "Type 'p' to display available products."
    display "Type '1', '2, or '3' to buy something."
    display "Type 'r' to return coins."
    display "Acceptable tender are 'quarter', 'dime', and 'nickel'."
    display "Or, you can say 'D## T## W##' for diameter, thickness, and weight in mm and g."
  end

  # TODO: Create a variable for this.
  PRODUCTS = [
    { :name => 'Cola',  :price => 1.00, :selector => '1' },
    { :name => 'Chips', :price => 0.50, :selector => '2' },
    { :name => 'Candy', :price => 0.65, :selector => '3' },
  ]
  def display_products
    display "Vending Machine Products:"
    PRODUCTS.each do |product|
      display "#{product[:name]}: #{"$%0.2f" % product[:price]} - buy with '#{product[:selector]}'"
    end
  end

  def try_to_purchase(selection)
    PRODUCTS.each do |product|
      next unless product[:selector] == selection

      if product[:price] > @value
        display "PRICE: #{"$%0.2f" % product[:price]}"
      else
        display "Dispensing #{product[:name]}"
        @value -= product[:price]
      end
    end

    return false
  end

  def display_value
    if value.zero?
      display "INSERT COIN"
    else
      display "$#{"%0.2f" % value}"
    end
  end

  def return_coins
    display "Returning #{"$%0.2f" % @value}" unless value.zero?
    @value = 0.0
  end

  # NOTE: This does not have a specific test for it. But, all of its cases
  # are tested in the tests for #find_coin.
  def in_variance?(target, value, variance=0.05)
    return false if target*(1-variance) > value
    return false if target*(1+variance) < value
    return true
  end

  # From: http://www.usmint.gov/about_the_mint/?action=coin_specifications
  # Quarter: D24.26 T1.75 W5.670
  # Dime:    D17.91 T1.35 W2.268
  # Nickel:  D21.21 T1.95 W5.000
  # Penny:   D19.05 T1.52 W2.500
  COINS = {
    :quarter => {
      :diameter => 24.26,
      :thickness => 1.75,
      :weight => 5.670,
      :value => 0.25,
    },
    :dime => {
      :diameter => 17.91,
      :thickness => 1.35,
      :weight => 2.268,
      :value => 0.10,
    },
    :nickel => {
      :diameter => 21.21,
      :thickness => 1.95,
      :weight => 5.000,
      :value => 0.05,
    },
  }

  def find_coin(diameter, thickness, weight)
    COINS.keys.each do |type|
      if in_variance?(COINS[type][:diameter], diameter) &&
         in_variance?(COINS[type][:thickness], thickness) &&
         in_variance?(COINS[type][:weight], weight)
        return COINS[type][:value]
      end
    end

    return nil
  end

  # NOTE: This does not have a specific test for it. But, all of its cases
  # are tested in the tests for #start
  def handle_input(input)
    case input.downcase
      when 'h'
        display_help
      when 'p'
        display_products
      when 'r'
        return_coins
      when 'q'
        return false
      when 'quarter'
        handle_input "D24.26 T1.75 W5.670"
      when 'dime'
        handle_input "D17.91 T1.35 W2.268"
      when 'nickel'
        handle_input "D21.21 T1.95 W5.000"
      when 'penny'
        # This is a special case to accomodate users entering 'penny'
        display "'#{input}' is not acceptable tender." 
      when /^\s*[Dd]([0-9.]+)\s+[Tt]([0-9.]+)\s+[Ww]([0-9.]+)\s*$/
        if value = find_coin($1.to_f, $2.to_f, $3.to_f)
          @value += value
        else
          display "'#{input}' is not acceptable tender." 
        end
      when '1', '2', '3'
        try_to_purchase(input)
      else
        display "'#{input}' rejected."
    end

    return true
  end

  def start
    display "Welcome to the Vending Machine."
    display "Please enter 'q' to exit."
    display ""

    while true do
      display_value
      handle_input(get_input) || break
    end

    display "Thank you for using the Vending Machine."
  end
end
