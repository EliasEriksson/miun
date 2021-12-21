using System;
using System.Threading;

namespace App.Game.Player
{
    public class EasyAi : Ai
    {
        public EasyAi(Marker marker) : base(marker)
        {
        }

        public override (int, int) Play(Board board)
        {
            Console.WriteLine($"Currently playing: {this}\n");
            board.Draw(0, 0);
            Thread.Sleep(1500);
            var availableCoordinates = board.GetEmptySlots();
            return availableCoordinates[this.RandomInt(availableCoordinates.Count)];
        }
    } // full random
}