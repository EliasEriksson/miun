using System.Collections.Generic;

namespace App.Generics
{
    /**
     * a generic 2x2 grid class
     *
     * creates a 2x2 grid for any type with some useful functionality for a tic tac toe game
     */
    public class Grid<T>
    {
        protected readonly T[,] _grid;
        protected readonly T _init;

        /**
         * constructs a grid with specified width and height filled
         * with the given initial value
         */
        public Grid(int width, int height, T init)
        {
            this._init = init;
            this._grid = new T[width, height];
            for (var x = 0; x < width; x++)
            {
                for (var y = 0; y < height; y++)
                {
                    this._grid[x, y] = this._init;
                }
            }
        }

        /**
         * returns the value at a given coordinate
         *
         * the first value in the tuple is the x value (column).
         * the second value in the tuple is the y value (row).
         */
        public T Get((int, int) coordinate)
        {
            var (x, y) = coordinate;
            return this.Get(x, y);
        }
        
        /**
         * returns the value at a given coordinate
         *
         * x is the column and y is the row.
         */
        public T Get(int x, int y)
        {
            return this._grid[x, y];
        }

        /**
         * sets a new value at the given coordinate
         *
         * the first value in the tuple is the x value (column).
         * the second value in the tuple is the y value (row).
         */
        public void Set((int, int) coordinate, T value)
        {
            var (x, y) = coordinate;
            this.Set(x, y, value);
        }
        
        /**
         * sets a new value at the given coordinate
         *
         * x is the column and y is the row.
         */
        public void Set(int x, int y, T value)
        {
            this._grid[x, y] = value;
        }

        /**
         * returns an array of values at the given row
         */
        public T[] GetRow(int y)
        {
            var values = new T[this.GetWidth()];
            for (var x = 0; x < this.GetWidth(); x++)
            {
                values[x] = this.Get(x, y);
            }

            return values;
        }

        /**
         * returns an array of values at the given column
         */
        public T[] GetColumn(int x)
        {
            var values = new T[this.GetHeight()];
            for (var y = 0; y < this.GetHeight(); y++)
            {
                values[y] = this.Get(x, y);
            }

            return values;
        }
        
        /**
         * returns the row length of the grid.
         */
        public int GetWidth()
        {
            return this._grid.GetLength(0);
        }

        /**
         * returns the column length of the grid.
         */
        public int GetHeight()
        {
            return this._grid.GetLength(1);
        }
        
        /**
         * returns a list of coordinates of values that have not changed
         * from the initial value.
         */
        public List<(int, int)> GetUnchangedPositions()
        {
            var unchanged = new List<(int, int)>();
            for (var x = 0; x < this.GetWidth(); x++)
            {
                for (var y = 0; y < this.GetHeight(); y++)
                {
                    if (this.Get(x, y).Equals(this._init))
                    {
                        unchanged.Add((x, y));
                    }
                }
            }

            return unchanged;
        }
    }
}