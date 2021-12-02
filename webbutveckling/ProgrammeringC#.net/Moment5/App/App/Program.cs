using System;

namespace App
{
    internal static class Program
    {
        public static int Mod(double a, double b)
        {
            return (int) (a - b * Math.Floor(a / b));
        }

        public static void ClearAllButN(int n)
        {
            for (var i = n; i < Console.BufferHeight - n; i++)
            {
                Console.SetCursorPosition(0, i);
                Console.Write(new string(' ', Console.BufferWidth));
            }
        }
        
        private static int Main()
        {
            Game.Game.Run();
            return 0;
        }
    }
}