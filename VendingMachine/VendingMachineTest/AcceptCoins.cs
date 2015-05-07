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
            Assert.AreEqual(machine.CurrentAmount, 5);
            Assert.AreEqual(machine.Display, "5");

            machine.InsertCoin(Coin.Dime);
            Assert.AreEqual(machine.CurrentAmount, 15);
            Assert.AreEqual(machine.Display, "15");
        }

        [TestMethod]
        public void ShouldDisplayINSERTCOINSWhenNoCoins()
        {
            Assert.AreEqual(machine.Display, VendingMachine.VendingMachine.INSERT_COIN_DISPLAY);
        }

    }
}
