using System;

namespace App.Game
{
    public abstract class Player
    {
        private readonly Marker _marker;
        private int _score;
        protected int SelectX;
        protected int SelectY;

        protected Player(Marker marker)
        {
            this._marker = marker;
            this._score = 0;
            this.SelectX = 1;
            this.SelectY = 1;
        }
        
        public abstract void Play(Board board);

        public Marker GetMarker()
        {
            return this._marker;
        }

        public int GetScore()
        {
            return this._score;
        }

        public void GrantScore()
        {
            this._score++;
        }

        public abstract void Reset();
    }

    public class Human : Player
    {
        public Human(Marker marker) : base(marker)
        {
        }

        public override void Play(Board board)
        {
            while (true)
            {
                Console.WriteLine($"Currently playing: {this}\n");
                board.Draw(this.SelectX, this.SelectY);
                var key = Console.ReadKey().Key;
                if (key == ConsoleKey.UpArrow)
                {
                    this.SelectY -= 1;
                } else if (key == ConsoleKey.DownArrow)
                {
                    this.SelectY += 1;
                } else if (key == ConsoleKey.LeftArrow)
                {
                    this.SelectX -= 1;
                } else if (key == ConsoleKey.RightArrow)
                {
                    this.SelectX += 1;
                } else if (key is ConsoleKey.Enter or ConsoleKey.Spacebar)
                {
                    try
                    {
                        board.SetMarker(this.SelectX, this.SelectY, this.GetMarker());
                        Program.ClearN(15);
                        return;
                    }
                    catch (Board.DuplicateEntryException)
                    {
                        
                    }
                }

                this.SelectX = Program.Mod(SelectX, 3);
                this.SelectY = Program.Mod(SelectY, 3);
                Program.ClearN(15);
            }
        }

        public override void Reset()
        {
            this.SelectX = 1;
            this.SelectY = 1;
        }

        public override string ToString()
        {
            return $"Player {(char) this.GetMarker()}";
        }
    }

    public class Ai : Player
    {
        public Ai(Marker marker) : base(marker)
        {
        }
        
        public override void Play(Board board)
        {
            
        }

        public override void Reset()
        {
            
        }

        public override string ToString()
        {
            return $"Ai {(char) this.GetMarker()}";
        }
    }
}