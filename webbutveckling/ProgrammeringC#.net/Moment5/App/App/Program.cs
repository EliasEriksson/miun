using System;

namespace App
{
    /**
     * main program class
     *
     * contains the entry point and utility functions for the console + real modulus function
     */
    internal static class Program
    {
        /**
         * reads and returns an integer from user input
         *
         * does not return until the user successfully does so
         */
        public static int ReadInt(string message)
        {
            Console.CursorVisible = true;
            Console.WriteLine(message);
            while (true)
            {
                try
                {
                    var result = Convert.ToInt32(Console.ReadLine());
                    ClearN(2);
                    Console.CursorVisible = false;
                    return result;
                }
                catch (OverflowException)
                {
                    
                }
                catch (FormatException)
                {
                    
                }
                ClearN(1);
            }
        }
        
        /**
         * a modulus function needed since C# % is not real modulus
         */
        public static int Mod(double a, double b)
        {
            return (int) (a - b * Math.Floor(a / b));
        }

        /**
         * clears a single line in the console
         *
         * overwrites the previous line in the console with spaces
         */
        private static void ClearCurrentLine()
        {
            var (x, y) = Console.GetCursorPosition();
            Console.SetCursorPosition(0, y);
            Console.Write(new string(' ', Console.BufferWidth));
            Console.SetCursorPosition(0, y);
        }

        /**
         * clears n lines in the console
         *
         * overwrites the previous n lines in the console with spaces
         *
         * needed if not the entire console window needs to be cleared.
         */
        public static void ClearN(int n)
        {
            var (x, y) = Console.GetCursorPosition();
            try
            {
                for (var i = 0; i <= n; i++)
                {
                    Console.SetCursorPosition(0, y - i);
                    ClearCurrentLine();
                }
            }
            catch (ArgumentOutOfRangeException)
            {
                Console.Clear();
            }
            
        }

        /**
         * main program entry point
         */
        private static int Main()
        {
            Console.Clear();
            Console.CursorVisible = false;
            Game.Game.MainMenu();
            Console.CursorVisible = true;
            return 0;
        }
    }
}