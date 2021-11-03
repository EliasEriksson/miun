namespace App.Book
{
    public class Page
    {
        private readonly string _name;
        private readonly string _content;

        public string Serialize()
        {
            return $"{this._name}\n{this._content}\n";
        }

        public Page(string name, string content)
        {
            this._name = name;
            this._content = content;
        }

        public string GetName()
        {
            return _name;
        }

        public string GetContent()
        {
            return _content;
        }
    }
}