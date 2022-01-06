using System;
using System.Collections.Generic;
using App.Generics;

namespace App.Game.Player
{
    /**
     * implements base functionality for an Ai and shared functions.
     */
    public abstract class Ai : Player
    {
        private readonly Random _random;

        /**
         * constructs an Ai with a random generator and the given marker.
         */
        protected Ai(Marker marker) : base(marker)
        {
            this._random = new Random();
        }

        /**
         * generates a random integer.
         *
         * low is inclusive, high is exclusive.
         */
        private int RandomInt(int low, int high)
        {
            return this._random.Next(low, high);
        }
        /**
         * generates a random integer.
         *
         * includes 0 up until but excluding high
         */
        protected int RandomInt(int high)
        {
            return this.RandomInt(0, high);
        }
        /**
         * string representation of the Ai
         */
        public override string ToString()
        {
            return $"Ai {(char) this.GetMarker()}";
        }
        private int CountMarker(Board board, Marker marker, Node<(int, int)> node)
        {
            if (node == null)
            {
                return 0;
            }

            var (x, y) = node.GetData();
            var current = board.Get(x, y);
            if (current == marker)
            {
                return 1 + this.CountMarker(board, marker, node.GetNext());
            }

            return this.CountMarker(board, marker, node.GetNext());
        }

        protected (int, int) FindMove(Board board, List<(int, int)> moves)
        {
            var moveCounter = new Dictionary<(int, int), int>();
            foreach (var move in moves)
            {
                if (!moveCounter.ContainsKey(move))
                {
                    moveCounter[move] = 1;
                }
                else
                {
                    moveCounter[move]++;
                }
            }

            var mostCommon = new List<(int, int)>();
            var highestCount = 0;
            foreach (var (coordinate, count) in moveCounter)
            {
                if (count > highestCount)
                {
                    highestCount = count;
                    mostCommon.Clear();
                    mostCommon.Add(coordinate);
                }
                else if (count == highestCount)
                {
                    mostCommon.Add(coordinate);
                }
            }

            if (mostCommon.Count > 0)
            {
                return mostCommon[this.RandomInt(mostCommon.Count)];
            }

            var availableCoordinates = board.GetUnchangedPositions();
            return availableCoordinates[this.RandomInt(availableCoordinates.Count)];
        }

        protected Node<(int, int)> FindMoves(Board board, int x, int y, int moveX, int moveY, int possibleInRow = 0,
            Node<(int, int)> previousPossibleNodes = null)
        {
            if (x >= board.GetWidth() || y >= board.GetHeight() || x < 0 || y < 0)
            {
                return null;
            }

            var current = board.Get(x, y);
            if (current == this.GetMarker() || current == Marker.None)
            {
                possibleInRow += 1;
                
                if (current == Marker.None)
                {
                    if (possibleInRow < board.GetWin())
                    {
                        // extend previous possible
                        if (previousPossibleNodes == null)
                        {
                            previousPossibleNodes = new Node<(int, int)>((x, y));
                        }
                        else
                        {
                            previousPossibleNodes.Add((x, y));
                        }
                    }
                    else if (possibleInRow == board.GetWin())
                    {
                        // return current and add previous possible, set previous possible to null

                        if (previousPossibleNodes == null)
                            return new Node<(int, int)>(
                                (x, y),
                                this.FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
                            );
                        if (current == this.GetMarker())
                        {
                            previousPossibleNodes.Add(this.FindMoves(board, x + moveX, y + moveY, moveX, moveY,
                                possibleInRow));
                            return previousPossibleNodes;
                        }

                        previousPossibleNodes.Add(new Node<(int, int)>(
                            (x, y),
                            this.FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
                        ));
                        return previousPossibleNodes;
                    }
                    else
                    {
                        // return with current and set previous possible to null again

                        if (previousPossibleNodes == null)
                        {
                            return new Node<(int, int)>(
                                (x, y),
                                this.FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
                            );
                        }

                        previousPossibleNodes.Add(new Node<(int, int)>((x, y), this.FindMoves(
                            board, x + moveX, y + moveY, moveX, moveY, possibleInRow
                        )));
                        return previousPossibleNodes;
                    }
                }
            }
            else
            {
                possibleInRow = 0;
                previousPossibleNodes = null;
            }

            return this.FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow, previousPossibleNodes);
        }

        protected Node<(int, int)> IdentifyWin(Board board, Marker player, int x, int y, int moveX, int moveY,
            Node<(int, int)> node = null)
        {
            if (x >= board.GetWidth() || y >= board.GetHeight() || x < 0 || y < 0)
            {
                return null;
            }

            var current = board.Get(x, y);
            if (current == player || current == Marker.None)
            {
                if (node == null)
                {
                    node = new Node<(int, int)>((x, y));
                }
                else
                {
                    node.Add((x, y));
                }

                if (node.GetLength() > board.GetWin())
                {
                    node = node.GetNext();
                }

                if (node.GetLength() == board.GetWin())
                {
                    if (this.CountMarker(board, Marker.None, node) == 1)
                    {
                        while (board.Get(node.GetData()) != Marker.None)
                        {
                            node = node.GetNext();
                        }

                        return new Node<(int, int)>(node.GetData());
                    }
                }
            }
            else
            {
                node = null;
            }

            return this.IdentifyWin(board, player, x + moveX, y + moveY, moveX, moveY, node);
        }
    }
}