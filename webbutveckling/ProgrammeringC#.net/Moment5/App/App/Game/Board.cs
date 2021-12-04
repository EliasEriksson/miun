using System;

namespace App.Game
{
    public enum Marker
    {
        None = 32,
        Cross = 88,
        Circle = 79
    }

    public class Board
    {
        public class DuplicateEntryException : Exception
        {
        }

        private readonly Marker[,] _board;

        public Board()
        {
            this._board = new Marker[3, 3];
            for (var i = 0; i < 3; i++)
            {
                for (var j = 0; j < 3; j++)
                {
                    this._board[i, j] = Marker.None;
                }
            }
        }

        public override string ToString()
        {
            var s = Line(Marker.None, Marker.None, Marker.None);
            s += Line(this._board[0, 0], this._board[1, 0], this._board[2, 0]);
            s += Line();
            s += Line(this._board[0, 1], this._board[1, 1], this._board[2, 1]);
            s += Line();
            s += Line(this._board[0, 2], this._board[1, 2], this._board[2, 2]);
            s += Line(Marker.None, Marker.None, Marker.None);
            return s;
        }

        public void Draw(int selectX, int selectY)
        {
            var s = Heading(selectX);
            s += Line(Marker.None, Marker.None, Marker.None);
            s += Line(
                this._board[0, 0],
                this._board[1, 0],
                this._board[2, 0],
                selectY == 0
            );
            s += Line();
            s += Line(
                this._board[0, 1],
                this._board[1, 1],
                this._board[2, 1],
                selectY == 1
            );
            s += Line();
            s += Line(
                this._board[0, 2],
                this._board[1, 2],
                this._board[2, 2],
                selectY == 2
            );
            s += Line(Marker.None, Marker.None, Marker.None);
            Console.WriteLine(s);
        }

        private static string Heading(int selected)
        {
            return $"     {(selected == 0 ? "v" : " ")}" +
                   $"         {(selected == 1 ? "v" : " ")}" +
                   $"         {(selected == 2 ? "v" : " ")}\n";
        }

        private static string Line()
        {
            var s = Line(Marker.None, Marker.None, Marker.None);
            s += "  — — — — + — — — — + — — — — \n";
            s += Line(Marker.None, Marker.None, Marker.None);
            return s;
        }

        private static string Line(Marker left, Marker middle, Marker right, bool selected = false)
        {
            return
                $"{(selected ? ">" : " ")}    {(char) left}    |    {(char) middle}    |    {(char) right}    \n";
        }

        public void SetMarker(int x, int y, Marker marker)
        {
            if (this._board[x, y] == Marker.None)
            {
                this._board[x, y] = marker;
            }
            else
            {
                throw new DuplicateEntryException();
            }
        }

        public bool IsWinner(Player player)
        {
            var marker = player.GetMarker();
            if (this.IsWinner(marker, 0, 0, 1, 1)) // top left to bottom right
            {
                return true;
            }

            if (this.IsWinner(marker, 0, 2, 1, -1)) // bottom left to top right
            {
                return true;
            }

            for (var x = 0; x < 3; x++)
            {
                if (this.IsWinner(marker, x, 0, 0, 1))
                {
                    return true;
                }
            }

            for (var y = 0; y < 3; y++)
            {
                if (this.IsWinner(marker, 0, y, 1, 0))
                {
                    return true;
                }
            }

            return false;
        }

        private bool IsWinner(Marker marker, int x, int y, int moveX, int moveY, int inRow = 0)
        {
            if (x > 2 || y > 2)
            {
                return false;
            }

            if (this._board[x, y] != marker)
            {
                return false;
            }

            return ++inRow == 3 || this.IsWinner(marker, x + moveX, y + moveY, moveX, moveY, inRow);
        }

        public static void Test()
        {
            var board = new Board();
            board.SetMarker(1, 1, Marker.Circle);
            board.SetMarker(0, 1, Marker.Cross);
            board.SetMarker(0, 2, Marker.Circle);
            board.SetMarker(2, 2, Marker.Cross);
            board.SetMarker(1, 0, Marker.Circle);
            board.SetMarker(1, 2, Marker.Cross);
            board.SetMarker(2, 0, Marker.Circle);
            board.SetMarker(2, 1, Marker.Cross);
            board.SetMarker(0, 0, Marker.Circle);
            Console.WriteLine(board);
        }

        public (int, int) GetSize()
        {
            return (this._board.GetLength(0), this._board.GetLength(1));
        }

        public int GetWidth()
        {
            return this._board.GetLength(0);
        }

        public int GetHeight()
        {
            return this._board.GetLength(1);
        }
        
        public Marker Get(int x, int y)
        {
            return this._board[x, y];
        }

        public int WinCondition()
        {
            return 3;
        }
        
    }
}