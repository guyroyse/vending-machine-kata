using Microsoft.VisualStudio.TestTools.UnitTesting;
using VendingMachine;

namespace VendingMachineTest
{
    [TestClass]
    public class VendingMachineTest
    {
        [TestMethod]
        public void ShouldAcceptValidCoins()
        {
            var machine = new VendingMachine.VendingMachine();
            Assert.IsTrue(machine.InsertCoin(Coin.Nickle));
            Assert.IsTrue(machine.InsertCoin(Coin.Dime));
            Assert.IsTrue(machine.InsertCoin(Coin.Quarter));
        }


    }
}
