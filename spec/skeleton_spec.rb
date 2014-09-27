require 'vending_machine'

describe VendingMachine, '#display' do
  before(:each) do
    @vend = VendingMachine.new

    $stdout = @stdout = StringIO.new
  end

  it 'prints to our $stdout' do
    @vend.display("Hello")

    expect(@stdout.string).to eq("Hello")
  end
end
