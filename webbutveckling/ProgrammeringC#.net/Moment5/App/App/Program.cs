using System;

namespace App
{
    internal static class Program
    {
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
        
        public static int Mod(double a, double b)
        {
            return (int) (a - b * Math.Floor(a / b));
        }

        private static void ClearCurrentLine()
        {
            var (x, y) = Console.GetCursorPosition();
            Console.SetCursorPosition(0, y);
            Console.Write(new string(' ', Console.BufferWidth));
            Console.SetCursorPosition(0, y);
        }

        public static void ClearN(int n)
        {
            var (x, y) = Console.GetCursorPosition();
            for (var i = 0; i <= n; i++)
            {
                Console.SetCursorPosition(0, y - i);
                ClearCurrentLine();
            }
        }

        private static int Main()
        {
            Console.Clear();
            Console.CursorVisible = false;
            Game.Game.MainMenu();
            Console.CursorVisible = true;
            // Test();
            return 0;
        }

        private static void Test()
        {
        }
    }
}