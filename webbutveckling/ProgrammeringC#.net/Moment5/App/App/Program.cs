using System;
using System.Collections.Generic;
using App.Game;

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
            return 0;
        }
    }
}