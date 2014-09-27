require 'vending_machine'

describe VendingMachine do
  let(:vend) { VendingMachine.new }
  let(:stdout) { StringIO.new }
  before(:each) { $stdout = stdout }

  describe '#display' do
    it 'prints to our $stdout' do
      vend.display("Hello")

      expect(stdout.string).to eq("Hello\n")
    end
  end

  describe '#prompt' do
    it 'prints "> " to our $stdout by default' do
      vend.prompt

      expect(stdout.string).to eq("> ")
    end

    it 'prints to our $stdout without newline' do
      vend.prompt("Hello")

      expect(stdout.string).to eq("Hello")
    end
  end

  describe '#start' do
    def set_input(*inputs)
      allow(vend).to receive(:gets).and_return(*inputs)
    end

    def expect_output(outputs=[])
      output = [
        "Welcome to the Vending Machine.\n",
        "Please enter 'q' to exit.\n",
      ]

      output << "> "
      outputs.each do |item|
        output << item
        output << "> "
      end

      output << "Thank you for using the Vending Machine.\n"

      expect(stdout.string).to eq(output.join)
    end

    it "responds to 'q' with no other input" do
      set_input("q\n")

      vend.start

      expect_output()
    end

    it "responds to 'Q' with no other input" do
      set_input("Q\n")

      vend.start

      expect_output()
    end

    it "responds to 'hello' and 'q'" do
      set_input("Hello\n", "q\n")

      vend.start

      expect_output([
        "Hello\n",
      ])
    end
  end
end
