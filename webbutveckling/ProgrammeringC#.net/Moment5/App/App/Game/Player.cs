using System;

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

        public abstract void Play(Board board);

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

        public abstract void Reset();
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

        public override void Play(Board board)
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
                        board.SetMarker(this._selectX, this._selectY, this.GetMarker());
                        Program.ClearN(15);
                        return;
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

        public override void Reset()
        {
            this._selectX = 1;
            this._selectY = 1;
        }

        public override string ToString()
        {
            return $"Player {(char) this.GetMarker()}";
        }
    }

    public abstract class Ai : Player
    {
        protected class Node<T>
        {
            private readonly T _data;
            private Node<T> _next;

            public Node(T data)
            {
                this._data = data;
                this._next = null;
            }

            public Node(T data, Node<T> next)
            {
                this._data = data;
                this._next = next;
            }

            public T GetData()
            {
                return this._data;
            }

            public Node<T> GetNext()
            {
                return this._next;
            }

            public Node<T> Add(T data)
            {
                if (this._next == null)
                {
                    this._next = new Node<T>(data);
                    return this._next;
                }

                return this._next.Add(data);
            }

            public void Add(Node<T> node)
            {
                if (this._next == null)
                {
                    this._next = node;
                }
                else
                {
                    this._next.Add(node);
                }
            }
        }

        protected Ai(Marker marker) : base(marker)
        {
        }
        
        public override void Reset()
        {
        }

        public override string ToString()
        {
            return $"Ai {(char) this.GetMarker()}";
        }

        protected Node<(int, int)> Traverse(Board board, int x, int y, int moveX, int moveY, int possibleInRow = 0,
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
                    
                    if (previousPossibleNodes == null) return new Node<(int, int)>(
                            (x, y),
                            Traverse(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
                        );
                    if (current == this.GetMarker())
                    {
                        previousPossibleNodes.Add(Traverse(board, x + moveX, y + moveY, moveX, moveY, possibleInRow));
                        return previousPossibleNodes;
                    }
                    var n = new Node<(int, int)>(
                        (x, y),
                        Traverse(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
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
                            Traverse(board, x + moveX, y + moveY, moveX, moveY, possibleInRow)
                        );
                    }
                }
            }
            else
            {
                possibleInRow = 0;
                previousPossibleNodes = null;
            }

            return Traverse(board, x + moveX, y + moveY, moveX, moveY, possibleInRow, previousPossibleNodes);
        }
    }

    public class AiEasy : Ai
    {
        public AiEasy(Marker marker) : base(marker)
        {
        }

        public override void Play(Board board)
        {
            throw new NotImplementedException();
        }
    } // full random

    public class MediumAi : Ai
    {
        public MediumAi(Marker marker) : base(marker)
        {
        }

        public override void Play(Board board)
        {
            throw new NotImplementedException();
        }
    } // weighted random

    public class HardAi : Ai
    {
        public HardAi(Marker marker) : base(marker)
        {
        }

        public override void Play(Board board)
        {
            throw new NotImplementedException();
        }
    } // max weight then random
}