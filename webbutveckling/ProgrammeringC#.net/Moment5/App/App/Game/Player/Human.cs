using System;

namespace App.Game.Player
{
    /**
     * the Human class that allows a human player to play its move.
     */
    public class Human : Player
    {
        private int _selectX;
        private int _selectY;

        public Human(Marker marker) : base(marker)
        {
            this._selectX = 0;
            this._selectY = 0;
        }

        /**
         * allows the human to control the selector and select a move.
         *
         * The player can move > with key up / down and v with key left / right.
         * if a move is already taken they will not be allowed to make that move.
         */
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

                this._selectX = Program.Mod(_selectX, board.GetWidth());
                this._selectY = Program.Mod(_selectY, board.GetHeight());
                board.Erase();
            }
        }

        public override string ToString()
        {
            return $"Player {(char) this.GetMarker()}";
        }
    }
}