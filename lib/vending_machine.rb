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

  def start
    display "Welcome to the Vending Machine."
    display "Please enter 'q' to exit."

    input = get_input
    while input.downcase != 'q' do
      display input
      input = get_input
    end

    display "Thank you for using the Vending Machine."
  end
end
