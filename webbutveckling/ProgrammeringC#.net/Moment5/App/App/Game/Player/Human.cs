using System;

namespace App.Game.Player
{
    public class Human : Player
    {
        private int _selectX;
        private int _selectY;

        public Human(Marker marker) : base(marker)
        {
            this._selectX = 1;
            this._selectY = 1;
        }

        public override void Play(Board board)
        {
            while (true)
            {
                board.Draw(this._selectX, this._selectY);
                var key = Console.ReadKey().Key;
                if (key == ConsoleKey.UpArrow)
                {
                    this._selectY -= 1;
                }
                else if (key == ConsoleKey.DownArrow)
                {
                    this._selectY += 1;
                }
                else if (key == ConsoleKey.LeftArrow)
                {
                    this._selectX -= 1;
                }
                else if (key == ConsoleKey.RightArrow)
                {
                    this._selectX += 1;
                }
                else if (key is ConsoleKey.Enter or ConsoleKey.Spacebar)
                {
                    try
                    {
                        board.Set(this._selectX, this._selectY, this.GetMarker());
                        return;
                    }
                    catch (Board.DuplicateEntryException)
                    {
                    }
                }

                this._selectX = Program.Mod(_selectX, 3);
                this._selectY = Program.Mod(_selectY, 3);
                board.Erase();
            }
        }

        public override string ToString()
        {
            return $"Player {(char) this.GetMarker()}";
        }
    }
}