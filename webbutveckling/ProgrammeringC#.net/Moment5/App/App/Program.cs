using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading;
using App.Game;
using App.Generics;

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
            // Console.Clear();
            // Console.CursorVisible = false;
            // Game.Game.MainMenu();
            // Console.CursorVisible = true;
            Test();
            return 0;
        }

        private static void Test()
        {
            var board = new BoardN(3, 3, 3);
            board.Set(0, 0, Marker.Cross);
            board.Set(1, 0, Marker.Cross);
            board.Set(2, 0, Marker.Cross);
            // Console.WriteLine(board.DrawEmptyLine());
            // Console.WriteLine(board.DrawLine(board.GetRow(0)));
            // Console.WriteLine(board.DrawEmptyLine());
            // board.Draw(0, 1);
            // Thread.Sleep(500);
            // board.Erase();
            // Console.WriteLine("exited");
        }
    }
}