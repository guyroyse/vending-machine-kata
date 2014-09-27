require 'vending_machine'

describe VendingMachine, '#truth' do
  it 'returns true' do
    expect(VendingMachine.new.truth).to be(true)
  end
end
