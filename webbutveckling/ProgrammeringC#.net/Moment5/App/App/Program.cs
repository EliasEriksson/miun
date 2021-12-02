using System;
using System.Linq.Expressions;
using System.Runtime.CompilerServices;

namespace App
{
    internal static class Program
    {
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
        
        public static void ClearAllButN(int n)
        {
            var (x, y) = Console.GetCursorPosition();
            for (var i = n; i < y - n; i++)
            {
                Console.SetCursorPosition(0, i);
                ClearCurrentLine();
            }
        }

        public static void ClearN(int n)
        {
            var (x, y) = Console.GetCursorPosition();
            
            // try
            // {
                for (var i = 0; i <= n; i++)
                {
                    Console.SetCursorPosition(0, y - i);
                    ClearCurrentLine();
                }
            // }
            // catch (System.ArgumentOutOfRangeException)
            // {
            // }
            
        }

        private static int Main()
        {
            Console.Clear();
            // Console.WriteLine("1 2 3");
            // Console.WriteLine("4 5 6");
            // Console.WriteLine("7 8 9");
            // ClearN(4);
            // Console.WriteLine("7 8 9");
            Console.CursorVisible = false;
            Game.Game.MainMenu();
            Console.CursorVisible = true;
            return 0;
        }
    }
}