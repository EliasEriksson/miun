namespace App.Game.Player
{
    /**
     * an abstract class to define a common interface for a regular human player and an Ai.
     *
     * both and Ai and a human player is given a marker and a score count but the method of
     * how they play ar vastly dirent (even between Ai difficulties) 
     */
    public abstract class Player
    {
        private readonly Marker _marker;
        private int _score;
        
        /**
         * constructs the base player with a given marker.
         */
        protected Player(Marker marker)
        {
            this._marker = marker;
            this._score = 0;
        }
        
        /**
         * abstract method for any child to implement.
         */
        public abstract void Play(Board board);

        /**
         * returns the players marker.
         */
        public Marker GetMarker()
        {
            return this._marker;
        }

        /**
         * returns the players score.
         */
        public int GetScore()
        {
            return this._score;
        }

        /**
         * increase the players score by one.
         */
        public void GrantScore()
        {
            this._score++;
        }
    }
}