using System;
using System.Linq;

namespace App.Game
{
    public class Game
    {
        private readonly Player.Player[] _players;

        private Game(Player.Player[] players)
        {
            this._players = players;
        }

        /**
         * starts the game with given settings.
         */
        private void Start(int x, int y, int win)
        {
            this.DrawScore();
            var board = new Board(x, y, win);
            while (true)
            {
                foreach (var player in this._players)
                {
                    Console.WriteLine($"Currently playing: {player}");
                    player.Play(board);
                    board.Erase(2);
                    Console.Clear();
                    this.DrawScore();

                    if (board.IsWinner(player))
                    {
                        player.GrantScore();
                        Program.ClearN(1);
                        Console.Clear();
                        this.DrawScore();
                        board.Draw(0, 0);
                        Console.WriteLine($"{player} won!");
                        if (!Continue())
                        {
                            board.Erase(3);
                            return;
                        }

                        board.Erase(2);
                        board = new Board(x, y, win);
                    }
                    else if (board.GetUnchangedPositions().Count == 0) // draw
                    {
                        board.Draw(0, 0);
                        Console.WriteLine($"Draw!");
                        if (!Continue())
                        {
                            board.Erase(3);
                            Console.Clear();
                            return;
                        }

                        board.Erase(2);
                        board = new Board(x, y, win);
                    }
                }
            }
        }

        /**
         * draws the current score to the screen.
         */
        private void DrawScore()
        {
            Console.WriteLine(
                string.Join(" | ", this._players.Select((player => $"{player}: {player.GetScore()}")))
            );
        }

        /**
         * asks the user if the game should continue.
         */
        private static bool Continue()
        {
            Console.WriteLine("Continue playing?");
            (string, Func<bool>)[] actions =
            {
                ("Yes", () => true),
                ("No", () => false)
            };
            return Chose(actions);
        }

        /**
         * the games main menu.
         *
         * allows the user to select:
         *  * player vs player, player vs Ai or Ai vs Ai.
         *  * a specific Ais difficulty.
         *  * the boards width, height and requirement to win.
         */
        public static void MainMenu()
        {
            const string xMessage = "Board width: ";
            const string yMessage = "Board height: ";
            const string winMessage = "In a row to win: ";

            // an array of choices. choices are described in the choice functions doc string.
            (string, Func<bool>)[] actions =
            {
                ("Player Vs Player", () =>
                {
                    new Game(new Player.Player[]
                    {
                        new Player.Human(Marker.Cross), new Player.Human(Marker.Circle)
                    }).Start(
                        Program.ReadInt(xMessage),
                        Program.ReadInt(yMessage),
                        Program.ReadInt(winMessage)
                    );
                    return false;
                }),
                ("Player Vs Ai", () =>
                {
                    new Game(new Player.Player[]
                    {
                        new Player.Human(Marker.Cross), ChoseAi(Marker.Circle)
                    }).Start(
                        Program.ReadInt(xMessage),
                        Program.ReadInt(yMessage),
                        Program.ReadInt(winMessage)
                    );
                    return false;
                }),
                ("Ai Vs Ai", () =>
                {
                    new Game(new Player.Player[]
                    {
                        ChoseAi(Marker.Cross), ChoseAi(Marker.Circle)
                    }).Start(
                        Program.ReadInt(xMessage),
                        Program.ReadInt(yMessage),
                        Program.ReadInt(winMessage)
                    );
                    return false;
                }),
                ("Exit", () => true)
            };

            while (!Chose(actions))
            {
            }
        }

        /**
         * allows the user to chose a difficulty of an Ai.
         */
        private static Player.Ai ChoseAi(Marker marker)
        {
            Player.Ai ai = null;
            // an array of choices. (described for the function chose)
            (string, Func<bool>)[] actions =
            {
                ("Easy Ai", () =>
                {
                    ai = new Player.EasyAi(marker);
                    return false;
                }),
                ("Medium Ai", () =>
                {
                    ai = new Player.MediumAi(marker);
                    return false;
                }),
                ("Hard Ai", () =>
                {
                    ai = new Player.HardAi(marker);
                    return false;
                })
            };

            Chose(actions);

            return ai;
        }

        /**
         * allows the user to chose one of the given options.
         *
         * the function takes an array of tuples of a string and a function.
         * the string is the text that the user will see in the console and should
         * describe the option.
         * the second value is a function that should execute if the user selects the option.
         *
         * the functions must return a boolean indicating of this chose function should continue running
         * after the options function was executed.
         *
         * if the options function returns true this chose function will return.
         */
        private static bool Chose((string, Func<bool>)[] actions)
        {
            var current = 0;

            while (true)
            {
                for (var i = 0; i < actions.Length; i++)
                {
                    Console.Write(i == current ? "> " : "  ");
                    Console.WriteLine(actions[i].Item1);
                }

                var key = Console.ReadKey().Key;
                switch (key)
                {
                    case ConsoleKey.UpArrow:
                        current -= 1;
                        break;
                    case ConsoleKey.DownArrow:
                        current += 1;
                        break;
                    case ConsoleKey.Enter or ConsoleKey.Spacebar:
                        Program.ClearN(actions.Length);
                        return actions[current].Item2();
                }

                current = Program.Mod(current, actions.Length);
                Program.ClearN(actions.Length);
            }
        }
    }
}