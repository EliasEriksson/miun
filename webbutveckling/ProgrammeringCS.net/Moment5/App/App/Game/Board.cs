using System;
using System.Linq;
using App.Generics;

namespace App.Game
{
    /**
     * and enum for different markers
     * the number represents a unicode code point
     * 32 == space (empty)
     * 88 == X
     * 79 == O
     */
    public enum Marker
    {
        None = 32,
        Cross = 88,
        Circle = 79
    }

    /**
     * a board class that extends 
     */
    public class Board : Grid<Marker>
    {
        public class DuplicateEntryException : Exception
        {
        }

        private readonly int _win;

        /**
         * creates a new board with x columns and y rows with a requirement of win in a row to win
         */
        public Board(int x, int y, int win) : base(x, y, Marker.None)
        {
            this._win = win;
        }

        /**
         * attempts to set a players marker on column x and row y on the internal grid.
         * 
         * throws an exception if the spot is taken by another player.
         */
        public new void Set(int x, int y, Marker marker)
        {
            if (this.Get(x, y) != Marker.None) throw new DuplicateEntryException();
            this._grid[x, y] = marker;
        }

        /**
         * attempts to set a players marker on the given coordinate on the internal grid.
         * 
         * the coordinate is a tuple where the first value is x/column and the second value is y/row.
         */
        public new void Set((int, int) coordinate, Marker marker)
        {
            var (x, y) = coordinate;
            this.Set(x, y, marker);
        }
        
        /**
         * gets a string representation of what the board currently looks like.
         */
        public string GetDrawing(int selectX, int selectY)
        {
            return
                this.DrawHeading(selectX) +
                string.Join(this.DrawCrossings(), Enumerable.Range(0, this.GetHeight()).Select(
                    (y) => this.DrawLine(this.GetRow(y), y == selectY))
                );
        }

        /**
         * draws the current string representation of the board to the console.
         */
        public void Draw(int selectX, int selectY)
        {
            Console.WriteLine(this.GetDrawing(selectX, selectY));
        }

        /**
         * creates a string representation of the markers as a row.
         *
         * if selected is true a '>' is added to indicate the row is selected.
         */
        private string DrawLine(Marker[] markers, bool selected)
        {
            return (selected ? ">" : " ") + string.Join('|', Enumerable.Range(0, this.GetWidth()).Select(
                (x) => DrawMarker(markers[x]))
            ) + "\n";
        }

        /**
         * draws a line with crossings.
         *
         * this is the filler lines between rows.
         */
        private string DrawCrossings()
        {
            return " " + string.Join('+', Enumerable.Range(0, this.GetWidth()).Select(
                (_) => "  —  ")
            ) + "\n";
        }

        /**
         * draws which column the user have selected.
         */
        private string DrawHeading(int selectX)
        {
            return " " + string.Join(" ", Enumerable.Range(0, this.GetWidth()).Select(
                (x) => "  " + (x == selectX ? "v" : " ") + "  "
            )) + "\n";
        }

        /**
         * draws a single marker
         */
        private static string DrawMarker(Marker marker)
        {
            return $"  {(char) marker}  ";
        }

        /**
         * returns how many in a row is required to win.
         */
        public int GetWin()
        {
            return this._win;
        }

        
        /**
         * calculates if the given player have won on the current board.
         */
        public bool IsWinner(Player.Player player)
        {
            return this.IsWinner(player.GetMarker());
        }

        /**
         * checks if there is enough of the same marker in a row for a win.
         *
         * this method heavily relies on Board.Traverse.
         */
        public bool IsWinner(Marker marker)
        {
            // initial assumption of no win.
            var winner = false;
            this.Traverse(((x, y, moveX, moveY) =>
            {
                // check if there is a win in the direction given by the traverse method.
                winner = this.IsWinner(marker, x, y, moveX, moveY);
                
                // returns winner since if there is a win the traverse function should stop executing.
                return winner;
            }));
            return winner;
        }

        /**
         * generates potential starting positions and directions that are passed down in given function.
         *
         * the five for loops generates potential starting positions that is interesting for a function.
         * the functions that heavily relies on these positions and directions are the methods that determine
         * if there is a win on the board or the Ai methods that determine where to place a marker.
         *
         * the different loops generate a positions for a certain direction where it is possible for it to be a
         * winnable amount of markers in a row.
         * it will for example generate 0, 0; 0, 1; 0, 2 for a 3x3 boards "row direction".
         *
         * All positions will always be start somewhere along the top, left or bottom border and with a direction
         * towards the right (diagonal or Orthogonal) or strait down.
         *
         * the given function can return true to stop traverse from continuing to execute.
         * (used to for example stop it from executing if a win is found)
         */
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
            } // potential middle diagonals up right and down right (does not go here on a 3x3 3 win board)

            for (var i = 0; i < this.GetHeight(); i++)
            {
                if (method(0, i, 1, 0)) return;
            } // all rows

            for (var i = 0; i < this.GetWidth(); i++)
            {
                if (method(i, 0, 0, 1)) return;
            } // all columns
        }

        /**
         * counts how many markers the is in a row along a given direction from Board.Traverse.
         *
         * if the same marker in a row is found 'win' times this returns true.
         */
        private bool IsWinner(Marker marker, int x, int y, int moveX, int moveY, int row = 0)
        {
            if (x >= this.GetWidth() || y >= this.GetHeight() || x < 0 || y < 0)
            {
                return false;
            }

            if (this.Get(x, y) == marker)
            {
                row++;
            }
            else
            {
                row = 0;
            }

            return row == this.GetWin() || this.IsWinner(marker, x + moveX, y + moveY, moveX, moveY, row);
        }
        
        /**
         * erases the amount of lines required to erase the current board from the console.
         */
        public void Erase(int extra = 0)
        {
            Program.ClearN(this.GetHeight() + this.GetHeight() - 1 + 2 + extra);
        }
    }
}