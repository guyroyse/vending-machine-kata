class VendingMachine
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

  def start
    display "Welcome to the Vending Machine."
    display "Please enter 'q' to exit."

    input = get_input
    while true do
      case input.downcase
        when 'h'
          print_help
        when 'q'
          break
        else
          display input
      end
      input = get_input
    end

    display "Thank you for using the Vending Machine."
  end
end
