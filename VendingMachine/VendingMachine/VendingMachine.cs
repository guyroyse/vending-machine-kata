using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace VendingMachine
{
    public enum Coin
    {
        Penny = 1,
        Nickle = 5,
        Dime = 10,
        Quarter = 25
    }

    public class VendingMachine
    {
        private Coin[] validCoins = { Coin.Nickle, Coin.Dime, Coin.Quarter };
         
        public Boolean InsertCoin(Coin coin)
        {
            return validCoins.Contains(coin);
        }
    }
}
