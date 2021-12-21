using System;
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

        public override (int, int) Play(Board board)
        {
            Console.WriteLine($"Currently playing: {this}\n");
            board.Draw(0, 0);
            Thread.Sleep(1500);
            
            var moveFrequencyList = new List<(int, int)>();
            Node<(int, int)> move = null;
            
            // searches for winning and optimal moves
            this.Traverse(board, (x, y, moveX, moveY) =>
            {
                var moves = this.FindMoves(board, x, y, moveX, moveY);
                // TODO FUCK THIS IS A BUG NOT CORRECT. WORKS BY COINSIDENCE ON 3x3 board
                if (board.GetWidth() - moves?.GetLength() >= board.WinCondition() - 1)
                {
                    move = this.IdentifyWin(board, this.GetMarker(), x, y, moveX, moveY);
                    if (move != null)
                    {
                        return true;
                    }
                }
                moves?.AddDataToList(ref moveFrequencyList);
                return false;
            });
            
            // if there is a winning move, use it
            if (move != null)
            {
                return move.GetData();
            }
            
            // finds opponent winning moves
            this.Traverse(board, (x, y, moveX, moveY) =>
            {
                var opponent = this.GetMarker() == Marker.Cross ? Marker.Circle : Marker.Cross;
                move = IdentifyWin(board, opponent, x, y, moveX, moveY);
                return move != null;
            });
            
            // if enemy have a winning move, block it. Else use the most optimal move
            return move?.GetData() ?? this.FindMove(board, moveFrequencyList);
        }
        
        
        

        
        
    } // max weight then random
}