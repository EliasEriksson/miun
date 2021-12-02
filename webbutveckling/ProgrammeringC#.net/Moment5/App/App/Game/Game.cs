using System;

namespace App.Game
{
    public class Game
    {
        private Player _player1;
        private Player _player2;
        private Board _board;

        private Game(Player player1, Player player2)
        {
            this._player1 = player1;
            this._player2 = player2;
            this._board = new Board();
        }
        private class ExitGame : Exception
        {
        }

        private static void StartGame(Player player1, Player player2)
        {
            var board = new Board();
            string endMessage;

            while (true)
            {
                player1.Play(board);
                if (board.IsWinner(player1))
                {
                    endMessage = "Player 1 Wins!";
                    break;
                }

                player2.Play(board);
                if (board.IsWinner(player2))
                {
                    endMessage = "Player 2 Wins!";
                    break;
                }
            }
            Console.Clear();
            Console.WriteLine();
            Console.WriteLine(board);
            Console.WriteLine(endMessage);
            Console.WriteLine("Press any key");
        }

        public static void Run()
        {
            (string, Action)[] actions = {
                ("Player Vs Player", () => StartGame(new Human(Marker.Cross), new Human(Marker.Circle))),
                ("Player Vs Ai", () => StartGame(new Human(Marker.Cross), new Ai(Marker.Circle))),
                ("Ai Vs Ai", () => StartGame(new Ai(Marker.Cross), new Ai(Marker.Circle))),
                ("Exit", () => throw new ExitGame())
            };
            
            Chose(actions);
        }

        private static void Chose((string, Action)[] actions, bool clear = true)
        {
            var current = 0;
            while (true)
            {
                if (clear) Console.Clear();
                for (var i = 0; i < actions.Length; i++)
                {
                    Console.Write(i == current ? "> " : "  ");
                    Console.WriteLine(actions[i].Item1);
                }

                var key = Console.ReadKey().Key;
                if (key == ConsoleKey.UpArrow)
                {
                    current -= 1;
                }
                else if (key == ConsoleKey.DownArrow)
                {
                    current += 1;
                }
                else if (key == ConsoleKey.Enter)
                {
                    try
                    {
                        actions[current].Item2();
                    }
                    catch (ExitGame)
                    {
                        return;
                    }
                }
                else
                {
                    continue;
                }

                current = Program.Mod(current, actions.Length);
            }
        }
    }
}