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

        private void Start()
        {
            DrawScore();
            var board = new Board(3, 3, 3);
            while (true)
            {
                foreach (var player in this._players)
                {
                    Console.WriteLine($"Currently playing: {player}\n");
                    player.Play(board);
                    board.Erase(2);
                    
                    if (board.IsWinner(player))
                    {
                        player.GrantScore();
                        Program.ClearN(1);
                        DrawScore();
                        board.Draw(0, 0);
                        Console.WriteLine($"{player} won!");
                        if (!Continue())
                        {
                            board.Erase(3);
                            return;
                        }
                        board.Erase(2);
                        board = new Board(3, 3, 3);
                    } else if (board.GetUnchangedPositions().Count == 0) // draw
                    {
                        board.Draw(0, 0);
                        Console.WriteLine($"Draw!");
                        if (!Continue())
                        {
                            board.Erase(3);
                            return;
                        }
                        board.Erase(2);
                        board = new Board(3, 3, 3);
                    }
                }
            }
        }

        private void DrawScore()
        {
            Console.WriteLine(
                string.Join(" | ", this._players.Select((player => $"{player}: {player.GetScore()}")))
            );
        }

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

        public static void MainMenu()
        {
            (string, Func<bool>)[] actions =
            {
                ("Player Vs Player", () =>
                {
                    new Game(new Player.Player[]
                    {
                        new Player.Human(Marker.Cross), new Player.Human(Marker.Circle)
                    }).Start();
                    return false;
                }),
                ("Player Vs Ai", () =>
                {
                    new Game(new Player.Player[]
                    {
                        new Player.Human(Marker.Cross), ChoseAi(Marker.Circle)
                    }).Start();
                    return false;
                }),
                ("Ai Vs Ai", () =>
                {
                    new Game(new Player.Player[]
                    {
                        ChoseAi(Marker.Cross), ChoseAi(Marker.Circle)
                    }).Start();
                    return false;
                }),
                ("Exit", () => true)
            };

            while (!Chose(actions))
            {
            }
        }

        private static Player.Ai ChoseAi(Marker marker)
        {
            Player.Ai ai = null;

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