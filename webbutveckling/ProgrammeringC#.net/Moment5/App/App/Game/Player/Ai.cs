using System;
using System.Collections.Generic;
using App.Generics;

namespace App.Game.Player
{
    public abstract class Ai : Player
    {
        private readonly Random _random;

        protected Ai(Marker marker) : base(marker)
        {
            this._random = new Random();
        }

        private int RandomInt(int low, int high)
        {
            return this._random.Next(low, high);
        }

        protected int RandomInt(int high)
        {
            return this.RandomInt(0, high);
        }
        
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
                return 1 + CountMarker(board, marker, node.GetNext());
            }

            return CountMarker(board, marker, node.GetNext());
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
                } else if (count == highestCount)
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

                if (possibleInRow < board.GetWin())
                {
                    if (current == Marker.None)
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
                }
                else if (possibleInRow == board.GetWin())
                {
                    // return current and add previous possible, set previous possible to null

                    if (previousPossibleNodes == null)
                        return new Node<(int, int)>(
                            (x, y),
                            FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
                        );
                    if (current == this.GetMarker())
                    {
                        previousPossibleNodes.Add(FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow));
                        return previousPossibleNodes;
                    }

                    var n = new Node<(int, int)>(
                        (x, y),
                        FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
                    );
                    n.Add(previousPossibleNodes);
                    return n;
                }
                else
                {
                    // return with current and set previous possible to null again
                    if (current == Marker.None)
                    {
                        return new Node<(int, int)>(
                            (x, y),
                            FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
                        );
                    }
                }
            }
            else
            {
                possibleInRow = 0;
                previousPossibleNodes = null;
            }

            return FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow, previousPossibleNodes);
        }
        
        protected Node<(int, int)> IdentifyWin(Board board, Marker player, int x, int y, int moveX, int moveY, Node<(int, int)> node = null)
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