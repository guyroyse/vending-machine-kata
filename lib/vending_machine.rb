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

  def print_help
    display "Vending Machine Help Menu:"
    display "Type 'q' to exit."
  end

  def display_value
    if value.zero?
      display "INSERT COIN"
    else
      display "$#{"%0.2f" % value}"
    end
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
    :penny => {
      :diameter => 19.05,
      :thickness => 1.52,
      :weight => 2.500,
      :value => 0.01,
    },
  }

  def in_variance?(target, value, variance=0.05)
    return false if target*(1-variance) > value
    return false if target*(1+variance) < value
    return true
  end

  def find_coin(diameter, thickness, weight)
    if in_variance?(COINS[:quarter][:diameter], diameter) &&
       in_variance?(COINS[:quarter][:thickness], thickness) &&
       in_variance?(COINS[:quarter][:weight], weight)
      return COINS[:quarter][:value]
    elsif in_variance?(COINS[:dime][:diameter], diameter) &&
       in_variance?(COINS[:dime][:thickness], thickness) &&
       in_variance?(COINS[:dime][:weight], weight)
      return COINS[:dime][:value]
    elsif in_variance?(COINS[:nickel][:diameter], diameter) &&
       in_variance?(COINS[:nickel][:thickness], thickness) &&
       in_variance?(COINS[:nickel][:weight], weight)
      return COINS[:nickel][:value]
    elsif in_variance?(COINS[:penny][:diameter], diameter) &&
       in_variance?(COINS[:penny][:thickness], thickness) &&
       in_variance?(COINS[:penny][:weight], weight)
      return COINS[:penny][:value]
    else
      return nil
    end
  end

  def start
    display "Welcome to the Vending Machine."
    display "Please enter 'q' to exit."
    display ""

    while true do
      display_value
      input = get_input

      case input.downcase
        when 'h'
          print_help
        when 'q'
          break
        when 'quarter'
          @value += 0.25
        when 'dime'
          @value += 0.10
        when 'nickel'
          @value += 0.05
        when 'penny'
          display "'#{input}' is not acceptable tender."
        #when /^\s*[Dd]([0-9.]+)\s+[Tt]([0-9.]+)\s+[Ww]([0-9.]+)\s*$/
        #  display "'#{input}' rejected" unless find_coin($1.to_f, $2.to_f, $3.to_f)
          #display "Diam #{$1} Thic #{$2} Weig #{$3}"
        else
          display "'#{input}' rejected"
      end
    end

    display "Thank you for using the Vending Machine."
  end
end
