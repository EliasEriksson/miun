namespace App.Book
{
    public class Page
    {
        private readonly string _name;
        private readonly string _content;

        /**
         * serializes the page to a text format that can be read by Book.DeSerialize
         */
        public string Serialize()
        {
            return $"{this._name}\n{this._content}\n";
        }
        /**
         * constructs a page from a name and content
         */
        public Page(string name, string content)
        {
            this._name = name;
            this._content = content;
        }
        /**
         * returns the name of the page
         */
        public string GetName()
        {
            return _name;
        }
        /**
         * returns the content on the page
         */
        public string GetContent()
        {
            return _content;
        }
    }
}