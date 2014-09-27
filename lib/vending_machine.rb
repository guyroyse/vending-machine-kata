class VendingMachine
  attr_accessor_with_default :value, 0.0

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
    if value.is_zero?
      display "INSERT COIN"
    else
      display "$#{value}"
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
          value += 0.25
        else
          display "'#{input}' rejected"
      end
    end

    display "Thank you for using the Vending Machine."
  end
end
