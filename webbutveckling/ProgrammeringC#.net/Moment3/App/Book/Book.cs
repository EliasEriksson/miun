using System;
using System.Collections.Generic;
using System.IO;

namespace App.Book
{
    public class Book
    {
        private readonly List<Page> _pages;

        private void DeSerialize()
        {
            var reader = new StreamReader("./book.txt");
            while (!reader.EndOfStream)
            {
                var name = reader.ReadLine();
                if (reader.EndOfStream)
                {
                    throw new SerializeException();
                }

                var content = reader.ReadLine();
                this._pages.Add(new Page(name, content));
            }
        }

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

        public Book()
        {
            this._pages = new List<Page>();
            if (File.Exists("./book.txt"))
            {
                this.DeSerialize();
            }
        }
        
        public void Save()
        {
            var writer = new StreamWriter("./book.txt");
            writer.Write(this.Serialize());
            writer.Close();
        }

        public string GetPages()
        {
            var pages = "";
            for (var i = 0; i < this._pages.Count; i++)
            {
                pages += $"[{i}] {this._pages[i].GetName()} - {this._pages[i].GetContent()}\n";
            }

            return pages;
        }

        public void AddPage(string name, string content)
        {
            this._pages.Add(new Page(name, content));
        }

        public bool RemovePage(int index)
        {
            if (this._pages.Count < 0) return false;
            if (0 > index || index >= this._pages.Count) return false;
            this._pages.RemoveAt(index);
            return true;
        }

        public int NumberOfPages()
        {
            return this._pages.Count;
        }
    }
}