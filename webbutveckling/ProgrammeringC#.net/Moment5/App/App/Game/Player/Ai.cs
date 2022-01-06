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

        /**
         * counts how many markers in the node chain that is of a specific marker.
         *
         * used to determine if a player is one move away from winning.
         */
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

        /**
         * finds the move that is the most common move in the list of moves.
         *
         * the list of moves should be generated from FindMoves.
         *
         * if a multiple moves have the same frequency in the list a random is chosen.
         *
         * if the list is empty a random move available on the board is chosen instead.
         */
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

        /**
         * finds every move on the board that could potentially lead to a win.
         *
         * a potential win is if the own squares + empty squares in a row is equal or larger than win requirement.
         */
        protected Node<(int, int)> FindMoves(Board board, int x, int y, int moveX, int moveY, int possibleInRow = 0,
            Node<(int, int)> previousPossibleNodes = null)
        {
            if (x >= board.GetWidth() || y >= board.GetHeight() || x < 0 || y < 0)
            {
                // outside of the board ->
                // stop going deeper
                return null;
            }

            var current = board.Get(x, y);
            if (current == this.GetMarker() || current == Marker.None)
            {
                // current marker is not enemy -> 
                // add one
                possibleInRow += 1;
                
                if (current == Marker.None)
                {
                    // this slot is empty -> 
                    // add it some way defined bellow
                    if (possibleInRow < board.GetWin())
                    {
                        // the win condition to win is already met ->
                        // add the move the the list of moves
                        if (previousPossibleNodes == null)
                        {
                            // there was no previous list of moves to add to so create a new one
                            previousPossibleNodes = new Node<(int, int)>((x, y));
                        }
                        else
                        {
                            previousPossibleNodes.Add((x, y));
                        }
                    }
                    else if (possibleInRow == board.GetWin())
                    {
                        // the condition to win just fulfilled ->
                        // return the already found empty slots and potential next ones.
                        if (previousPossibleNodes == null)
                            // there was no previous slots so create a new one here and add the upcoming.
                            return new Node<(int, int)>(
                                (x, y),
                                this.FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
                            );
                        // the current slot is empty ->
                        // create a new node here and add it to the previous chain and add the next ones.
                        previousPossibleNodes.Add(new Node<(int, int)>(
                            (x, y),
                            this.FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
                        ));
                        return previousPossibleNodes;
                    }
                    else
                    {
                        // the win condition is not yet fulfilled -> 
                        // add the nodes to possible nodes and continue looking
                        if (previousPossibleNodes == null)
                        {
                            // there was no previous nodes so create a new one.
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
                // enemy is blocking. reset in row and previous possible
                possibleInRow = 0;
                previousPossibleNodes = null;
            }

            return this.FindMoves(board, x + moveX, y + moveY, moveX, moveY, possibleInRow, previousPossibleNodes);
        }

        /**
         * identifies if a specific player have the potential to win along a specific direction and position.
         *
         * returns a linked list of where a specified player can win.
         */
        protected Node<(int, int)> IdentifyWin(Board board, Marker player, int x, int y, int moveX, int moveY,
            Node<(int, int)> node = null)
        {
            if (x >= board.GetWidth() || y >= board.GetHeight() || x < 0 || y < 0)
            {
                // outside the board. stop going deeper.
                return null;
            }

            var current = board.Get(x, y);
            if (current == player || current == Marker.None)
            {
                // this direction is not blocked by another player -> 
                // add new node.
                if (node == null)
                {
                    // create a new node if there currently is none.
                    node = new Node<(int, int)>((x, y));
                }
                else
                {
                    node.Add((x, y));
                }
                
                if (node.GetLength() > board.GetWin())
                {
                    // if the chain is longer than win requirement reduce it by one
                    // it is never longer than win requirement + 1
                    node = node.GetNext();
                }

                if (node.GetLength() == board.GetWin())
                {
                    // this direction have a possible win.
                    if (this.CountMarker(board, Marker.None, node) == 1)
                    {
                        // player is one move away from winning
                        while (board.Get(node.GetData()) != Marker.None)
                        {
                            // find the marker that is empty
                            node = node.GetNext();
                        }
                        // skip recursion and strait up return this location
                        return new Node<(int, int)>(node.GetData());
                    }
                }
            }
            else
            {
                // path is blocked -> 
                // reset the node chain to null
                node = null;
            }
            
            return this.IdentifyWin(board, player, x + moveX, y + moveY, moveX, moveY, node);
        }
    }
}