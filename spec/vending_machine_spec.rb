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

  describe '#display_help' do
    it 'prints the help information' do
      vend.display_help

      expect(stdout.string).to eq([
        "Vending Machine Help Menu:",
        "Type 'q' to exit.",
        "Type 'p' to display available products.",
        "Type '1', '2, or '3' to buy something.",
        "Type 'r' to return coins.",
        "Acceptable tender are 'quarter', 'dime', and 'nickel'.",
        "Or, you can say 'D## T## W##' for diameter, thickness, and weight in mm and g."
      ].join("\n").concat("\n"))
    end
  end

  describe '#display_products' do
    it 'displays the avaialble products' do
      vend.display_products

      expect(stdout.string).to eq([
        "Vending Machine Products:",
        "Cola: $1.00 - buy with '1'",
        "Chips: $0.50 - buy with '2'",
        "Candy: $0.65 - buy with '3'",
      ].join("\n").concat("\n"))
    end
  end

  describe '#find_coin' do
    it "can find a quarter (within variance)" do
      # Exact
      expect(vend.find_coin(24.26, 1.75, 5.670)).to eq(0.25)

      # Within variance
      expect(vend.find_coin(24.1, 1.75, 5.670)).to eq(0.25)
      expect(vend.find_coin(25.1, 1.75, 5.670)).to eq(0.25)
      expect(vend.find_coin(24.26, 1.79, 5.670)).to eq(0.25)
      expect(vend.find_coin(24.26, 1.69, 5.670)).to eq(0.25)
      expect(vend.find_coin(24.26, 1.75, 5.770)).to eq(0.25)
      expect(vend.find_coin(24.26, 1.75, 5.570)).to eq(0.25)

      # All below
      expect(vend.find_coin(24.1, 1.70, 5.570)).to eq(0.25)

      # All above
      expect(vend.find_coin(24.5, 1.80, 5.920)).to eq(0.25)

      # Not correct
      expect(vend.find_coin(22.1, 1.75, 5.670)).to eq(nil)
      expect(vend.find_coin(24.1, 1.55, 5.670)).to eq(nil)
    end
    it "can find a dime (within variance)" do
      # Exact
      expect(vend.find_coin(17.91, 1.35, 2.268)).to eq(0.10)

      # Within variance
      expect(vend.find_coin(17.51, 1.35, 2.268)).to eq(0.10)
      expect(vend.find_coin(18.21, 1.35, 2.268)).to eq(0.10)

      # All below

      # All above

      # Not correct
    end
    it "can find a nickel (within variance)" do
      # Exact
      expect(vend.find_coin(21.21, 1.95, 5.000)).to eq(0.05)

      # Within variance
      expect(vend.find_coin(21.71, 1.95, 5.000)).to eq(0.05)
      expect(vend.find_coin(20.21, 1.95, 5.000)).to eq(0.05)

      # All below

      # All above

      # Not correct
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

    describe "non-monetary commands" do
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

      it "prints the help menu when the input is 'h'" do
        set_input("h\n", "q\n")

        vend.start

        expect_output([
          [
            "Vending Machine Help Menu:",
            "Type 'q' to exit.",
            "Type 'p' to display available products.",
            "Type '1', '2, or '3' to buy something.",
            "Type 'r' to return coins.",
            "Acceptable tender are 'quarter', 'dime', and 'nickel'.",
            "Or, you can say 'D## T## W##' for diameter, thickness, and weight in mm and g.",
            "INSERT COIN\n",
          ].join("\n")
        ])
      end

      it "prints the help menu when the input is 'H'" do
        set_input("H\n", "q\n")

        vend.start

        expect_output([
          [
            "Vending Machine Help Menu:",
            "Type 'q' to exit.",
            "Type 'p' to display available products.",
            "Type '1', '2, or '3' to buy something.",
            "Type 'r' to return coins.",
            "Acceptable tender are 'quarter', 'dime', and 'nickel'.",
            "Or, you can say 'D## T## W##' for diameter, thickness, and weight in mm and g.",
            "INSERT COIN\n",
          ].join("\n")
        ])
      end

      it "displays the products when the input is 'p'" do
        set_input("p\n", "q\n")

        vend.start

        expect_output([
          [
            "Vending Machine Products:",
            "Cola: $1.00 - buy with '1'",
            "Chips: $0.50 - buy with '2'",
            "Candy: $0.65 - buy with '3'",
            "INSERT COIN\n",
          ].join("\n")
        ])
      end

      describe "returns the coins entered (if any) on 'r'" do
        it "handles returns without any coins entered" do
          set_input("r\n", "q\n")

          vend.start

          expect_output([
            "INSERT COIN\n",
          ])
        end

        it "handles returns of one coin" do
          set_input("quarter\n", "r\n", "q\n")

          vend.start

          expect_output([
            "$0.25\n",
            "Returning $0.25\nINSERT COIN\n",
          ])
        end

        it "handles returns of one coin, then additional coins" do
          set_input("quarter\n", "r\n", "dime\n", "q\n")

          vend.start

          expect_output([
            "$0.25\n",
            "Returning $0.25\nINSERT COIN\n",
            "$0.10\n",
          ])
        end
      end
    end

    it "rejects invalid input" do
      set_input("floober\n", "blah\n", "q\n")

      vend.start

      expect_output([
        "'floober' rejected.\nINSERT COIN\n",
        "'blah' rejected.\nINSERT COIN\n",
      ])
    end

    describe "handling money" do
      it "responds to 'quarter'" do
        set_input("quarter\n", "q\n")

        vend.start

        expect_output([
          "$0.25\n",
        ])
      end

      it "responds to 'dime'" do
        set_input("dime\n", "q\n")

        vend.start

        expect_output([
          "$0.10\n",
        ])
      end

      it "responds to 'nickel'" do
        set_input("nickel\n", "q\n")

        vend.start

        expect_output([
          "$0.05\n",
        ])
      end

      it "responds to two 'quarter'" do
        set_input("quarter\n", "quarter\n", "q\n")

        vend.start

        expect_output([
          "$0.25\n",
          "$0.50\n",
        ])
      end

      it "responds to multiple different coins" do
        set_input("quarter\n", "nickel\n", "dime\n", "q\n")

        vend.start

        expect_output([
          "$0.25\n",
          "$0.30\n",
          "$0.40\n",
        ])
      end

      it "rejects penny" do
        set_input("penny\n", "q\n")

        vend.start

        expect_output([
          "'penny' is not acceptable tender.\nINSERT COIN\n",
        ])
      end
    end

    describe "handling specified diameter/thickness/weight" do
      it "handles D24.26 T1.75 W5.670 (quarter)" do
        set_input("D24.26 T1.75 W5.670\n", "q\n")

        vend.start

        expect_output([
          "$0.25\n",
        ])
      end

      it "rejects D21.26 T1.55 W5.670 (shaved quarter)" do
        set_input("D21.26 T1.55 W5.670\n", "q\n")

        vend.start

        expect_output([
          "'D21.26 T1.55 W5.670' is not acceptable tender.\nINSERT COIN\n",
        ])
      end
    end

    describe "purchasing products" do
      it "doesn't vend if you don't put enough money" do
        set_input("2\n", "q\n")

        vend.start

        expect_output([
          "PRICE: $0.50\nINSERT COIN\n",
        ])
      end

      it "vends chips if you have put enough money" do
        set_input("quarter\n", "quarter\n", "2\n", "q\n")

        vend.start

        expect_output([
          "$0.25\n",
          "$0.50\n",
          "Dispensing Chips\nINSERT COIN\n",
        ])
      end

      it "vends chips and returns change if you have put too much money" do
        set_input("quarter\n", "quarter\n", "quarter\n", "2\n", "q\n")

        vend.start

        expect_output([
          "$0.25\n",
          "$0.50\n",
          "$0.75\n",
          "Dispensing Chips\nReturning $0.25\nINSERT COIN\n",
        ])
      end
    end
  end
end
