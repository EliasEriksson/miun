using System;
using System.Linq;
using App.Generics;

namespace App.Game
{
    public enum Marker
    {
        None = 32,
        Cross = 88,
        Circle = 79
    }

    public class Board : Grid<Marker>
    {
        public class DuplicateEntryException : Exception
        {
        }

        private readonly int _win;

        public Board(int x, int y, int win) : base(x, y, Marker.None)
        {
            this._win = win;
        }

        public new void Set(int x, int y, Marker marker)
        {
            if (this.Get(x, y) != Marker.None) throw new DuplicateEntryException();
            this._grid[x, y] = marker;
        }

        public new void Set((int, int) coordinate, Marker marker)
        {
            var (x, y) = coordinate;
            this.Set(x, y, marker);
        }
        
        public string GetDrawing(int selectX, int selectY)
        {
            return
                this.DrawHeading(selectX) +
                string.Join(this.DrawCrossings(), Enumerable.Range(0, this.GetHeight()).Select(
                    (y) => this.DrawLine() + this.DrawLine(this.GetRow(y), y == selectY) + this.DrawLine())
                );
        }

        public void Draw(int selectX, int selectY)
        {
            Console.WriteLine(this.GetDrawing(selectX, selectY));
        }

        private string DrawLine()
        {
            return "  " + string.Join('|', Enumerable.Range(0, this.GetWidth()).Select(
                (_) => "         ")
            ) + "\n";
        }

        private string DrawLine(Marker[] markers, bool selected)
        {
            return (selected ? "> " : "  ") + string.Join('|', Enumerable.Range(0, this.GetWidth()).Select(
                (x) => DrawMarker(markers[x]))
            ) + "\n";
        }

        private string DrawCrossings()
        {
            return "  " + string.Join('+', Enumerable.Range(0, this.GetWidth()).Select(
                (_) => " — — — — ")
            ) + "\n";
        }

        private string DrawHeading(int selectX)
        {
            return "  " + string.Join(" ", Enumerable.Range(0, this.GetWidth()).Select(
                (x) => "    " + (x == selectX ? "v" : " ") + "    "
            )) + "\n";
        }

        private static string DrawMarker(Marker marker)
        {
            return $"    {(char) marker}    ";
        }

        public int GetWin()
        {
            return this._win;
        }

        public bool IsWinner(Player.Player player)
        {
            return this.IsWinner(player.GetMarker());
        }

        public bool IsWinner(Marker marker)
        {
            var winner = false;
            this.Traverse(((x, y, moveX, moveY) =>
            {
                winner = this.IsWinner(marker, x, y, moveX, moveY);

                return winner;
            }));
            return winner;
        }

        public void Traverse(Func<int, int, int, int, bool> method)
        {
            for (var i = 0; i < this.GetHeight() - this.GetWin() + 1; i++)
            {
                if (method(0, i, 1, 1)) return;
            } // top left to bottom right

            for (var i = this.GetWin() - 1; i < this.GetHeight(); i++)
            {
                if (method(0, i, 1, -1)) return;
            } // bottom left to top right

            for (var i = 1; i < this.GetWidth() - this.GetWin(); i++)
            {
                if (method(i, 0, 1, 1)) return;
                if (method(i, this.GetHeight() - 1, 1, -1)) return;
            }

            for (var i = 0; i < this.GetHeight(); i++)
            {
                if (method(0, i, 1, 0)) return;
            } // all rows

            for (var i = 0; i < this.GetWidth(); i++)
            {
                if (method(i, 0, 0, 1)) return;
            } // all columns
        }

        private bool IsWinner(Marker marker, int x, int y, int moveX, int moveY, int row = 0)
        {
            if (x > this.GetWidth() || y > this.GetHeight())
            {
                return false;
            }

            if (this.Get(x, y) != marker)
            {
                return false;
            }

            return ++row == 3 || this.IsWinner(marker, x + moveX, y + moveY, moveX, moveY, row);
        }
        
        public void Erase(int extra = 0)
        {
            Program.ClearN(this.GetHeight() * 3 + this.GetHeight() - 1 + 2 + extra);
        }
    }
}