using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace VendingMachine
{
    public enum Coin : int
    {
        Penny = 1,
        Nickle = 5,
        Dime = 10,
        Quarter = 25
    }

    public class VendingMachine
    {
        public const String INSERT_COIN_DISPLAY = "INSERT COIN";

        public class InvalidCoinException : Exception { };

        private Coin[] validCoins = { Coin.Nickle, Coin.Dime, Coin.Quarter };

        public int CurrentAmount { get;set; }
        public String Display {
            get
            {
                if (CurrentAmount == 0)
                {
                    return INSERT_COIN_DISPLAY;
                }
                return CurrentAmount.ToString();
            }
        }

         
        public int InsertCoin(Coin coin)
        {
            if (validCoins.Contains(coin))
            {
                CurrentAmount += (int)coin;
                return CurrentAmount;
            }

            throw new InvalidCoinException();
        }
    }   
}
