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

        public override void Play(Board board)
        {
            board.Draw(0, 0);
            Thread.Sleep(250);
            var result = new List<(int, int)>();
            Node<(int, int)> move = null;
            board.Traverse((x, y, moveX, moveY) =>
            {
                var moves = this.FindMoves(board, x, y, moveX, moveY);
                move = this.IdentifyWin(board, this.GetMarker(), x, y, moveX, moveY);
                if (move != null)
                {
                    return true;
                }

                moves?.AddDataToList(ref result);
                return false;
            });

            if (move != null)
            {
                board.Set(move.GetData(), this.GetMarker());
                return;
            }

            if (result.Count > 0)
            {
                board.Set(result[this.RandomInt(result.Count)], this.GetMarker());
                return;
            }

            var availableMoves = board.GetUnchangedPositions();
            board.Set(availableMoves[this.RandomInt(availableMoves.Count)], this.GetMarker());
        }

        public override string ToString()
        {
            return $"Medium {base.ToString()}";
        }
    }
}