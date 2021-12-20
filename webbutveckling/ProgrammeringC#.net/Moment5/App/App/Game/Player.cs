using System;
using System.Collections.Generic;
using System.Threading;

namespace App.Game
{
    public abstract class Player
    {
        private readonly Marker _marker;
        private int _score;

        protected Player(Marker marker)
        {
            this._marker = marker;
            this._score = 0;
        }

        public abstract (int, int) Play(Board board);

        public Marker GetMarker()
        {
            return this._marker;
        }

        public int GetScore()
        {
            return this._score;
        }

        public void GrantScore()
        {
            this._score++;
        }
        
    }

    public class Human : Player
    {
        private int _selectX;
        private int _selectY;

        public Human(Marker marker) : base(marker)
        {
            this._selectX = 1;
            this._selectY = 1;
        }

        public override (int, int) Play(Board board)
        {
            while (true)
            {
                Console.WriteLine($"Currently playing: {this}\n");
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
                        // board.SetMarker(this._selectX, this._selectY, this.GetMarker());
                        Program.ClearN(15);
                        return (this._selectX, this._selectY);
                    }
                    catch (Board.DuplicateEntryException)
                    {
                    }
                }

                this._selectX = Program.Mod(_selectX, 3);
                this._selectY = Program.Mod(_selectY, 3);
                Program.ClearN(15);
            }
        }

        public override string ToString()
        {
            return $"Player {(char) this.GetMarker()}";
        }
    }

    public abstract class Ai : Player
    {
        private readonly Random _random;
        protected class Node<T>
        {
            private readonly T _data;
            private Node<T> _next;
            private int _length;

            public Node(T data)
            {
                this._data = data;
                this._next = null;
                this._length = 1;
            }

            public Node(T data, Node<T> next)
            {
                this._data = data;
                this._next = next;
                if (next == null)
                {
                    this._length = 1;
                }
                else
                {
                    this._length = this._next._length + 1;
                }
            }

            public T GetData()
            {
                return this._data;
            }

            public Node<T> GetNext()
            {
                return this._next;
            }

            public int GetLength()
            {
                return this._length;
            }

            public Node<T> Add(T data)
            {
                return this.Add(new Node<T>(data));
            }

            public Node<T> Add(Node<T> node)
            {
                if (node == null)
                {
                    return null;
                }

                this._length += node._length;

                if (this._next == null)
                {
                    this._next = node;
                    return this._next;
                }
                return this._next.Add(node);
            }

            public void AddDataToList(ref List<T> list)
            {
                list.Add(this.GetData());
                if (this._next == null)
                {
                    return;
                }
                this._next.AddDataToList(ref list);
            }
        }

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

            var availableCoordinates = board.GetEmptySlots();
            return availableCoordinates[this.RandomInt(availableCoordinates.Count)];
        }
        
        protected Node<(int, int)> FindMoves(Board board, int x, int y, int moveX, int moveY, int possibleInRow = 0,
            Node<(int, int)> previousPossibleNodes = null)
        {
            if (x >= board.GetWidth() || y >= board.GetHeight())
            {
                return null;
            }

            var current = board.Get(x, y);
            if (current == this.GetMarker() || current == Marker.None)
            {
                possibleInRow += 1;

                if (possibleInRow < board.WinCondition())
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
                else if (possibleInRow == board.WinCondition())
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
            if (x >= board.GetWidth() || y >= board.GetHeight())
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
                
                if (node.GetLength() > board.WinCondition())
                {
                    node = node.GetNext();
                }

                if (node.GetLength() == board.WinCondition())
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


        protected void Traverse(Board board, Func< int, int ,int, int, bool> method)
        {

            for (var i = 0; i < board.GetHeight() - board.WinCondition() + 1; i++)
            {
                if (method(0, i, 1, 1)) return;
            } // top left to bottom right
 
            for (var i = board.WinCondition() - 1; i < board.GetHeight(); i++)
            {
                if (method(0, i, 1, -1)) return;
            } // bottom left to top right

            for (var i = 1; i < board.GetWidth() - board.WinCondition(); i++)
            {
                if(method(i, 0, 1, 1)) return;
                if(method(i, board.GetHeight() - 1, 1, -1)) return;
            }

            for (var i = 0; i < board.GetHeight(); i++)
            {
                if(method(0, i, 1, 0)) return;
            } // all rows

            for (var i = 0; i < board.GetWidth(); i++)
            {
                if(method(i, 0, 0, 1)) return;
            } // all columns
        }
    }

    public class EasyAi : Ai
    {
        public EasyAi(Marker marker) : base(marker)
        {
        }

        public override (int, int) Play(Board board)
        {
            Console.WriteLine($"Currently playing: {this}\n");
            board.Draw(0, 0);
            Thread.Sleep(1500);
            var availableCoordinates = board.GetEmptySlots();
            return availableCoordinates[this.RandomInt(availableCoordinates.Count)];
        }
    } // full random

    public class MediumAi : Ai
    {
        public MediumAi(Marker marker) : base(marker)
        {
        }

        public override (int, int) Play(Board board)
        {
            Console.WriteLine($"Currently playing: {this}\n");
            board.Draw(0, 0);
            Thread.Sleep(1500);
            var result = new List<(int, int)>();
            Node<(int, int)> move = null;
            this.Traverse(board, (x, y, moveX, moveY) =>
            {
                var moves = this.FindMoves(board, x, y, moveX, moveY);
                if (board.GetWidth() - moves?.GetLength() >= board.WinCondition() - 1)
                {
                    move = this.IdentifyWin(board, this.GetMarker(), x, y, moveX, moveY);
                    if (move != null)
                    {
                        return true;
                    }
                }
                moves?.AddDataToList(ref result);
                return false;
            });

            if (move != null)
            {
                return move.GetData();
            }

            if (result.Count > 0)
            {
                return result[this.RandomInt(result.Count)];
            }

            var availableMoves = board.GetEmptySlots();
            return availableMoves[this.RandomInt(availableMoves.Count)];
        }
    } // weighted random

    public class HardAi : Ai
    {
        public HardAi(Marker marker) : base(marker)
        {
        }

        public override (int, int) Play(Board board)
        {
            Console.WriteLine($"Currently playing: {this}\n");
            board.Draw(0, 0);
            Thread.Sleep(1500);
            
            var moveFrequencyList = new List<(int, int)>();
            Node<(int, int)> move = null;
            
            // searches for winning and optimal moves
            this.Traverse(board, (x, y, moveX, moveY) =>
            {
                var moves = this.FindMoves(board, x, y, moveX, moveY);
                if (board.GetWidth() - moves?.GetLength() >= board.WinCondition() - 1)
                {
                    move = this.IdentifyWin(board, this.GetMarker(), x, y, moveX, moveY);
                    if (move != null)
                    {
                        return true;
                    }
                }
                moves?.AddDataToList(ref moveFrequencyList);
                return false;
            });
            
            // if there is a winning move, use it
            if (move != null)
            {
                return move.GetData();
            }
            
            // finds opponent winning moves
            this.Traverse(board, (x, y, moveX, moveY) =>
            {
                var opponent = this.GetMarker() == Marker.Cross ? Marker.Circle : Marker.Cross;
                move = IdentifyWin(board, opponent, x, y, moveX, moveY);
                return move != null;
            });
            
            // if enemy have a winning move, block it. Else use the most optimal move
            return move?.GetData() ?? this.FindMove(board, moveFrequencyList);
        }
        
        
        

        
        
    } // max weight then random
}