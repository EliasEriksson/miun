using System.Collections.Generic;

namespace App.Generics
{
    public class Grid<T>
    {
        private readonly T[,] _grid;
        private readonly T _init;

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

        public T Get((int, int) coordinate)
        {
            var (x, y) = coordinate;
            return this.Get(x, y);
        }
        
        public T Get(int x, int y)
        {
            return this._grid[x, y];
        }

        public void Set((int, int) coordinate, T value)
        {
            var (x, y) = coordinate;
            this.Set(x, y, value);
        }
        
        public void Set(int x, int y, T value)
        {
            this._grid[x, y] = value;
        }

        public T[] GetRow(int y)
        {
            var values = new T[this.GetWidth()];
            for (var x = 0; x < this.GetWidth(); x++)
            {
                values[x] = this.Get(x, y);
            }

            return values;
        }

        public T[] GetColumn(int x)
        {
            var values = new T[this.GetHeight()];
            for (var y = 0; y < this.GetHeight(); y++)
            {
                values[y] = this.Get(x, y);
            }

            return values;
        }
        
        public int GetWidth()
        {
            return this._grid.GetLength(0);
        }

        public int GetHeight()
        {
            return this._grid.GetLength(1);
        }
        
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