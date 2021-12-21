namespace App.Game.Player
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
    }
}