using System.Collections.Generic;

namespace App.Generics
{
    /**
     * a generic linked list implementation that only consists of nodes.
     */
    public class Node<T>
        {
            private readonly T _data;
            private Node<T> _next;
            private int _length;

            /**
             * creates a new node with given data pointing on null
             */
            public Node(T data)
            {
                this._data = data;
                this._next = null;
                this._length = 1;
            }

            /**
             * creates a new node with given data pointing on the given node
             */
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

            /**
             * returns teh data of the node
             */
            public T GetData()
            {
                return this._data;
            }

            /**
             * returns the reference to the next node.
             */
            public Node<T> GetNext()
            {
                return this._next;
            }

            /**
             * returns the length of the remainder of the node chain.
             *
             * a node chain of 3
             * a -> b -> c
             *
             * the length of the chain from a is 3
             * the length of the chain from b is 2
             * the length of the chain from c is 1
             */
            public int GetLength()
            {
                return this._length;
            }

            /**
             * creates a new node and adds it at the end of the chain
             *
             * creates a new node from the given data an the node is passed down the chain
             * to the node that points to null which will point to this new node instead.
             */
            public Node<T> Add(T data)
            {
                return this.Add(new Node<T>(data));
            }

            /**
             * creates adds a new node at the end of the chain
             *
             * the node is passed down the chain to the node that points to null which
             * will point to this node instead.
             *
             * returns the node that was added
             */
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

            /**
             * adds all the nodes in teh chains data to the referenced list
             *
             * a node chain of 3 and a list foo
             * foo []
             * a -> b -> c
             * 
             * will mutate foo to
             * foo [data of a, data of b, data of c]
             */
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