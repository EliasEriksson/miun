using System;
using System.Collections.Generic;
using System.Threading;
using App.Generics;

namespace App.Game.Player
{
    public class MediumAi : Ai
    {
        public MediumAi(Marker marker) : base(marker)
        {
        }

        public override (int, int) Play(Board board)
        {
            Console.WriteLine($"Currently playing: {this}\n");
            board.Draw(0, 0);
            Thread.Sleep(1500);
            var result = new List<(int, int)>();
            Node<(int, int)> move = null;
            this.Traverse(board, (x, y, moveX, moveY) =>
            {
                var moves = this.FindMoves(board, x, y, moveX, moveY);
                if (board.GetWidth() - moves?.GetLength() >= board.WinCondition() - 1)
                {
                    move = this.IdentifyWin(board, this.GetMarker(), x, y, moveX, moveY);
                    if (move != null)
                    {
                        return true;
                    }
                }
                moves?.AddDataToList(ref result);
                return false;
            });

            if (move != null)
            {
                return move.GetData();
            }

            if (result.Count > 0)
            {
                return result[this.RandomInt(result.Count)];
            }

            var availableMoves = board.GetEmptySlots();
            return availableMoves[this.RandomInt(availableMoves.Count)];
        }
    } // weighted random
}