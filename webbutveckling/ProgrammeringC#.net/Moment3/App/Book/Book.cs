using System;
using System.Collections.Generic;
using System.IO;

namespace App.Book
{
    public class Book
    {
        private readonly List<Page> _pages;

        /**
         * deserializes the book from book.txt
         */
        private void DeSerialize()
        {
            var reader = new StreamReader("./book.txt");
            while (!reader.EndOfStream)
            {
                var name = reader.ReadLine();
                if (reader.EndOfStream)
                {
                    // hopefully it was just something at the end of the file
                    // that was not supposed to be there. 
                    // save the book in its current state and pray for the best.
                    this.Save();
                    break;
                }

                var content = reader.ReadLine();
                this._pages.Add(new Page(name, content));
            }
            reader.Close();
        }

        /**
         * serializes the book to book.txt
         */
        private string Serialize()
        {
            var blob = "";
            foreach (var page in this._pages)
            {
                blob += page.Serialize();
            }
            Console.Write(blob);

            return blob;
        }

        /**
         * constructs a book by reading book.txt if it exists
         */
        public Book()
        {
            this._pages = new List<Page>();
            if (File.Exists("./book.txt"))
            {
                this.DeSerialize();
            }
        }
        
        /**
         * saves the book to book.txt
         */
        public void Save()
        {
            var writer = new StreamWriter("./book.txt");
            writer.Write(this.Serialize());
            writer.Close();
        }

        /**
         * returns a string representation of all pages
         */
        public string GetPages()
        {
            var pages = "";
            for (var i = 0; i < this._pages.Count; i++)
            {
                pages += $"[{i}] {this._pages[i].GetName()} - {this._pages[i].GetContent()}\n";
            }

            return pages;
        }

        /**
         * adds a page to the book
         */
        public void AddPage(string name, string content)
        {
            this._pages.Add(new Page(name, content));
        }

        /**
         * removes a page from the book by index
         */
        public void RemovePage(int index)
        {
            if (this._pages.Count < 0) return;
            if (0 > index || index >= this._pages.Count) return;
            this._pages.RemoveAt(index);
        }

        /**
         * returns the number of pages in the book
         */
        public int NumberOfPages()
        {
            return this._pages.Count;
        }
    }
}