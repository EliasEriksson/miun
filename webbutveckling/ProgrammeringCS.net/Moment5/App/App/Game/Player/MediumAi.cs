using System.Collections.Generic;
using System.Threading;
using App.Generics;

namespace App.Game.Player
{
    /**
     * the medium ai. the medium ai plays slightly better than fully random.
     */
    public class MediumAi : Ai
    {
        public MediumAi(Marker marker) : base(marker)
        {
        }

        /**
         * medium ai play.
         *
         * if there is a move that allows it to win it will play that move.
         *
         * if no move to win is found it finds moves with the find moves method.
         * if moves are found it will randomize one of the found moves.
         * this will:
         *  * guarantee that the move is not useless.
         *  * since multiple positions can be found multiple times it will
         *    have a higher chance to pick a slot with more potential ways of winning.
         *
         * if no move that can result in a win is found it picks a random move of the remaining
         * to play for a draw.
         */
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