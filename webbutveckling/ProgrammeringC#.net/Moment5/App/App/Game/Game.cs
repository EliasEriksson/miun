using System;

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
            var board = new Board();
            while (true)
            {
                foreach (var player in this._players)
                {
                    var (x, y) = player.Play(board);
                    board.SetMarker(x, y, player.GetMarker());
                    if (!board.IsWinner(player)) continue;

                    player.GrantScore();
                    Program.ClearN(1);
                    DrawScore();
                    Console.WriteLine();
                    Console.WriteLine(board);
                    Console.WriteLine($"{player} won!");
                    if (!Continue())
                    {
                        Program.ClearN(16);
                        return;
                    }

                    board = new Board();
                    Program.ClearN(15);
                }
            }
        }

        private void DrawScore()
        {
            foreach (var player in this._players)
            {
                Console.Write($"| {player}: {player.GetScore()} ");
            }

            Console.WriteLine("|");
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
                    Console.WriteLine("returning");
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