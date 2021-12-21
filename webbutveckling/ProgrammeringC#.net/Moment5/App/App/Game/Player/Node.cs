using System.Collections.Generic;

namespace App.Game.Player
{
    public class Node<T>
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
    
}