using System.Collections.Generic;
using System.Threading;
using App.Generics;

namespace App.Game.Player
{
    /**
     * the hard AI that allows the Ai to make a very good move (at least on a 3x3 board)
     */
    public class HardAi : Ai
    {
        public HardAi(Marker marker) : base(marker)
        {
        }

        /**
         * hard Ai play.
         *
         * the hard Ai first looks over the board for a place where it self can win.
         * if a place where it can win is found it plays that move.
         * 
         * if there is no way for the Ai to win this round it will look for
         * places where the enemy can win the next round.
         * if one such move is found it plays there to block the enemy win.
         *
         * if there is no way for the opponent to win it will find moves with Ai.FindMoves.
         * if moves are found it will find the most frequent move in the list.
         * if multiple moves with the same frequency is found it picks one at random.
         *
         * if no moves that can result in a win is found it plays a random move to play for draw.
         */
        public override void Play(Board board)
        {
            board.Draw(0, 0);
            Thread.Sleep(250);
            
            var moveFrequencyList = new List<(int, int)>();
            Node<(int, int)> move = null;
            
            // searches for winning and optimal moves
            board.Traverse((x, y, moveX, moveY) =>
            {
                var moves = this.FindMoves(board, x, y, moveX, moveY);

                move = this.IdentifyWin(board, this.GetMarker(), x, y, moveX, moveY);
                if (move != null)
                {
                    return true;
                }
                
                moves?.AddDataToList(ref moveFrequencyList);
                return false;
            });
            
            // if there is a winning move, use it
            if (move != null)
            
            {
                board.Set(move.GetData(), this.GetMarker());
                return;
            }
            
            // finds opponent winning moves
            board.Traverse((x, y, moveX, moveY) =>
            {
                var opponent = this.GetMarker() == Marker.Cross ? Marker.Circle : Marker.Cross;
                move = IdentifyWin(board, opponent, x, y, moveX, moveY);
                return move != null;
            });
            
            // if enemy have a winning move, block it. Else use the most optimal move
            board.Set(move?.GetData() ?? this.FindMove(board, moveFrequencyList), this.GetMarker());
        }

        public override string ToString()
        {
            return $"Hard {base.ToString()}";
        }
    }
}