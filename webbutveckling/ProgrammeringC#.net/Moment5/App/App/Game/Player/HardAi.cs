using System.Collections.Generic;
using System.Threading;
using App.Generics;

namespace App.Game.Player
{
    public class HardAi : Ai
    {
        public HardAi(Marker marker) : base(marker)
        {
        }

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