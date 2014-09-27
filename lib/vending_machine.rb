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
        else
          display "'#{input}' rejected"
      end
    end

    display "Thank you for using the Vending Machine."
  end
end
