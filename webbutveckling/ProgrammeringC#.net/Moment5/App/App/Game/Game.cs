using System;

namespace App.Game
{
    public class Game
    {
        private readonly Player[] _players;
        
        private Game(Player[] players)
        {
            this._players = players;
        }

        private class Exit : Exception
        {
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
                    try
                    {
                        player.GrantScore();
                        Program.ClearN(1);
                        DrawScore();
                        Console.WriteLine();
                        Console.WriteLine(board);
                        Console.WriteLine($"{player} won!");
                        Continue();
                        board = new Board();
                        Program.ClearN(15);
                    }
                    catch (Exit)
                    {
                        Program.ClearN(16);
                        return;
                    }
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
        
        private static void Continue()
        {
            Console.WriteLine("Continue playing?");
            (string, Action)[] actions =
            {
                ("Yes", () => { }),
                ("No", () => throw new Exit())
            };
            Chose(actions);
        }

        public static void MainMenu()
        {
            (string, Action)[] actions =
            {
                ("Player Vs Player", () => new Game(new Player[]{new Human(Marker.Cross), new Human(Marker.Circle)}).Start()),
                ("Player Vs Ai", () => new Game(new Player[]{new Human(Marker.Cross), new MediumAi(Marker.Circle)}).Start()),
                ("Ai Vs Ai", () => new Game(new Player[]{ new EasyAi(Marker.Cross), new EasyAi(Marker.Circle)}).Start()),
                ("Exit", () => throw new Exit())
            };
            try
            {
                while (true)
                {
                    Chose(actions);
                }
            }
            catch (Exit)
            {
            }
        }

        private static void Chose((string, Action)[] actions)
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
                        actions[current].Item2();
                        return;
                }

                current = Program.Mod(current, actions.Length);
                Program.ClearN(actions.Length);
            }
        }
    }
}