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

  describe '#print_help' do
    it 'prints the help information' do
      vend.print_help

      expect(stdout.string).to eq([
        "Vending Machine Help Menu:",
        "Type 'q' to exit.",
      ].join("\n").concat("\n"))
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
        "\n",
        "INSERT COIN\n",
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

    it "prints the help menu when the input is 'h'" do
      set_input("h\n", "q\n")

      vend.start

      expect_output([
        "Vending Machine Help Menu:\nType 'q' to exit.\n",
      ])
    end

    it "prints the help menu when the input is 'H'" do
      set_input("H\n", "q\n")

      vend.start

      expect_output([
        "Vending Machine Help Menu:\nType 'q' to exit.\n",
      ])
    end
  end
end
