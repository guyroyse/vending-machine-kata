using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;

namespace Vending_Machine_WPF
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        //Initialize Vending Machine, including products & changes.
        //Restart this app means to renew the vending machine.
        int cola_num = 20;
        int chip_num = 30;
        int candy_num = 30;

        int quarter_num = 12;
        int dime_num = 70;
        int nickel_num = 30;

        //products prices
        double cola_price = 1.5;
        double chip_price = 0.5;
        double candy_price = 0.65;

        // coins inserted
        double current_coin = 0;
        string current_result = "";

        //three kinds of coins
        private static decimal[] CoinArr = new decimal[] { 0.25M, 0.10M, 0.05M };
        //numbers of three kinds of coins to return
        private static int[] CoinCountList = null;

        public MainWindow()
        {
            current_result = current_coin.ToString("#0.00");
            InitializeComponent();
        }

        // check if there are coins to make change 
        private void check_remain()
        {
            if (quarter_num == 0 | dime_num == 0 | nickel_num == 0)
            {
                label_message.Content = "EXACT CHANGE ONLY";
            }
            // there are 8 possibility of coins remain in machine, quarter(1,0), dime(1,0), nickel(1,0)
            // however, before users insert coins, it cannot be sure that could make changes
            // so, I use this simple judgement instead of the 8 situations computation
        }

        // after purchase, return changes 
        private void return_coin(double changes)
        {
            int change_quarter_num = 0;
            int change_dime_num = 0;
            int change_nickel_num = 0;

            decimal currentCoin = Convert.ToDecimal(changes);        
            CoinCountList = new int[] { 0, 0, 0 };
            int tmpCoin = Convert.ToInt32(currentCoin * 100);
            while (tmpCoin > 0)
            {
                for (int i = 0; i < CoinArr.Length; i++)
                {
                    if (tmpCoin >= Convert.ToInt32(CoinArr[i] * 100))
                    {
                        int result = tmpCoin / Convert.ToInt32(CoinArr[i] * 100); 
                        CoinCountList[i] = result;
                        tmpCoin = tmpCoin % Convert.ToInt32(CoinArr[i] * 100); 
                        break;
                    }
                }
            }

            int[] moneyCount = CoinCountList;
            
            change_quarter_num = moneyCount[0];
            change_dime_num = moneyCount[1];
            change_nickel_num = moneyCount[2];

            quarter_num -= change_quarter_num;
            dime_num -= change_dime_num;
            nickel_num -= change_nickel_num;
            
            label_changes_quarter_num.Content = change_quarter_num.ToString();
            label_changes_dime_num.Content = change_dime_num.ToString();
            label_changes_nickel_num.Content = change_nickel_num.ToString();
            current_coin = 0;
            current_result = current_coin.ToString("#0.00");
            check_remain();
        }

        #region Insert Coins =========================================================
        // when insert a quarter
        private void button25_Click(object sender, RoutedEventArgs e)
        {
            current_coin += 0.25;
            current_result = current_coin.ToString("#0.00");
            quarter_num++;
            label_message.Content = "  NOW $" + current_result;
        }

        //when insert a dime
        private void button10_Click(object sender, RoutedEventArgs e)
        {
            current_coin += 0.10;
            current_result = current_coin.ToString("#0.00");
            dime_num++;
            label_message.Content = "  NOW $" + current_result;
        }

        //when insert a nickel
        private void button5_Click(object sender, RoutedEventArgs e)
        {
            current_coin += 0.05;
            current_result = current_coin.ToString("#0.00");
            nickel_num++;
            label_message.Content = "  NOW $" + current_result;
        }

        //when press return botton
        private void button_return_Click(object sender, RoutedEventArgs e)
        {
            return_coin(current_coin);
            current_coin = 0;
            current_result = current_coin.ToString("#0.00");
            label_message.Content = "INSERT COIN";
        }
        #endregion

        #region Select Product ===============================================
        private void button_cola_Click(object sender, RoutedEventArgs e)
        {
            if (cola_num > 0)
            {
                if (current_coin < cola_price)
                {
                    label_message.Content = "NEED $1.50";
                    current_result = current_coin.ToString("#0.00");
                    label_message.Content += "  NOW $" + current_result;
                }
                else
                {
                    current_coin -= 1.50;
                    return_coin(current_coin);
                    label_message.Content = "THANK YOU";
                    label_result.Content = "You get one Cola!";
                    cola_num--;
                }
            }
            else {
                label_message.Content = "SOLD OUT";
            }
            
        }              

        private void button_chip_Click(object sender, RoutedEventArgs e)
        {
            if (chip_num > 0)
            {
                if (current_coin < chip_price)
                {
                    label_message.Content = "NEED $0.50";
                    current_result = current_coin.ToString("#0.00");
                    label_message.Content += "  NOW $" + current_result;
                }
                else
                {
                    current_coin -= 0.50;
                    return_coin(current_coin);
                    label_message.Content = "THANK YOU";
                    label_result.Content = "You get one Chips!";
                    chip_num--;
                }
            }
            else
            {
                label_message.Content = "SOLD OUT";
            }
        }

        private void button_candy_Click(object sender, RoutedEventArgs e)
        {
            if (candy_num > 0)
            {
                if (current_coin < candy_price)
                {
                    label_message.Content = "NEED $0.65";
                    current_result = current_coin.ToString("#0.00");
                    label_message.Content += "  NOW $" + current_result;
                }
                else
                {
                    current_coin -= 0.65;
                    return_coin(current_coin);
                    label_message.Content = "THANK YOU";
                    label_result.Content = "You get one Candy!";
                    candy_num--;
                }
            }
            else
            {
                label_message.Content = "SOLD OUT";
            }
        }

        #endregion

        // press this button, the hint message and make changes message can be clear
        private void button_clear_Click(object sender, RoutedEventArgs e)
        {
            label_changes_nickel_num.Content = "0";
            label_changes_dime_num.Content = "0";
            label_changes_quarter_num.Content = "0";
            label_result.Content = "";
        }
    }
}
