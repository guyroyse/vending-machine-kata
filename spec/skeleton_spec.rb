require 'vending_machine'

describe VendingMachine do
  let(:vend) { VendingMachine.new }
  let(:stdout) { StringIO.new }

  # Capture all the streams
  before(:each) do
    $stdout = stdout
  end

  describe '#display' do
    it 'prints to our $stdout' do
      vend.display("Hello")

      expect(stdout.string).to eq("Hello\n")
    end
  end

  describe '#start' do
    it "responds to 'q' with no other input" do
      allow(vend).to receive(:gets).and_return("q\n")
      vend.start
      expect(stdout.string).to eq("Welcome to the Vending Machine.\nPlease enter 'q' to exit.\n> Thank you for using the Vending Machine.\n")
    end

    it "responds to 'hello' and 'q'" do
      allow(vend).to receive(:gets).and_return("Hello\n", "q\n")
      vend.start
      expect(stdout.string).to eq("Welcome to the Vending Machine.\nPlease enter 'q' to exit.\n> hello\n> Thank you for using the Vending Machine.\n")
    end
  end
end
