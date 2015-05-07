using Microsoft.VisualStudio.TestTools.UnitTesting;
using VendingMachine;

namespace VendingMachineTest
{
    [TestClass]
    public class VendingMachineTest
    {
        private VendingMachine.VendingMachine machine;

        [TestInitialize]
        public void Setup()
        {
            machine  = new VendingMachine.VendingMachine();
        }

        [TestMethod]
        public void ShouldAcceptValidCoins()
        {
            Assert.IsTrue(machine.InsertCoin(Coin.Nickle));
            Assert.IsTrue(machine.InsertCoin(Coin.Dime));
            Assert.IsTrue(machine.InsertCoin(Coin.Quarter));
        }

        [TestMethod]
        public void ShouldRejectInvalidCoins()
        {
            Assert.IsFalse(machine.InsertCoin(Coin.Penny));
        }

        [TestMethod]
        public void ShouldAddAmountToAndDisplayCurrentAmount()
        {
            machine.InsertCoin(Coin.Nickle);
            Assert.Equals(machine.CurrentAmount, 5);
            Assert.Equals(machine.Display, "5");

            machine.InsertCoin(Coin.Dime);
            Assert.Equals(machine.CurrentAmount, 15);
            Assert.Equals(machine.Display, "15");


        }

    }
}
